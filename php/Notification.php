<?php

class Notification {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getNotifications($user) {
        $user = $this->db->getConnection()->real_escape_string($user);
        return $this->db->simpleQuery("SELECT * FROM notifications WHERE userid='" . $user . "' AND isread = 0 ORDER BY inserted DESC");
    }
    
    public function addNotification($user, $text) {
        $user = $this->db->getConnection()->real_escape_string($user);
        $text = $this->db->getConnection()->real_escape_string($text);
        return $this->db->simpleQuery("INSERT INTO notifications (userid, message) VALUES ('" . $user . "', '" . $text . "')");
    }
    
    public function removeNotification($user, $id) {
        $user = $this->db->getConnection()->real_escape_string($user);
        $id = $this->db->getConnection()->real_escape_string($id);
        $sql = "SELECT * FROM notifications WHERE userid='" . $user . "' AND id =" . $id . " LIMIT 1";
        $res = $this->db->simpleQuery($sql);
        if ($res->num_rows >= 1) {
            $sql = "UPDATE notifications SET isread=1 WHERE userid='" . $user . "' AND id =" . $id;
            $res = $this->db->simpleQuery($sql);
            if (isset($_GET['return'])) {
                header("Location: " . $_GET['return']);
            } else {
                header("Location: index.php");
            }
        }
    }
}