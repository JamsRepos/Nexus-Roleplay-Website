<?php
                    $perm = permcheck(1);
                    if ($perm) {
                    ?>
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
                    <?php
                    } else {
                    ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Looks like you don't have permission to view this 🙄
                        </div>
                    <?php
                    }
                    ?>