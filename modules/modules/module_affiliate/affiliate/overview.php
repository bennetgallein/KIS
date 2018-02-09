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
$nextlevel = $db->simpleQuery("SELECT * FROM affiliate_leveldata WHERE id=$nextlevel");
$nextlevel = $nextlevel->fetch_object();
$userrequierd = $nextlevel->userrequired;
$percent = round((100 / $userrequierd) * $userused, 2);

if ($userused >= $userrequierd) {
    $levelplusone = $nextlevel->id;
    $newLevel = $db->simpleQuery("UPDATE affiliate_levels SET userlevel=" . $levelplusone . " WHERE userid='" . $user->getId() . "'");
    $db->redirect("module.php?module=affiliate/overview.php");
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
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $percent ?>"
                                 aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent ?>%;">
                                <?= $percent ?>%
                            </div>
                        </div>
                        <p>You already recuted <u><b><?= $userused ?></b></u> Users and need
                            <u><b><?= round($userrequierd - $userused, 2) ?></b></u> for the next level! Current level:
                            <b><u><?= $userlevel ?></u></b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>