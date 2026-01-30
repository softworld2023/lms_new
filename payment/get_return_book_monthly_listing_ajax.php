<?php
// ajax/monthly_payment_list.php
include_once '../include/dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Content-Type: text/html; charset=utf-8');
  echo '<tr><td colspan="7" style="text-align:center;">- Invalid Request -</td></tr>';
  exit;
}

// =========================
// Inputs
// =========================
$selected_year  = isset($_POST['year']) ? $_POST['year'] : date('Y');
$selected_month = isset($_POST['month']) ? $_POST['month'] : date('m');

$loan_code      = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
$customer_code  = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';
$customer_name  = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

$db = isset($_SESSION['login_database']) ? $_SESSION['login_database'] : '';

if ($db === '') {
  header('Content-Type: text/html; charset=utf-8');
  echo '<tr><td colspan="7" style="text-align:center;">- Session database not set -</td></tr>';
  exit;
}

// normalize month to 2 digits
$selected_month = str_pad((int)$selected_month, 2, '0', STR_PAD_LEFT);
$ym = $selected_year . '-' . $selected_month;

// basic escaping (keeps your mysql_* style)
$loan_code     = mysql_real_escape_string($loan_code);
$customer_code = mysql_real_escape_string($customer_code);
$customer_name = mysql_real_escape_string($customer_name);
$ym            = mysql_real_escape_string($ym);

// =========================
// Filters (applied on final merged dataset)
// =========================
$extra = "";
if ($loan_code !== '') {
  $extra .= " AND X.loan_code = '$loan_code' ";
}
if ($customer_code !== '') {
  $extra .= " AND X.customer_code = '$customer_code' ";
}
// keep exact match as your original; change to LIKE if you want partial search
if ($customer_name !== '') {
  $extra .= " AND X.customer_name = '$customer_name' ";
}

// =========================
// Build MPD joins dynamically (only if master table exists)
// Requirements for master table (example):
//   $db.loan_collection: id, loan_code, customer_id
// If you have different columns, update the join + COALESCE fields below.
// =========================
$mpdMasterJoin = "";
$mpdLoanCodeExpr = "mpr2.loan_code";
$mpdCustomerIdExpr = "mpr2.customer_id";

if ($hasLoanMaster) {
  $mpdMasterJoin = "
    LEFT JOIN `$db`.`$loanMasterTable` lc ON lc.id = mpd.collection_id
  ";
  // Prefer MPR data, fallback to master table
  $mpdLoanCodeExpr = "COALESCE(mpr2.loan_code, lc.loan_code)";
  $mpdCustomerIdExpr = "COALESCE(mpr2.customer_id, lc.customer_id)";
}

// If no master table, still include MPD rows even if MPR is missing,
// but loan_code/customer may be NULL. We will group those by collection_id in PHP.
$mpdLoanCodeExprNoMaster = "mpr2.loan_code";


function getLoanStyle($loanCode) {
  $prefix = strtoupper(substr($loanCode, 0, 2));

  $valid = array('SD','AS','SB','BP','KT','MS','MJ','PP','NG','TS','LT','BT','DK');

  if ($prefix == 'SD') {
    return '';
  }

  if (!in_array($prefix, $valid)) {
    return "color:#FF0000;";
  }

  return '';
}

function formatLoanCode($loanCode) {
  $prefix = strtoupper(substr($loanCode, 0, 2));
  $highlight = array('SB','MS','MJ','PP','CL','BT');

  if (in_array($prefix, $highlight)) {
    return preg_replace(
      '/^([a-z]{2})/i',
      '<span style="color:red;">\1</span>',
      htmlspecialchars($loanCode)
    );
  }

  return htmlspecialchars($loanCode);
}


// =========================
// One merged query: MPR first, MPD added only when not duplicated in MPR
// Duplicate rule: same loan_code + same date + same amount exists in MPR
// =========================
// $sql = "
//   SELECT
//     X.source,
//     X.collection_id,
//     X.loan_code,
//     X.customer_code,
//     X.customer_name,
//     X.amount AS payout_amount,
//     X.pay_date AS monthly_date
//   FROM (
//     /* ========= SOURCE 1: monthly_payment_record ========= */
//     SELECT
//       'MPR' AS source,
//       NULL AS collection_id,
//       mpr.loan_code AS loan_code,
//       cd.customercode2 AS customer_code,
//       cd.name AS customer_name,
//       mpr.payout_amount AS amount,
//       mpr.monthly_date AS pay_date
//     FROM `$db`.`monthly_payment_record` mpr
//     JOIN `$db`.`customer_details` cd ON cd.id = mpr.customer_id
//     WHERE
//       mpr.month = '$ym'
//       AND mpr.status != 'DELETED'

