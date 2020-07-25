<?php

$filename = '../core/steamauth/steamconfig.php';

if (file_exists($filename)) {
  require '../core/steamauth/steamconfig.php';
} else {
  require '../steamauth/steamconfig.php';
}
$return = $steamauth['logoutpage'];
function checkperm()
{
  global $conn;
  $stmt = $conn->prepare("SELECT pid from nexus_siteusers where steamid = ?");
  $stmt->execute([$_SESSION['steamid']]);
  $pid = $stmt->fetchColumn();
  if ($pid != 1) {
    header("Location: ../");
    die();
  }
}
