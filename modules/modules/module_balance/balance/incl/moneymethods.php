<?php

class MoneyMethods {

    public function sendMoney($db, $message, $userid, $amount, $positive = 1, $plusforcompany = 1) {
        $res = $db->simpleQuery("INSERT INTO balance_transactions (text, userid, price, positive, plusforcompany) VALUES ('" . $db->getConnection()->escape_string($message) . "', '" . $db->getConnection()->escape_string($userid) . "', " . $amount . ", " . $positive . ", " . $plusforcompany . ")");
        if ($res) {
            $query = $db->simpleQuery("SELECT * FROM balances WHERE userid='" . $db->getConnection()->escape_string($userid) . "' LIMIT 1");
            $obj = $query->fetch_object();
            $new = $obj->balance + $amount;
            $query = $db->simpleQuery("UPDATE balances SET balance=" . $new . " WHERE userid='" . $db->getConnection()->escape_string($userid) . "'");
            if (!$query) {
                echo "FAILED!";
            }
            $query = $db->simpleQuery("INSERT INTO notifications (userid, message) VALUES ('" . $db->getConnection()->escape_string($userid) . "', '" . $db->getConnection()->escape_string($message) . "')");
        }
    }
}