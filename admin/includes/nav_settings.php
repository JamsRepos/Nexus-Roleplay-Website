<?php
$perm = permcheck(2);
if ($perm) {
?>
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
    </div><?php
        } else {
            ?>
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle"></i> Looks like you don't have permission to view this 🙄
    </div>
<?php
        }
?>