<?php
// PHP 5.6 safe
session_start();

$branch = isset($_SESSION['login_branch']) ? $_SESSION['login_branch'] : '';
$date   = isset($_GET['date']) && $_GET['date'] !== '' ? $_GET['date'] : date('Y-m-d');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
  header('HTTP/1.1 400 Bad Request');
  echo 'Invalid date';
  exit;
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// helper (guard to avoid redeclare if an include defines it too)
if (!function_exists('h')) {
  function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cash In / Cash Out (<?php echo h($date); ?>) - <?php echo h($branch); ?></title>
<style>
  #tbl-excel-container{width:1920px;min-height:100vh;display:flex;flex-direction:column;align-items:center}
  .tbl-excel-row{width:1920px;display:flex;align-items:flex-start}

  .tbl-excel{border-collapse:collapse;border:3px solid #000;background:#fff}
  .tbl-excel tr td{border:1px solid #000;height:25px;width:100px;text-align:center;color:#000;padding:0 2px;white-space:nowrap}

  #table8,#table8 tr td{border:1px solid grey}

  #table7{table-layout:fixed;width:1200px;border-collapse:collapse}
  #table7 td{border:1px solid #000;height:24px;overflow:hidden}

  .band-col{width:36px}
  .band-blue{background:#7fd6e5 !important}
  .band-red{background:#f06b6b !important}
  .band-green{background:#bfecc3 !important;position:relative}
  #greenTotalBubble{position:absolute;left:50%;top:6px;transform:translateX(-50%);font-weight:bold;font-size:12px}

  .hdr-plus{font-weight:bold;text-align:center}
  .hdr-minus{font-weight:bold;text-align:center}
  .hdr-nama-red{background:#f3b6b6 !important;font-weight:bold;text-align:center}
  .hdr-nama-mint{background:#bfecc3 !important;font-weight:bold;text-align:center}
  .hdr-nama-cyan{background:#7fd6e5 !important;font-weight:bold;text-align:center}

  .cell-text{display:inline-block;width:98%;height:90%;line-height:1.2;text-align:center;border:none;outline:none;background:transparent;font:12px Arial,Helvetica,sans-serif}

  .sticky-button{position:fixed;top:10px;right:10px;z-index:1000;padding:10px 20px;background:#007BFF;color:#fff;border:none;border-radius:5px;cursor:pointer}

  h2#title{margin:16px 0;font-family:Arial,Helvetica,sans-serif}

  @media print{
    body{zoom:0.5; margin-left:150px;}
    *{-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important}
    .sticky-button{display:none}
  }
</style>
</head>
<body>
  <button type="button" class="sticky-button" onclick="window.print();" hidden>Print</button>

  <?php include_once 'get_printable_cash_in_out.php'; ?>

  <script>
    (function(){
      var t=document.getElementById('title');
      if(t){ t.textContent='Cash In / Cash Out (<?php echo h($date); ?>) - <?php echo h($branch); ?>'; }
    })();

    // Freeze form controls to plain text so printed values are accurate
    (function(){
      var all=document.querySelectorAll('input,select,textarea');
      for(var i=0;i<all.length;i++){ var el=all[i]; if('value' in el) el.setAttribute('value', el.value); }
      var nodes=[], q=document.querySelectorAll('input,select,textarea,[contenteditable="true"]');
      for(var j=0;j<q.length;j++) nodes.push(q[j]);
      for(var k=0;k<nodes.length;k++){
        var el2=nodes[k], val='', tn=el2.tagName?el2.tagName.toUpperCase():'';
        if(tn==='INPUT'||tn==='TEXTAREA'||tn==='SELECT'){ val=el2.value; }
        else if(el2.getAttribute&&el2.getAttribute('contenteditable')==='true'){ val=el2.textContent||''; }
        var span=document.createElement('span'); span.className='cell-text'; span.textContent=val||'';
        if(el2.parentNode) el2.parentNode.replaceChild(span, el2);
      }
    })();
  </script>
</body>
</html>
