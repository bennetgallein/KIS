<?php
$res = $db->simpleQuery("SELECT displayname, id FROM vserver");
while ($row = $res->fetch_object()):
?>
<div class="row">
    <div class="col-md-4 col-md-push-4">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                        <h4 class="title text-center"><?= $row->displayname ?></h4>
                    </div>
                    <div class="card-content" style="text-align: center">
                        <h5><span style="border-bottom: 1px solid #000;">Erhältlich ab:</span></h5>
                        <h2><b>4,49$</b>/mo</h2>
                        <table class="table text-center">
                            <tr>
                                <td>1 vCore</td>
                            </tr>
                            <tr>
                                <td>512MB RAM</td>
                            </tr>
                            <tr>
                                <td>10 GB SSD</td>
                            </tr>
                            <tr>
                                <td>Dauerschutz DDOS Protection</td>
                            </tr>
                            <tr>
                                <td>250 Mbit/s Anbindung</td>
                            </tr>
                            <tr>
                                <td>Fair Use Traffic</td>
                            </tr>
                            <tr>
                                <td>KVM virtualisiert</td>
                            </tr>
                        </table>
                        <a href="module.php?module=<?= $module->getBasePath() . $product['configurepage']  . "&params=base|" . $row->id?>" class="btn" data-background-color="<?= $db->getConfig()['color'] ?>">Jetzt konfigurieren</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
endwhile;
?>