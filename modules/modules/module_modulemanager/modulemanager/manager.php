<?php
if ($params->update) {

}
$modulemanager = $db->getModuleByName("Module Manager");
$id = 0;
foreach ($db->getModules() as $modd): ?>
    <?php
    // update composer:
    if ($modd->getName() == $params->update) {
        shell_exec('');
        echo $modd->getPath() . "/" . $modd->getBasePath();
        $ar = array();
        $arK = array();
        //$output = exec("composer global update -d " . $modd->getPath() . "/" . $modd->getBasePath() . " 2>&1", $ar, $arK);
        $output = system("su - pi <<! >/dev/null 2>&1
Jannosch353
composer global update -d " . $modd->getPath() . "/" . $modd->getBasePath() . " 2>&1
!", $ar);
        echo "<pre>";
        foreach ($ar as $line) {
            echo $output;
            echo $line;
        }
        echo "</pre>";
    }
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                    <h4 class="title">Info about <?= $modd->getName() ?></h4>
                </div>
                <div class="card-content">
                    <ul class="list-group">
                        <a class="btn btn-primary"
                           href="module.php?module=modulemanager/manager.php&params=update|<?= $modd->getName() ?>"
                           aria-expanded="false">Update Composer<span
                                    class="caret"></span>
                        </a>
                        <li class="list-group-item"><b>Name: </b><?= $modd->getName() ?></li>
                        <li class="list-group-item"><b>Version: </b><?= $modd->getVersion() ?></li>
                        <li class="list-group-item"><b>Baseperm: </b><?= $modd->getBaseperm() ?></li>
                        <li class="list-group-item"><b>Supports:</b></li>
                        <?php


                        foreach ($modd->getRaw()['supports'] as $author): ?>
                            <li class="list-group-item" style="margin-left: 15px;">
                                <b><?= $author['name'] ?></b></li>
                        <?php endforeach; ?>
                        <li class="list-group-item"><b>Authors: </b></li>
                        <?php foreach ($modd->getAuthors() as $author): ?>
                            <li class="list-group-item" style="margin-left: 15px;">
                                <b><?= $author['name'] . "</b> &lt;" . $author['email'] . "&gt;" ?></li>
                        <?php endforeach; ?>
                        <?php

                        foreach ($modd->getNavs() as $nav): ?>
                            <a class="btn btn-primary" data-toggle="collapse" href="#collapse<?= $id ?>"
                               aria-expanded="false" aria-controls="collapse<?= $id ?>">Navbar <span
                                        class="caret"></span>
                            </a>
                            <div class="collapse" id="collapse<?= $id ?>">
                                <li class="list-group-item"><b>Navs:</b></li>
                                <li class="list-group-item" style="margin-left: 15px;"><b>Icon: <?= $nav['icon'] ?> </b>
                                </li>
                                <li class="list-group-item" style="margin-left: 15px;"><b>Name: <?= $nav['name'] ?></b>
                                </li>
                                <li class="list-group-item" style="margin-left: 15px;"><b>Link: <?= $nav['link'] ?></b>
                                </li>
                                <li class="list-group-item" style="margin-left: 15px;">
                                    <b>Permission: <?= $nav['permission'] ?></b></li>
                                <li class="list-group-item" style="margin-left: 15px;"><b>Type: <?= $nav['type'] ?></b>
                                </li>
                            </div>
                            <?php $id++; endforeach; ?>
                        <br>
                        <?php
                        foreach ($modd->getDashboards() as $dashboard): ?>
                            <a class="btn btn-primary" data-toggle="collapse" href="#collapse<?= $id ?>"
                               aria-expanded="false" aria-controls="collapse<?= $id ?>">Dashboard <span
                                        class="caret"></span>
                            </a>
                            <div class="collapse" id="collapse<?= $id ?>">
                                <li class="list-group-item"><b>Dashboards: <?= "Dashboard" ?></b></li>
                                <li class="list-group-item" style="margin-left: 15px;">
                                    <b>Link: <?= $dashboard['link'] ?></b></li>
                                <li class="list-group-item" style="margin-left: 15px;">
                                    <b>Permission: <?= $dashboard['permission'] ?></b></li>
                            </div>
                            <?php $id++; endforeach; ?>
                        <br>
                        <?php foreach ($modd->getIncludeables() as $includeable): ?>
                            <a class="btn btn-primary" data-toggle="collapse" href="#collapse<?= $id ?>"
                               aria-expanded="false" aria-controls="collapse<?= $id ?>">Includeable <span
                                        class="caret"></span>
                            </a>
                            <div class="collapse" id="collapse<?= $id ?>">
                                <li class="list-group-item">Includeables:</li>
                                <li class="list-group-item" style="margin-left: 15px;">
                                    <b>Name: <?= $includeable['name'] ?></b></li>
                                <li class="list-group-item" style="margin-left: 15px;">
                                    <b>Link: <?= $includeable['link'] ?></b></li>
                                <li class="list-group-item" style="margin-left: 15px;">
                                    <b>Permission: <?= $includeable['permission'] ?></b></li>
                            </div>
                            <?php $id++; endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php
endforeach;
?>