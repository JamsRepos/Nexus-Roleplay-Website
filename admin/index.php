<?php
require('../core/steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "<div style='margin: 30px auto; text-align: center;'>Welcome Guest! Please log in!<br>";
    loginbutton();
    echo "</div>";
} else {
    require('../core/admin/permcheck.php');
    include('../core/steamauth/userInfo.php');
    checkperm();

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
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $steamauth['logoutpage'] ?>">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Store</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Discord</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Launcher</a>
                            </li>
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

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Index Settings
                            </div>
                            <div class="card-body">
                                <form method="post" action="../core/admin/form.php">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="name">Community Name</label>
                                            <input type="text" name="title" class="form-control" id="name" value="<?= $title ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="motto">Motto</label>
                                        <input type="text" name="motto" class="form-control" id="motto" value="<?= $motto ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Server Settings
                            </div>
                            <div class="card-body">
                                <form method="post" action="../core/admin/sv_form.php">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="ip">Server IP</label>
                                            <input type="text" name="ip" class="form-control" id="ip" value="<?=$ip?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="port">Server Port</label>
                                        <input type="text" name="port" class="form-control" id="port" value="<?=$port?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>

    </html>

<?php } ?>