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

        $fid = $_POST['fid'];

        $stmt2 = $conn->prepare('SELECT * FROM `nexus_forms` WHERE id=:fid');
            $stmt2->bindParam(':fid', $fid, PDO::PARAM_INT);
            $stmt2->execute();
            $result = $stmt2->fetch();
            $name = $result['name'];
            $pic = $result['pic'];
            $sort = $result['orderby'];
            $cd = $result['cooldown'];
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        }
        if (isset($_POST['pic'])) {
            $pic = $_POST['pic'];
        }
        if (isset($_POST['sort'])) {
            $sort = $_POST['sort'];
        }
        if (isset($_POST['cd'])) {
            $cd = $_POST['cd'];
        }
        try {

            $stmt = $conn->prepare("UPDATE `nexus_forms` SET `name` = ?, `pic` = ?, `orderby` = ?, `cooldown` = ? WHERE `id` = ?;");
            $stmt->execute([$name, $pic, $sort, $cd, $fid]);

            $_POST = "";
        } catch (PDOException $e) {
            echo $e->getMessage();
            $_POST = "";
        }
    }
}
