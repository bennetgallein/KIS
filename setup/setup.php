<?php
/*
1. checken in der config.json ob Werte für database host da sind.
2. Lizenz prüfen (setup_database).
3. Datenbank & Tabellen erstellen (setup_database).
*/
if (file_exists(dirname(__FILE__) . "/../config.json")) {
    $config = json_decode(file_get_contents(dirname(__FILE__) . "/../config.json"), true);
    if ($config['database'][0]['host'] == ""):
        ?>
        <form action="setup_database.php" method="get">
            <input type="text" name="license" placeholder="License Key" />
            <input type="submit" name="submit" />
        </form>
        <?php
    else:
        header("Location: ../index.php");
    endif;
}
?>
