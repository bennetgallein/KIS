<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="#" style="text-decoration: none;">
                <a href="<?= $db->getConfig()['url']?>"><i class="mdi mdi-star"></i><?= $db->getConfig()['site_name'] ?>
            </a>
        </li>
        <?php
        $currentfile = basename($_SERVER["SCRIPT_FILENAME"]);
        ?>
            <li>
                <?php
            $link = "index.php";
            $active = ($currentfile == $link) ? "active" : "";
            ?>
                    <a class="mdi mdi-home" href="#">Dashboard</a>
            </li>
            <?php
            $module = $db->getModuleByName("Balance Manager");
            if ($module != false) {
                if ($module->isActive()) {
                    if ($module->getIncludeable("display_money")['permission'] <= $user->getPermissions()) {
                        include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("display_money")['link']);
                        $money = getMoney($db, $user);
                        $money = "<span class=\"badge\">" . $money . "€</span>";
                    }
                }
            }
            ?>
            <li>
                <a class="mdi mdi-person"><?= $db->m("sidebar_profile")?> <?= isset($money) ? $money : ""?></a>
            </li>
            <li>
                <?php
                unset($active);
                foreach ($db->getModules() as $module) {
                    if ($module->isActive()) {
                        echo '<a class="collapseable-nav" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">' . $module->getName() . '<i class="mdi mdi-plus"></i></a>
                        <div class="collapse" id="collapse">
                            <ul class="collapsible">
                        ';
                        foreach ($module->getNavs() as $navpoint) {
                            if ($navpoint['type'] == 'nav') {
                                if ($navpoint['permission'] <= $user->getPermissions()) {
                                    $path = $module->getBasepath() . $navpoint['link'];
                                    if (isset($amodule)) {
                                        if ($path == $amodule) {
                                            $active = "active";
                                        }
                                    }
                                    $active = isset($active) ? $active : "";
                                    echo '<li class="' . $active . '">
                                          <a href="module.php?module=' . $module->getBasepath() . $navpoint['link'] . '">

                                              <p class="<mdi mdi-' . $navpoint['icon'] . '">' . $module->getMessage($navpoint['name']) . '</p>
                                          </a>
                                      </li>';
                                    $active = "";

                                }
                            }
                        }
                        echo '</ul></div></li>';
                    }
                }
                unset($module);
                ?>
            </li>
            <?php
            $link = "changelog.php";
            $active = ($currentfile == $link) ? "active" : "";
            ?>
            <li class="<?= $active ?>">
                <a href="<?= $link ?>"><?= $db->m("sidebar_changelog")?></a>
            </li>
            <li>
                <a href="../php/logout.php"><?= $db->m("sidebar_logout")?></a>
            </li>
    </ul>
</div>
