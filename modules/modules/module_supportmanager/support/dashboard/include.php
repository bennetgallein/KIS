<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <a href="#">
                <div class="card-header" data-background-color="red">
                    <i class="material-icons">access_time</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Open Tickets</p>
                <?php
                $res = $db->simpleQuery("SELECT id FROM tickets WHERE status=1");
                $opentickets = $res->num_rows;
                ?>
                <h3 class="title"><?= $opentickets ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">access_time</i> Open
                </div>
            </div>
        </div>
    </div>
</div>