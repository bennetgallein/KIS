<?php
if ($params->confirm == "1") {
    // confirm order and continue ->
    if (isset($_POST['slots'])) {
        if (is_numeric($_POST['slots'])) {
            $slots = $_POST['slots'];
            if ($slots >= 10 && $slots <= 350) {
                $price = 0.15;
                $price = $slots * $price;
                echo $price;

                $module = $db->getModuleByName("Balance Manager");
                $re = include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("moneymethods")['link']);
                $moneymethods = new MoneyMethods();
                if ($moneymethods->getAmount($db, $user->getId()) >= $price) {
                    // genug Geld, remove Money from Account, Add product to Database and create TS3 Server
                    $moneymethods->removeAmount($db, "TS3-Server", $user->getId(), $price);
                    // Add product to database.
                    // CREATE TS3SERVER!
                    $db->simpleQuery("INSERT INTO product_ts3server (userid, tsid, slots, expectedrenewal) VALUES ('" . $user->getId() . "', 123, " . $slots . ", now() + INTERVAL 30 DAY)");
                    echo "Purchase complete!";
                } else {
                    echo "Nö, nicht genug Geld!";
                }
            }
        }
    }
}
?>
<style>
    .slidecontainer {
        width: 100%;
    }

    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 25px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <?php
            $returnurl = 'module.php?module=productmanager/products/configure_ts3server.php&params=confirm|1';
            $cancelurl = 'module.php?module=productmanager/products/configure_ts3server.php';
            $module = $db->getModuleByName("Balance Manager");
            if (isset($module)) {
                if ($module->getIncludeable("paybutton")['permission'] <= $user->getPermissions()) {
                    $re = include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("paybutton")['link']);
                }
            } ?>
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                        <h4 class="title text-center">Konfigurieren</h4>
                    </div>
                    <div class="card-content">
                        <form action="module.php?module=productmanager/products/configure_ts3server.php&params=confirm|0" method="post">
                            <div class="col-md-9">
                                <h3><b>Infos:</b></h3>
                                <table class="table">
                                    <tr>
                                        <td>Filetransfer:</td>
                                        <td>Free</td>
                                    </tr>
                                    <tr>
                                        <td>Setup:</td>
                                        <td>Sofort</td>
                                    </tr>
                                    <tr>
                                        <td>Anbindung:</td>
                                        <td>bis zu 1Gbit</td>
                                    </tr>
                                    <tr>
                                        <td>inklusive</td>
                                        <td> DDos Protection</td>
                                    </tr>
                                    <tr>
                                        <td>Unlimited</td>
                                        <td>Traffic</td>
                                    </tr>

                                </table>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="sel1">Abrechnungszeitraum:</label>
                                            <select class="form-control calculate" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="slidecontainer">
                                            <label for="myRange">Slots:</label>
                                            <input type="range" name="slots" min="10" max="350" value="10" step="1"
                                                   class="slider" id="myRange">
                                            <p>Current slots: <span id="demo"></span></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="card-content text-center">
                                    <table class="table" style="font-size: 0.9em;">
                                        <tr>
                                            <td>Name</td>
                                            <td>Price</td>
                                        </tr>
                                        <tr>
                                            <td id="server">TS³-Server</td>
                                            <td id="serverprice">0.00€</td>
                                        </tr>
                                        <tr>
                                            <td id="slots">Slots: 10</td>
                                            <td id="slotsprice"><?= number_format(10 * 0.15, 2) ?>€</td>
                                        </tr>
                                        <tr class="info">
                                            <td>Monatlich</td>
                                            <td id="monthlyprice">price</td>
                                        </tr>
                                    </table>
                                    <h3>Zu bezahlen:</h3>
                                    <h4><b>
                                            <div id="item-price"><?= number_format(10 * 0.15, 2) ?>€</div>
                                        </b></h4>
                                    <hr>
                                    <button type="submit" class="btn" data-background-color="blue">Checkout</button>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    output.innerHTML = slider.value;

    var price = 0.15;

    slider.oninput = function () {
        output.innerHTML = this.value;
        //$("myRange").val(this.value);
        $("input[type=range]").val(slider.value);
        var newPrice = (this.value * price).toFixed(2);
        document.getElementById("slots").innerText = this.value + " Slots";
        document.getElementById("slotsprice").innerHTML = newPrice + "€";
        document.getElementById("monthlyprice").innerHTML = newPrice + "€";
        document.getElementById("item-price").innerHTML = newPrice + "€";
    }
</script>