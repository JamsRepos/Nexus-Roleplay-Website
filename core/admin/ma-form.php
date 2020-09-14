<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $perm = permcheck(6);
    if ($perm) {

        $id = $_POST['id'];
        $status = $_POST['status'];
        $reason = $_POST['reason'];
        $date = new DateTime();
        $date = $date->getTimestamp();
        try {

            $stmt = $conn->prepare("UPDATE `nexus_apps` SET `status` = ?, `reason` = ?, `date_updated` = ? WHERE `id` = ?;");
            $stmt->execute([$status, $reason, $date, $id]);

            unset($_POST['id']);
        } catch (PDOException $e) {
            echo $e->getMessage();
            unset($_POST['id']);
        }
    }
}
