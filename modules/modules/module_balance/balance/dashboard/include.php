<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <a href="module.php?module=balance/manager.php">
                <div class="card-header" data-background-color="red">
                    <i class="material-icons">attach_money</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Account Balance</p>
                <?php
                $res = $db->simpleQuery("SELECT balance FROM balances WHERE userid='" . $user->getId() . "'");
                $balance = $res->fetch_object();
                $balance = $balance->balance;
                ?>
                <h3 class="title"><?= $balance ?>€</h3>
            </div>
        </div>
    </div>
</div>