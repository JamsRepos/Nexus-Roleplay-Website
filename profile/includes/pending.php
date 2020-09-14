<?php
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th width="5%" scope="col">#</th>
                <th width="55%" scope="col">Name</th>
                <th width="15%" class="text-center" scope="col">Date Created</th>
                <th width="15%" class="text-center" scope="col">Date Updated</th>
                <th width="10%" class="text-center" scope="col">View</th>
            </tr>
        </thead>
        <tbody id="links">
            <?php
            $status = "pending";
            $stmt = $conn->prepare('SELECT * FROM nexus_apps WHERE status=? AND userid=? ORDER BY id DESC');
            $stmt->execute([$status, $_SESSION['steamid']]);
            $count = $stmt->rowCount();
            while ($row = $stmt->fetch()) {
                if (!isset($row['date_updated'])) {
                    $updated = "N/A";
                } else {
                    $updated = time_elapsed_string('@' . $row['date_updated']);
                }
                echo $count;
                $stmt2 = $conn->prepare('SELECT * FROM nexus_forms WHERE id=? LIMIT 1');
                $stmt2->execute([$row['form_id']]);
                $result = $stmt2->fetch();
                $appname = $result['name'];
                $made = $row['date_created'];
                echo "<tr id=\"" . $row['id'] . "\">";
                echo "
                                                    <th scope=\"row\">" . $row['id'] . "</th>
                                                    <td>" . $_SESSION['steam_personaname'] . "'s $appname</td>
                                                    <td class=\"text-center\">" . time_elapsed_string('@' . $made) . "</td>
                                                    <td class=\"text-center\">" . $updated . "</td>
                                                    <td class=\"text-center\"><button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"location.href = 'view.php?id=" . $row['id'] . "'\">VIEW</button></td>
                                                    ";
                echo "</tr>";
            }
            if ($count < 1) {
                echo "<tr>";
                echo "<td class=\"text-center\" colspan=\"5\" scope=\"row\">No applications found</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>