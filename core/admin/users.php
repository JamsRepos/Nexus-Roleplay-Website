<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $stmt = $conn->prepare('SELECT * FROM nexus_siteusers ORDER BY uid');
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        if ($row['pid'] == 1) {
            $btn = "<td class=\"text-center\"><button type=\"button\" onclick=\"mUser(0, ".$row['uid'].")\" class=\"btn btn-warning\">REMOVE ADMIN</button></td>";
        } else {
            $btn = "<td class=\"text-center\"><button type=\"button\" onclick=\"mUser(1, ".$row['uid'].")\" class=\"btn btn-warning\">MAKE ADMIN</button></td>";
        }
        echo "<tr>";
        echo "<th scope=\"row\">" . $row['uid'] . "</th>
            <td>" . $row['steamname'] . "</td>
            <td>" . $row['steamid'] . "</td>
            <td class=\"text-center\">" . $row['pid'] . "</td>
            $btn";
        echo "</tr>";
    }
}
