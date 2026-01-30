<?php
session_start();

// Only SuperAdmin can switch branches
if(isset($_POST['branch']) && $_SESSION['superadmin'] === 1) {
    $selectedBranch = strtoupper($_POST['branch']);

    switch($selectedBranch) {
        case 'MJ': $conn_database = 'majusama'; break;
        case 'MJ2': $conn_database = 'majusama2'; break;
        case 'ANSENG': $conn_database = 'anseng'; break;
        case 'YUWANG': $conn_database = 'yuwang'; break;
        case 'KTL': $conn_database = 'ktl'; break;
        case 'TSY': $conn_database = 'tsy'; break;
        case 'TSY2': $conn_database = 'tsy2'; break;
        case 'DK': $conn_database = 'dk'; break;
        default: $conn_database = $_SESSION['login_database']; break;
    }

    $_SESSION['login_branch'] = $selectedBranch;
    $_SESSION['login_database'] = $conn_database;

    echo "success";
    exit;
}

echo "error";
?>
