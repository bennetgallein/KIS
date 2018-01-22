<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['title']) && isset($_POST['message']) && isset($_POST['product'])) {


        // insert and notification to user
        $userid = $user->getId();

        $db->simpleQuery("INSERT INTO tickets (title, userid, product) VALUES ('" . $db->getConnection()->escape_string($_POST['title']) . "', '" . $db->getConnection()->escape_string($userid) . "', '" . $db->getConnection()->escape_string($_POST['product']) . "')");
        $res = $db->getConnection()->query("SELECT LAST_INSERT_ID()");
        $res = $res->fetch_assoc();
        $db->prepareQuery("INSERT INTO tickets_messages (ticketid, message) VALUES (?, ?)", array(
            $db->escape($res['LAST_INSERT_ID()']), $db->escape($_POST['message']))
        );

        echo "Your Ticket was submitted!";
    } else {
        echo "Please fill every field!";
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="blue">
                <h4 class="title">Create a Ticket</h4>
            </div>
            <form action="module.php?module=support/createticket.php" method="post">
                <div class="card-content">
                    <div class="input-group col-md-12">
                        <input type="text" name="title" class="form-control" placeholder="Title">
                    </div>
                    <label>Product:
                        <select name="product">
                            <option value="123">Webhosting</option>
                            <option value="231">vServer</option>
                            <option value="321">KVM</option>
                            <option value="132">PLEXwas</option>
                        </select>
                    </label>

                    <div class="input-group col-md-12">
                        <textarea type="text" name="message" class="form-control" placeholder="Ticket text" id="comment"
                                  rows="5"></textarea>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle pull-right" type="submit"
                                data-background-color="blue">
                            Submit
                            <i class="material-icons">send</i>
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>