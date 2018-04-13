<footer class="footer mt-3 border-top pt-3">
    <div class="container-fluid row">
        <nav class="float-left text-dark col-md-6">
            <ul class="nav">
                <?php
                foreach ($db->getFooter()[0] as $name => $link) {
                    echo '<li class="nav-item ml-2 mr-2"><a class="text-dark" href="' . $link . '">' . $name . '</a></li>';
                }
                if (isset($moddd) && $user->getPermissions() >= 2) {
                    echo '<li class="nav-item"><a class="text-dark" href="module.php?module=modulemanager/manager.php&params=module|' . $moddd->getName() . '">Module Information</a></li>';
                }
                echo '<li class="nav-item"><p>version: ' . $db->getVersion() . '</p></li>';
                ?>
            </ul>
        </nav>
        <p class="copyright float-right col-md-6 text-right">
            &copy;<?= "2017-" . date('Y') ?>
            <a href="https://www.intranetproject.net">Intranetproject</a>, made with <span style="color: red;"><i class="mdi mdi-heart"></i></span> and passion
        </p>
    </div>
</footer>
