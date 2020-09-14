<?php
var_dump(($_POST));
require('../core/steamauth/SteamConfig.php');
require('../core/steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    header("Location: ../login");
} else {
    include('../core/steamauth/userInfo.php');

    $fid = $_GET['id'];
    $data = serialize($_POST);
    $status = "pending";
    $uid = $_SESSION['steamid'];
    $date = new DateTime();
    $date = $date->getTimestamp();

    $stmt = $conn->prepare('SELECT * FROM nexus_forms WHERE id=? LIMIT 1');
    $stmt->execute([$fid]);
    $result = $stmt->fetch();
    $name = $result['name'];
    $pic = $result['pic'];
    $cooldown = $result['cooldown'];

    $status3 = "denied";

    $stmt3 = $conn->prepare('SELECT * FROM `nexus_apps` WHERE form_id=? AND userid=? AND status=? ORDER BY id DESC LIMIT 1');
    $stmt3->execute([$fid, $_SESSION['steamid'], $status3]);
    $result = $stmt3->fetch();

    $updated = $result['date_updated'];
    $wait = $cooldown + $updated;
    $now = new DateTime();
    $formatted = $now->getTimestamp();
    if ($formatted < $wait) {
        die("I don't think so");
    }

    try {

        /*$stmt = $conn->prepare("INSERT INTO nexus_apps (form_id, data, status, userid, date_created) VALUES(?, ?, ?, ?, ?)");
        $stmt->execute([$fid, $data, $status, $uid, $date]);*/

        $stmt = $conn->prepare("INSERT INTO nexus_apps (form_id, data, status, userid, date_created) VALUES(:fid, :data, :status, :userid, :date)");
        $stmt->bindParam(':fid', $fid, PDO::PARAM_INT);
        $stmt->bindParam(':data', $data, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':userid', $uid, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_INT);
        $stmt->execute();

        $_POST = array();
        header('Location: ../profile/');
    } catch (PDOException $e) {
        echo $e->getMessage();
        $_POST = array();
    }
}



/*foreach ($_POST as $question => $answer) {
    $qst = str_replace("_"," ",$question);
    echo "<h4>$qst</h4>";
    echo "<p>$answer</p>";
}*/
