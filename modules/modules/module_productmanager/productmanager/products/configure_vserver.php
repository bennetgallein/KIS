<?php

use Virtualizor\Virtualizor;

$in = include(__DIR__ . "/../vendor/autoload.php");

if (!isset($params->base)) {
    $params->base = 1;
}

$virt = new Virtualizor("ip", "key", "pass");

//$ostemplates = $virt->ostemplates()->setAct(\Virtualizor\Objects\OSTemplates::LISTOS)->exec();
if ($params->confirm == "1") {
    $module = $db->getModuleByName("Balance Manager");
    if (isset($module)) {
        if ($module->getIncludeable("moneymethods")['permission'] <= $user->getPermissions()) {
            // Recalculate price with data provided in $_POST, check if user has the money, if yes, proceed. If not, cancel. Proceed = remove Money from Account, Add product to Database and create in Virtualizor!

            // RAM, vCores, SSD-Speicher, IPs
            // check if above is in post
            if (isset($_POST['password']) && isset($_POST['hostname']) && isset($_POST['base']) && isset($_POST['RAM']) && isset($_POST['vCores']) && isset($_POST['SSD-Speicher']) && isset($_POST['IPs'])) {
                $query = $db->simpleQuery("SELECT * FROM vserver WHERE id='" . $db->getConnection()->escape_string($_POST['base']) . "'");
                if (!$query->num_rows == 0) {
                    $query = $query->fetch_object();

                    $price = $query->price;

                    // RAM
                    $ram = $_POST['RAM'];
                    $limit = 32768;
                    $counter = 0;
                    $start = 0.50;
                    $currentram = $query->basenext1;

                    while ($currentram <= $limit) {
                        if ($ram == $currentram) {
                            $price += $start + ($query->nextprice1 * $counter);
                            break;
                        }
                        $counter = $counter + 1;
                        $currentram += $query->nextstep1;
                    }

                    // vCores
                    $ram = $_POST['vCores'];
                    $limit = 8;
                    $counter = 0;
                    $start = 1.25;
                    $currentram = $query->basenext2;

                    while ($currentram <= $limit) {
                        if ($ram == $currentram) {
                            $price += $start + ($query->nextprice2 * $counter);
                            break;
                        }
                        $counter = $counter + 1;
                        $currentram += $query->nextstep2;
                    }

                    // SSD-Speicher
                    $ram = $_POST['SSD-Speicher'];
                    $limit = 100;
                    $counter = 0;
                    $start = 0.5;
                    $currentram = $query->basenext3;

                    while ($currentram <= $limit) {
                        if ($ram == $currentram) {
                            $price += $start + ($query->nextprice3 * $counter);
                            break;
                        }
                        $counter = $counter + 1;
                        $currentram += $query->nextstep3;
                    }

                    // IPs
                    $ram = $_POST['IPs'];
                    $limit = 4;
                    $counter = 0;
                    $start = 2;
                    $currentram = $query->basenext4;

                    while ($currentram <= $limit) {
                        if ($ram == $currentram) {
                            $price += $start + ($query->nextprice4 * $counter);
                            break;
                        }
                        $counter = $counter + 1;
                        $currentram += $query->nextstep4;
                    }

                    //var_dump($price);

                    $re = include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("moneymethods")['link']);
                    $moneymethods = new MoneyMethods();
                    if ($moneymethods->getAmount($db, $user->getId()) >= $price) {
                        // genug Geld, remove Money from Account, Add product to Database and create in Virtualizor!.
                        $moneymethods->removeAmount($db, $query->displayname, $user->getId(), $price);
                        // Add product to database.
                        // CREATE IN VIRTUALIZOR
                        $db->simpleQuery("INSERT INTO product_vserver (userid, vpsid, expectedrenewal) VALUES ('" . $user->getId() . "', 123, now() + INTERVAL 30 DAY)");
                        echo "Purchase complete!";
                    } else {
                        echo "Nö, nicht genug Geld!";
                    }
                } else {
                    echo "Base not found!";
                }
            } else {
                echo "Not all post fields are filled!";
            }
        }
    }
} else

