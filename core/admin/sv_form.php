<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $perm = permcheck(1);
    if ($perm) {

        $ip = $_POST['ip'];
        $port = $_POST['port'];
        try {

            $stmt = $conn->prepare("DROP TABLE IF EXISTS nexus_serverlist");
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS `nexus_serverlist` (
            sid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            serverip VARCHAR(30) DEFAULT '185.141.207.151',
            serverport INT(6) DEFAULT '30120'
           )";
            $conn->exec($sql);

            $stmt = $conn->prepare("INSERT INTO nexus_serverlist (serverip, serverport) VALUES('$ip', '$port')");
            $stmt->execute();
            unset($_POST['title']);
            unset($_POST['motto']);
            header('Location: ../../admin/index.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
            unset($_POST['title']);
            unset($_POST['motto']);
        }
    }
}
