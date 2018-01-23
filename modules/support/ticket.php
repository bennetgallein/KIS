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

if (property_exists($params, 'awnser')) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['message'])) {
            if ($row->userid == $user->getId()) {
                $awns = 2;
            } else {
                $awns = 1;
            }
            $db->simpleQuery("INSERT INTO tickets_messages (ticketid, message, awnser) VALUES ('" . $row->id . "', '" . $db->getConnection()->escape_string($_POST['message']) . "', '" . $awns . "')");
        }
    }
}
if (property_exists($params, 'close')) {
    if ($row->userid == $user->getId()) {
        $db->simpleQuery("UPDATE tickets SET status = 2 WHERE id='" . $id . "'");
        $db->simpleQuery("INSERT INTO tickets_messages (ticketid, message, awnser) VALUES ('" . $row->id . "', 'Ticket closed by customer', 3)");
    } else {
        die("YOU DON'T HAVE PERMISSION!");
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title"><i class="material-icons">question_answer</i> Chat - Support - Ticket
                    ID:<?= $row->id ?> - <?= $row->title ?></h4>
            </div>
            <div class="card-content">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php
                            $res = $db->simpleQuery("SELECT * FROM tickets_messages WHERE ticketid=" . $db->getConnection()->escape_string($id));
                            while ($row1 = $res->fetch_object()) {
                                if ($row1->awnser == '1'):?>
                                    <div class="panel-group col-md-10 pull-right">
                                        <div class="panel panel-default">
                                            <div class="panel-body" data-background-color="blue">
                                                <?= $row1->message ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($row1->awnser == '2'): ?>
                                    <div class="panel-group col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <?= $row1->message ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="panel-group col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body" data-background-color="red"
                                                 style="text-align: center;">
                                                <?= $row1->message ?>
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
                    <?php if ($row->status != 2): ?>
                    <button class="btn btn-default col-md-3" data-background-color="red"><i
                                class="material-icons">close</i><a
                                href="module.php?module=support/ticket.php&params=id|<?= $id ?>_close|1">Close
                            ticket</a>
                    </button>
                    <?php endif; ?>
                    <div class="dropdown">
                        <button class="btn btn-default pull-right" type="submit" data-background-color="blue">Submit<i
                                    class="material-icons">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>





