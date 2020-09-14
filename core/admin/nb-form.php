<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $perm = permcheck(2);
    if ($perm) {

        $name = $_POST['title'];
        $link = $_POST['link'];
        $sb = $_POST['sort'];
        try {

            $stmt = $conn->prepare("INSERT INTO nexus_navbar (lname, link, sortby) VALUES('$name', '$link', '$sb')");
            $stmt->execute();

            unset($_POST['title']);
            unset($_POST['link']);
            unset($_POST['sort']);
            header('Location: ../../admin/index.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
            unset($_POST['title']);
            unset($_POST['link']);
            unset($_POST['sort']);
        }
    }
}
