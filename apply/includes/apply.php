<?php
require('../../core/steamauth/SteamConfig.php');
require('../../core/steamauth/steamauth.php');
$id = $_GET['id'];
$stmt = $conn->prepare('SELECT * FROM nexus_forms WHERE id=? LIMIT 1');
$stmt->execute([$id]);
$result = $stmt->fetch();
$name = $result['name'];
$pic = $result['pic'];
$cooldown = $result['cooldown'];
$status3 = "denied";

$stmt3 = $conn->prepare('SELECT * FROM `nexus_apps` WHERE form_id=? AND userid=? AND status=? ORDER BY id DESC LIMIT 1');
        $stmt3->execute([$id, $_SESSION['steamid'], $status3]);
        $result = $stmt3->fetch();

        $updated = $result['date_updated'];
        $wait = $cooldown + $updated;
        $now = new DateTime();
        $formatted = $now->getTimestamp();
        if ($formatted < $wait) {
            die("I don't think so");
        }
?>

<div class="row">
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                You're currently applying for: <?= $name ?>
            </div>
            <div class="card-body">
                <form class="row" action="process.php?id=<?= $id ?>" method="POST">

                    <?php
                    $stmt = $conn->prepare('SELECT * FROM nexus_questions WHERE form_id=? ORDER BY orderby');
                    $stmt->execute([$id]);
                    while ($row = $stmt->fetch()) {
                        if ($row['type'] == "textarea") {
                    ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="<?= $row['id'] ?>"><?= $row['question'] ?></label>
                                        <textarea name="<?= $row['question'] ?>" class="form-control" id="<?= $row['id'] ?>" required rows="3"><?= $row['placeholder'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } else if ($row['type'] == "dropdown") {
                        ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="<?= $row['id'] ?>">Type of Question</label>
                                        <select name="<?= $row['question'] ?>" class="form-control" id="<?= $row['id'] ?>" required>
                                            <?php
                                            $array = $row['selectables'];
                                            $arr = explode(",", $array);
                                            foreach ($arr as $name) {
                                            ?>
                                                <option value="<?= $name ?>"><?= $name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="<?= $row['id'] ?>"><?= $row['question'] ?></label>
                                        <input type="<?= $row['type'] ?>" name="<?= $row['question'] ?>" class="form-control" id="<?= $row['id'] ?>" placeholder="<?= $row['placeholder'] ?>" required>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-info" value="Submit">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>