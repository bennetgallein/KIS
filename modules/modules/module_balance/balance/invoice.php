<?php
if (isset($params->id)) {
    $id = $db->getConnection()->escape_string($params->id);
    $invoiceobj = $db->simpleQuery("SELECT * FROM balance_transactions WHERE id=" . $id . " AND positive=0 LIMIT 1");
    if ($invoiceobj->num_rows >= 1) {
        $row = $invoiceobj->fetch_object();
        $proceed = true;
    } else {
        echo "This is not an Invoice!";
        die();
    }
} else {
    echo "No ID provided!";
    die();
}
if (!($row->userid == $user->getId() || $user->getPermissions() >= 2)) {
    echo "This is not yours!";
    die();
}
$address = $db->simpleQuery("SELECT * FROM adresses WHERE userid='" . $user->getId() . "'");
if ($address->num_rows == 0) {
    echo "No public Address set, set it on your profile!";
    die();
}
$address = $address->fetch_object();
?>
<div class="row">
    <div class="col-md-12">
        <?php
        if (isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        }

        if ($proceed):
        ?>
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Invoice ID: <?= $row->id ?></h4>
            </div>
            <div class="card-content table-responsive">
                <div>
                <div class="pull-right col-md-3">
                    <!-- Company Data -->
                    <address>
                        <?= $row->createdate?><br>
                        <?= $db->getConfig()['contactdata']['address_1']?><br>
                        <?= $db->getConfig()['contactdata']['address_2']?><br>
                        <abbr title="Phone">P:</abbr> <?= $db->getConfig()['contactdata']['phone']?>
                    </address>
                </div>
                <div class="pull-left">
                    <address>
                        <h3><strong><?= $address->company?></strong></h3><br>
                        <?= $address->adress?><br>
                        <?= $address->city?> , <?=$address->postalcode?><br>
                        <abbr title="Email">E:</abbr> <?= $user->getEmail()?>
                    </address>

                </div>
                </div>
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>Product</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= $row->text ?></td>
                        <td>1</td>
                    </tr>
                    </tbody>
                </table>
                <div class="table-responsive pull-right col-md-3">
                    <table class="table">
                        <!--<tr>
                            <td>Subtotal</td>
                            <td>50.00€</td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td>10.00%</td>
                        </tr>
                        <tr>
                            <td>Other</td>
                            <td>1.00€</td>
                        </tr>-->
                        <tr>
                            <td>Total</td>
                            <td><?= $row->price?>€</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12" style="font-size: 1.5em;">
                    <p>If you experience any problems contact us here:</p><br>
                    <h4><?= $db->getConfig()['contactdata']['email']; ?></h4>
                </div>
                <br><br><br>
                <h2 class="col-md-12">Thank you for your business!</h2>
            </div>
        </div>
        <?php
        endif;
        ?>
    </div>
</div>