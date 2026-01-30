<?php
// ajax_pending_summary.php (REAL VERSION)

// Ensure clean output
while (ob_get_level() > 0) { ob_end_clean(); }
ob_start();

// Quiet bootstrap that does NOT echo HTML
include('../include/page_header.php'); 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Fatal-safety: always return JSON
register_shutdown_function(function(){
  $err = error_get_last();
  if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
    $json = json_encode(['error'=>'Fatal: '.$err['message'], 'table_html'=>'', 'summary_html'=>'']);
    while (ob_get_level() > 0) { ob_end_clean(); }
    echo $json;
  }
});

// INPUT: date range
$dateFrom = isset($_REQUEST['date_from']) ? trim($_REQUEST['date_from']) : '';
$dateTo   = isset($_REQUEST['date_to'])   ? trim($_REQUEST['date_to'])   : '';

$isFrom = (bool)preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom);
$isTo   = (bool)preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo);

// SESSION GUARD
if (!isset($_SESSION['login_branchid'])) {
  $json = json_encode([
    'error' => 'Session missing: login_branchid',
    'table_html' => '<div style="padding:10px;">Session expired.</div>',
    'summary_html' => '<div class="summary-box"><div class="summary-title">Summary</div><div>Session expired.</div></div>'
  ]);
  while (ob_get_level() > 0) { ob_end_clean(); }
  echo $json; exit;
}

// WHERE (range-inclusive); default = ALL
$branch = mysql_real_escape_string($_SESSION['login_branchid']);
$baseWhere = "cd.branch_id = '{$branch}' AND cd.customer_status IS NOT NULL";

if ($isFrom) {
  $baseWhere .= " AND DATE(cd.created_date) >= '".mysql_real_escape_string($dateFrom)."'";
}
if ($isTo) {
  $baseWhere .= " AND DATE(cd.created_date) <= '".mysql_real_escape_string($dateTo)."'";
}

// QUERIES
$statement = "`customer_details` cd WHERE {$baseWhere} ORDER BY cd.created_date ASC, cd.id ASC";
$recordsPerPage = 30;
$fromRecordNum  = 0;

$sql       = mysql_query("SELECT * FROM {$statement} LIMIT {$fromRecordNum}, {$recordsPerPage}");
$sqlPaging = mysql_query("SELECT * FROM {$statement}");
if ($sql===false || $sqlPaging===false) {
  $json = json_encode(['error'=>'List query failed: '.mysql_error(), 'table_html'=>'', 'summary_html'=>'']);
  while (ob_get_level() > 0) { ob_end_clean(); }
  echo $json; exit;
}

