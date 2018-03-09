<?php
if (isset($_POST['userid']) && isset($_POST['amount']) && isset($_POST['message'])) {
    $does = $db->simpleQuery("SELECT * FROM balances WHERE userid='" . $_POST['userid'] . "'");
    if ($does->num_rows <= 0) {
        $ins = $db->simpleQuery("INSERT INTO balances (userid, balance) VALUES ('" . $_POST['userid'] . "', 0)");
    }
    $res = $db->simpleQuery("INSERT INTO balance_transactions (text, userid, price, positive, plusforcompany) VALUES ('" . $db->getConnection()->escape_string($_POST['message']) . "', '" . $db->getConnection()->escape_string($_POST['userid']) . "', " . $_POST['amount'] . ", 1, 2)");
    if ($res) {
        $query = $db->simpleQuery("SELECT * FROM balances WHERE userid='" . $db->getConnection()->escape_string($_POST['userid']) . "' LIMIT 1");
        $obj = $query->fetch_object();
        $new = $obj->balance + $_POST['amount'];
        $query = $db->simpleQuery("UPDATE balances SET balance=" . $new . " WHERE userid='" . $db->getConnection()->escape_string($_POST['userid']) . "'");
        if (!$res) {
            echo "FAILED!";
        }
        $query = $db->simpleQuery("INSERT INTO notifications (userid, message) VALUES ('" . $db->getConnection()->escape_string($_POST['userid']) . "', '" . $_POST['amount'] . "&euro; were added to your account!')");
        header("Location: module.php?module=balance/balancemanager.php");
    }
}
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Add money</h4>
            </div>
            <div class="card-content">
                <form action="module.php?module=balance/balancemanager.php" method="post">
                    <div class="input-group col-md-12">
                        <input type="text" name="userid" class="form-control" placeholder="User ID">
                    </div>
                    <div class="input-group col-md-4">
                        <input type="text" name="amount" class="form-control" aria-label="Amount (to the nearest dollar)"
                               placeholder="Amount (Example: 7.60)">
                    </div>
                    <div class="input-group col-md-12">
                    <textarea type="text" name="message" class="form-control" placeholder="Tip text" id="comment"
                              rows="3"></textarea>
                    </div>
                    <div class="input-group-btn">
                        <input type="submit" data-background-color="blue" class="btn pull-right" value="GO!">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>