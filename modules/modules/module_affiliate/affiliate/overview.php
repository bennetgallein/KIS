<?php
$level = $db->simpleQuery("SELECT * FROM affiliate_levels WHERE userid='" . $user->getId() . "'");

if ($level->num_rows == 0) {
    $level = $db->simpleQuery("INSERT INTO affiliate_levels (userid, link) VALUES ('" . $user->getId() . "', '" . $db->getConfig()['url'] . "/module.php?module=affiliate/overview.php&params=from|')");
    $level = $db->simpleQuery("SELECT * FROM affiliate_levels WHERE userid='" . $user->getId() . "'");
}
$data = $level->fetch_object();

$userlevel = $data->userlevel;
$userused = $data->usersused;
$invitelink = $data->link . $user->getId();

$nextlevel = $userlevel + 1;
$currentlevel = $db->simpleQuery("SELECT * FROM affiliate_leveldata WHERE id=$userlevel");
$currentlevel = $currentlevel->fetch_object();
$nextlevel = $db->simpleQuery("SELECT * FROM affiliate_leveldata WHERE id=$nextlevel");
if ($nextlevel->num_rows == 0) {
    $userrequierd = $userused;
    $nextlevelid = $userlevel;
} else {
    $nextlevel = $nextlevel->fetch_object();
    $userrequierd = $nextlevel->userrequired;
    $nextlevelid = $nextlevel->id;
    $amounttorecieve = $currentlevel->reward;
}
//$percent = round((100 / ($userrequierd - $userused)) * $userused, 2);

$percent = round(($userused % ($nextlevel->userrequired - $currentlevel->userrequired)) * (100 / ($nextlevel->userrequired - $currentlevel->userrequired)), 2);

if ($userused >= $userrequierd) {
    $levelplusone = $nextlevelid;
    $newLevel = $db->simpleQuery("UPDATE affiliate_levels SET userlevel=" . $levelplusone . " WHERE userid='" . $user->getId() . "'");
    //$db->redirect("module.php?module=affiliate/overview.php");
}
if ($userlevel - 1 == 0 || $userlevel - 1 == -1) {
    $userlevel = 2;
}
$userlevel -= 1;
$amounttorecieve = $db->simpleQuery("SELECT * FROM affiliate_leveldata WHERE id=" . $userlevel);
$amounttorecieve = $amounttorecieve->fetch_object();
$amounttorecieve = $amounttorecieve->reward;
if (isset($params->claim)) {
    if (!$data->claimed) {
        if (!$data->claimed && $data->userlevel >= 2) {
            // Transaktion machen.
        }
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Become an affiliate</h4>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <h4>Your affiliate link is:</h4>
                        <p><a href="<?= $invitelink ?>"><?= htmlspecialchars($invitelink) ?></a></p><h4>Copy it and
                            invite your friends via it and get cool perks!</h4>
                        <p>Progress for next level:</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                 aria-valuenow="<?= $percent ?>"
                                 aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent ?>%;">
                                <?= $percent ?>%
                            </div>
                        </div>
                        <p>You already recuted <u><b><?= $userused ?></b></u> Users and need
                            <u><b><?= $userrequierd - $userused ?></b></u> for the next level! Current level:
                            <b><u><?= $userlevel ?></u></b></p>
                        <?php
                        if (!$data->claimed && $data->userlevel >= 2):
                            ?>
                            <button class="btn btn-default" data-background-color="<?= $db->getConfig()['color'] ?>">
                                <a href="module.php?module=affiliate/overview.php&params=claim|true">Claim Reward (<?= $amounttorecieve ?>)</a>
                            </button>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Initialize Bootstrap functionality -->
<script>
    // Initialize tooltip component
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // Initialize popover component
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>