<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="red">
                <i class="material-icons">attach_money</i>
            </div>
            <div class="card-content">
                <p class="category">Account Balance</p>
                <?php
                $res = $db->simpleQuery("SELECT balance FROM balance WHERE userid='" . $user->getId() . "'");
                $balance = $res->fetch_object();
                $balance = $balance->balance;
                ?>
                <h3 class="title"><?= $balance ?>€</h3>
            </div>
        </div>
    </div>
</div>