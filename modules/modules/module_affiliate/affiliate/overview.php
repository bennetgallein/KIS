<?php
$level = $db->simpleQuery("SELECT * FROM affiliate_levels WHERE userid='" . $user->getId() . "'");

if ($level->num_rows == 0) {
    $level = $db->simpleQuery("INSERT INTO affiliate_levels (userid, link) VALUES ('" . $user->getId() . "', '" . $db->getConfig()['url'] . "/dashboard/module.php?module=affiliate/overview.php&params=redeem|')");
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
    $percent = 100;
} else {
    $nextlevel = $nextlevel->fetch_object();
    $userrequierd = $nextlevel->userrequired;
    $nextlevelid = $nextlevel->id;
    $amounttorecieve = $currentlevel->reward;
    $percent = round(($userused % ($nextlevel->userrequired - $currentlevel->userrequired)) * (100 / ($nextlevel->userrequired - $currentlevel->userrequired)), 2);
}

if ($userused >= $userrequierd) {
    $levelplusone = $nextlevelid;
    $newLevel = $db->simpleQuery("UPDATE affiliate_levels SET userlevel=" . $levelplusone . " WHERE userid='" . $user->getId() . "'");
}
$amounttorecieve = $db->simpleQuery("SELECT * FROM affiliate_leveldata WHERE id=" . ($userlevel - 1));
$amounttorecieve = $amounttorecieve->fetch_object();
$amounttorecieve = $amounttorecieve->reward;
if (isset($params->claim)) {
    if (!$data->claimed) {
        if (!$data->claimed && $data->userlevel >= 2) {
            // Transaktion machen.
            $module = $db->getModuleByName("Balance Manager");
            if (isset($module)) {
                if ($module->getIncludeable("moneymethods")['permission'] <= $user->getPermissions()) {
                    $re = include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("moneymethods")['link']);
                    $methods = new MoneyMethods();
                    $message = ("You recieved " . $amounttorecieve . "&euro; from the Affiliate Program!");
                    $methods->sendMoney($db, $message, $user->getId(), $amounttorecieve);
                    $result = $db->simpleQuery("UPDATE affiliate_levels SET claimed=1 WHERE userid='" . $user->getId() . "'");
                }
            }
        }
    }
}
if (isset($params->redeem)) {
    if ($data->usedone != 1) {
        if (isset($_POST['id'])) {
            $toredeemfrom = $_POST['id'];
        } else {
            $toredeemfrom = $params->redeem;
        }
        if ($user->getId() != $toredeemfrom) {
            $userid = $db->getConnection()->escape_string($toredeemfrom);
            $query = $db->simpleQuery("SELECT * FROM affiliate_levels WHERE userid='" . $userid . "'");
            if ($query->num_rows >= 1) {
                $userobject = $query->fetch_object();
                // currentuser +2€. userobject->id usersused +1
                $module = $db->getModuleByName("Balance Manager");
                if (isset($module)) {
                    if ($module->getIncludeable("moneymethods")['permission'] <= $user->getPermissions()) {
                        $re = include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("moneymethods")['link']);
                        $methods = new MoneyMethods();
                        $amounttorecieve = 2;
                        $message = ("You recieved " . $amounttorecieve . "&euro; from the Affiliate Program!");
                        $methods->sendMoney($db, $message, $user->getId(), $amounttorecieve);
                        $result = $db->simpleQuery("UPDATE affiliate_levels SET usedone=1 WHERE userid='" . $user->getId() . "'");
                        $newusersused = $userobject->usersused + 1;
                        $query = $db->simpleQuery("UPDATE affiliate_levels SET usersused=" . $newusersused . " WHERE userid='" . $userobject->userid . "'");
                        $_SESSION['error'] = "Successfully redeemed code!";
                    }
                }

            } else {
                $_SESSION['error'] = "Unknown User ID!";
            }
        } else {
            $_SESSION['error'] = "You cannot redeem your own code";
        }
    } else {
        $_SESSION['error'] = "You already redeemed a code!";
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
                                <a href="module.php?module=affiliate/overview.php&params=claim|true">Claim Reward
                                    (<?= $amounttorecieve ?>)</a>
                            </button>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <form action="module.php?module=affiliate/overview.php&params=redeem|true" method="post">
                        <div class="col-md-4 col-md-offset-3">
                            <div class="form-group label-floating">
                                <label class="control-label">Redeem a code</label>
                                <input name="id" value="" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" data-background-color="<?= $db->getConfig()['color'] ?>"
                                    class="btn btn-primary">Claim!
                            </button>
                        </div>
                    </form>
                </div>
                <?php
                if (isset($_SESSION['error'])) {
                    unset($_SESSION['error']);
                }
                ?>
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