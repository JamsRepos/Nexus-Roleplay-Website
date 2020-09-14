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

            <div class="container mt-5" id="loadme">
                <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        ?>
                        <script>
                            $("#loadme").load("includes/apply.php?id=<?=$id?>");
                        </script>
                        <?php
                    } else {
                        include('includes/forms.php');
                    }
                ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>

    </html>

<?php } ?>