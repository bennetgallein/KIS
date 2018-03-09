<div class="row">
    <div class="col-md-12">
        <div class="card">
            <a href="#">
                <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                    <h4 class="title">Product Overview</h4>
                </div>
            </a>
            <?php
            $res = $db->simpleQuery("SELECT * FROM product_ts3server WHERE userid='" . $params->user . "'")
            ?>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Slots</th>
                        <th>TSID</th>
                        <th>Ordered</th>
                        <th>Expected Renewal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $res->fetch_object()):?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td>Ts3-Server</td>
                        <td><?= $row->active == 1 ? "active" : "inactive"?></td>
                        <td><?= $row->slots ?></td>
                        <td><?= $row->tsid ?></td>
                        <td><?= $row->orderedon ?></td>
                        <td><?= $row->expectedrenewal?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php
            $res = $db->simpleQuery("SELECT * FROM product_vserver WHERE userid='" . $params->user . "'")
            ?>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>vpsid</th>
                        <th>Ordered</th>
                        <th>Expected Renewal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $res->fetch_object()):?>
                        <tr>
                            <td><?= $row->id ?></td>
                            <td>vServer</td>
                            <td><?= $row->active == 1 ? "active" : "inactive"?></td>
                            <td><?= $row->vpsid ?></td>
                            <td><?= $row->orderedon ?></td>
                            <td><?= $row->expectedrenewal?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>