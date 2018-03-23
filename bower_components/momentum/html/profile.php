<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="An Admin Template">
    <meta name="keywords" content="HTML,CSS,JavaScript">
    <meta name="author" content="Intranetproject">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.css">
    <link rel="stylesheet" href="../css/momentum.css">
</head>

<body>
    <div id="wrapper" class="">
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
                                echo '<a class="collapseable-nav" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">Module<i class="mdi mdi-plus"></i></a>
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
                        <a class="collapseable-nav" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">Module<i class="mdi mdi-plus"></i></a>
                        <div class="collapse" id="collapse">
                            <ul class="collapsible">
                                <li>
                                    <a href="#">this and that</a>
                                </li>
                                <li>
                                    <a href="#">this and that</a>
                                </li>
                                <li>
                                    <a href="#">this and that</a>
                                </li>
                                <li>
                                    <a href="#">this and that</a>
                                </li>
                            </ul>
                        </div>
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
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="mdi mdi-star"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">one</a>
                            <a class="dropdown-item" href="#">two</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">three</a>
                        </div>
                    </li>

                </ul>
                <a href="#" class="ml-3"><i class="mdi mdi-bell mdi-light mdi-24px"></i></a>
                <a href="html/profile.html" class="ml-3"><i class="mdi mdi-account mdi-light mdi-24px"></i></a>
            </div>
        </nav>
        <div class="website-content pb-4">
            <div class="inner pl-3 pb-3 pr-3">
                <div class="row col-md-12 mb-3">
                    <div class="card col-md-12">
                        <div class="card-body">
                            <h5 class="card-title"><?= $db->m("profile_edit_profile") ?></h5>
                            <p>
                                <?= $db->m("profile_edit_complete") ?>
                            </p>
                            <p class="card-text">
                                <form>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="fName"><?= $db->m("profile_edit_firstname")?></label>
                                            <input type="text" class="form-control" id="fName" value="<?= $user->getFirstname() ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lName"><?= $db->m("profile_edit_lastname")?></label>
                                            <input type="text" class="form-control" id="lName" value="<?= $user->getLastname() ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="email"><?= $db->m("profile_edit_email") ?></label>
                                            <input type="email" class="form-control" id="email" value="<?= $user->getEmail() ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="conf"><?= $db->m("profile_edit_confirmemail") ?></label>
                                            <input type="email" class="form-control" id="conf" value="<?= $user->getEmail() ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="address"><?= $db->m("profile_edit_addresss") ?></label>
                                            <input type="text" class="form-control" id="address" value="<?= (isset($data->adress) ? $data->adress : " ") ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="comp"><?= $db->m("profile_edit_company") ?></label>
                                            <input type="text" class="form-control" id="comp" value="<?= isset($data->company) ? $data->company : " " ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="city"><?= $db->m("profile_edit_city") ?></label>
                                            <input type="text" class="form-control" id="city" value="<?= isset($data->city) ? $data->city : " " ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="country"><?= $db->m("profile_edit_country") ?></label>
                                            <input type="text" class="form-control" id="country" value="<?= isset($data->country) ? $data->country : " " ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="postcode"><?= $db->m("profile_edit_postalcode") ?></label>
                                            <input type="text" class="form-control" id="postcode" value="<?= isset($data->postalcode) ? $data->postalcode : " " ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary col-md-2 float-right"><?= $db->m("profile_edit_submit")?></button>
                                </form>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($_GET['confirm'])):
                    $toConfirm = $_GET['confirm'];
                    if ($toConfirm == "delete"): ?>
                    <a href="../php/userbtn.php?method=delete&confirmed=1"><?= $db->m("profile_delete_confirm") ?></a>
                    <?php elseif ($toConfirm == "resetpw"): ?>
                    <a href="../php/userbtn.php?method=resetpw&confirmed=1"><?= $db->m("profile_reset_confirm") ?></a>
                    <?php endif; endif; ?>
                    <div class="row col-md-12">
                        <div class="card col-md-12">
                            <div class="card-body">
                                <h5 class="card-title bg-danger text-center text-white pt-3 pr-3 pb-3 pl-3">DANGER ZONE</h5>
                                <p class="card-text">
                                    <a href="../php/userbtn.php" class="btn btn-info col-md-4 offset-md-1"><?= $db->m("profile_danger_title") ?><br><small><?= $db->m("profile_danger_password_desc") ?></small></a>
                                    <a href="../php/userbtn.php?method=resetpw&confirmed=1" class="btn btn-info col-md-4 offset-md-2"><?= $db->m("profile_danger_delete_title")?><br><small><?= $db->m("profile_danger_delete_desc") ?></small></a>
                                </p>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript & jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="../js/morris.js"></script>
    <script src="../js/momentum.js" type="text/javascript"></script>
</body>

</html>
