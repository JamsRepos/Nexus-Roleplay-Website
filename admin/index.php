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
                                echo "<a class=\"nav-link\" href=\"".$row['link']."\">".$row['lname']."</a>";
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

            <div class="container mt-5">
                <nav class="nav nav-pills nav-fill tab">
                    <a role="button" class="nav-item nav-link tablinks" onclick="openTab(event, 'General')">General Settings</a>
                    <a role="button" class="nav-item nav-link tablinks" onclick="openTab(event, 'Nav')">NavBar Settings</a>
                    <a role="button" class="nav-item nav-link tablinks" onclick="openTab(event, 'Users')">Users</a>
                </nav>
                <div id="General" class="tabcontent">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Index Settings
                                </div>
                                <div class="card-body">
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
                                    <button type="submit" class="btn btn-info" onclick="updateIndex()">Update</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Server Settings
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="ip">Server IP</label>
                                            <input type="text" name="ip" class="form-control" id="ip" value="<?= $ip ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="port">Server Port</label>
                                        <input type="text" name="port" class="form-control" id="port" value="<?= $port ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-info" onclick="updateServer()">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="Nav" class="tabcontent">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Create a Link
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="name">Title</label>
                                                    <input type="text" name="title" class="form-control" id="nname" placeholder="Forums" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="link">Link</label>
                                                <input type="text" name="link" class="form-control" id="nlink" placeholder="https:/gg.gg/forums/" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sort">Sort Order</label>
                                                <input type="number" name="sort" class="form-control" id="nsort" placeholder="1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-info" onclick="addLink()">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    View Links
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="5%" scope="col">#</th>
                                                    <th width="40%" scope="col">Name</th>
                                                    <th width="40%" scope="col">Link</th>
                                                    <th width="5%" class="text-center" scope="col">Sort</th>
                                                    <th width="10%" class="text-center" scope="col">Manage</th>
                                                </tr>
                                            </thead>
                                            <tbody id="links">
                                                <?php
                                                $stmt = $conn->prepare('SELECT * FROM nexus_navbar ORDER BY sortby');
                                                $stmt->execute();
                                                while ($row = $stmt->fetch()) {
                                                    echo "<tr id=\"" . $row['sid'] . "\">";
                                                    echo "
                                                    <th scope=\"row\">" . $row['sid'] . "</th>
                                                    <td>" . $row['lname'] . "</td>
                                                    <td>" . $row['link'] . "</td>
                                                    <td class=\"text-center\">" . $row['sortby'] . "</td>
                                                    <td class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"dLink(" . $row['sid'] . ")\">DELETE</button></td>
                                                    ";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="Users" class="tabcontent">
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
                                                    <th width="35%" scope="col">SteamID</th>
                                                    <th width="5%" class="text-center" scope="col">Permissions</th>
                                                    <th width="20%" class="text-center" scope="col">Manage</th>
                                                </tr>
                                            </thead>
                                            <tbody id="users">
                                                <?php
                                                $stmt = $conn->prepare('SELECT * FROM nexus_siteusers ORDER BY uid');
                                                $stmt->execute();
                                                while ($row = $stmt->fetch()) {
                                                    if ($row['pid'] == 1) {
                                                        $btn = "<td class=\"text-center\"><button type=\"button\" onclick=\"mUser('0', " . $row['uid'] . ")\" class=\"btn btn-warning\">REMOVE ADMIN</button></td>";
                                                    } else {
                                                        $btn = "<td class=\"text-center\"><button type=\"button\" onclick=\"mUser('1', " . $row['uid'] . ")\" class=\"btn btn-warning\">MAKE ADMIN</button></td>";
                                                    }
                                                    echo "<tr>";
                                                    echo "
                                                    <th scope=\"row\">" . $row['uid'] . "</th>
                                                    <td>" . $row['steamname'] . "</td>
                                                    <td>" . $row['steamid'] . "</td>
                                                    <td class=\"text-center\">" . $row['pid'] . "</td>
                                                    $btn
                                                    ";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
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

<?php } ?>