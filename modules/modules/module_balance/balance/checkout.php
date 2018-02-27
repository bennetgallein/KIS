<?php
if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    die("Not allowed");
}
//$_POST = array("name" => "Test Product", "test" => "value1", "return" => "test", "test2" => "value2", "total" => "13.2");

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
                                <h3><?= $_POST['name'] ?></h3>
                                <?php unset($_POST['name']); ?>
                                <table class="table">
                                    <?php
                                    foreach ($_POST as $categorie => $detail): ?>
                                    <?php if ($categorie == "return" || $categorie == "total") continue; ?>
                                        <tr>
                                            <td><?= $categorie ?></td>
                                            <td><?= $detail ?></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </table>
                                <div class="col-md-2 pull-right">
                                    <h4><u>Price:</u></h4><h5><b><?= $_POST['total'] ?>€</b></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="col-md-2">
                    <div class="card-content text-center">
                        <h4>Total:</h4>
                        <h3><?= $_POST['total'] ?>€</h3>
                        <hr>
                        <button type="submit" class="btn" data-background-color="blue">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>