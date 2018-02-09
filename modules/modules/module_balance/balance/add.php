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

if (isset($_POST['amount'])) {
    $api = new ApiContext(
        new OAuthTokenCredential(
            'Abna9IF-w46mZQL5vE5lF32WI1UXW_A-uaTWWINk6sJdB8zthx4O53fjkMzP73JYzArRLw7iorOMN41p',
            'EDf1zdSYGmIrUFg2U0WfsSyyLhZQ6PcorU_nXQ__6zgU_uIuWfJw_KrI_TRwL6kis7LWtgFJgWUtoVUF'
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
    $productionsuccessurl = "http://kis.intranetproject.net/thankyou.php";
    $productioncancelurl = "http://kisp.intranetproject.net/maybe.php";
    $redirectUrls->setReturnUrl("http://server/KIS/intranet_shop/thankyou.php")
        ->setCancelUrl("http://server/KIS/intranet_shop/maybe.php");
    $payment->setRedirectUrls($redirectUrls);
    try {
        $payment->create($api);
        $hash = md5($payment->getId());
        $_SESSION['paypal_hash'] = $hash;
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
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Add balance</h4>
            </div>
            <div class="card-content">
                <form action="module.php?module=balance/add.php" method="post">
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
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>