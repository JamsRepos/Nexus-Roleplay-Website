
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
            $status = "denied";
            $stmt = $conn->prepare('SELECT * FROM nexus_apps WHERE status=? AND userid=? ORDER BY id DESC');
            $stmt->execute([$status, $_SESSION['steamid']]);
            while ($row = $stmt->fetch()) {
                if (!isset($row['date_updated'])) {
                    $updated = "N/A";
                } else {
                    $updated = time_elapsed_string('@' . $row['date_updated']);
                }
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
                                                    <td class=\"text-center\"><button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"location.href = 'view.php?id=".$row['id']."'\">VIEW</button></td>
                                                    ";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>