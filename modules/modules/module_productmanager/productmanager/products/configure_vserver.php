<?php

use Virtualizor\Virtualizor;

$in = include(__DIR__ . "/../vendor/autoload.php");

if (!isset($params->base)) {
    $params->base = 1;
}

$virt = new Virtualizor("ip", "key", "pass");

//$ostemplates = $virt->ostemplates()->setAct(\Virtualizor\Objects\OSTemplates::LISTOS)->exec();

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
                        <div class="col-md-9">
                            <form>
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
                                            <select class="form-control calculate ramsel" id="<?= $ram->base1 ?>">
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
                                            <select class="form-control calculate cpusel" id="<?= $cpu->base2 ?>">
                                                <option data-price="0"
                                                        value="<?= $cpu->baseprice2 ?>"><?= $cpu->baseprice2 ?> vCore
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
                                            <select class="form-control calculate ssdsel" id="<?= $ssd->base3 ?>">
                                                <option data-price="0" value="<?= $ssd->baseprice3 ?>"><?= $ssd->baseprice3 ?>GB
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
                                            <select class="form-control calculate ipsel" id="<?= $ips->base4 ?>">
                                                <option data-price="0" value="<?= $ips->baseprice4 ?>"><?= $ips->baseprice4 ?>IPs
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
                                            <select class="form-control" id="os">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                                        <td><?= $row->price . "€"?></td>
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
                                        <td><?= $row->price . "€"?></td>
                                    </tr>
                                </table>
                                <h3>Zu bezahlen:</h3>
                                <h4><b>
                                        <div id="item-price"><?= $row->price . "€"?></div>
                                    </b></h4>
                                <hr>
                                <button type="submit" class="btn" data-background-color="blue">Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script
        src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script>
    $(function () {
        $("select.calculate").on("change", calc);
        $("select.calculate").on("change", update);
        function calc() {
            var basePrice = <?= $row->price ?>;
            newPrice = basePrice;
            $("select.calculate option:selected").each(function (idx, el) {
                console.log($(el).data('price'));
                newPrice += parseInt($(el).data('price'));
                console.log(newPrice);
            });

            newPrice = newPrice.toFixed(2);
            $("#item-price").html(newPrice + "€");

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