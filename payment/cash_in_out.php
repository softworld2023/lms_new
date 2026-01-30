<?php
  // payment/cash_in_out.php — jQuery 1.4.2 compatible; keeps pagination working; saves clean HTML
  include('../include/page_header.php');
  session_start();

  // Default date (today)
  $date = (isset($_GET['date']) && $_GET['date'] != '') ? $_GET['date'] : date('Y-m-d');

  // Check if today's date already exists in closing table
  $check_q = mysql_query("
      SELECT 1 
      FROM cash_in_out_closing 
      WHERE closing_date = '".$date."' 
      LIMIT 1
  ");

  if (mysql_num_rows($check_q) > 0) {
      // If exists, move to next day
      $date = date('Y-m-d', strtotime($date . ' +1 day'));
  }

  $date = (isset($_GET['date']) && $_GET['date'] != '') ? $_GET['date'] : $date;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cash In / Cash Out</title>
<script src="../include/js/jquery-1.4.2.min.js"></script>
<style>
*{box-sizing:border-box}
html,body{height:100%}
body{margin:0;font-family:Arial,Helvetica,sans-serif}

/* top bar */
#list_table{border-collapse:collapse;border:none;width:1280px;max-width:100%}
#list_table td{height:35px;padding:0 10px}

/* universal excel tables */
.tbl-excel{border-collapse:collapse;border-spacing:0;border:3px solid #000;table-layout:fixed}
.tbl-excel td{
  border:1px solid #000; height:24px; line-height:24px;
  padding:0 4px; text-align:center; color:#000;
  font-size:12px; white-space:nowrap; vertical-align:middle;
}

/* inputs look like cells but don’t stretch rows */
input.cell, .cell{
  display:inline-block; width:98%; height:18px; line-height:18px;
  margin:0; padding:0 2px; border:none; outline:none; text-align:center; font-size:12px;
}

/* hard widths so columns never drift */
#table1{width:600px}
#table4{width:600px}
#table2{width:800px}
#table5{width:800px}
#table7{width:1600px}
#table8{width:600px}

/* keep list tables 2/5 columns aligned with print */
#table2 col:nth-child(1), #table5 col:nth-child(1){ width:120px }
#table2 col:nth-child(2), #table5 col:nth-child(2){ width:120px }
#table2 col:nth-child(3), #table5 col:nth-child(3){ width:80px }
#table2 col:nth-child(4), #table5 col:nth-child(4){ width:90px }
#table2 col:nth-child(5), #table5 col:nth-child(5){ width:90px }
#table2 col:nth-child(6), #table5 col:nth-child(6){ width:60px }
#table2 col:nth-child(7), #table5 col:nth-child(7){ width:90px }
#table2 col:nth-child(8), #table5 col:nth-child(8){ width:70px }
#table2 col:nth-child(9), #table5 col:nth-child(9){ width:110px }

/* layout */
.scroll-page{height:calc(100vh - 320px); overflow:auto; margin:0 24px; border-top:1px solid #eee; background:#fff}
#board{ width:100%; height:auto; overflow:auto;}
#boardInner{ width:max-content; min-width:2200px; overflow:visible; padding:20px 20px 40px;}

/* grid */
.sheet-grid{
  display:grid;
  grid-template-columns:600px 800px 800px;
  grid-template-rows:auto auto;
  grid-template-areas:
    "table1 table2 table5"
    "table4 table2 table5";
  gap:20px; margin-bottom:20px;
}
#table1-wrap{grid-area:table1}
#table2-wrap{grid-area:table2}
#table4-wrap{grid-area:table4}
#table5-wrap{grid-area:table5; margin-left: 5%;}

.bottom-row{display:grid; grid-template-columns:1600px 600px; gap:20px}

/* table7 colored bands (if present) */
.band-col{ width:36px }
.band-blue { background:#7fd6e5 !important }
.band-red  { background:#f06b6b !important }
.band-green{ background:#bfecc3 !important; position:relative }
#greenTotalBubble{ position:absolute; left:50%; top:6px; transform:translateX(-50%); font-weight:bold; font-size:12px; }

/* “ONG TIAP2 HARI” (table8) */
#table8 .ong-card{ border:3px solid #000 }
#table8 .ong-head{ background:#e41e26; color:#fff; font-weight:800; font-size:20px; text-align:left; padding:8px 12px }
#table8 .ong-value{ font-weight:800; font-size:42px; line-height:46px; letter-spacing:1px; padding:8px 12px; border-top:3px solid #000; border-bottom:3px solid #000 }
#table8 .ong-hint{ font-size:11px; font-weight:700; text-align:center; padding:6px 0; border-bottom:1px solid #000 }
#table8 .ong-mini td{ height:26px }

/* print */
@media print{
  @page{size:landscape;margin:0}
  body *{visibility:hidden}
  .print-area,.print-area *{visibility:visible}
  .print-area{position:absolute;top:0;left:0;width:100%; overflow:hidden}
}

.customBtn {
   /* border-top: 1px solid #96d1f8; */
   border: none;
   background: #1a63ffff;
   padding: 6px 14px;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
}

.customBtn2 {
   /* border-top: 1px solid #96d1f8; */
   border: none;
   background: #f59300ff;
   padding: 6px 14px;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
}

.customBtn3 {
   /* border-top: 1px solid #96d1f8; */
   border: none;
   background: #0fad29ff;
   padding: 6px 14px;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
}
</style>
</head>
<body>
<center class="print-area">
  <table style="width:1280px;max-width:100%;">
    <tr>
      <td width="65"><img src="../img/payment-received/payment-received.png" alt=""></td>
      <td>Cash In / Cash Out</td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr>
      <td colspan="3">
        <div class="subnav">
          <a href="index.php">Ledger Listing</a>
          <a href="payment_monthly.php">Monthly Listing</a>
          <a href="payment_instalment.php">Instalment Listing</a>
          <a href="lateIntPayment.php">Late Payment Listing</a>
          <a href="collection.php">Collection</a>
          <a href="cash_in_out.php" id="active-menu">Cash In / Cash Out</a>
          <a href="close_listing.php">Closing History</a>
          <a href="shortInstalment.php">Short Listing</a>
          <a href="half_month.php">Half Month Listing</a>
          <a href="return_book_monthly.php">Monthly</a>
          <a href="return_book_instalment.php">Return Book</a>
          <a href="account_book_monthly.php">Account Book (Monthly)</a>
          <a href="account_book_instalment.php">Account Book (Instalment)</a>
        </div>
      </td>
    </tr>
  </table>

  <table id="list_table">
    <tr>
      <td colspan="2">
        <table width="460" border="0">
          <tr>
            <td style="width:200px">
              <input type="date" id="date" value="<?php echo htmlspecialchars($date); ?>">
            </td>
            <td style="width:90px"><button class="customBtn" type="button" id="btn-print" style="cursor:pointer;">Print</button></td>
            <td style="width:80px"><button class="customBtn2" type="button" id="btn-save"  style="cursor:pointer;">Save</button></td>
            <td style="width:90px"><button class="customBtn3" type="button" id="btn-close" style="cursor:pointer;">Close</button></td>
            <td id="message"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</center>

<div class="scroll-page">
  <div id="board">
    <div id="boardInner">
      <div class="sheet-grid">
        <!-- T1 -->
        <div id="table1-wrap">
          <table id="table1" class="tbl-excel">
            <colgroup><col><col><col><col><col><col></colgroup>
            <tbody></tbody>
          </table>
        </div>

        <!-- T2 -->
        <div id="table2-wrap">
          <table id="table2" class="tbl-excel">
            <colgroup>
              <col><col><col><col><col><col><col><col><col>
            </colgroup>
            <tbody></tbody>
          </table>
        </div>

        <!-- T4 -->
        <div id="table4-wrap">
          <table id="table4" class="tbl-excel">
            <colgroup><col><col><col><col><col><col></colgroup>
            <tbody></tbody>
          </table>
        </div>

        <!-- T5 -->
        <div id="table5-wrap">
          <table id="table5" class="tbl-excel">
            <colgroup>
              <col><col><col><col><col><col><col><col><col>
            </colgroup>
            <tbody></tbody>
          </table>
        </div>
      </div>

      <div class="bottom-row">
        <!-- T7 -->
        <table id="table7" class="tbl-excel"><tbody></tbody></table>

        <!-- T8 -->
        <table id="table8" class="tbl-excel">
          <colgroup><col><col><col><col><col><col></colgroup>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
/* ===== Small helpers ===== */
function makePageLocked(){
  jQuery('input.cell, select, textarea').attr('readonly', true).attr('disabled', true);
  jQuery('[contenteditable="true"]').each(function(){ this.setAttribute('contenteditable','false'); });
  jQuery('#btn-save,#btn-close').hide();
}

/* Manual values (carry-forward) */
window.__manualData=null; window.__manualApplied=false;
var __tablesToLoad=[1,2,4,5,7,8], __tablesLoaded=0;

function applyManualIfReady(){
  if (window.__manualApplied || !window.__manualData) return;
  var $mbb = jQuery('#val_mbb'),
      $pbe = jQuery('#val_pbe'),
      $pl  = jQuery('#val_pl');
  if (!($mbb.length && $pbe.length && $pl.length)) return;

  // Don’t overwrite if user already filled or frozen
  var locked = ($mbb.attr('data-frozen') === '1') ||
               ($pbe.attr('data-frozen') === '1') ||
               ($pl.attr('data-frozen')  === '1');

  function isBlank($el){ var t = jQuery.trim($el.text()); return (t === '' || t === '0'); }
  var alreadyFilled = !(isBlank($mbb) && isBlank($pbe) && isBlank($pl));
  if (locked || alreadyFilled) { window.__manualApplied = true; return; }

  var d = window.__manualData, changed=false;
  if (d){
    if(d.val_mbb!=null && d.val_mbb!==''){ $mbb.text(d.val_mbb); changed=true; }
    if(d.val_pbe!=null && d.val_pbe!==''){ $pbe.text(d.val_pbe); changed=true; }
    if(d.val_pl !=null && d.val_pl !==''){ $pl.text(d.val_pl);  changed=true; }
  }
  if (changed){ columnTotals(); setB28FromCurrent(); }
  window.__manualApplied = true;
}
function loadManualOverrides(dateStr){
  if(!dateStr) return;
  jQuery.ajax({
    url:'get_cash_in_out_manual_values.php', type:'POST', dataType:'json', data:{date:dateStr},
    success:function(resp){ try{ window.__manualData = resp||null; applyManualIfReady(); }catch(e){} }
  });
}

/* Extract rows only (robust if backend returns a whole <table>) */
function extractRows(html){
  if(!html) return '';
  var s=jQuery.trim(html);
  if (/<table[\s>]/i.test(s)){
    var m=s.match(/<tbody[^>]*>([\s\S]*?)<\/tbody>/i);
    return m ? jQuery.trim(m[1]) : jQuery.trim((s.match(/<tr[\s\S]*?<\/tr>/ig)||[]).join(''));
  }
  s=s.replace(/^\s*<tbody[^>]*>/i,'').replace(/<\/tbody>\s*$/i,'');
  return s;
}
function injectTableBody(tableNo, html){
  jQuery('#table'+tableNo+' > tbody').html( extractRows(html||'') );
}
function hydrateInputsFor(tableNo){
  jQuery('#table'+tableNo+' input, #table'+tableNo+' select, #table'+tableNo+' textarea').each(function(){
    var v=this.getAttribute('value'); if(v!=null) this.value=v;
  });
}

/* ===== Pager Enhancer (adds js-pager and data attrs) ===== */
function enhancePager(tableNo){
  var $host = jQuery('#table' + tableNo);

  $host.find('a').each(function(){
    var $a   = jQuery(this);
    var page = null;

    // 1) read & strip inline onclick
    var oc = $a.attr('onclick') || '';
    var m  = oc.match(/loadTable\(\s*(\d+)\s*,\s*(\d+)(?:\s*,\s*(\d+))?\s*\)/i);
    if (m){
      var tn  = parseInt(m[1],10);
      var pg  = parseInt(m[2],10);
      var lim = m[3] ? parseInt(m[3],10) : 20;

      // only convert if it's really for THIS table
      if (!isNaN(tn) && tn === tableNo && !isNaN(pg)){
        page = pg;
        // strip inline — this removes the old handler that was killing our delegate
        $a.removeAttr('onclick');
        // store limit if you ever want to use it (we default 30 anyway)
        $a.attr('data-limit', lim);
      }
    }

    // 2) if no inline, try href ?page=…
    if (!page){
      var href = $a.attr('href') || '';
      var mh   = href.match(/[?&#]page=(\d+)/i);
      if (mh) page = parseInt(mh[1],10);
    }

    // 3) numeric text like <a>3</a>
    if (!page){
      var t = jQuery.trim($a.text());
      if (/^\d+$/.test(t)) page = parseInt(t,10);
    }

    // 4) mark as pager (and ensure a stable href)
    if (page && !isNaN(page)){
      if (!$a.attr('href')) $a.attr('href','#');
      $a
        .addClass('js-pager')
        .attr('data-table', tableNo)
        .attr('data-page', page)
        .css('cursor','pointer');
    }
  });
}


/* ===== Pagination bindings (bind once) ===== */
// clear old
jQuery(document).undelegate('a.js-pager', 'click.pager');

// one handler for both tables (works with content loaded via AJAX)
jQuery(document).delegate('a.js-pager', 'click.pager', function(e){
  e.preventDefault();
  var $a = jQuery(this);
  var tn  = parseInt($a.attr('data-table'), 10);
  var pg  = parseInt($a.attr('data-page'), 10);
  var lim = parseInt($a.attr('data-limit') || '28', 10);
  if (tn && pg){
    loadTable(tn, pg, lim);
  }
  return false;
});


// Safety: any <a> that has inline loadTable(...)
jQuery(document).delegate('a', 'click.inlinepager', function(e){
  var oc = this.getAttribute('onclick') || '';
  var m  = oc.match(/loadTable\(\s*(\d+)\s*,\s*(\d+)(?:\s*,\s*(\d+))?\s*\)/i);
  if (m){
    e.preventDefault();
    var tn  = parseInt(m[1],10) || 0;
    var pg  = parseInt(m[2],10) || 1;
    var lim = m[3] ? parseInt(m[3],10) : 28;
    loadTable(tn, pg, lim);
    return false;
  }
});

/* ===== Load (paged) ===== */
function loadTable(tableNo, page, limit){
  page  = page  || 1;
  limit = limit || 28;
  jQuery.ajax({
    url:'get_cash_in_out_content_ajax.php', type:'POST', dataType:'html',
    data:{ date:jQuery('#date').val(), table_no:tableNo, page:page, limit:limit, force:0, all:0 },
    success:function(resp){
      injectTableBody(tableNo, resp);
      enhancePager(tableNo);
      hydrateInputsFor(tableNo);
      if(tableNo===2) jQuery('#f5').val(jQuery('#hidden_f5').val());
      applyManualIfReady();
    },
    complete:function(){ __tablesLoaded++; if(__tablesLoaded>=__tablesToLoad.length) applyManualIfReady(); }
  });
}

/* Strip pager when expanding for print */
function stripPager(html){
  return (html||'').replace(
    /<tr[^>]*>\s*<td[^>]*colspan\s*=\s*["']?9["']?[^>]*>.*?loadTable\((?:2|5)\s*,.*?<\/tr>/gis,''
  );
}

/* Expand list for print (DOM only) */
function fetchAllAndInject(tableNo, cb){
  jQuery.ajax({
    url:'get_cash_in_out_content_ajax.php', type:'POST', dataType:'html',
    data:{ date:jQuery('#date').val(), table_no:tableNo, all:1, page:1, limit:28, force:1 },
    success:function(html){
      html=jQuery.trim(html||''); html=stripPager(html);
      injectTableBody(tableNo, html); hydrateInputsFor(tableNo);
      try{ columnTotals(); setB28FromCurrent(); }catch(e){}
    },
    complete:function(){ if(typeof cb==='function') cb(); }
  });
}

/* ===== Number helpers / totals ===== */
function nv(sel){ var v=jQuery(sel).val()||"0"; v=v.toString().replace(/,/g,''); v=parseFloat(v); return isNaN(v)?0:v; }
function nhtml(sel){ var v=jQuery(sel).html()||"0"; v=v.toString().replace(/,/g,''); v=parseFloat(v); return isNaN(v)?0:v; }

function input(el){
  jQuery(el).attr('value',jQuery(el).val());
  var id=jQuery(el).attr('id'), kId, mId, tId, vId;
  if(id==='b4'||id==='c4'||id==='d4'||id==='e4'){ var f4=nv('#b4')+nv('#c4')+nv('#d4')+nv('#e4'); jQuery('#f4').html(f4); }
  else if(/^b(9|1[0-9]|20)$/.test(id)){ var sum=0; for(var r=9;r<=20;r++) sum+=nv('#b'+r); jQuery('#b21').html(sum); }
  else if(/^e(7|8|9|10|11)$/.test(id)){ var e12=nv('#e7')+nv('#e8')+nv('#e9')+nv('#e10')+nv('#e11'); jQuery('#e12').html(e12); jQuery('#e21').html(nhtml('#f4') + nhtml('#f7') + e12); }
  else if(/^j(5|6|7|8|9|1[0-9]|2[0-9]|30)$/.test(id)){ kId=id.replace('j','k'); jQuery('#'+kId).attr('value', nv('#'+id)*0.9 ); }
  else if(/^l(5|6|7|8|9|1[0-9]|2[0-9]|30)$/.test(id)){ kId=id.replace('l','k'); mId=id.replace('l','m'); jQuery('#'+mId).attr('value', nv('#'+kId) - nv('#'+id) ); }
  else if(/^s(5|6|7|8|9|1[0-9]|2[0-9]|30)$/.test(id)){ tId=id.replace('s','t'); jQuery('#'+tId).attr('value', nv('#'+id)*0.9 ); }
  else if(/^u(5|6|7|8|9|1[0-9]|2[0-9]|30)$/.test(id)){ tId=id.replace('u','t'); vId=id.replace('u','v'); jQuery('#'+vId).attr('value', nv('#'+tId) - nv('#'+id) ); }
}
function toNumber(raw){ if(raw==null) return 0; var s=String(raw).replace(/^\s+|\s+$/g,'').replace(/,/g,''); if(!s) return 0; var m=s.match(/^\((.*)\)$/); var v=parseFloat(m?('-'+m[1]):s); return isNaN(v)?0:v; }
function readSmart(sel){ var $el=jQuery(sel); if(!$el.length) return 0; var tag=($el.prop('tagName')||'').toUpperCase(); var raw=(tag==='INPUT'||tag==='SELECT'||tag==='TEXTAREA')?$el.val():$el.text(); return toNumber(raw); }
function writeSmart(sel,value,fmt){ if(typeof fmt==='undefined') fmt='int'; var $el=jQuery(sel); if(!$el.length) return; var out=value; if(fmt==='int') out=Math.round(value).toString(); else if(fmt==='2dp') out=Number(value).toFixed(2); else if(fmt==='locale') out=Math.round(value).toLocaleString(); var tag=($el.prop('tagName')||'').toUpperCase(); if(tag==='INPUT'||tag==='SELECT'||tag==='TEXTAREA'){ $el.val(out); $el.attr('value',out);} else {$el.text(out);} }
function sumRange(col,from,to){ var t=0,r; for(r=from;r<=to;r++) t+=readSmart('#'+col+r); return t; }

var manualValues={ mbb:null, pbe:null, pl:null };

function computeOngFromTotals(){
  function sum(letter){ var t=0,r; for(r=40;r<=58;r++) t+=readSmart('#'+letter+r); return t; }
  var d59=readSmart('#d59')||sum('d'), e59=readSmart('#e59')||sum('e'), g59=readSmart('#g59')||sum('g'),
      h59=readSmart('#h59')||sum('h'), j59=readSmart('#j59')||sum('j'), k59=readSmart('#k59')||sum('k'),
      m59=readSmart('#m59')||sum('m'), n59=readSmart('#n59')||sum('n'), p59=readSmart('#p59')||sum('p'), q59=readSmart('#q59')||sum('q');

  var redNet=(d59-e59)+(g59-h59)+(j59-k59), greenNet=(m59-n59), blueNet=(p59-q59);
  var val_pl=readSmart('#val_pl'), val_mbb=readSmart('#val_mbb'), val_pbe=readSmart('#val_pbe');
  var ong=val_pl+redNet, pl=val_pl+redNet, mbb=val_mbb+greenNet, pbe=val_pbe+blueNet;
  return {ong:ong, mbb:mbb, pbe:pbe, pl:pl};
}
function columnTotals(){
  var S={d:0,e:0,g:0,h:0,j:0,k:0,m:0,n:0,p:0,q:0}, r;
  for(r=40;r<=58;r++){ S.d+=readSmart('#d'+r); S.e+=readSmart('#e'+r); S.g+=readSmart('#g'+r); S.h+=readSmart('#h'+r); S.j+=readSmart('#j'+r); S.k+=readSmart('#k'+r); S.m+=readSmart('#m'+r); S.n+=readSmart('#n'+r); S.p+=readSmart('#p'+r); S.q+=readSmart('#q'+r); }
  writeSmart('#d59',S.d); writeSmart('#e59',S.e); writeSmart('#g59',S.g); writeSmart('#h59',S.h); writeSmart('#j59',S.j); writeSmart('#k59',S.k); writeSmart('#m59',S.m); writeSmart('#n59',S.n); writeSmart('#p59',S.p); writeSmart('#q59',S.q);
  writeSmart('#gb59', (S.m-S.n)); writeSmart('#b59', (S.p-S.q));
  setB28FromCurrent();
  writeSmart('#b21', sumRange('b',10,20), 'int');
}
/* New: one source of truth for B28 */
function computeB28(){
  var e21 = readSmart('#e21');
  var b7  = readSmart('#b7');
  var b8  = readSmart('#b8');
  var b9  = readSmart('#b9');
  var b21 = readSmart('#b21');
  var m39 = readSmart('#total_m39_all');   // “total_m39_all”
  var v39 = readSmart('#total_v39_all');   // “total_v39_all”
  return e21 - b7 - b8 - b9 - b21 - m39 - v39;
}

/* Lock B28 from manual edits */
(function lockB28(){
  window.__b28_allow=false;
  window.setB28=function(formatted){ window.__b28_allow=true; jQuery('#b28').text(formatted); window.__b28_allow=false; };
  var _text=jQuery.fn.text, _val=jQuery.fn.val;
  jQuery.fn.text=function(){ if(arguments.length && this.filter('#b28').length && !window.__b28_allow){ return this; } return _text.apply(this,arguments); };
  jQuery.fn.val=function(){ if(arguments.length && this.filter('#b28').length && !window.__b28_allow){ return this; } return _val.apply(this,arguments); };
  var el=document.getElementById('b28');
  if(el && window.MutationObserver){
    new MutationObserver(function(){
      if(window.__b28_allow) return;
      var calc=computeOngFromTotals(); var formatted=Math.round(calc.ong).toLocaleString();
      window.__b28_allow=true; el.textContent=formatted; window.__b28_allow=false;
    }).observe(el,{childList:true,characterData:true,subtree:true});
  }
})();
function setB28FromCurrent(){
  var o=computeOngFromTotals(), formatted=Math.round(o.ong).toLocaleString();
  // window.setB28(formatted);
  var b28 = computeB28();
  window.setB28(Math.round(b28).toLocaleString());
  writeSmart('#c59', o.ong, 'locale'); writeSmart('#gb59', o.mbb, 'locale'); writeSmart('#b59', o.pbe, 'locale');
  writeSmart('#b7', o.pl, 'locale'); writeSmart('#b8', o.mbb, 'locale'); writeSmart('#b9', o.pbe, 'locale');
}

/* Mirror before saving */
function syncInputAttributesFor(tableNo){
  jQuery('#table'+tableNo+' input, #table'+tableNo+' select, #table'+tableNo+' textarea').each(function(){
    this.setAttribute('value', this.value);
  });
}
function syncAllTablesAttributes(){ var arr=[1,2,4,5,7,8]; for(var i=0;i<arr.length;i++){ syncInputAttributesFor(arr[i]); } }

/* live mirror + recalc */
jQuery(document).delegate('input,select,textarea','input change',function(){
  this.setAttribute('value', this.value); columnTotals();
});

/* One-shot recompute (don’t set #b28 directly here) */
function recomputeAllNow(){
  var j21=0,k21=0,l21=0,m21=0,s21=0,t21=0,u21=0,v21=0, r;
  for(r=3;r<=20;r++){ j21+=nv('#j'+r); k21+=nv('#k'+r); l21+=nv('#l'+r); s21+=nv('#s'+r); t21+=nv('#t'+r); u21+=nv('#u'+r); }
  m21=k21-l21; v21=t21-u21;
  jQuery('#j21').html(j21); jQuery('#k21').html(k21); jQuery('#l21').html(l21); jQuery('#m21').html(m21);
  jQuery('#s21').html(s21); jQuery('#t21').html(t21); jQuery('#u21').html(u21); jQuery('#v21').html(v21);

  var j39=0,k39=0,l39=0,m39=0,s39=0,t39=0,u39=0,v39=0, r2;
  for(r2=3;r2<=30;r2++){ j39+=nv('#j'+r2); k39+=nv('#k'+r2); l39+=nv('#l'+r2); s39+=nv('#s'+r2); t39+=nv('#t'+r2); u39+=nv('#u'+r2); }
  m39=k39-l39; v39=t39-u39;
  jQuery('#j39').html(j39); jQuery('#k39').html(k39); jQuery('#l39').html(l39); jQuery('#m39').html(m39);
  jQuery('#s39').html(s39); jQuery('#t39').html(t39); jQuery('#u39').html(u39); jQuery('#v39').html(v39);

  var f7= nv('#a5')+nv('#b5')+nv('#c5')+nv('#d5')+nv('#e5')+nv('#f5')+ nv('#a6')+nv('#b6')+nv('#c6')+nv('#d6')+nv('#e6')+nv('#f6');
  jQuery('#f7').html(f7);
  jQuery('#e21').html(nhtml('#f4') + f7 +nv('#e7')+nv('#e8')+nv('#e9')+nv('#e10')+nv('#e11'));

  var d59=0,e59=0,g59=0,h59=0,k59=0,l59=0, rr;
  for(rr=40; rr<=58; rr++){ d59+=nv('#d'+rr); e59+=nv('#e'+rr); g59+=nv('#g'+rr); h59+=nv('#h'+rr); k59+=nv('#k'+rr); l59+=nv('#l'+rr); }
  writeSmart('#d59',d59); writeSmart('#e59',e59); writeSmart('#g59',g59); writeSmart('#h59',h59); writeSmart('#k59',k59); writeSmart('#l59',l59);

  var c36=nv('#c36'); writeSmart('#c39', c36 + d59 - e59 + g59 - h59 + k59 - l59);
  writeSmart('#u42', nhtml('#l21') + nhtml('#u21') + nhtml('#l39') + nhtml('#u39'));

  columnTotals();
  setB28FromCurrent();
}

/* ===== Initial boot (with interval) ===== */
var __calcTimer = null;
function getTableContents(){
  __tablesLoaded=0;
  for(var i=0;i<__tablesToLoad.length;i++){ loadTable(__tablesToLoad[i],1,30); }
  if(__calcTimer){ clearInterval(__calcTimer); }
  __calcTimer = setInterval(function(){ recomputeAllNow(); }, 1000);
}

/* ===== SAVE PIPELINE (race-free + clean pager HTML for DB) ===== */
function ajaxSaveTables(expandForPrint, done){
  function persistCurrent(cb){
    if (window.__calcTimer) { clearInterval(window.__calcTimer); window.__calcTimer = null; }

    // Recompute once and freeze b28 (raw number)
    recomputeAllNow();
    var o = computeOngFromTotals();
    var b28Raw = Math.round(o.ong);
    var $b28 = jQuery('#table4 #b28');
    if ($b28.length) { window.__b28_allow=true; $b28.text(b28Raw); window.__b28_allow=false; }

    columnTotals();
    setB28FromCurrent();
    syncAllTablesAttributes();

    var list=[1,4,7,8], remaining=list.length, ok=true;

    for(var i=0;i<list.length;i++){
      (function(tn){
        var payloadHtml = jQuery('#table'+tn+' tbody').html();

        // Keep DB clean for tables 2 & 5: strip helper class/data (onclick stays)
        if (tn === 2 || tn === 5){
          var $tmp = jQuery('<div>').html(payloadHtml);
          $tmp.find('a.js-pager').removeClass('js-pager').removeAttr('data-page data-table');
          payloadHtml = $tmp.html();
        }

        // Freeze manual values to avoid carry-forward overwrite after reload
        jQuery('#val_mbb, #val_pbe, #val_pl').attr('data-frozen','1');

        var extra = {};
        if (tn === 4) { extra.b28_curr = b28Raw; }

        jQuery.ajax({
          url:'update_cash_in_out_content_ajax.php',
          type:'POST',
          data: jQuery.extend({ date:jQuery('#date').val(), table_no:tn, content: payloadHtml }, extra),
          dataType:'text',
          success:function(r){ if(jQuery.trim(r)!=='success') ok=false; },
          complete:function(){
            remaining--;
            if(remaining===0){
              if(!window.__calcTimer){ __calcTimer = setInterval(function(){ recomputeAllNow(); }, 1000); }
              if(typeof cb==='function') cb(ok);
            }
          }
        });
      })(list[i]);
    }
  }

  persistCurrent(function(ok){
    if(!ok){ if(typeof done==='function') done(false); return; }
    if(expandForPrint){
      fetchAllAndInject(2, function(){ fetchAllAndInject(5, function(){ if(typeof done==='function') done(true); }); });
    }else{
      if(typeof done==='function') done(true);
    }
  });
}


/* ===== Boot + Buttons ===== */
jQuery(function($){
  var date=$('#date').val();

  if(date){
    $.ajax({ url:'check_cash_in_out_date.php', type:'POST', dataType:'json', data:{date:date},
             success:function(resp){ if(resp && resp.exists){ makePageLocked(); } } });
    loadManualOverrides(date);
  }

  columnTotals(); setB28FromCurrent(); getTableContents();

  // Save
  $('#btn-save').unbind('click').bind('click', function(){
    $('#message').text('Saving...');
    ajaxSaveTables(false, function(ok){ if(ok) window.location.reload(); else $('#message').text('Save error.'); });
  });

  // Print
  $('#btn-print').unbind('click').bind('click', function(){
    $('#message').text('Saving before print…');
    ajaxSaveTables(true, function(ok){
      if(!ok){ $('#message').text('Save error.'); alert('Save failed, cannot print.'); return; }
      $('#message').text('');
      var d=$('#date').val();
      window.open('./print_cash_in_out.php?date='+encodeURIComponent(d),'_blank');
      setTimeout(function(){ window.location.reload(); }, 1000);
    });
  });

  // Date change
  $('#date').unbind('change').bind('change', function(){
    window.location.href='./cash_in_out.php?date='+$(this).val();
  });

  // function snapshotAndClose(done){
  //   // stop live recompute
  //   if (window.__calcTimer) { clearInterval(window.__calcTimer); window.__calcTimer = null; }

  //   // 1) recompute once and freeze b28 as raw number
  //   recomputeAllNow();
  //   var calc = computeOngFromTotals();
  //   var b28Raw = Math.round(calc.ong);
  //   (function freezeB28(){
  //     var $b28 = jQuery('#table4 #b28');
  //     if ($b28.length){
  //       window.__b28_allow = true;
  //       $b28.text(b28Raw);
  //       window.__b28_allow = false;
  //     }
  //   })();

  //   // mirror values
  //   columnTotals();
  //   setB28FromCurrent();
  //   syncAllTablesAttributes();

  //   var date = jQuery('#date').val();
  //   var ok = true;

  //   // 2) expand T2 & T5 to ALL rows (fresh from DB, no pagers)
  //   fetchAllAndInject(2, function(){
  //     fetchAllAndInject(5, function(){

  //       // 3) collect HTML payloads
  //       var t1 = jQuery('#table1 tbody').html();
  //       var t2 = jQuery('#table2 tbody').html(); // now ALL rows
  //       var t4 = jQuery('#table4 tbody').html();
  //       var t5 = jQuery('#table5 tbody').html(); // now ALL rows
  //       var t7 = jQuery('#table7 tbody').html();
  //       var t8 = jQuery('#table8 tbody').html();

  //       // 4) save 1,4,7,8 normally; save 2 & 5 with force_save=1
  //       var jobs = [
  //         {no:1, content:t1},
  //         {no:4, content:t4, b28_curr:b28Raw},
  //         {no:7, content:t7},
  //         {no:8, content:t8},
  //         {no:2, content:t2, force_save:1}, // ONLY during close
  //         {no:5, content:t5, force_save:1}, // ONLY during close
  //       ];

  //       var remain = jobs.length;

  //       function next(){ if(--remain===0) doCloseFlag(); }

  //       jQuery.each(jobs, function(_, job){
  //         jQuery.ajax({
  //           url:'update_cash_in_out_content_ajax.php',
  //           type:'POST',
  //           data: jQuery.extend({date:date, table_no:job.no, content:job.content}, 
  //                               job.b28_curr ? {b28_curr: job.b28_curr} : {},
  //                               job.force_save ? {force_save:1} : {}),
  //           dataType:'text',
  //           success:function(r){ if(jQuery.trim(r)!=='success') ok=false; },
  //           complete: next
  //         });
  //       });

  //       // 5) mark closed (your existing endpoint)
  //       function doCloseFlag(){
  //         if(!ok){ done && done(false); return; }

  //         jQuery.ajax({
  //           url:'close_cash_in_out_ajax.php',
  //           type:'POST',
  //           dataType:'json',
  //           data:{
  //             date:date,
  //             b7:readSmart('#b7'),
  //             b8:readSmart('#b8'),
  //             b9:readSmart('#b9')
  //           },
  //           success:function(resp){
  //             done && done(resp && resp.status==='success');
  //           },
  //           error:function(){ done && done(false); }
  //         });
  //       }
  //     });
  //   });
  // }


  // ---- CLOSE (freeze + save snapshot of T2/T5, then mark closed) ----
  $('#btn-close').unbind('click').bind('click', function () {
    var date = $('#date').val();
    if (!date) { alert('Pick a date first.'); return; }
    if (!confirm('Close records for ' + date + '?')) return;

    $('#message').text('Closing (freezing data)…');

    // stop the 1s recompute loop if running
    if (window.__calcTimer) { clearInterval(window.__calcTimer); window.__calcTimer = null; }

    // 1) recompute once & freeze #b28 as a raw number
    recomputeAllNow();
    var o = computeOngFromTotals();
    var b28Raw = Math.round(o.ong);
    (function freezeB28() {
      var $b28 = $('#table4 #b28');
      if ($b28.length) {
        window.__b28_allow = true;
        $b28.text(b28Raw);   // write plain number into DOM so it’s captured in HTML
        window.__b28_allow = false;
      }
    })();

    // mirror inputs into value="" so the serialized HTML has the latest values
    columnTotals();
    setB28FromCurrent();
    syncAllTablesAttributes();

    // 2) expand T2 & T5 to ALL rows (fresh from DB, no pagers) so we save a full snapshot
    fetchAllAndInject(2, function () {
      fetchAllAndInject(5, function () {

        // 3) collect HTML payloads after expansion
        var date = $('#date').val();
        var jobs = [
          { no: 1, content: $('#table1 tbody').html() },
          { no: 4, content: $('#table4 tbody').html(), b28_curr: b28Raw },
          { no: 7, content: $('#table7 tbody').html() },
          { no: 8, content: $('#table8 tbody').html() },

          // ONLY during close we persist 2 & 5 (live tables) with force_save=1
          { no: 2, content: $('#table2 tbody').html(), force_save: 1 },
          { no: 5, content: $('#table5 tbody').html(), force_save: 1 }
        ];

        var ok = true, remaining = jobs.length;
        function afterOne(){ if(--remaining === 0) markClosed(); }

        $.each(jobs, function (_, job) {
          $.ajax({
            url: 'update_cash_in_out_content_ajax.php',
            type: 'POST',
            dataType: 'text',
            data: $.extend(
              { date: date, table_no: job.no, content: job.content },
              job.b28_curr ? { b28_curr: job.b28_curr } : {},
              job.force_save ? { force_save: 1 } : {}
            ),
            success: function (r) { if ($.trim(r) !== 'success') ok = false; },
            complete: afterOne
          });
        });

        // 4) finally mark the day closed (your existing endpoint)
        function markClosed() {
          if (!ok) { $('#message').text('Save error during close.'); return; }
          $.ajax({
            url: 'close_cash_in_out_ajax.php',
            type: 'POST',
            dataType: 'json',
            data: { date: date, b7: readSmart('#b7'), b8: readSmart('#b8'), b9: readSmart('#b9') },
            success: function (resp) {
              if (resp && resp.status === 'success') {
                $('#message').text('Closed.');
                makePageLocked();
              } else {
                alert('Close failed: ' + (resp && resp.error ? resp.error : 'unknown error'));
              }
            },
            error: function(){ alert('Close failed (network/server).'); }
          });
        }
      });
    });
  });

});
</script>
</body>
</html>