//     UNION ALL

//     /* ========= SOURCE 2: monthly_payment_details ========= */
//     SELECT
//       'MPD' AS source,
//       mpd.collection_id AS collection_id,
//       " . ($hasLoanMaster ? $mpdLoanCodeExpr : $mpdLoanCodeExprNoMaster) . " AS loan_code,
//       cd2.customercode2 AS customer_code,
//       cd2.name AS customer_name,
//       mpd.payment_amount AS amount,
//       mpd.payment_date AS pay_date
//     FROM `$db`.`monthly_payment_details` mpd
//     LEFT JOIN `$db`.`monthly_payment_record` mpr2 ON mpr2.id = mpd.mprid
//     " . $mpdMasterJoin . "
//     LEFT JOIN `$db`.`customer_details` cd2 ON cd2.id = " . ($hasLoanMaster ? $mpdCustomerIdExpr : "mpr2.customer_id") . "
//     WHERE
//       DATE_FORMAT(mpd.payment_date, '%Y-%m') = '$ym'
//       AND (mpd.collection_status IS NULL OR mpd.collection_status != 'DELETED')
//       AND NOT EXISTS (
//         SELECT 1
//         FROM `$db`.`monthly_payment_record` mprx
//         WHERE
//           mprx.month = '$ym'
//           AND mprx.status != 'DELETED'
//           AND mprx.loan_code = " . ($hasLoanMaster ? $mpdLoanCodeExpr : "mpr2.loan_code") . "
//           AND DATE(mprx.monthly_date) = DATE(mpd.payment_date)
//           AND mprx.payout_amount = mpd.payment_amount
//       )
//   ) X
//   WHERE 1=1
//     $extra
//   ORDER BY X.pay_date ASC
// ";

$sql = "
SELECT
  CASE WHEN mpdAgg.mprid IS NULL THEN 'MPR' ELSE 'MPD' END AS source,
  NULL AS collection_id,
  mpr.loan_code,
  cd.customercode2 AS customer_code,
  cd.name AS customer_name,

  COALESCE(mpdAgg.total_amount, mpr.payout_amount) AS payout_amount,
  COALESCE(mpdAgg.first_date, mpr.monthly_date)   AS monthly_date

FROM `$db`.`monthly_payment_record` mpr
JOIN `$db`.`customer_details` cd ON cd.id = mpr.customer_id

LEFT JOIN (
  SELECT
    mprid,
    SUM(payment_amount) AS total_amount,
    MIN(payment_date)   AS first_date
  FROM `$db`.`monthly_payment_details`
  WHERE DATE_FORMAT(payment_date, '%Y-%m') = '$ym'
    AND (collection_status IS NULL OR collection_status != 'DELETED')
  GROUP BY mprid
) mpdAgg ON mpdAgg.mprid = mpr.id

WHERE
  mpr.month = '$ym'
  AND mpr.status != 'DELETED'
  $extra

ORDER BY COALESCE(mpdAgg.first_date, mpr.monthly_date) ASC
";

// var_dump($sql);
// Run
$query = mysql_query($sql);

$grouped_data = array();

