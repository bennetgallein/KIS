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

    public function getAmount($db, $userid) {
        $res = $db->simpleQuery("SELECT * FROM balances WHERE userid='" . $db->getConnection()->escape_string($userid) . "'");
        if ($res) {
            $obj = $res->fetch_object();
            return $obj->balance;
        }
    }

    public function removeAmount($db, $message, $userid, $amount, $positive = 0, $plusforcompay = 1) {
        $message = $db->getConnection()->escape_string($message);
        $userid = $db->getConnection()->escape_string($userid);

        $query = "INSERT INTO balance_transactions (text, userid, price, positive, plusforcompany) VALUES ('" . $message . "', '" . $userid . "', " . $amount . ", " . $positive . ", " . $plusforcompay . ")";
        $res = $db->simpleQuery($query);
        echo $db->error . "<br>";
        $query = $db->simpleQuery("SELECT * FROM balances WHERE userid='" . $userid . "' LIMIT 1");
        echo $db->error . "<br>";
        $obj = $query->fetch_object();
        $new = $obj->balance - $amount;
        $query = $db->simpleQuery("UPDATE balances SET balance=" . $new . " WHERE userid='" . $userid . "'");
        echo $db->error . "<br>";
        if (!$query) {
            echo "FAILED!";
        }
        $query = $db->simpleQuery("INSERT INTO notifications (userid, message) VALUES ('" . $userid . "', '" . $message . "')");
        echo $db->error . "<br>";

    }
}