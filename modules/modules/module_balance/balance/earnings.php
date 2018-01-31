<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="green">
                <i class="material-icons">attach_money</i>
            </div>
            <?php
            $total = 0;
            $res = $db->simpleQuery("SELECT * FROM balance_transactions WHERE NOT plusforcompany=0 AND createdate >= now() - INTERVAL 1 DAY");
            while ($row = $res->fetch_object()) {
                if ($row->plusforcompany == "1") {
                    $total += $row->price;
                } else {
                    $total -= $row->price;
                }
            }
            ?>
            <div class="card-content">
                <p class="category">Earned:</p>
                <h3 class="title"><?= $total ?>€</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> last Day
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="orange">
                <i class="material-icons">attach_money</i>
            </div>
            <?php
            $total = 0;
            $res = $db->simpleQuery("SELECT * FROM balance_transactions WHERE (plusforcompany=1 OR plusforcompany=2) AND createdate >= now() - INTERVAL 7 DAY");
            while ($row = $res->fetch_object()) {
                if ($row->plusforcompany == "1") {
                    $total += $row->price;
                } else {
                    $total -= $row->price;
                }
            }
            ?>
            <div class="card-content">
                <p class="category">Earned:</p>
                <h3 class="title"><?= $total ?>€</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> last 7 Days
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="red">
                <i class="material-icons">attach_money</i>
            </div>
            <?php
            $total = 0;
            $res = $db->simpleQuery("SELECT * FROM balance_transactions WHERE NOT plusforcompany=0 AND createdate >= now() - INTERVAL 30 DAY");
            while ($row = $res->fetch_object()) {
                if ($row->plusforcompany == "1") {
                    $total += $row->price;
                } else {
                    $total -= $row->price;
                }
            }
            ?>
            <div class="card-content">
                <p class="category">Earned:</p>
                <h3 class="title"><?= $total ?>€</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> last 30 Days
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="grey">
                <i class="material-icons">attach_money</i>
            </div>
            <?php
            $total = 0;
            $res = $db->simpleQuery("SELECT * FROM balance_transactions WHERE NOT plusforcompany=0 AND createdate >= now() - INTERVAL 365 DAY");
            while ($row = $res->fetch_object()) {
                if ($row->plusforcompany == "1") {
                    $total += $row->price;
                } else {
                    $total -= $row->price;
                }
            }
            ?>
            <div class="card-content">
                <p class="category">Earned:</p>
                <h3 class="title"><?= $total ?>€</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Overall
                </div>
            </div>
        </div>
    </div>
</div>