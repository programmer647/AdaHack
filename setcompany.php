<?php
session_start();

$companyName = trim($_POST['company_name'] ?? '');

if ($companyName === '') {
    header('Location: company.php');
    exit;
}

$_SESSION['company_name'] = $companyName;

header('Location: graph.php');
exit;
