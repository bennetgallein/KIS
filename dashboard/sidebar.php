<div class="sidebar" data-color="<?= $db->getConfig()['color'] ?>" data-image="../assets/img/sidebar-1.jpg">
    <!--
Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

Tip 2: you can also add an image using data-image tag
-->
    <div class="logo">
        <a href="<?= $db->getConfig()['url'] ?>" class="simple-text">
            <?= $db->getConfig()['site_name'] ?>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <?php
        $currentfile = basename($_SERVER["SCRIPT_FILENAME"]);
        ?>
        <ul class="nav">
            <?php
            $link = "index.php";
            $active = ($currentfile == $link) ? "active" : "";
            ?>
            <li class="<?= $active ?>">
                <a href="<?= $link ?>">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <?php
            $link = "user.php";
            $active = ($currentfile == $link) ? "active" : "";
            ?>
            <li class="<?= $active ?>">
                <?php
                $module = $db->getModuleByName("Balance Manager");
                if ($module->isActive()) {
                    if ($module->getIncludeable("display_money")['permission'] <= $user->getPermissions()) {
                        include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("display_money")['link']);
                        $money = getMoney($db, $user);
                        $money = "<span class=\"badge\">" . $money . "€</span>";
                    }
                }
                ?>
                <a href="<?= $link ?>">
                    <i class="material-icons">person</i>
                    <p><?= $db->m("sidebar_profile")?> <?= isset($money) ? $money : ""?></p>
                </a>
            </li>
            <?php
            unset($active);
            foreach ($db->getModules() as $module) {
                $hrset = false;
                if ($module->isActive()) {
                    foreach ($module->getNavs() as $navpoint) {
                        if ($navpoint['type'] == 'nav') {
                            if ($navpoint['permission'] <= $user->getPermissions()) {
                                if ($hrset == false) {
                                    echo "<hr>";
                                }
                                $hrset = true;
                                $path = $module->getBasepath() . $navpoint['link'];
                                if (isset($amodule)) {
                                    if ($path == $amodule) {
                                        $active = "active";
                                    }
                                }
                                $active = isset($active) ? $active : "";
                                echo '<li class="' . $active . '">
                                      <a href="module.php?module=' . $module->getBasepath() . $navpoint['link'] . '">
                                          <i class="material-icons">' . $navpoint['icon'] . '</i>
                                          <p>' . $navpoint['name'] . '</p>
                                      </a>
                                  </li>';
                                $active = "";

                            }
                        }
                    }
                }
            }
            unset($module);
            ?>
            <?php
            $link = "changelog.php";
            $active = ($currentfile == $link) ? "active" : "";
            ?>
            <li class="<?= $active ?>">
                <a href="<?= $link ?>">
                    <i class="material-icons">add_circle</i>
                    <p><?= $db->m("sidebar_changelog") ?></p>
                </a>
            </li>
            <li>
                <a href="../php/logout.php">
                    <i class="material-icons">power_settings_new</i>
                    <p><?= $db->m("sidebar_logout") ?></p>
                </a>
            </li>
        </ul>
    </div>
</div>