// ─────────────────────────────────────────────────────────────
// Summary query (same logic as pending_approval.php, but with date range)
// ─────────────────────────────────────────────────────────────
$sumSql = mysql_query("
  SELECT 
    cd.`FROM` AS src,
    SUM(cd.customer_status='APPROVED') AS approved_cnt,
    SUM(cd.customer_status='REJECTED') AS rejected_cnt,

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
if ($sumSql===false) {
  $json = json_encode(['error'=>'Summary query failed: '.mysql_error(), 'table_html'=>'', 'summary_html'=>'']);
  while (ob_get_level() > 0) { ob_end_clean(); }
  echo $json; exit;
}

// Build HTML
function rm($n){ if($n===null || $n==='') $n=0; return 'RM '.number_format((float)$n,2); }

ob_start(); ?>
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
  $ctr=0;
  $r = mysql_num_rows($sqlPaging);
  if($r>0){
    while($row = mysql_fetch_assoc($sql)){ $ctr++; ?>
      <tr>
        <td><?php echo (($fromRecordNum)+$ctr)."."; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['nric']; ?></td>
        <td><?php echo $row['created_date']; ?></td>
        <td>
          <?php
          $company_q = mysql_query("SELECT company FROM customer_employment WHERE customer_id='".$row['id']."' LIMIT 1");
          $company   = mysql_fetch_assoc($company_q);
          echo $company ? $company['company'] : '';
          ?>
        </td>
        <td>
          <?php
          $loan_q = mysql_query("SELECT SUM(loan_amount) AS loan_amount FROM customer_loanpackage_draft WHERE customer_id='".$row['id']."'");
          $loan = mysql_fetch_assoc($loan_q);
          echo "RM ".number_format((float)($loan['loan_amount'] ?: 0), 2);
          ?>
        </td>
        <td>
          <?php
          $loan2_q = mysql_query("SELECT payout_amount AS loan_amount FROM monthly_payment_record_draft WHERE customer_id='".$row['id']."' LIMIT 1");
          $loan2 = mysql_fetch_assoc($loan2_q);
          echo "RM ".number_format((float)($loan2['loan_amount'] ?: 0), 2);
          ?>
        </td>
        <td>
          <?php
          $status = $row['customer_status'];
          if ($status==='REJECTED')      echo '<span style="color:red;">REJECTED</span>';
          elseif ($status==='APPROVED')  echo '<span style="color:green;">APPROVED</span>';
          else                           echo $status;
          ?>
        </td>
        <td>
          <center>
            <?php if($row['customer_status']=='PENDING APPROVAL'){ ?>
              <a href="edit_customer_loan.php?id=<?php echo $row['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
            <?php } ?>
          </center>
        </td>
      </tr>
    <?php }
  } else { ?>
    <tr><td colspan="9" style="text-align:center;color:#666;">No records found.</td></tr>
  <?php } ?>
</table>
<?php $table_html = ob_get_clean();

// ─────────────────────────────────────────────────────────────
// Build summary HTML (per FROM + overall totals)
// ─────────────────────────────────────────────────────────────
$summary = [];
$grandApprovedCount = 0;
$grandMonthly       = 0.0;
$grandInstalment    = 0.0;

while($s = mysql_fetch_assoc($sumSql)) {
  $summary[] = $s;
  $grandApprovedCount += (int)$s['approved_cnt'];
  $grandMonthly       += (float)$s['approved_amount_mon'];
  $grandInstalment    += (float)$s['approved_amount_ins'];
}
$grandAll = $grandMonthly + $grandInstalment;

ob_start(); ?>
<div class="summary-box">
  <div class="summary-title">Summary</div>
  <?php if(empty($summary)) { ?>
    <div style="color:#666;">No data.</div>
  <?php } else { foreach($summary as $s) { ?>
    <div class="summary-item">
      <div style="font-weight:bold; font-size:16px; margin-bottom:4px;">
        <?php echo htmlspecialchars(($s['src']!=='' && $s['src']!==null) ? $s['src'] : 'Unknown'); ?>
      </div>
      <div>Approved Customer : <?php echo (int)$s['approved_cnt']; ?></div>
      <div>Rejected Customer : <?php echo (int)$s['rejected_cnt']; ?></div>
      <div>Total Amount Approved (Instalment) : <?php echo rm($s['approved_amount_ins']); ?></div>
      <div>Total Amount Approved (Monthly) : <?php echo rm($s['approved_amount_mon']); ?></div>
    </div>
  <?php } ?>

    <hr style="margin:8px 0;">

    <div class="summary-item">
      <div style="font-weight:bold; font-size:16px; margin-bottom:4px;">Overall</div>
      <!-- 1. 一个total for monthly -->
      <!-- <div>Total Amount Approved (Monthly) : <?php echo rm($grandMonthly); ?></div> -->

      <!-- 2. 一个大总数 给全部的 -->
      <div>Total Amount (Instalment + Monthly) : <?php echo rm($grandAll); ?></div>

      <!-- 3. total approve人数 -->
      <div>Total Approved Customers : <?php echo (int)$grandApprovedCount; ?></div>
    </div>
  <?php } ?>
</div>
<?php $summary_html = ob_get_clean();

// Emit JSON cleanly
$out = ['table_html'=>$table_html, 'summary_html'=>$summary_html];
$json = json_encode($out);
while (ob_get_level() > 0) { ob_end_clean(); }
echo $json; // no closing PHP tag
