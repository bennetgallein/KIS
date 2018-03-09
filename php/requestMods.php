<?php
include("database.php");
$db = new DB();

if (isset($_GET['key'])) {
    if ($_GET['key'] == "aa") {
        $response = array();
        foreach($db->getModules() as $mod) {
            $response[] = $mod->getRaw();
        }
        echo json_encode($response);
    }
}