if ($query) {
  while ($row = mysql_fetch_assoc($query)) {

    // Group by loan_code; if loan_code is missing (MPD without MPR/master),
    // fallback to grouping by collection_id to avoid duplicates & keep stable grouping.
    $loan = isset($row['loan_code']) ? trim($row['loan_code']) : '';
    $colId = isset($row['collection_id']) ? trim($row['collection_id']) : '';

    $key = ($loan !== '') ? $loan : (($colId !== '') ? ('COL-' . $colId) : 'UNKNOWN');

    if (!isset($grouped_data[$key])) {
      $grouped_data[$key] = array(
        'loan_code' => ($loan !== '' ? $loan : $key),
        'customer_code' => isset($row['customer_code']) ? $row['customer_code'] : '',
        'customer_name' => isset($row['customer_name']) ? $row['customer_name'] : '',
        'first_monthly_date' => $row['monthly_date'],
        'total_payout' => 0,
        'records' => array()
      );
    }

    // Update earliest date
    if (strtotime($row['monthly_date']) < strtotime($grouped_data[$key]['first_monthly_date'])) {
      $grouped_data[$key]['first_monthly_date'] = $row['monthly_date'];
    }

    // Add payout
    $grouped_data[$key]['total_payout'] += (float)$row['payout_amount'];

    // Add record detail
    $grouped_data[$key]['records'][] = array(
      'loan_code' => $grouped_data[$key]['loan_code'],
      'monthly_date' => $row['monthly_date'],
      'payout_amount' => $row['payout_amount'],
      'source' => isset($row['source']) ? $row['source'] : '',
    );
  }
}

// =========================
// Build HTML rows
// =========================
$html = '';
$count = 0;
$monthly_total = 0;
foreach ($grouped_data as $group) {
  $count++;
  $firstDate = $group['first_monthly_date'] ? date('d/m/Y', strtotime($group['first_monthly_date'])) : '-';
  $loanStyle = getLoanStyle($group['loan_code']);

  $loanCode  = formatLoanCode($group['loan_code']);
  $custCode  = htmlspecialchars($group['customer_code']);
  $custName  = htmlspecialchars($group['customer_name']);

  $total     = number_format((float)$group['total_payout'], 2);
  var_dump($total);
  $monthly_total += $group['total_payout'];
  $html .= '<tr>
    <td style="border:1px solid black; text-align:center;">' . $count . '</td>
    <td style="border:1px solid black; text-align:center;">' . $firstDate . '</td>

    <td style="border:1px solid black; text-align:center;' . $loanStyle . '">' . $loanCode . '</td>
    <td style="border:1px solid black; text-align:center;' . $loanStyle . '">' . $custCode . '</td>
    <td style="border:1px solid black;' . $loanStyle . '">' . $custName . '</td>

    <td style="border:1px solid black; text-align:right;">RM ' . $total . '</td>
    <td style="border:1px solid black; text-align:center;">';

  if (count($group['records']) > 1) {
    $html .= '<button type="button" onclick="toggleDetails(\'detail-' . $count . '\', this)">More ▼</button>';
  } else {
    $html .= '-';
  }

  $html .= '</td></tr>';

  if (count($group['records']) > 1) {
    $html .= '<tr id="detail-' . $count . '" style="display:none;">
      <td colspan="7" style="padding:0;">
        <table border="1" width="100%" style="margin-top:10px; margin-bottom:10px; border-collapse:collapse;">
          <thead>
            <tr>
              <th style="text-align:center;">Date</th>
              <th style="text-align:center;">Agreement No.</th>
              <th style="text-align:right;">Monthly</th>
            </tr>
          </thead>
          <tbody>';

    foreach ($group['records'] as $record) {
      $d = $record['monthly_date'] ? date('d/m/Y', strtotime($record['monthly_date'])) : '-';
      $lc = formatLoanCode($record['loan_code']);
      $amt = number_format((float)$record['payout_amount'], 2);
      $src = htmlspecialchars($record['source']);

      $html .= '<tr>
        <td style="text-align:center;">' . $d . '</td>
        <td style="text-align:center;' . getLoanStyle($record['loan_code']) . '">' . $lc . '</td>
        <td style="text-align:right;">RM ' . $amt . '</td>
      </tr>';
    }

    $html .= '</tbody>
        </table>
      </td>
    </tr>';
  }
}

if ($count == 0) {
  $html .= '<tr><td colspan="7" style="text-align:center;">- No Records -</td></tr>';
}else {
  $html .= '<tr>
    <td style="border:1px solid black; text-align:center;"></td>
    <td style="border:1px solid black; text-align:center;"></td>

    <td style="border:1px solid black; text-align:center;"></td>
    <td style="border:1px solid black; text-align:center;"></td>
    <td style="border:1px solid black;">Total</td>

    <td style="border:1px solid black; text-align:right;">RM ' . number_format($monthly_total, 2) . '</td>
    <td style="border:1px solid black; text-align:center;"></td></tr>';
}

header('Content-Type: text/html; charset=utf-8');
echo $html;
exit;
?>