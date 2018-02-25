<?php

if (isset($params->update) && isset($params->product)) {
    if ($params->product == "vserver") {
        // name, price, base1-4, baseprice1-4, basenext1-4, nextstep1-4, nextprice1-4
        $post = new stdClass();
        foreach ($_POST as $key => $value) {
            $post->$key = ($value);
        }
        if (isset($post->nameinsystem) &&
            isset($post->name) &&
            isset($post->price) &&
            isset($post->base1) && isset($post->base2) && isset($post->base3) && isset($post->base4) &&
            isset($post->baseprice1) && isset($post->baseprice2) && isset($post->baseprice3) && isset($post->baseprice4) &&
            isset($post->basenext1) && isset($post->basenext2) && isset($post->basenext3) && isset($post->basenext4) &&
            isset($post->nextstep1) && isset($post->nextstep2) && isset($post->nextstep3) && isset($post->nextstep4) &&
            isset($post->nextprice1) && isset($post->nextprice2) && isset($post->nextprice3) && isset($post->nextprice4)) {
            $result = $db->simpleQuery("SELECT * FROM vserver WHERE nameinsystem='" . ($post->nameinsystem) . "'");
            if ($result->num_rows >= 1) {
                // update
                $sql = "UPDATE vserver SET 
displayname='$post->name', 
price='$post->price', 
base1='$post->base1', 
base2='$post->base2', 
base3='$post->base3', 
base4='$post->base4', 
baseprice1='$post->baseprice1', 
baseprice2='$post->baseprice2', 
baseprice3='$post->baseprice3', 
baseprice4='$post->baseprice4', 
basenext1='$post->basenext1', 
basenext2='$post->basenext2', 
basenext3='$post->basenext3', 
basenext4='$post->basenext4', 
nextstep1='$post->nextstep1', 
nextstep2='$post->nextstep2', 
nextstep3='$post->nextstep3', 
nextstep4='$post->nextstep4', 
nextprice1='$post->nextprice1', 
nextprice2='$post->nextprice2', 
nextprice3='$post->nextprice3', 
nextprice4='$post->nextprice4' 
WHERE nameinsystem='" . ($post->nameinsystem) . "'";
                echo $sql . "<br>";
                $result = $db->simpleQuery($sql);
                var_dump($result);
                if ($result) {
                    echo "Success!";
                } else {
                    echo "Failed!";
                }
            } else {
                // insert
            }
        }
    }
}

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Edit Product</h4>
            </div>
            <div class="card-content">
                <?php
                foreach ($module->getRaw()['products'] as $product) {
                    echo '<a class="btn btn-primary" href="module.php?module=productmanager/manager.php&params=product|vserver">' . $product['name'] . '</a>';
                }
                if (isset($params->product)) {
                    if ($params->product == "vserver"):
                        ?>
                        <form action="module.php?module=productmanager/manager.php&params=product|vserver_update|1"
                              method="post">
                            <!-- display name, base price, base properties .
                            property ( properties increment (1gb => 2gb => 3gb), properties increment price
                            -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group label-floating">
                                        <label class="control-label">name in system</label>
                                        <input name="nameinsystem" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Display Name</label>
                                        <input name="name" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Price</label>
                                        <input name="price" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!-- properties -->
                            <h3>Base Properties</h3>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 1</label>
                                        <input name="base1" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 2</label>
                                        <input name="base2" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 3</label>
                                        <input name="base3" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 4</label>
                                        <input name="base4" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!-- properties values -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 1 value</label>
                                        <input name="baseprice1" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 2 value</label>
                                        <input name="baseprice2" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 3 value</label>
                                        <input name="baseprice3" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 4 value</label>
                                        <input name="baseprice4" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <h2>Next in List </h2>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 1 next value</label>
                                        <input name="basenext1" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 2 next value</label>
                                        <input name="basenext2" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 3 next value</label>
                                        <input name="basenext3" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Base property 4 next value</label>
                                        <input name="basenext4" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <h4>step size</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Step size of property 1 (+value on 'next
                                            value')</label>
                                        <input name="nextstep1" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Step size of property 2 (+value on 'next
                                            value')</label>
                                        <input name="nextstep2" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Step size of property 3 (+value on 'next
                                            value')</label>
                                        <input name="nextstep3" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Step size of property 4 (+value on 'next
                                            value')</label>
                                        <input name="nextstep4" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <h4>step price</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Price for each step (property 1)</label>
                                        <input name="nextprice1" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Price for each step (property 1)</label>
                                        <input name="nextprice2" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Price for each step (property 1)</label>
                                        <input name="nextprice3" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Price for each step (property 1)</label>
                                        <input name="nextprice4" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">Update Product
                            </button>
                            <div class="clearfix"></div>
                        </form>
                    <?php
                    endif;
                }
                ?>

            </div>
        </div>
    </div>
</div>