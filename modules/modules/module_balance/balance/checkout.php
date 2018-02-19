<?php
if (!($_SERVER['REQUEST_METHOD'] != 'POST')) {
    die("Not allowed");
}
$price = 13.2;
$_POST = array("test" => "value1", "test2" => "value2");

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Checkout</h4>
            </div>
            <div class="card-content">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-content">
                            <div class="col-md-12">
                                <h3>Product name</h3>
                                <table class="table">
                                    <?php
                                    foreach ($_POST as $categorie => $detail): ?>
                                        <tr>
                                            <td><?= $categorie ?></td>
                                            <td><?= $detail ?></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </table>
                                <div class="col-md-2 pull-right">
                                    <h4><u>Price:</u></h4><h5><b><?= $price ?>€</b></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="col-md-2">
                    <div class="card-content text-center">
                        <h4>Total:</h4>
                        <h3><?= $price ?>€</h3>
                        <hr>
                        <button type="submit" class="btn" data-background-color="blue">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>