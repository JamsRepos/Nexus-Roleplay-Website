<?php
require('../core/steamauth/steamauth.php');

if (!isset($_SESSION['steamid'])) {
    echo "<div style='margin: 30px auto; text-align: center;'>Welcome Guest! Please log in!<br>";
    loginbutton();
    echo "</div>";
} else {
    require('../core/admin/permcheck.php');
    include('../core/steamauth/userInfo.php');
    $perm = permcheck(5);
    if ($perm) {

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

                <div class="container mt-5">
                    <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        $stmt = $conn->prepare('SELECT * FROM nexus_forms WHERE id=? LIMIT 1');
                        $stmt->execute([$id]);
                        $result = $stmt->fetch();
                        $name = $result['name'];
                        $pic = $result['pic'];
                    }
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> There will automatically be a question for their steamid!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="submit" class="btn btn-info" onclick="location.href = 'index.php#Apps';"><i class="fas fa-long-arrow-alt-left"></i> BACK TO FORMS</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#formModal">Add Question</button>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    Edit form: <?= $name ?>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="name" class="form-control" id="aname" placeholder="Forums" value="<?= $name ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="link">Link</label>
                                                <input type="text" name="apic" class="form-control" id="apic" placeholder="image.png" value="<?= $pic ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cd">Cooldown</label>
                                                <input type="number" name="cd" id="cd" class="form-control" value="<?= $result['cooldown'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sort">Order By</label>
                                                <input type="number" name="sort" id="asort" class="form-control" value="<?= $result['orderby'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-info" onclick="updateApp(<?= $id ?>)">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    View Questions
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="5%" scope="col">#</th>
                                                    <th width="40%" scope="col">Question</th>
                                                    <th width="40%" scope="col">Type</th>
                                                    <th width="5%" class="text-center" scope="col">Sort</th>
                                                    <th width="10%" class="text-center" scope="col">Manage</th>
                                                </tr>
                                            </thead>
                                            <tbody id="qsts">
                                                <?php
                                                $stmt = $conn->prepare('SELECT * FROM nexus_questions WHERE form_id=? ORDER BY orderby');
                                                $stmt->execute([$id]);
                                                while ($row = $stmt->fetch()) {
                                                    echo "<tr id=\"" . $row['id'] . "\">";
                                                    echo "
                                                    <th scope=\"row\">" . $row['id'] . "</th>
                                                    <td>" . $row['question'] . "</td>
                                                    <td>" . $row['type'] . "</td>
                                                    <td class=\"text-center\">" . $row['orderby'] . "</td>
                                                    <td class=\"text-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"dQst(" . $row['id'] . "," . $id . ")\">DELETE</button></td>
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

                    <div id="formModal" class="modal show" role="dialog" tabindex="-1" aria-labelledby="formModal" aria-hidden="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="formModalLabel">Add a question</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="atype">Type of Question</label>
                                            <select class="form-control" name="atype" id="atype" required>
                                                <option value="text">Small Text</option>
                                                <option value="textarea">Large Text</option>
                                                <option value="number">Number</option>
                                                <option value="dropdown">Dropdown</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="que">Question</label>
                                        <input type="text" name="que" class="form-control" id="que" placeholder="How old are you" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="ph">Placeholder</label>
                                        <input type="text" name="ph" class="form-control" id="ph" placeholder="17" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sb">Selectables</label>
                                        <input type="text" name="sb" class="form-control" id="sb" placeholder="<12,12-16,16-18,18+">
                                        <small id="sb" class="form-text text-muted">Use commas to seperate these, you only need to fill these out if you have Dropdown as your type! Please refrain from using spaces!</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="ob">Order By</label>
                                        <input type="number" name="qsort" class="form-control" id="qsort" placeholder="1" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-info" id="cqst_btn" onclick="addQuestion(<?= $id ?>)">Create</button>
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

<?php } else {
        header("Location: ../");
    }
}
?>