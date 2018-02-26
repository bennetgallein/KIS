<?php
$level = $db->simpleQuery("SELECT * FROM affiliate_levels WHERE userid='" . $user->getId() . "'");

if ($level->num_rows == 0) {
    $level = $db->simpleQuery("INSERT INTO affiliate_levels (userid, link) VALUES ('" . $user->getId() . "', '" . $db->getConfig()['url'] . "/dashboard/module.php?module=affiliate/overview.php&params=redeem|')");
    $level = $db->simpleQuery("SELECT * FROM affiliate_levels WHERE userid='" . $user->getId() . "'");
}
$data = $level->fetch_object();

$userlevel = $data->userlevel + 1;
$userused = $data->usersused;
$invitelink = $data->link . $user->getId();

$nextlevel = $userlevel + 1;
$currentlevel = $db->simpleQuery("SELECT * FROM affiliate_leveldata WHERE id=$userlevel");
$currentlevel = $currentlevel->fetch_object();
$nextlevel = $db->simpleQuery("SELECT * FROM affiliate_leveldata WHERE id=$nextlevel");

?>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <a href="module.php?module=affiliate/overview.php">
                <div class="card-header" data-background-color="orange">
                    <i class="material-icons">account_circle</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Recruted</p>
                <h3 class="title"><?= $userused ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">transfer_within_a_station</i> <b><?= $currentlevel->userrequired ?></b>
                    more users needed for the next Level!
                </div>
            </div>
        </div>
    </div>
</div>
