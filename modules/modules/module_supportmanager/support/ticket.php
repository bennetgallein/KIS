<?php
$exists = property_exists($params, 'id');
if (!$exists) {
    die("NO ID PROVIDED");
}
$id = $params->id;

$res = $db->simpleQuery("SELECT * FROM tickets WHERE id=" . $db->getConnection()->escape_string($id) . " LIMIT 1");
$row = $res->fetch_object();
if (!($res->num_rows == 1)) {
    die("NO TICKET WITH THAT ID FOUND");
}
$use = $db->simpleQuery("SELECT * FROM users WHERE id='" . $db->getConnection()->escape_string($row->userid) . "'");
$aaa = $use->fetch_object();
if (property_exists($params, 'awnser')) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['message']) && (trim($_POST['message']) != "")) {
            if ($row->userid == $user->getId()) {
                $awns = 2;
            } else {
                $awns = 1;
            }
            $db->simpleQuery("INSERT INTO tickets_messages (ticketid, message, writername, awnser) VALUES ('" . $row->id . "', '" . $db->getConnection()->escape_string($_POST['message']) . "', '" . $db->getConnection()->escape_string($user->getName()) . "', " . $awns . "')");
        }
    }
}
if (property_exists($params, 'take')) {
    if ($row->status == 1 && $user->getPermissions() >= 2) {
        $db->simpleQuery("UPDATE tickets SET status = 3 WHERE id='" . $id . "'");
        $db->simpleQuery("INSERT INTO tickets_messages (ticketid, message, awnser) VALUES ('" . $row->id . "', '" . $user->getName() . "is now supporting this Ticket.', 3)");
        header("Location: module.php?module=support/ticket.php&params=id|" . $id);
    } else {
        die("YOU DON'T HAVE PERMISSION!");
    }
}
if (property_exists($params, 'close')) {
    if ($row->userid == $user->getId()) {
        $db->simpleQuery("UPDATE tickets SET status = 2 WHERE id='" . $id . "'");
        $db->simpleQuery("INSERT INTO tickets_messages (ticketid, message, awnser) VALUES ('" . $row->id . "', 'Ticket closed by customer', 3)");
        header("Location: module.php?module=support/ticket.php&params=id|" . $id);
    } else {
        die("YOU DON'T HAVE PERMISSION!");
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title"><i class="material-icons">question_answer</i>Ticket
                    ID: <?php echo $row->id ?> - <?= $aaa->firstname . " " . $aaa->lastname . ": " . $row->title;
                    if ($user->getPermissions() >= 2) {
                        echo '<button class="btn btn-default pull-right" type="submit" style="background-color: white; color: #000; margin-top: -5px"><a href="' . $requestpath . '_take|1" style="color: black !important">Take this ticket</a></button>';
                    }
                    ?>
                </h4>
            </div>

            <div class="card-content">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php
                            $res = $db->simpleQuery("SELECT * FROM tickets_messages WHERE ticketid=" . $db->getConnection()->escape_string($id) . " ORDER BY id");
                            while ($row1 = $res->fetch_object()) {
                                if ($row1->awnser == '1'):?>
                                    <div class="panel-group col-md-10 pull-right">
                                        <div class="panel panel-default">
                                            <div class="panel-body" data-background-color="blue">
                                                <small><?= $row1->writername . " on " . $row1->created_at?>:</small><br>
                                                <?= $row1->message ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($row1->awnser == '2'): ?>
                                    <div class="panel-group col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <small><?= $row1->writername . " on " . $row1->created_at?>:</small><br>
                                                <?= $row1->message ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="panel-group col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body" data-background-color="red"
                                                 style="text-align: center;">
                                                <hr><?= $row1->message ?>
                                            </div>
                                        </div>
                                    </div>

                                <?php endif;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel" style="background-color: #EDEDED">
                <form action="module.php?module=support/ticket.php&params=id|<?= $id ?>_awnser|1" method="post">
                    <div class="input-group col-md-12">
                        <textarea type="text" name="message" class="form-control" placeholder="Response" id="comment"
                                  rows="5"></textarea>
                    </div>
                    <div class="col-md-12" style="background-color: #FFF;">
                        <?php if ($row->status != 2): ?>
                            <button class="btn btn-default" data-background-color="red"><i
                                        class="material-icons">close</i><a
                                        href="module.php?module=support/ticket.php&params=id|<?= $id ?>_close|1">Close
                                    ticket</a>
                            </button>
                        <?php endif; ?>
                        <button class="btn btn-default pull-right" type="submit" data-background-color="blue">Submit<i
                                    class="material-icons">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>





