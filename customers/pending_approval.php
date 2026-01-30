<?php 
error_reporting(0);
include('../include/page_header.php'); 
$_SESSION['custid'] = '';

// ─────────────────────────────────────────────────────────────
// ORIGINAL server-side render for initial page load (no date filter)
// ─────────────────────────────────────────────────────────────
$cond = '';
if(isset($_POST['search']))
{
    if($_POST['customer_name'] != '')
    {
        $cond .= " and cd.name = '".mysql_real_escape_string($_POST['customer_name'])."'";	
    }
    
    if($_POST['nric'] != '')
    {
        $cond .= " and cd.nric = '".mysql_real_escape_string($_POST['nric'])."'";	
    }
    
    if($_POST['customer_code'] != '')
    {
        $cond .= " and cd.customercode2 = '".mysql_real_escape_string($_POST['customer_code'])."'";	
    }
    
    if($_POST['lcode'] != '')
    {
        $lcodeq = mysql_query("SELECT * FROM customer_loanpackage_draft WHERE 
                                loan_code = '".mysql_real_escape_string($_POST['lcode'])."' 
                                and branch_id = '".$_SESSION['login_branchid']."'");
        $lcode = mysql_fetch_assoc($lcodeq);
        if($lcode && isset($lcode['customer_id'])){
            $cond .= " and cd.id = '".mysql_real_escape_string($lcode['customer_id'])."'";
        }
    }
}

$baseWhere  = "cd.branch_id = '".$_SESSION['login_branchid']."' AND cd.customer_status IS NOT NULL";
if (!empty($cond)) { $baseWhere .= " ".$cond; }

// Default list: old → new
$statement = "`customer_details` cd WHERE ".$baseWhere." ORDER BY cd.created_date ASC, cd.id ASC";
$_SESSION['cust_s'] = $statement;

// Pagination (initial render only; AJAX has its own)
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$recordsPerPage = 30;
$fromRecordNum  = ($recordsPerPage * $page) - $recordsPerPage;

$sql       = mysql_query("SELECT * FROM {$statement} LIMIT {$fromRecordNum}, {$recordsPerPage}");
$sqlPaging = mysql_query("SELECT * FROM {$statement}");

// ─────────────────────────────────────────────────────────────
// Summary (initial render only; AJAX has its own)
//  - per FROM:
//      * approved_cnt / rejected_cnt
//      * approved_amount_ins  (Instalment)
//      * approved_amount_mon  (Monthly)
//  - overall:
//      * $grandMonthly       (total monthly)
//      * $grandInstalment    (total instalment)
//      * $grandAll           (overall total)
//      * $grandApprovedCount (total approved customers)
// ─────────────────────────────────────────────────────────────
$sumSql = mysql_query("
    SELECT 
        cd.`FROM`                           AS src,
        SUM(cd.customer_status='APPROVED')  AS approved_cnt,
        SUM(cd.customer_status='REJECTED')  AS rejected_cnt,

        -- Instalment (INS) approved total
        SUM(
            CASE WHEN cd.customer_status='APPROVED'
                 THEN IFNULL(lo.total_loan,0)
                 ELSE 0
            END
        ) AS approved_amount_ins,

        -- Monthly (MON) approved total
        SUM(
            CASE WHEN cd.customer_status='APPROVED'
                 THEN IFNULL(mo.total_mon,0)
                 ELSE 0
            END
        ) AS approved_amount_mon
    FROM customer_details cd

    -- Instalment loan (INS)
    LEFT JOIN (
        SELECT customer_id, SUM(loan_amount) AS total_loan
        FROM customer_loanpackage_draft
        GROUP BY customer_id
    ) lo ON lo.customer_id = cd.id

    -- Monthly loan (MON)
    LEFT JOIN (
        SELECT customer_id, SUM(payout_amount) AS total_mon
        FROM monthly_payment_record_draft
        GROUP BY customer_id
    ) mo ON mo.customer_id = cd.id

    WHERE {$baseWhere}
    GROUP BY cd.`FROM`
    ORDER BY cd.`FROM`
");

$summary = [];

$grandApprovedCount = 0;
$grandMonthly       = 0.0;
$grandInstalment    = 0.0;

while($row = mysql_fetch_assoc($sumSql)) {
    $summary[] = $row;

    $grandApprovedCount += (int)$row['approved_cnt'];
    $grandMonthly       += (float)$row['approved_amount_mon'];
    $grandInstalment    += (float)$row['approved_amount_ins'];
}

$grandAll = $grandMonthly + $grandInstalment;

function rm($n){ if($n===null || $n==='') $n = 0; return 'RM '.number_format((float)$n, 2); }
?>

<style>
/* buttons (kept) */
.submit_style{color:#eee;padding:4px;border:none;background:transparent url("<?php echo IMAGE_PATH.'remove.png'; ?>") no-repeat;cursor:pointer;background-size:21px 21px;text-indent:-1000em;width:25px;}
.app_style{color:#eee;padding:4px;border:none;background:transparent url("<?php echo IMAGE_PATH.'sent.png'; ?>") no-repeat;cursor:pointer;background-size:21px 21px;text-indent:-1000em;width:25px;}
.reject_style{color:#eee;padding:4px;border:none;background:transparent url("<?php echo IMAGE_PATH.'cancel-icon.png'; ?>") no-repeat;cursor:pointer;background-size:21px 21px;text-indent:-1000em;width:25px;}

/* table */
#list_table{border-collapse:collapse;border:none;}
#list_table tr th{height:36px;background:#666;text-align:left;padding-left:10px;color:#FFF;}
#list_table tr td{height:35px;padding-left:10px;padding-right:10px;}
#rl{width:318px;height:36px;background:url(../img/customers/right-left.jpg);}

/* search buttons */
#search{background:url(../img/enquiry/search-btn.jpg);width:109px;height:30px;border:none;cursor:pointer;}
#search:hover{background:url(../img/enquiry/search-btn-roll-over.jpg);}

/* layout: table 1280px + summary 320px (outside table) */
.page-wrap{max-width:1640px;margin:0 auto;padding:0 10px;}
.content-row{display:flex;gap:20px;align-items:flex-start;justify-content:center;}
.table-block{width:1280px;} /* keep table fixed at 1280 */
.summary-block{width:320px;flex:0 0 320px;}

.summary-box{
  background:#eaf5f7;border:1px solid #cfe2e6;padding:16px;border-radius:6px;
  width:320px;box-shadow:0 2px 8px rgba(0,0,0,.06);
  text-align:left;
}
.summary-title{font-size:20px;font-weight:bold;margin-bottom:12px;text-align:left;}
.summary-item{margin-bottom:16px;text-align:justify;text-justify:inter-word;}
.summary-item div{ text-align-last:left; word-break:break-word; }

/* responsive: stack on smaller screens so nothing overlaps */
@media (max-width: 1560px){
  .content-row{flex-direction:column;align-items:center;}
  .summary-block{width:100%;max-width:1280px;}
  .summary-box{width:95%; margin-bottom: 20px;}
}

/* simple loading */
.loading{opacity:.6; pointer-events:none;}
</style>

<center>
<div class="page-wrap">

  <!-- header / controls -->
  <table width="1280">
    <tr>
        <td width="65"><img src="../img/customers/customers.png"></td>
        <td>Customers</td>
    </tr>

    <!-- search form (full-page submit; unchanged) -->
    <tr>
        <td colspan="3">
          <div class="subnav">
            <a href="index.php" >Customer Listing</a>
            <a href="pending_approval.php" id="active-menu" >Pending Approval Listing</a>
          </div>	
        </td>
        <td align="right">
          <form action="pending_approval.php" method="post" style="margin:0;">
            <table>
              <tr>
                <td align="right" style="padding-right:10px">I/C No. </td>
                <td align="right" style="padding-right:10px"><input type="text" name="nric" id="nric" style="height:30px" /></td>
                <td align="right" style="padding-right:10px">Customer</td>
                <td style="padding-right:10px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                <td align="right" style="padding-right:10px">Customer ID </td>
                <td align="right" style="padding-right:10px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px" /></td>
                <td style="padding-right:10px">Agreement No </td>
                <td style="padding-right:10px"><input type="text" name="lcode" id="lcode" style="height:30px; width:70px" /></td>
                <td style="padding-right:8px">
                  <input type="submit" id="search" name="search" value="" />
                </td>
              </tr>
            </table>
          </form>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <div id="message" style="width:100%;">
            <?php
            if($_SESSION['msg'] != '')
            {
                echo $_SESSION['msg'];
                $_SESSION['msg'] = '';
            }
            ?>
            </div>
        </td>
    </tr>   
  </table>

  <!-- DATE RANGE FILTER: AJAX (does NOT share with name/NRIC/etc) -->
  <table width="1280">
    <tr>
      <td align="left" style="padding:20px 10px;">
        <b>Request Date Range:</b>
        &nbsp;From&nbsp;<input type="date" id="date_from" style="height:30px" />
        &nbsp;To&nbsp;<input type="date" id="date_to" style="height:30px" />
        &nbsp;<button hidden type="button" id="btnFilterDate" style="height:32px">Filter</button>
        &nbsp;<button hidden type="button" id="btnClearDate" style="height:32px">Clear</button>
      </td>
    </tr>
  </table>
        
  <!-- content: TABLE (1280) + SUMMARY (320) -->
  <div class="content-row">
    <!-- TABLE container (will be replaced by AJAX) -->
    <div class="table-block" id="table-container">
      <table width="1280" id="list_table">
        <tr>
          <th width="40">No.</th>
          <th width="190">Name</th>
          <th width="150">I.C Number</th>
          <th width="100">Request Date</th>
          <th width="200">Company</th>
          <th width="150">Total Loan (INS)</th>
          <th width="150">Total Loan (MON)</th>
          <th width="100">Status</th>
          <th width="200"><div id="rl"></div></th>
        </tr>
        <?php 
        $ctr = 0;
        $r = mysql_num_rows($sqlPaging);
        if($r>0){
            while($get_q = mysql_fetch_assoc($sql)){ 
                $ctr++;
        ?>
        <tr>
          <td><?php echo (($fromRecordNum)+$ctr)."."; ?></td>
          <td><?php echo $get_q['name']; ?></td>
          <td><?php echo $get_q['nric']; ?></td>
          <td><?php echo $get_q['created_date']; ?></td>
          <td>
            <?php 
              $company_q = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$get_q['id']."'");
              $company   = mysql_fetch_assoc($company_q);
              echo $company ? $company['company'] : '';
            ?>
          </td>
          <td>
            <?php
              $loan_q = mysql_query("SELECT SUM(loan_amount) AS loan_amount 
                                      FROM customer_loanpackage_draft 
                                      WHERE customer_id = '".$get_q['id']."' ");
              $loan = mysql_fetch_assoc($loan_q);
              echo "RM ".number_format((float)($loan['loan_amount'] ?: 0), 2);
            ?>        
          </td>
          <td>
            <?php
              $loan2_q = mysql_query("SELECT payout_amount AS loan_amount 
                                      FROM monthly_payment_record_draft 
                                      WHERE customer_id = '".$get_q['id']."' ");
              $loan2 = mysql_fetch_assoc($loan2_q);
              echo "RM ".number_format((float)($loan2['loan_amount'] ?: 0), 2);
            ?>        
          </td>
          <td>
            <?php 
              $status = $get_q['customer_status'];
              if ($status == 'REJECTED') {
                  echo '<span style="color:red;">' . $status . '</span>';
              } elseif ($status == 'APPROVED') {
                  echo '<span style="color:green;">' . $status . '</span>';
              } else {
                  echo $status;
              }
            ?>
          </td>
          <td>
            <center>
              <?php if($get_q['customer_status'] =='PENDING APPROVAL'){ ?>
                <a href="edit_customer_loan.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
              <?php } ?>
            </center>
          </td>
        </tr>
        <?php 
            } // while
        } // if
        ?>
      </table>
    </div>

    <!-- SUMMARY container (will be replaced by AJAX) -->
    <div class="summary-block" id="summary-container">
      <div class="summary-box">
        <div class="summary-title">Summary</div>
        <?php if(empty($summary)) { ?>
          <div style="color:#666;">No data.</div>
        <?php } else { foreach($summary as $s) { ?>
          <div class="summary-item">
            <div style="font-weight:bold; font-size:16px; margin-bottom:4px;">
              <?php echo htmlspecialchars(($s['src'] !== '' && $s['src'] !== null) ? $s['src'] : 'Unknown'); ?>
            </div>
            <div>Approved Customer : <?php echo (int)$s['approved_cnt']; ?></div>
            <div>Rejected Customer : <?php echo (int)$s['rejected_cnt']; ?></div>
            <div>Total Amount Approved (Instalment) : <?php echo rm($s['approved_amount_ins']); ?></div>
            <div>Total Amount Approved (Monthly) : <?php echo rm($s['approved_amount_mon']); ?></div>
          </div>
        <?php } ?>

          <!-- Overall totals -->
          <hr style="margin:8px 0;">

          <div class="summary-item">
            <div style="font-weight:bold; font-size:16px; margin-bottom:4px;">Overall</div>
            <!-- 1. 一个 total for monthly -->
            <!-- <div>Total Amount Approved (Monthly) : <?php echo rm($grandMonthly); ?></div> -->

            <!-- 2. 一个大总数 给全部的 -->
            <div>Total Amount (Inst + Mon) : <?php echo rm($grandAll); ?></div>

            <!-- 3. total approve人数 -->
            <div>Total Approved Customers : <?php echo (int)$grandApprovedCount; ?></div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div><!-- /content-row -->

</div><!-- /page-wrap -->
</center>

<script>
(function(){
  function setLoading(isOn){
    $('#table-container, #summary-container').toggleClass('loading', isOn);
  }

  // Date validation: YYYY-MM-DD
  function isISODate(s){ return /^\d{4}-\d{2}-\d{2}$/.test(s); }

  function refreshByRange(df, dt){
    $('#table-container, #summary-container').toggleClass('loading', true);

    // Auto-swap if from > to (client-side)
    if (isISODate(df) && isISODate(dt) && df > dt) {
      var tmp = df; df = dt; dt = tmp;
      $('#date_from').val(df);
      $('#date_to').val(dt);
    }

    $.ajax({
      url: 'ajax_pending_summary.php',
      type: 'POST',
      data: { date_from: df || '', date_to: dt || '' },
      dataType: 'json',
      cache: false,
      success: function(res){
        if (!res || res.table_html === undefined || res.summary_html === undefined){
          console.error('Unexpected JSON:', res);
          alert('Unexpected server response. See console.');
          return;
        }
        $('#table-container').html(res.table_html);
        $('#summary-container').html(res.summary_html);
      },
      error: function(xhr, status, err){
        console.error('AJAX error:', status, err);
        console.error('Raw response:', xhr.responseText);
        alert('Failed to load (' + status + '). Open console for details.');
      },
      complete: function(){
        $('#table-container, #summary-container').toggleClass('loading', false);
      }
    });
  }

  $(function(){
    // Live refresh on change:
    $('#date_from, #date_to').on('change', function(){
      refreshByRange($('#date_from').val(), $('#date_to').val());
    });

    $('#btnFilterDate').on('click', function(){
      refreshByRange($('#date_from').val(), $('#date_to').val());
    });

    $('#btnClearDate').on('click', function(){
      $('#date_from').val('');
      $('#date_to').val('');
      refreshByRange('', '');
    });
  });
})();
</script>

<style>
.loading { opacity:.6; pointer-events:none; }
</style>
