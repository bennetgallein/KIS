<?php
$query = $db->simpleQuery("SELECT * FROM tickets WHERE userid='$params->user'");
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color']?>">
                <h4 class="title">Support</h4>
                <p class="category">Open and Closed Support Tickets</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>userid</th>
                        <th>created on</th>
                        <th>updated on</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $query->fetch_object()): ?>
                    <tr data-background-color="<?= $row->status ? "green" : "red" ?>">
                        <td><?= $row->id ?></td>
                        <td><?= $row->title ?></td>
                        <td><?= $row->userid ?></td>
                        <td><?= $row->created_at ?></td>
                        <td><?= $row->updated_at ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>