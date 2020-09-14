<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $perm = permcheck(3);
    if ($perm) {

        $stmt2 = $conn->prepare('SELECT * FROM `nexus_siteusers` WHERE steamid=:steamid LIMIT 1');
        $stmt2->bindParam(':steamid', $_SESSION['steamid'], PDO::PARAM_INT);
        $stmt2->execute();

        $result = $stmt2->fetch();
        $myLvl = $result['pid'];

        $stmt = $conn->prepare('SELECT * FROM nexus_siteusers WHERE pid<?');
        $stmt->execute([$myLvl]);
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "
                                                    <th scope=\"row\">" . $row['uid'] . "</th>
                                                    <td>" . $row['steamname'] . "</td>
                                                    <td>" . $row['steamid'] . "</td>
                                                    <td class=\"text-center\">" . $row['pid'] . "</td>
                                                    <td class=\"text-center\"><button type=\"button\" data-toggle=\"modal\" data-target=\"#permModal\" onclick=\"oUser(" . $row['steamid'] . ")\" class=\"btn btn-warning\">EDIT PERMISSIONS</button></td>
                                                    ";
            echo "</tr>";
        }
    }
}
