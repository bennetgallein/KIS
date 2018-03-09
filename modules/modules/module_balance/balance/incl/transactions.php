<?php
$query = $db->simpleQuery("SELECT * FROM balance_transactions WHERE userid='" . $params->user . "' ORDER BY createdate DESC");
if (!$query) {
    die("MySQL ERROR! <a href='changelog.php'>Submit Error here!</a>");
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Monthly transactions</h4>
                <p class="category">These are all of the users transactions</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>Order ID</th>
                        <th>Price</th>
                        <th>Message</th>
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
                                <td><?= (($row->positive) ? "+" : "-") . $row->price ?>€</td>
                                <td><?= $row->text ?></td>
                                <td><?= $row->createdate ?></td>
                            </tr>
                        <?php endwhile;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>