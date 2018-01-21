<?php
if (!isset($params->user)) {
    header("Location: index.php");
}

$user1 = $db->simpleQuery("SELECT * FROM users WHERE id='" . $params->user . "'");
if ($user1) {
    $user1 = $user1->fetch_object();
}
$address = $db->simpleQuery("SELECT * FROM adresses WHERE userid='" . $params->user . "'");
if ($user) {
    $address = $address->fetch_object();
}
if (isset($params->delete)) {
    $res = $db->simpleQuery("DELETE FROM users WHERE id='" . $params->user . "'");
    header("Location: module.php?module=customermanagment/list.php");
    die();
}
?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" data-background-color="purple">
                    <h4 class="title">Profile of <?= $user1->firstname . " " . $user1->lastname ?></h4>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group label-floating">
                                <label class="control-label">First Name</label>
                                <p class="form-control"><?= $user1->firstname ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group label-floating">
                                <label class="control-label">Last Name</label>
                                <p class="form-control"><?= $user1->lastname ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group label-floating">
                                <label class="control-label">Email address</label>
                                <p class="form-control"><?= $user1->email ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group label-floating">
                                <label class="control-label">Adress</label>
                                <p class="form-control"><?= isset($address->adress) ? ($address->adress) : "Not set" ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group label-floating">
                                <label class="control-label">Company</label>
                                <p class="form-control"><?= isset($address->company) ? $address->company : "Not set" ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group label-floating">
                                <label class="control-label">City</label>
                                <p class="form-control"><?= isset($address->city) ? $address->city : "Not set" ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group label-floating">
                                <label class="control-label">Country</label>
                                <p class="form-control"><?= isset($address->country) ? $address->country : "Not set" ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group label-floating">
                                <label class="control-label">Postal Code</label>
                                <p class="form-control"><?= isset($address->postalcode) ? $address->postalcode : "Not set" ?></p>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <!-- THIS BUTTON IS NOT IN HTML. NOT EVEN IN THE DEVTOOLS!?!?!? WTF-->
                        <a class="btn btn-primary pull-right" data-background-color="red"
                           href="module.php?module=customermanagment/profile.php&params=user|<?= $user->getId() ?>_delete|1">Delete
                            Profile </a>
                        <!--<div class="clearfix"></div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
if ($db->moduleExists("Support Manager")) {
    $module = $db->getModuleByName("Support Manager");
    if ($module->getIncludeable("profile_overview")['permission'] <= $user->getPermissions()) {
        include(dirname(__FILE__) . "/../" . $module->getIncludeable("profile_overview")['link']);
    }
}
?>