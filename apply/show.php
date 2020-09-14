<?php
/*require('../core/steamauth/SteamConfig.php');
require('../core/steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    header("Location: ../login");
} else {
    include('../core/steamauth/userInfo.php');

    $stmt2 = $conn->prepare("SELECT * FROM `nexus_apps` LIMIT 1");
    $stmt2->execute();
    $result = $stmt2->fetch();

    $app = $result['data'];
    $newapp = unserialize($app);

    foreach ($newapp as $question => $answer) {
        $qst = str_replace("_"," ",$question);
        echo "<h4>$qst</h4>";
        echo "<p>$answer</p>";
    }
}