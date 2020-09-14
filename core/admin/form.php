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

        $stitle = htmlspecialchars($_POST['title']);
        $motto = htmlspecialchars_decode($_POST['motto']);

        try {

            $stmt = $conn->prepare("DROP TABLE IF EXISTS nexus__sitesetting");
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS `nexus__sitesetting` (
        sid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sname VARCHAR(30) DEFAULT 'Nexus Roleplay',
        svalue VARCHAR(500) DEFAULT 'The community built for roleplayers'
       )";
            $conn->exec($sql);

            $stmt = $conn->prepare("INSERT INTO nexus__sitesetting (sname, svalue) VALUES(?, ?)");
            $stmt->execute([$stitle, $motto]);
            unset($_POST['title']);
            unset($_POST['motto']);
        } catch (PDOException $e) {
            echo $e->getMessage();
            unset($_POST['title']);
            unset($_POST['motto']);
        }
        
    }
}
