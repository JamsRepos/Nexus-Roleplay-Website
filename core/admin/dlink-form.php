<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $id = $_POST['id'];
    try {

        $stmt = $conn->prepare("DELETE FROM nexus_navbar WHERE sid = ?");
        $stmt->execute([$id]);

        unset($_POST['id']);
    } catch (PDOException $e) {
        echo $e->getMessage();
        unset($_POST['id']);
    }
}