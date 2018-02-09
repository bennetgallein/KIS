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

include("../vendor/autoload.php");
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
    $productionsuccessurl = "http://shop.intranetproject.net/thankyou.php";
    $productioncancelurl = "http://shop.intranetproject.net/maybe.php";
    $redirectUrls->setReturnUrl("http://server/Intranet_GitHub/intranet_shop/thankyou.php")
        ->setCancelUrl("http://server/Intranet_GitHub/intranet_shop/maybe.php");
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
    header('Location:' . $redirectUrl);
}