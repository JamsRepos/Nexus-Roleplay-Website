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
        $type = $_POST['type'];
        $qst = $_POST['qst'];
        $ph = $_POST['ph'];
        if (!isset($_POST['sb'])) {
            $sb = "n/a";
        } else {
            $sb = $_POST['sb'];
        }
        $sort = $_POST['sort'];
        $by = $_SESSION['steamid'];
        try {

            $stmt = $conn->prepare("INSERT INTO nexus_questions (form_id, type, question, placeholder, selectables, orderby, created_by) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$fid, $type, $qst, $ph, $sb, $sort, $by]);

            $_POST = "";
        } catch (PDOException $e) {
            echo $e->getMessage();
            $_POST = "";
        }
    }
}
