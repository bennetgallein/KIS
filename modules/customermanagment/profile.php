<?php
if (!isset($params->user)) {
    header("Location: index.php");
}

$user = $db->simpleQuery("SELECT * FROM users WHERE id='" . $params->user . "'");
if ($user) {
    $user = $user->fetch_object();
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
                <h4 class="title">Profile of <?= $user->firstname . " " . $user->lastname ?></h4>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group label-floating">
                            <label class="control-label">First Name</label>
                            <p class="form-control"><?= $user->firstname ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group label-floating">
                            <label class="control-label">Last Name</label>
                            <p class="form-control"><?= $user->lastname ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group label-floating">
                            <label class="control-label">Email address</label>
                            <p class="form-control"><?= $user->email ?></p>
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
                <a class="btn btn-primary pull-right" data-background-color="red" href="module.php?module=customermanagment/profile.php&params=user|<?= $user->id?>_delete|1">Delete Profile </a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php if ($db->moduleExistts("Billing Manager")):?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="purple">
                <h4 class="title">Billing</h4>
                <p class="category">These are all the billing information</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>well</th>
                        <th>hmm</th>
                        <th>yeah</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>das</td>
                        <td>sad</td>
                        <td>asd</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($db->moduleExistts("Product Manager")): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="purple">
                <h4 class="title">Products</h4>
                <p class="category">These are all the products used by the user.</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>well</th>
                        <th>hmm</th>
                        <th>yeah</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>das</td>
                        <td>sad</td>
                        <td>asd</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif;?>