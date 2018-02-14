<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ItemList;
use PayPal\Api\Item;

include("vendor/autoload.php");
// canceled payment: module=balance/manager.php&token=x
// success payment:  module=balance/manager.php&paymentId=x&token=x&PayerID=x
if (isset($_GET['token']) && isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
    $pid = $db->getConnection()->escape_string($_GET['paymentId']);
    $result1 = $db->simpleQuery("SELECT * FROM transactions_paypal WHERE payment_id='$pid' AND complete=0");
    if ($result1->num_rows >= 1) {
        $obj1 = $result1->fetch_object();
        $result = $db->simpleQuery("UPDATE transactions_paypal SET complete=1 WHERE payment_id='$pid'");
        if ($result) {
            include(__DIR__ . "/incl/moneymethods.php");
            $moneymethods = new MoneyMethods();
            $moneymethods->sendMoney($db, "PayPal payment", $user->getId(), $obj1->amount);
            $_SESSION['error'] = "Payment completed! " . $obj1->amount . " were added to your account!";
        } else {
            echo mysqli_error($link);
        }
    }
}
if (isset($_GET['token']) && !isset($_GET['paymentId']) && !isset($_GET['PayerID'])) {
    $_SESSION['error'] = "Payment canceled!";
}
if (isset($_POST['amount'])) {
    $amount = $_POST['amount'];
    if (is_int($amount)) {
        if ($amount > 0) {

            $api = new ApiContext(
                new OAuthTokenCredential(
                    $db->getConfig()['paypal']['client'],
                    $db->getConfig()['paypal']['secret']
                )
            );
            $api->setConfig([
                'mode' => 'sandbox',
                'http.ConnectionTimeout' => 30,
                'log.LogEnabled' => false,
                'log.FileName' => 'log.txt',
                'log.LogLevel' => 'FINE',
                'validation.level' => 'log'
            ]);
            $itemlist = new ItemList();
            $payer = new Payer();
            $details = new Details();
            $amounta = new Amount();
            $transaction = new Transaction();
            $payment = new Payment();
            $redirectUrls = new RedirectUrls();
            $payer->setPaymentMethod('paypal');

            $amount = 1;
            $total = $_POST['amount'];
            $cartids = "";
            $items = array();
            $item = new Item();
            $item->setName("Fee-Hosting Balance")->setCurrency("EUR")->setQuantity("1")->setPrice("0" + $_POST['amount']);
            array_push($items, $item);
            $itemlist->setItems($items);
            $details->setShipping('0.00')
                ->setTax('0.00')
                ->setSubtotal($total);
            $amounta->setCurrency('EUR')
                ->setTotal($total)
                ->setDetails($details);
            $transaction->setAmount($amounta)->setItemList($itemlist)->setDescription("Fee-Hosting Balance");
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions(array($transaction));
            $productionsuccessurl = "http://kis.intranetproject.net/dashboard/module.php?module=balance/manager.php";
            $productioncancelurl = "http://kisp.intranetproject.net/maybe.php";
            $redirectUrls->setReturnUrl("http://server/KIS/dashboard/module.php?module=balance/manager.php")
                ->setCancelUrl("http://server/KIS/dashboard/module.php?module=balance/manager.php");
            $payment->setRedirectUrls($redirectUrls);
            try {
                $payment->create($api);
                $hash = md5($payment->getId());
                $_SESSION['paypal_hash'] = $hash;

                $result = $db->simpleQuery("INSERT INTO transactions_paypal (userid, payment_id, hash, amount, complete, cartid) VALUES ('" . $user->getId() . "', '" . $payment->getId() . "', '" . $hash . "'," . $_POST['amount'] . ", 0, 1)");

            } catch (PayPal\Exception\PayPalConnectionException $e) {
                echo '<pre>';
                print_r(json_decode($e->getData()));
                exit;
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirectUrl = $link->getHref();
                }
            }
            $db->redirect($redirectUrl);
        }
    }
}
$query = $db->simpleQuery("SELECT * FROM balance_transactions WHERE userid='" . $user->getId() . "' ORDER BY createdate DESC");
if (!$query) {
    die("MySQL ERROR! <a href='changelog.php'>Submit Error here!</a>");
}
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Add balance</h4>
                <p>dezimal seperated by "." and thousand by ","!</p>
            </div>
            <div class="card-content">
                <form action="module.php?module=balance/manager.php" method="post">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group label-floating">
                                <label class="control-label">Amount</label>
                                <input name="amount" value="" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <button type="submit" class="btn btn-default"
                                    data-background-color="<?= $db->getConfig()['color'] ?>"><i
                                        class="fab fa-paypal fa-lg"></i> Paypal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo "<div class=row>" . (isset($_SESSION['error']) ? $_SESSION['error'] : "") . "</div>";
unset($_SESSION['error']); ?>

<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Monthly transactions</h4>
                <p class="category">These are your transactions of all time.</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>Order ID</th>
                        <th>Price</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($query->num_rows == 0) {
                        echo "No Results found!";
                    } else {
                        while ($row = $query->fetch_object()): ?>
                            <tr>
                                <td><?= $row->id ?></td>
                                <td><?= (($row->positive) ? "+" : "-") . $row->price ?>€</td>
                                <td><?= $row->text ?></td>
                                <td><?= $row->createdate ?></td>
                            </tr>
                        <?php endwhile;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>