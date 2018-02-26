<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <a href="module.php?module=customermanagment/list.php">
                <div class="card-header" data-background-color="grey">
                    <i class="material-icons">account_circle</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Customers</p>
                <?php
                $res = $db->simpleQuery("SELECT id FROM users");
                $accounts = $res->num_rows;
                ?>
                <h3 class="title"><?= $accounts ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Overall
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <a href="module.php?module=customermanagment/list.php">
                <div class="card-header" data-background-color="red">
                    <i class="material-icons">account_circle</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Customers</p>
                <?php
                $res = $db->simpleQuery("SELECT id FROM users WHERE registered_at >= now() - INTERVAL 30 DAY");
                $accounts = $res->num_rows;
                ?>
                <h3 class="title"><?= $accounts ?></h3>
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
            <a href="module.php?module=customermanagment/list.php">
                <div class="card-header" data-background-color="orange">
                    <i class="material-icons">account_circle</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Customers</p>
                <?php
                $res = $db->simpleQuery("SELECT id FROM users WHERE registered_at >= now() - INTERVAL 7 DAY");
                $accounts = $res->num_rows;
                ?>
                <h3 class="title"><?= $accounts ?></h3>
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
            <a href="module.php?module=customermanagment/list.php">
                <div class="card-header" data-background-color="green">
                    <i class="material-icons">account_circle</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Customers</p>
                <?php
                $res = $db->simpleQuery("SELECT id FROM users WHERE registered_at >= now() - INTERVAL 1 DAY");
                $accounts = $res->num_rows;
                ?>
                <h3 class="title"><?= $accounts ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> last Day
                </div>
            </div>
        </div>
    </div>
</div>