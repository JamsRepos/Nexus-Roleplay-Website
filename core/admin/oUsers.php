<?php

require('../steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "Woah there buddy what you trying to access?";
} else {
    require('permcheck.php');
    include('../steamauth/userInfo.php');
    checkperm();

    $perm = permcheck(4);
    if ($perm) {

        $uid = $_GET['id'];

        $stmt2 = $conn->prepare('SELECT * FROM `nexus_siteusers` WHERE steamid=:steamid LIMIT 1');
        $stmt2->bindParam(':steamid', $uid, PDO::PARAM_INT);
        $stmt2->execute();

        $result = $stmt2->fetch();

        try {

            $stmt = $conn->prepare("SELECT * FROM `nexus_permission_user` WHERE user_steamid=:steamid");
            $stmt->bindParam(':steamid', $uid, PDO::PARAM_INT);
            $stmt->execute();
?>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permModalLabel">Editing: <?= $result["steamname"] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        if ($stmt->rowCount() > 0) {
                            $perms = [];
                            while ($row = $stmt->fetch()) {

                                $perms[] = $row['permission_id'];
                            }

                            if (in_array(1, $perms)) {
                            }
                            if (in_array(1, $perms)) { ?>
                                <div class="form-group form-check">
                                    <h6>General Settings</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="1" id="p1" checked>
                                    <label class="form-check-label" for="p1">Edit general settings & server ip</label>
                                </div>
                            <?php
                            } else {
                                echo '
                                <div class="form-group form-check">
                                    <h6>General Settings</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="1" id="p1">
                                    <label class="form-check-label" for="p1">Edit general settings & server ip</label>
                                </div>
                                ';
                            }

                            if (in_array(2, $perms)) { ?>
                                <div class="form-group form-check">
                                    <h6>Navbar Settings</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="2" id="p2" checked>
                                    <label class="form-check-label" for="p2">Edit navigation links</label>
                                </div>
                            <?php
                            } else {
                                echo '
                                <div class="form-group form-check">
                                    <h6>Navbar Settings</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="2" id="p2">
                                    <label class="form-check-label" for="p2">Edit navigation links</label>
                                </div>
                                ';
                            }

                            if (in_array(3, $perms)) { ?>
                                <div class="form-group form-check">
                                    <h6>View Users</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="3" id="p3" checked>
                                    <label class="form-check-label" for="p3">View all the users signed up</label>
                                </div>
                            <?php
                            } else {
                                echo '
                                <div class="form-group form-check">
                                    <h6>View Users</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="3" id="p3">
                                    <label class="form-check-label" for="p3">View all the users signed up</label>
                                </div>
                                ';
                            }

                            if (in_array(4, $perms)) { ?>
                                <div class="form-group form-check">
                                    <h6>Permissions Settings</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="4" id="p4" checked>
                                    <label class="form-check-label" for="p4">Be able to change users permissions</label>
                                </div>
                            <?php
                            } else {
                                echo '
                                <div class="form-group form-check">
                                    <h6>Permissions Settings</h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="4" id="p4">
                                    <label class="form-check-label" for="p4">Be able to change users permissions</label>
                                </div>
                                ';
                            }
                            /*END OF PERM LOOP */
                        } else {
                            $stmt2 = $conn->prepare("SELECT * FROM `nexus_permissions`");
                            $stmt2->execute([$uid]);
                            while ($row = $stmt2->fetch()) {
                            ?>
                                <div class="form-group form-check">
                                    <h6><?= $row['display_name'] ?></h6>
                                    <input type="checkbox" name="perm" class="form-check-input" value="p<?= $row['id'] ?>">
                                    <label class="form-check-label" for="p<?= $row['id'] ?>"><?= $row['description'] ?></label>
                                </div>
                        <?php
                            }
                        }

                        ?>
                        <div class="form-group">
                            <label for="pl">Permission Level</label>
                            <input type="number" class="form-control" id="pl" value="<?= $result["pid"] ?>" placeholder="<?= $result["pid"] ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="mUser('<?= $uid ?>')">Save changes</button>
                    </div>
                </div>
            </div>
        <?php
        } catch (PDOException $e) {
        ?>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permModalLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= $e->getMessage(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
?>