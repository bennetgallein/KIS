<?php
$exists = property_exists($params, 'id');
if (!$exists) {
    die("NO ID PROVIDED");
}
$id = $params->id;

$res = $db->simpleQuery("SELECT * FROM tickets WHERE id=" . $db->getConnection()->escape_string($id) . " LIMIT 1");
$row = $res->fetch_object();
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
                            while ($row = $res->fetch_object()) {
                                if ($row->awnser == '1'):?>
                                    <div class="panel-group col-md-10 pull-right">
                                        <div class="panel panel-default">
                                            <div class="panel-body" data-background-color="blue">
                                                <?= $row->message ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($row->awnser == '2'): ?>
                                    <div class="panel-group col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <?= $row->message ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="panel-group col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body" data-background-color="red" style="text-align: center;">
                                                <?= $row->message ?>
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
                <div class="input-group col-md-12">
                        <textarea type="text" name="message" class="form-control" placeholder="Response" id="comment"
                                  rows="5"></textarea>
                </div>
                <button class="btn btn-default col-md-3" data-background-color="red"><i
                            class="material-icons">close</i>Close ticket
                </button>
                <div class="dropdown">
                    <button class="btn btn-default pull-right" type="submit" data-background-color="blue">Submit<i
                                class="material-icons">send</i></button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>





