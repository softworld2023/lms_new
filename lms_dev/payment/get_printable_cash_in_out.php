<?php
include_once '../include/dbconnection.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$date = (isset($_GET['date']) && $_GET['date'] !== '') ? $_GET['date'] : date('Y-m-d');
$db   = isset($_SESSION['login_database']) ? $_SESSION['login_database'] : '';

if (!$db) { header('HTTP/1.1 500 Internal Server Error'); echo 'No database selected.'; exit; }
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) { header('HTTP/1.1 400 Bad Request'); echo 'Invalid date'; exit; }

if (!function_exists('h')) {
  function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
}

/**
 * Normalize stored fragments so printing shows exactly what users see:
 *  - ensure every <input> has value="…"
 *  - keep class="cell"
 *  - for case-7 totals, if someone saved plain <td>text</td>, wrap as readonly input to print nicely
 */
function normalize_cashio_html($frag) {
  if (!is_string($frag) || $frag === '') return $frag;

  // clean oddities
  $frag = str_replace(' class="cell" "=""', ' class="cell"', $frag);
  $frag = preg_replace('/\s+"="\s*""/', '', $frag);

  // ensure inputs carry value=""
  $frag = preg_replace_callback('/<input\b([^>]*)>/i', function($m){
    $tag = $m[0];
    if (preg_match('/\bvalue\s*=\s*"/i', $tag)) return $tag;
    return rtrim(substr($tag,0,-1)).' value="">';
  }, $frag);

  // case-7 (row 59) totals as readonly inputs
  $cols = ['b','c','gb','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r'];
  foreach ($cols as $col) {
    $id = preg_quote($col.'59', '/');
    $frag = preg_replace(
      '/<td([^>]*)\bid\s*=\s*"'.$id.'"\b([^>]*)>(.*?)<\/td>/is',
      '<td$1 id="'.$col.'59"$2><input id="'.$col.'59" class="cell" value="$3" readonly></td>',
      $frag
    );
  }

  return $frag;
}

function fetch_table_content($qualifiedTable, $date) {
  $safeDate = mysql_real_escape_string($date);
  $sql = "SELECT content FROM $qualifiedTable WHERE date = '$safeDate'";
  $q   = mysql_query($sql);
  if (!$q) return '';
  $r = mysql_fetch_assoc($q);
  return isset($r['content']) ? $r['content'] : '';
}

