<?php
if (isset($params->view)) {
    $view = $params->view;
    if (!($view == '1' || $view == '2' || $view == '3' || $view == '4' || $view == '5')) {
        $view = '1';
    }
} else {
    $view = "1";
}
if ($view == '4') {
    $query = $db->simpleQuery("SELECT * FROM tickets ORDER BY id DESC");
} else if ($view == '5') {
    $query = $db->simpleQuery("SELECT * FROM tickets WHERE supporter='". $user->getId() . "' ORDER BY id DESC");
} else {
    $query = $db->simpleQuery("SELECT * FROM tickets WHERE status='$view' ORDER BY id DESC");
}

if ($view == '1') {
    $color = "data-background-color='orange'";
}
if ($view == '2') {
    $color = "data-background-color='red'";
}
if ($view == '3') {
    $color = "data-background-color='orange'";
}
if ($view == '4' || $view == '5') {
    $color = '';
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="center-block">
            <a class="btn" href="module.php?module=support/ticketoverview.php&params=view|1" style="background-color: #62ADF0">View Open Tickets</a>
            <a class="btn" href="module.php?module=support/ticketoverview.php&params=view|2" style="background-color: #62ADF0">View Closed Tickets</a>
            <a class="btn" href="module.php?module=support/ticketoverview.php&params=view|3" style="background-color: #62ADF0">View Only Assigned Tickets</a>
            <a class="btn" href="module.php?module=support/ticketoverview.php&params=view|4" style="background-color: #62ADF0">View All Tickets</a>
            <a class="btn" href="module.php?module=support/ticketoverview.php&params=view|5" style="background-color: #62ADF0">View My Tickets</a>
        </div>
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Support</h4>
                <p class="category"><?= $query->num_rows ?> Tickets</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>userid</th>
                        <th>status</th>
                        <th>created on</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $query->fetch_object()): ?>
                        <tr <?= $color ?>>
                            <td><?= $row->id ?></td>
                            <td><?= $row->title ?></td>
                            <td><?= $row->userid ?></td>
                            <td><?= $row->status ?></td>
                            <td><?= $row->created_at ?></td>
                            <td><a href="module.php?module=support/ticket.php&params=id|<?= $row->id ?>"><i
                                            class="material-icons">open_in_new</i></a></td>
                            <td><a href="module.php?module=support/ticket.php&params=id|<?= $row->id ?>_close|1"><i
                                            class="material-icons">delete_forever</i></a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

