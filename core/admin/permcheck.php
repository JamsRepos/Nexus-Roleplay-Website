<?php

$filename = '../core/steamauth/SteamConfig.php';

if (file_exists($filename)) {
  require '../core/steamauth/SteamConfig.php';
} else {
  require '../steamauth/SteamConfig.php';
}
$return = $steamauth['logoutpage'];
function checkperm()
{
  global $conn;
  $stmt = $conn->prepare("SELECT pid from nexus_siteusers where steamid = ?");
  $stmt->execute([$_SESSION['steamid']]);
  $pid = $stmt->fetchColumn();
  if ($pid < 1) {
    header("Location: ../");
    die();
  }
}

function permcheck($id)
{
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM `nexus_permission_user` where user_steamid = ? AND permission_id = ?");
  $stmt->execute([$_SESSION['steamid'], $id]);
  $pid = $stmt->fetchColumn();
  if ($pid < 1) {
    $perm = false;
  } else {
    $perm = true;
  }
  return $perm;
}
