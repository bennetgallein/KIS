<?php
function getMoney($db, $user) {
    $res = $db->simpleQuery("SELECT * FROM balances WHERE userid='" . $user->getId() . "' LIMIT 1");
    if (!$res) {
        return 0;
    } else {
        $row = $res->fetch_object();
        return $row->balance;
    }
}