?>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                        <h4 class="title text-center">Konfigurieren</h4>
                    </div>
                    <div class="card-content">
                        <form action="module.php?module=productmanager/products/configure_vserver.php&params=base|1_confirm|0"
                              method="post">
                            <input type="hidden" name="base" value="<?= $params->base ?>"
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sel1">Abrechnungszeitraum:</label>
                                                <select class="form-control" id="sel1">
                                                    <option>#</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="text-center">Server konfigurieren</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Hostname</label>
                                                <input name="hostname" value="" type="text"
                                                       class="form-control" placeholder="servername.yourdomain.com">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Root-Passwort</label>
                                                <input name="password" value="" type="password"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="text-center">Optionen</h3>
                                    <div class="row">
                                        <?php
                                        $res = $db->simpleQuery("SELECT base1,baseprice1,basenext1,nextstep1,nextprice1 FROM vserver WHERE id=" . $params->base);
                                        $ram = $res->fetch_object();
                                        $currentram = $ram->basenext1;
                                        $limit = 32768;
                                        $counter = 0;
                                        $start = 0.50;
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel1"><?= $ram->base1 ?></label>
                                                <select name="<?= $ram->base1 ?>" class="form-control calculate ramsel"
                                                        id="<?= $ram->base1 ?>">
                                                    <option data-price="0"
                                                            value="<?= $ram->baseprice1 ?>"><?= $ram->baseprice1 ?>MB
                                                    </option>
                                                    <?php
                                                    while ($currentram <= $limit) {
                                                        echo "<option data-price='" . number_format($start + ($ram->nextprice1 * $counter), 2) . "' value='" . $currentram . "'>" . $currentram . "MB (" . ($currentram * (1 / 1024)) . "GB) + " . number_format($start + ($ram->nextprice1 * $counter), 2) . "€</option>";
                                                        $counter = $counter + 1;
                                                        $currentram += $ram->nextstep1;
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        $res = $db->simpleQuery("SELECT base2,baseprice2,basenext2,nextstep2,nextprice2 FROM vserver WHERE id=" . $params->base);
                                        $cpu = $res->fetch_object();
                                        $currentcpu = $cpu->basenext2;
                                        $limit = 8;
                                        $counter = 0;
                                        $start = 1.25;
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel1"><?= $cpu->base2 ?></label>
                                                <select name="<?= $cpu->base2 ?>" class="form-control calculate cpusel"
                                                        id="<?= $cpu->base2 ?>">
                                                    <option data-price="0"
                                                            value="<?= $cpu->baseprice2 ?>"><?= $cpu->baseprice2 ?>
                                                        vCore
                                                    </option>
                                                    <?php
                                                    while ($currentcpu <= $limit) {
                                                        echo "<option data-price='" . number_format(($start + ($cpu->nextprice2 * $counter)), 2) . "' value='" . $currentcpu . "'>" . $currentcpu . " vCores +" . number_format(($start + ($cpu->nextprice2 * $counter)), 2) . "€</option>";
                                                        $counter++;
                                                        $currentcpu += $cpu->nextstep2;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php
                                        $res = $db->simpleQuery("SELECT base3,baseprice3,basenext3,nextstep3,nextprice3 FROM vserver WHERE id=" . $params->base);
                                        $ssd = $res->fetch_object();
                                        $currentssd = $ssd->basenext3;
                                        $limit = 100;
                                        $counter = 0;
                                        $start = 0.5;
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel1"><?= $ssd->base3 ?></label>
                                                <select name="<?= $ssd->base3 ?>" class="form-control calculate ssdsel"
                                                        id="<?= $ssd->base3 ?>">
                                                    <option data-price="0"
                                                            value="<?= $ssd->baseprice3 ?>"><?= $ssd->baseprice3 ?>GB
                                                    </option>
                                                    <?php
                                                    while ($currentssd <= $limit) {
                                                        echo "<option data-price='" . number_format($start + ($ssd->nextprice3 * $counter), 2) . "' value='" . $currentssd . "'>" . $currentssd . "GB +" . number_format($start + ($ssd->nextprice3 * $counter), 2) . "€</option>";
                                                        $counter++;
                                                        $currentssd += $ssd->nextstep3;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        $res = $db->simpleQuery("SELECT base4,baseprice4,basenext4,nextstep4,nextprice4 FROM vserver WHERE id=" . $params->base);
                                        $ips = $res->fetch_object();
                                        $currentips = $ips->basenext4;
                                        $limit = 4;
                                        $counter = 0;
                                        $start = 2;
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel1"><?= $ips->base4 ?></label>
                                                <select name="<?= $ips->base4 ?>" class="form-control calculate ipsel"
                                                        id="<?= $ips->base4 ?>">
                                                    <option data-price="0"
                                                            value="<?= $ips->baseprice4 ?>"><?= $ips->baseprice4 ?>IPs
                                                    </option>
                                                    <?php
                                                    while ($currentips <= $limit) {
                                                        echo "<option data-price='" . number_format($start + ($ips->nextprice4 * $counter), 2) . "' value='" . $currentips . "'>" . $currentips . " IPs +" . number_format($start + ($ips->nextprice4 * $counter), 2) . "€</option>";
                                                        $counter++;
                                                        $currentips += $ips->nextstep4;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="text-center">Weiteres</h3>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sel1">Betriebssystem</label>
                                                <select name="os" class="form-control" id="os">
                                                    <option>#</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="card-content text-center">
                                        <table class="table" style="font-size: 0.9em;">
                                            <tr>
                                                <?php
                                                $res = $db->simpleQuery("SELECT displayname, price FROM vserver WHERE id=" . $params->base);
                                                $row = $res->fetch_object();
                                                ?>
                                                <td><?= $row->displayname ?></td>
                                                <td><?= $row->price . "€" ?></td>
                                            </tr>
                                            <tr>
                                                <td id="ram">RAM: 512MB</td>
                                                <td id="ramprice">0.00€</td>
                                            </tr>
                                            <tr>
                                                <td id="vcores">vCores: 1 vCore</td>
                                                <td id="vcoresprice">0.00€</td>
                                            </tr>
                                            <tr>
                                                <td id="ssd">SSD-Speicher: 10GB</td>
                                                <td id="ssdprice">0.00€</td>
                                            </tr>
                                            <tr>
                                                <td id="ips">IPs: 1 IP</td>
                                                <td id="ipsprice">0.00€</td>
                                            </tr>
                                            <tr class="info">
                                                <td>Monatlich</td>
                                                <td><?= $row->price . "€" ?></td>
                                            </tr>
                                        </table>
                                        <h3>Zu bezahlen:</h3>
                                        <h4><b>
                                                <div id="item-price"><?= $row->price . "€" ?></div>
                                                <input id="hidden-item-price" name="total" type="hidden"
                                                       value="<?= $row->price ?>">
                                            </b></h4>
                                        <hr>
                                        <button type="submit" class="btn" data-background-color="blue">Checkout</button>
                                        <?php

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                $returnurl = 'module.php?module=productmanager/products/configure_vserver.php&params=base|1_confirm|1';
                $module = $db->getModuleByName("Balance Manager");
                if (isset($module)) {
                    if ($module->getIncludeable("paybutton")['permission'] <= $user->getPermissions()) {
                        $re = include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("paybutton")['link']);
                    }
                } ?>
            </div>
        </div>
    </div>
</div>


<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script>
    $(function () {
        $("select.calculate").on("change", calc);
        $("select.calculate").on("change", update);

        function calc() {
            var basePrice = <?= $row->price ?>;
            var newPrice = basePrice;
            $("select.calculate option:selected").each(function (idx, el) {
                newPrice += parseFloat($(el).data('price'));
            });

            newPrice = newPrice.toFixed(2);
            $("#item-price").html(newPrice + "€");
            $("#hidden-item-price").val(newPrice);

        }

        function update() {
            $("select.ramsel option:selected").each(function (idx, el) {
                $("#ramprice").html($(el).data('price') + "€");
            });
            $("select.cpusel option:selected").each(function (idx, el) {
                $("#vcoresprice").html($(el).data('price') + "€");
            });
            $("select.ssdsel option:selected").each(function (idx, el) {
                $("#ssdprice").html($(el).data('price') + "€");
            });
            $("select.ipsel option:selected").each(function (idx, el) {
                $("#ipsprice").html($(el).data('price') + "€");
            });
        }
    });


</script>