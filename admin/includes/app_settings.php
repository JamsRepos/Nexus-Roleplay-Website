<?php
$perm = permcheck(6);
if ($perm) {
?>
    <div class="row">
        <?php $perm = permcheck(5);
        if ($perm) { ?>
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#formModal">Create Form</button>
            </div>
        <?php }

        $stmt = $conn->prepare('SELECT * FROM nexus_forms ORDER BY orderby');
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $stmt2 = $conn->prepare('SELECT count(form_id) as questions FROM `nexus_questions` WHERE form_id=:fid');
            $stmt2->bindParam(':fid', $row['id'], PDO::PARAM_INT);
            $stmt2->execute();
            $result = $stmt2->fetch();
            $questions = $result['questions'];

            $stmt4 = $conn->prepare('SELECT count(id) as total FROM nexus_apps WHERE form_id = ?');
            $stmt4->execute([$row['id']]);
            $result = $stmt4->fetch();
            $appcount = $result['total'];
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
                                <p><?=$questions?></p>
                            </div>
                            <div class="col-md-6 text-center">
                                <h6>Applications</h6>
                                <p><?=$appcount?></p>
                            </div>
                            <?php $perm = permcheck(5);
                            if ($perm) {
                            ?>
                                <div class="col-md-6 text-center">
                                    <button type="submit" class="btn btn-warning w-100" onclick="window.location.href='manage-form.php?id=<?= $row['id'] ?>'">Manage Form</button>
                                </div>
                            <?php }
                            $perm = permcheck(6);
                            if ($perm) {
                            ?>
                                <div class="col-md-6 text-center">
                                    <button type="submit" class="btn btn-info w-100" onclick="window.location.href='applications.php?id=<?= $row['id'] ?>'">View Apps</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <!-- Modal -->
    <?php $perm = permcheck(5);
    if ($perm) { ?>
        <div id="formModal" class="modal show" role="dialog" tabindex="-1" aria-labelledby="formModal" aria-hidden="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">Create an Applications</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="fname">Name</label>
                                <input type="text" name="aname" class="form-control" id="aname" placeholder="Police Application" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic">Picture</label>
                            <input type="text" name="apic" class="form-control" id="apic" placeholder="image.png" required>
                        </div>
                        <div class="form-group">
                            <label for="ob">Order By</label>
                            <input type="number" name="asort" class="form-control" id="asort" placeholder="1" required>
                        </div>
                        <div class="form-group">
                            <label for="cd">Cooldown</label>
                            <input type="number" name="cd" class="form-control" id="cd" placeholder="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" id="capp_btn" onclick="addApp()">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- end of modal -->
<?php
} else {
?>
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle"></i> Looks like you don't have permission to view this 🙄
    </div>
<?php
}
?>