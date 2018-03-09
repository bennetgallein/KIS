<?php
$query = $db->simpleQuery("SELECT * FROM tickets WHERE userid='$params->user' ORDER BY created_at DESC");
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Support</h4>
                <p class="category">Open and Closed Support Tickets (click on the title to get detailed information)</p>
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
                    <?php
                    if ($query->num_rows == 0) {
                        echo "No Results found!";
                    } else {
                        while ($row = $query->fetch_object()): ?>
                            <tr data-background-color="<?= $row->status ? "green" : "red" ?>">
                                <td><?= $row->id ?></td>
                                <td>
                                    <a href="module.php?module=support/ticket.php&params=id|<?= $row->id ?>"><?= $row->title ?></a>
                                </td>
                                <td><?= $row->userid ?></td>
                                <td><?= $row->created_at ?></td>
                                <td><?= $row->updated_at ?></td>
                                <td><a href="module.php?module=support/ticket.php&params=id|<?= $row->id ?>"><i
                                                class="material-icons">open_in_new</i></a></td>
                                <td><a href="module.php?module=support/ticket.php&params=id|<?= $row->id ?>_close|1"><i
                                                class="material-icons">delete_forever</i></a></td>
                            </tr>
                        <?php endwhile;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>