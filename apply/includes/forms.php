<div class="row">
    <?php

    $stmt = $conn->prepare('SELECT * FROM nexus_forms ORDER BY orderby');
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $stmt2 = $conn->prepare('SELECT count(form_id) as questions FROM `nexus_questions` WHERE form_id=:fid');
        $stmt2->bindParam(':fid', $row['id'], PDO::PARAM_INT);
        $stmt2->execute();
        $result = $stmt2->fetch();
        $questions = $result['questions'];

        $stmt3 = $conn->prepare('SELECT count(form_id) as attempts FROM `nexus_apps` WHERE form_id=:fid AND userid=:uid');
        $stmt3->bindParam(':fid', $row['id'], PDO::PARAM_INT);
        $stmt3->bindParam(':uid', $_SESSION['steamid'], PDO::PARAM_INT);
        $stmt3->execute();
        $result = $stmt3->fetch();
        $attempts = $result['attempts'];
        $status = "pending";
        $status2 = "accepted";
        $status3 = "denied";

        $stmt3 = $conn->prepare('SELECT * FROM `nexus_apps` WHERE form_id=? AND userid=? AND status=? OR status=?');
        $stmt3->execute([$row['id'], $_SESSION['steamid'], $status, $status2]);
        $count = $stmt3->rowCount();
        if ($count > 0) {
            $county = "yes";
        }

        $cooldown = $row['cooldown'];

        $stmt3 = $conn->prepare('SELECT * FROM `nexus_apps` WHERE form_id=? AND userid=? AND status=? ORDER BY id DESC LIMIT 1');
        $stmt3->execute([$row['id'], $_SESSION['steamid'], $status3]);
        $result = $stmt3->fetch();

        $updated = $result['date_updated'];

        $wait = $cooldown + $updated;
        $now = new DateTime();
        $formatted = $now->getTimestamp();
        if ($formatted < $wait) {
            $now = new DateTime();
            $timeuntil = $updated + $cooldown;
            $redate = gmdate("Y-m-d\TH:i:s\Z", $timeuntil);
            $future_date = new DateTime($redate);

            $interval = $future_date->diff($now);
        }
    ?>

        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-header text-center">
                    <h2 style="margin-bottom: 0;"><?= $row['name'] ?></h2>
                </div>
                <img class="img-fluid" style="max-height: 150px; object-fit: cover;" src="<?= $row['pic'] ?>" />
                <div class="card-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6 text-center">
                            <h6>Questions</h6>
                            <p><?= $questions ?></p>
                        </div>
                        <div class="col-md-6 text-center">
                            <h6>Your Attempts</h6>
                            <p><?= $attempts ?></p>
                        </div>

                        <div class="col-md-12 text-center">
                        <?php if (isset($interval)) { ?>
                            <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> You need to wait another <?=$interval->format("%a days, %h hours, %i minutes, %s seconds");?> to re-apply.
                                </div>
                        <?php } else if (isset($county)) { ?>
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> You currently already have an application pending, you cannot re-apply until they have made there decision
                                </div>
                            <?php } else { ?>
                                <button type="submit" class="btn btn-info w-100" onclick="window.location.href='?id=<?= $row['id'] ?>'">Apply</button>
                            <?php }
                            unset($county); $count = 0; unset($interval) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>