// paged (saved) fragments – good for t1,t4,t7,t8; t2,t5 will be replaced with expanded versions via JS
$t1 = normalize_cashio_html(fetch_table_content($db.'.cash_in_out_table1', $date));
$t2 = normalize_cashio_html(fetch_table_content($db.'.cash_in_out_table2', $date));
$t4 = normalize_cashio_html(fetch_table_content($db.'.cash_in_out_table4', $date));
$t5 = normalize_cashio_html(fetch_table_content($db.'.cash_in_out_table5', $date));
$t7 = normalize_cashio_html(fetch_table_content($db.'.cash_in_out_table7', $date));
$t8 = normalize_cashio_html(fetch_table_content($db.'.cash_in_out_table8', $date));
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cash In / Cash Out — Print</title>
<style>
  body{margin:10px;font-family:Arial,Helvetica,sans-serif}
  .tbl-excel { border-collapse: collapse; border: 3px solid #000; }
  .tbl-excel td { border: 1px solid #000; height: 24px; line-height: 24px; text-align: center; white-space: nowrap; }
  .tbl-excel input.cell {
    border: none; outline: none; width: 98%; height: 90%;
    text-align: center; font: 12px Arial, Helvetica, sans-serif;
    background: transparent; padding: 0 2px;
  }
  tr {
    border: 1px solid black !important;
  }
  .row { display:flex; gap:20px; align-items:flex-start; }
  .col { display:flex; flex-direction:column; gap:20px; }
  @media print {
    @page { size: landscape; margin: 8mm; }
    a, button { display:none !important; }
  }
</style>
</head>
<body>
  <!-- header -->
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
    <div style="font-size:18px;font-weight:bold;">Cash In / Cash Out</div>
    <div>Date: <b><?php echo h($date); ?></b></div>
  </div>

  <!-- grid: (t1 over t4) | t2 | t5 -->
  <div class="row">
    <div class="col">
      <table id="table1" class="tbl-excel" style="width:600px;"><?php echo $t1; ?></table>
      <table id="table4" class="tbl-excel" style="width:600px;"><?php echo $t4; ?></table>
    </div>

    <table id="table2" class="tbl-excel" style="width:800px;"><?php echo $t2; ?></table>
    <table id="table5" class="tbl-excel" style="width:800px;"><?php echo $t5; ?></table>
  </div>

  <!-- second row: t7 | t8 -->
  <div class="row" style="margin-top:20px;">
    <table id="table7" class="tbl-excel" style="width:1600px;"><?php echo $t7; ?></table>
    <table id="table8" class="tbl-excel" style="width:600px;"><?php echo $t8; ?></table>
  </div>

  <!-- Minimal jQuery (1.8.x compatible) just for POST + DOM inject -->
  <script>
  (function(){
    // tiny $ helper (works on very old jQuery-less pages)
    function ajaxPost(url, data, cb){
      var xhr=new XMLHttpRequest();
      xhr.open('POST', url, true);
      xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
      xhr.onreadystatechange=function(){
        if(xhr.readyState===4){ cb(xhr.status, xhr.responseText); }
      };
      var body=[];
      for(var k in data){
        if(!data.hasOwnProperty(k)) continue;
        body.push(encodeURIComponent(k)+'='+encodeURIComponent(data[k]));
      }
      xhr.send(body.join('&'));
    }

    function stripOuterTbody(html){
      if(!html) return '';
      return html.replace(/^\s*<tbody[^>]*>/i,'').replace(/<\/tbody>\s*$/i,'');
    }
    function stripPager(html){
      // remove the pager row that includes "loadTable(5, ..." or "loadTable(2, ...)"
      return (html||'').replace(
        /<tr[^>]*>\s*<td[^>]*colspan\s*=\s*["']?9["']?[^>]*>.*?loadTable\((?:2|5)\s*,.*?<\/tr>/gis,
        ''
      );
    }
    function ensureInputsHaveValueAttr(html){
      return (html||'').replace(/<input\b([^>]*)(?<!value="[^"]*")>/gi, function(tag, attrs){
        // if tag already contains value= we keep it
        if (/value\s*=/.test(tag)) return tag;
        return tag.replace(/>$/, ' value="">');
      });
    }

    var date = <?php echo json_encode($date); ?>;

    var pending = 2; // we will expand t2 & t5, then print
    function doneOne(){
      pending--;
      if (pending<=0){
        // give the browser a tick to layout
        setTimeout(function(){
          window.print();
          // optional: auto-close the print tab after a short delay
          setTimeout(function(){ window.close && window.close(); }, 500);
        }, 50);
      }
    }

    // Expand TABLE 2 (full) for print only
    ajaxPost('get_cash_in_out_content_ajax.php', {
      date: date,
      table_no: 2,
      all: 1,
      page: 1,
      limit: 100000,
      force: 1  // read from generator, not from saved
    }, function(status, resp){
      if (status===200 && resp){
        var html = ensureInputsHaveValueAttr(stripPager(stripOuterTbody(resp)));
        if (html){ document.getElementById('table2').innerHTML = html; }
      }
      doneOne();
    });

    // Expand TABLE 5 (full) for print only
    ajaxPost('get_cash_in_out_content_ajax.php', {
      date: date,
      table_no: 5,
      all: 1,
      page: 1,
      limit: 100000,
      force: 1
    }, function(status, resp){
      if (status===200 && resp){
        var html = ensureInputsHaveValueAttr(stripPager(stripOuterTbody(resp)));
        if (html){ document.getElementById('table5').innerHTML = html; }
      }
      doneOne();
    });

  })();
  </script>
</body>
</html>
