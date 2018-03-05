<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <a href="module.php?module=productmanager/myproducts.php">
                <div class="card-header" data-background-color="grey">
                    <i class="material-icons">account_circle</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Active Products</p>
                <?php
                $ts3 = $db->simpleQuery("SELECT id FROM product_ts3server WHERE active=1 AND userid='" . $user->getId() . "'");
                $vserver = $db->simpleQuery("SELECT id FROM product_vserver WHERE active=1 AND userid='" . $user->getId() . "'");
                $products = $ts3->num_rows;
                $products += $vserver->num_rows;
                ?>
                <h3 class="title"><?= $products ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Active
                </div>
            </div>
        </div>
    </div>
</div>