<div class="sidebar" data-color="<?= $db->getConfig()['color']?>" data-image="../assets/img/sidebar-1.jpg">
    <!--
Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

Tip 2: you can also add an image using data-image tag
-->
    <div class="logo">
        <a href="<?= $db->getConfig()['url']?>" class="simple-text">
            <?= $db->getConfig()['site_name']?>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="active">
                <a href="index.php">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li>
                <a href="user.php">
                    <i class="material-icons">person</i>
                    <p>Profil</p>
                </a>
            </li>
            <?php

            foreach ($db->getModules() as $module) {
                foreach ($module->getNavs() as $navpoint) {
                  if ($navpoint['permission'] <= $user->getPermissions())
                    echo '<li>
                                  <a href="module.php?module=' . $navpoint['link'] . '">
                                      <i class="material-icons">' . $navpoint['icon'] . '</i>
                                      <p>' . $navpoint['name'] . '</p>
                                  </a>
                              </li>';
                }
                echo "<hr>";
            }

            ?>
            <li>
                <a href="changelog.php">
                    <i class="material-icons">add_circle</i>
                    <p>Changelog</p>
                </a>
            </li>
            <li>
                <a href="../php/logout.php">
                    <i class="material-icons">power_settings_new</i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </div>
</div>
