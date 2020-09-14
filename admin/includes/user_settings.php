<?php
$perm = permcheck(3);
if ($perm) {
?>
<div class="row">

    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                View Users
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%" scope="col">#</th>
                                <th width="35%" scope="col">Name</th>
                                <th width="25%" scope="col">SteamID</th>
                                <?php
                                $perm = permcheck(4);
                                if ($perm) {
                                ?>
                                <th width="15%" class="text-center" scope="col">Permission Level</th>
                                <th width="20%" class="text-center" scope="col">Manage</th>
                                <?php
                                } else {
                                    ?>
                                    <th width="35%" class="text-center" scope="col">Permission Level</th>
                                    <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody id="users">
                            <?php
                            $stmt2 = $conn->prepare('SELECT * FROM `nexus_siteusers` WHERE steamid=:steamid LIMIT 1');
                            $stmt2->bindParam(':steamid', $_SESSION['steamid'], PDO::PARAM_INT);
                            $stmt2->execute();

                            $result = $stmt2->fetch();
                            $myLvl = $result['pid'];
                            $stmt = $conn->prepare('SELECT * FROM nexus_siteusers WHERE pid<?');
                            $stmt->execute([$myLvl]);
                            while ($row = $stmt->fetch()) {
                                /*if ($row['pid'] == 1) {
                                                        $btn = "<td class=\"text-center\"><button type=\"button\" onclick=\"mUser('0', " . $row['uid'] . ")\" class=\"btn btn-warning\">REMOVE ADMIN</button></td>";
                                                    } else {
                                                        $btn = "<td class=\"text-center\"><button type=\"button\" onclick=\"mUser('1', " . $row['uid'] . ")\" class=\"btn btn-warning\">MAKE ADMIN</button></td>";
                                                    }*/
                                echo "<tr>";
                                echo "
                                                    <th scope=\"row\">" . $row['uid'] . "</th>
                                                    <td>" . $row['steamname'] . "</td>
                                                    <td>" . $row['steamid'] . "</td>
                                                    <td class=\"text-center\">" . $row['pid'] . "</td>
                                                    ";
                                                    $perm = permcheck(4);
                                                    if ($perm) {
                                                    echo "<td class=\"text-center\"><button type=\"button\" data-toggle=\"modal\" data-target=\"#permModal\" onclick=\"oUser('" . $row['steamid'] . "')\" class=\"btn btn-warning\">EDIT PERMISSIONS</button></td>
                                                    ";
                                                    }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Modal -->
                <div id="permModal" class="modal show" role="dialog" tabindex="-1" aria-labelledby="permModal" aria-hidden="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="permModalLabel">Edit User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <i class="fa fa-spinner fa-spin"></i> Loading...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of modal -->
            </div>
        </div>
    </div>
</div><?php
        } else {
            ?>
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle"></i> Looks like you don't have permission to view this 🙄
    </div>
<?php
        }
?>