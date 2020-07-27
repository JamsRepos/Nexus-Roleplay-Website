<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $type = $_POST['type'];
    $uid = $_POST['uid'];


    try {

        $stmt = $conn->prepare("UPDATE nexus_siteusers SET pid= ? WHERE uid = ?");
        $stmt->execute([$type, $uid]);

        unset($_POST['type']);
        unset($_POST['uid']);
    } catch (PDOException $e) {
        echo $e->getMessage();
        unset($_POST['type']);
        unset($_POST['uid']);
    }
}
