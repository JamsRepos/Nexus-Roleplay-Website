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

        $stmt = $conn->prepare('SELECT * FROM nexus_navbar ORDER BY sortby');
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            echo "<tr id=\"" . $row['sid'] . "\">";
            echo "<th scope=\"row\">" . $row['sid'] . "</th>
        <td>" . $row['lname'] . "</td>
        <td>" . $row['link'] . "</td>
        <td class=\"text-center\">" . $row['sortby'] . "</td>
        <td class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"dLink(" . $row['sid'] . ")\">DELETE</button></td>";
            echo "</tr>";
        }
    }
}
