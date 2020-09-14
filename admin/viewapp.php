<?php
require('../core/steamauth/SteamConfig.php');
require('../core/steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    header("Location: ../login");
} else {
    include('../core/steamauth/userInfo.php');

    $stmt = $conn->prepare('SELECT * FROM nexus__sitesetting');
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $title = $row['sname'];
        $motto = $row['svalue'];
    }
?>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?= $title ?></title>
        <meta name="description" content="<?= $motto ?>">
        <meta name="og:title" content="<?= $title ?>">
        <meta name="og:description" content="<?= $motto ?>">
        <meta name="author" content="polite#1745">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="../assets/js/admin.js"></script>

    </head>

    <body>
        <div class="header">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="../assets/img/logo.png" alt="<?= $title ?>">
                        <?= $title ?>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <?php
                            $stmt = $conn->prepare('SELECT * FROM nexus_navbar ORDER BY sortby');
                            $stmt->execute();
                            while ($row = $stmt->fetch()) {
                                echo "<li class=\"nav-item me\">";
                                echo "<a class=\"nav-link\" href=\"" . $row['link'] . "\">" . $row['lname'] . "</a>";
                                echo "</li>";
                            }
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= $steamprofile['personaname'] ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <?php logoutbutton(); ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <?php

        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            try {

                $stmt = $conn->prepare("SELECT * FROM nexus_apps WHERE id=? AND userid=? LIMIT 1");
                $stmt->execute([$id, $_SESSION['steamid']]);
                $result = $stmt->fetch();

                $fid = $result['form_id'];
                $data = unserialize($result['data']);
                $status = $result['status'];
                $date = $result['date_created'];
                $update = $result['date_updated'];
                $aid = $result['id'];
                $reason = $result['reason'];

                $stmt = $conn->prepare("SELECT * FROM nexus_forms WHERE id=? LIMIT 1");
                $stmt->execute([$fid]);
                $result = $stmt->fetch();
                $fn = $result['name'];
                $cooldown = $result['cooldown'];

                $now = new DateTime();
                $timeuntil = $update + $cooldown;
                $redate = gmdate("Y-m-d\TH:i:s\Z", $timeuntil);
                $future_date = new DateTime($redate);

                $interval = $future_date->diff($now);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        ?>
            <div class="container mt-5" id="loadme">
                <div class="row">
                    <div class="col-md-12 text-left">
                        <button class="btn btn-info" onclick="location.href = 'applications.php?id=<?= $fid ?>';"><i class="fas fa-long-arrow-alt-left"></i> BACK TO APPS</button>
                    </div>
                    <div class="col-md-12 mt-3">
                        <?php
                        if ($status == "pending") {
                        ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> This application is pending.
                            </div>
                        <?php
                        } else if ($status == "denied") {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle"></i> This application has been denied with reason: <?=$reason?> They'll need to wait another <?=$interval->format("%a days, %h hours, %i minutes, %s seconds");?> to re-apply.
                            </div>
                        <?php
                        } else if ($status == "accepted") {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle"></i> This application has been accepted!
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-check-circle"></i> We can't seem to be able to determind the status of this application, please contact an administrator, your APP ID: <?= $aid ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="card">
                            <div class="card-header">
                                You're currently viewing your application for: <?= $fn ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    foreach ($data as $question => $answer) {
                                        $qst = str_replace("_", " ", $question);
                                        if (strlen($answer) > 135) {
                                    ?>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label><?= $qst ?></label>
                                                        <textarea onkeydown="return false" class="form-control" rows="3"><?= $answer ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label><?= $qst ?></label>
                                                        <input onkeydown="return false" type="text" class="form-control" value="<?= $answer ?>">
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <div class="col-md-6 text-center mt-3">
                                        <button class="btn btn-success w-100" onclick="mApp('accepted')">ACCEPT APPLICATION</button>
                                    </div>
                                    <div class="col-md-6 text-center mt-3">
                                        <button class="btn btn-warning w-100" onclick="mApp('pending')">SET APPLICATION TO PENDING</button>
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <div class="form-group">
                                            <input type="text" id="reason" class="form-control" placeholder="Put your reason here" value="<?= $reason ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center mt-3">
                                        <button class="btn btn-danger w-100" onclick="mApp('denied')">DENY APPLICATION</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>

    </html>
<?php
        }
    }
