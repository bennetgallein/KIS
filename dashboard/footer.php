<footer class="footer">
    <div class="container-fluid">
        <nav class="pull-left">
            <ul>
                <?php
                foreach ($db->getFooter()[0] as $name => $link) {
                    echo '<li><a href="' . $link . '">' . $name . '</a></li>';
                }
                echo '<li><p>version: ' . $db->getConfig()['version'] . '</p></li>';

                if (isset($params)) {
                    echo "<li><a href='info.php?module=$aamod'>Module Information</a>";
                }
                ?>
            </ul>
        </nav>
        <p class="copyright pull-right">
            &copy;<?= "2017-" . date('Y') ?>
            <a href="https://www.intranetproject.net">Intranetroject</a>, made with <span style="color: red;"><i class="material-icons">favorite_border</i></span> and passion | Design by <a href="https://www.creative-tim.com/product/material-dashboard">CreativeTim</a>
        </p>
    </div>
</footer>