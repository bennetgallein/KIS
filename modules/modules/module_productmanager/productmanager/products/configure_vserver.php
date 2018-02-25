<?php
if (!isset($params->base)) {
    $params->base = 1;
}
setlocale(LC_MONETARY, 'de_DE');
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
                                            <select class="form-control" id="sel1">
                                                <option><?= $ram->baseprice1 ?>MB</option>
                                                <?php
                                                while ($currentram <= $limit) {
                                                    echo "<option>" . $currentram . "MB (" . ($currentram * (1/1024)) . "GB) + " . number_format($start + ($ram->nextprice1 * $counter), 2) . "€</option>";
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
                                            <label for="sel1"><?= $cpu->base2?></label>
                                            <select class="form-control" id="sel1">
                                                <option><?= $cpu->baseprice2 ?> vCore</option>
                                                <?php
                                                while ($currentcpu <= $limit) {
                                                    echo "<option>" . $currentcpu . " vCores +" . number_format(($start + ($cpu->nextprice2 * $counter)), 2) . "€</option>";
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
                                            <label for="sel1"><?= $ssd->base3?></label>
                                            <select class="form-control" id="sel1">
                                                <option><?= $ssd->baseprice3 ?>GB</option>
                                                <?php
                                                while ($currentssd <= $limit) {
                                                    echo "<option>" . $currentssd . "GB +" . number_format($start + ($ssd->nextprice3 * $counter), 2) . "€</option>";
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
                                            <label for="sel1"><?= $ips->base4?></label>
                                            <select class="form-control" id="sel1">
                                                <option><?= $ips->baseprice4 ?> IPs</option>
                                                <?php
                                                while ($currentips <= $limit) {
                                                    echo "<option>" . $currentips . " IPs +" . number_format($start + ($ips->nextprice4 * $counter), 2) . "€</option>";
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
                                            <select class="form-control" id="sel1">
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
                                        <td>vServer | FEE</td>
                                        <td>13,37$</td>
                                    </tr>
                                    <tr>
                                        <td>RAM: 512MB</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr>
                                        <td>vCores: 1 vCore</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr>
                                        <td>SSD-Speicher: 10GB</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr>
                                        <td>IPs: 1 IP</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr class="info">
                                        <td>Einrichtungsgebühren:</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr class="info">
                                        <td>vierteljährlich</td>
                                        <td>13,47$</td>
                                    </tr>
                                </table>
                                <h3>Zu bezahlen:</h3>
                                <h4><b>13,47$</b></h4>
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