<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $perm = permcheck(5);
    if ($perm) {

        $name = $_POST['aname'];
        $pic = $_POST['apic'];
        $sb = $_POST['asort'];
        $by = $_SESSION['steamid'];
        $cd = $_POST['cd'];
        try {

            $stmt = $conn->prepare("INSERT INTO nexus_forms (name, pic, created_by, orderby, cooldown) VALUES(?, ?, ?, ?, ?)");
            $stmt->execute([$name, $pic, $by, $sb, $cd]);

            $_POST = array();
            header('Location: ../../admin/index.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
            $_POST = array();
        }
    }
}