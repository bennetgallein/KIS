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
                    N°<?= $row->id ?> - <?= $row->title ?></h4>
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
                                    <!--<div class="panel-group col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body" data-background-color="red"
                                                 style="text-align: center;">
                                                <i class="material-icons">block</i><br>
                                                <p><?= $row->message ?></p>
                                            </div>
                                        </div>
                                    </div>-->
                                <?php endif;
                            }
                            ?>
                            <!-- Support message
                            <div class="panel-group col-md-10 pull-right">
                                <div class="panel panel-default">
                                    <div class="panel-body" data-background-color="blue">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam neque purus,
                                        porttitor vitae turpis eget, facilisis ultrices nunc. Mauris sed nisi aliquam,
                                        finibus arcu eget, bibendum est. Quisque sit amet lobortis est. Aliquam blandit,
                                        purus id consectetur vehicula, dolor erat ullamcorper risus, a luctus nulla
                                        turpis ut mi. Morbi interdum ante ac lacinia aliquam. Nam pretium lacus in purus
                                        sodales pulvinar. Cras eget erat mi.
                                    </div>
                                </div>
                            </div>-->
                            <!-- awnser from customer
                            <div class="panel-group col-md-10">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        Donec non consequat ligula, et scelerisque eros. Nunc vitae dapibus diam, non
                                        aliquet tellus. Aenean ligula nisl, aliquam ut eros ut, eleifend mattis magna.
                                        Mauris dui orci, semper sed convallis consequat, porttitor eget sapien. Class
                                        aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
                                        himenaeos. Sed lacus leo, hendrerit vel facilisis volutpat, placerat eget nunc.
                                        Curabitur vitae sodales augue, quis molestie purus. Integer facilisis odio
                                        placerat faucibus malesuada.
                                    </div>
                                </div>
                            </div>-->
                            <!-- Information
                            <div class="panel-group col-md-6 col-md-push-3" >
                                <div class="panel panel-default" style="border-radius: 15px;">
                                    <div class="panel-body" data-background-color="red" style="text-align: center; border-radius: 15px;">
                                        <i class="material-icons">block</i><br>
                                        <p>This is what happened bitch</p>
                                    </div>
                                </div>
                            </div>
                            -->
                            <button class="btn btn-default pull-right col-md-3" data-background-color="red"><i
                                        class="material-icons">close</i>Close ticket
                            </button>
                        </div>
                            <div class="panel panel-default" style="margin-top: 10px;">
                                <div class="panel-body" data-background-color="red" style="text-align: center;">
                                    <i class="material-icons">block</i><br>
                                    <p><?= $row->message ?></p>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="panel" style="background-color: #EDEDED">
                    <div class="input-group col-md-12">
                        <textarea type="text" name="message" class="form-control" placeholder="Response" id="comment"
                                  rows="5"></textarea>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-default pull-right" type="submit" data-background-color="blue">Submit<i
                                    class="material-icons">send</i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





