<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <a href="#">
                <div class="card-header" data-background-color="grey">
                    <i class="material-icons">settings</i>
                </div>
            </a>
            <div class="card-content">
                <p class="category">Installed Modules</p>
                <h3 class="title"><?= count($db->getModules()) - 1 ?></h3>
                <?php
                $includeables = 0;
                $interfacefiles = 0;
                $navs = 0;
                foreach ($db->getModules() as $module) {
                    if ($module->getName() != "Module Manager") {
                        $includeables += count($module->getIncludeables());
                        $interfacefiles += count($module->getDashboards());
                        $navs += count($module->getNavs());
                    }
                }
                ?>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <p><i class="material-icons">access_time</i> Includeables: <?= $includeables ?>
                        <i class="material-icons">access_time</i> Dashboard Files: <?= $interfacefiles ?>
                        <i class="material-icons">access_time</i> Navbar Links: <?= $navs ?></p>
                </div>
            </div>
        </div>
    </div>
</div>