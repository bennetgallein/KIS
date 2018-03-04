<?php
if (!isset($params->list)) {
    $params->list = "all";
}
if ($params->list != "all" || $params->list != "vserver" || $params->list != "ts3") {
    $params->list = "all";
}
if ($params->list == "all") {
    $vservers = $db->simpleQuery("SELECT * FROM product_vserver WHERE userid='" . $user->getId() . "'");
}
?>
<div class="row">
    <div class="col-md-12">
        <?php if (isset($vservers)):?>
        <div class="card">
            <a href="#">
                <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                    <h4 class="title">Product Overview</h4>
                </div>
            </a>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>ID</th>
                        <th>Server ID</th>
                        <th>Status</th>
                        <th>Bought on</th>
                        <th>Expected Renewal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $vservers->fetch_object()):?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->vpsid?></td>
                        <td>running</td>
                        <td><?= $row->orderedon?></td>
                        <td><?= $row->expectedrenewal?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>