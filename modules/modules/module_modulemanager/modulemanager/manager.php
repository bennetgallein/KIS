<?php
foreach ($db->getModules() as $module) {
    echo "<div class='row'><p class='navbar-brand'>" . $module->getName() . "</p></div>";
    highlight_string("<?php\n\$module =\n" . var_export($module, true) . ";\n?>");
}
?>