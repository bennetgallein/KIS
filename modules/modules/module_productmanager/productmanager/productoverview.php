<?php
foreach ($module->getRaw()['products'] as $product) {
    if ($product['active']) {
        echo "<div class='row'><p class='navbar-brand'>";
        echo $product['name'];
        echo "</p></div>";
        include($module->getPath() . "/" . $module->getBasePath() . $product['link']);
    }
}
