<?php
$query = $db->simpleQuery("SELECT * FROM balance_transactions WHERE userid='" . $params->user . "' AND createdate >= now() - INTERVAL 30 DAY");
if (!$query) {
    die("MySQL ERROR! <a href='changelog.php'>Submit Error here!</a>");
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color']?>">
                <h4 class="title">Monthly transactions</h4>
                <p class="category">These are your transactions in the last month.</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>Order ID</th>
                        <th>Price</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($query->num_rows == 0) {
                        echo "No Results found!";
                    } else {
                    while ($row = $query->fetch_object()): ?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->price ?>€</td>
                        <td><?= $row->createdate ?></td>
                    </tr>
                    <?php endwhile; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>