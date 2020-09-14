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

        $uid = $_POST['uid'];
        echo json_encode($_POST['uid']);

        $stmt2 = $conn->prepare('SELECT * FROM `nexus_siteusers` WHERE steamid=:steamid LIMIT 1');
        $stmt2->bindParam(':steamid', $_SESSION['steamid'], PDO::PARAM_INT);
        $stmt2->execute();

        $act = $stmt2->fetch();

        $stmt = $conn->prepare('SELECT * FROM `nexus_siteusers` WHERE steamid=:steamid LIMIT 1');
        $stmt->bindParam(':steamid', $uid, PDO::PARAM_INT);
        $stmt->execute();

        $slave = $stmt->fetch();

        if ($act['pid'] > $slave['pid']) {

            if (isset($_POST['pl']) && $_POST['pl'] < $act['pid']) {
                try {
                    $stmt = $conn->prepare('UPDATE `nexus_siteusers` SET pid = ? WHERE steamid = ?');
                    $stmt->execute([$_POST['pl'], $uid]);
                    echo json_encode('Updated Permission level :)');
                    unset($_POST['pl']);
                } catch (PDOException $e) {
                    echo json_encode(['message' => $e->getMessage()]);
                }
            } else {
                echo json_encode('You cant do that silly');
            }

            // HAS PERMISSION

            if (isset($_POST['perms'])) {

                // ADDING PERMS

                $perms = $_POST['perms'];

                $stmt = $conn->prepare('DELETE FROM nexus_permission_user WHERE user_steamid=:steamid');
                $stmt->bindParam(':steamid', $uid, PDO::PARAM_INT);
                $stmt->execute();




                foreach ($perms as $perm) {
                    try {
                        $perm2 = preg_replace('/\D/', '', $perm); // make sure it is only getting numbers
                        $stmt = $conn->prepare("INSERT INTO nexus_permission_user (permission_id, user_steamid) VALUES(?, ?)");
                        $stmt->execute([$perm2, $uid]);
                        echo json_encode('Added/Updated Permissions');
                        unset($_POST['perms']);
                        unset($_POST['uid']);
                    } catch (PDOException $e) {
                        echo json_encode(['message' => $e->getMessage()]);
                    }
                }
            } else {

                // REMOVING ALL PERMS
                try {
                    $stmt = $conn->prepare('DELETE FROM nexus_permission_user WHERE user_steamid=?');
                    $stmt->execute([$uid]);
                    echo json_encode('Deleted all permissions');
                    unset($_POST['perms']);
                    unset($_POST['uid']);
                } catch (PDOException $e) {
                    echo json_encode(['message' => $e->getMessage()]);
                }
            }
        } else {
            // NO PERMISSION
            echo json_encode('No permission :)');
        }
    }
}
