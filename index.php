<?php
require('core/steamauth/SteamConfig.php');

$stmt = $conn->prepare('SELECT * FROM nexus__sitesetting');
$stmt->execute();
while ($row = $stmt->fetch()) {
    $title = $row['sname'];
    $motto = $row['svalue'];
}

$stmt = $conn->prepare('SELECT * FROM nexus_serverlist');
$stmt->execute();
while ($row = $stmt->fetch()) {
    $ip = $row['serverip'];
    $port = $row['serverport'];
    $serverip = $ip . ':' . $port;
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
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#players").load("core/server.php");
        });
    </script>

</head>

<body>
    <div class="header">
        <video autoplay muted loop id="myVideo">
            <source src="assets/img/bg.m4v" type="video/mp4" loop>
        </video>
        <div class="overlay">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="assets/img/logo.png" alt="<?= $title ?>">
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
                                echo "<li class=\"nav-item\">";
                                echo "<a class=\"nav-link\" href=\"" . $row['link'] . "\">" . $row['lname'] . "</a>";
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container">
                <div class="row d-h-90">
                    <div class="col-md-12 my-auto">
                        <div class="serverinfo text-center">
                            <h1><?= $title ?></h1>
                            <h4><?= $motto ?></h4>
                            <div class="btn-group" role="group" aria-label="Basic example" onclick="window.open('fivem://connect/<?= $serverip ?>','_blank')" id="players">
                                <button type="button" class="btn btn-nexus"><i class="fa fa-spinner fa-spin"></i> Loading...</button>
                                <button type="button" class="btn btn-nexus" style="margin-left: 0px; padding-left: 0px!important;"><i class="fas fa-sign-in-alt"></i></button>
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