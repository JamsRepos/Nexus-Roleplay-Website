<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $perm = permcheck(5);
    $id = $_GET['id'];
    if ($perm) {
        $stmt = $conn->prepare('SELECT * FROM nexus_questions WHERE form_id=? ORDER BY orderby');
        $stmt->execute([$id]);
        while ($row = $stmt->fetch()) {
            echo "<tr id=\"" . $row['id'] . "\">";
            echo "
                                                    <th scope=\"row\">" . $row['id'] . "</th>
                                                    <td>" . $row['question'] . "</td>
                                                    <td>" . $row['type'] . "</td>
                                                    <td class=\"text-center\">" . $row['orderby'] . "</td>
                                                    <td class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"dQst(" . $row['id'] . "," . $id . ")\">DELETE</button></td>
                                                    ";
            echo "</tr>";
        }
    }
}
