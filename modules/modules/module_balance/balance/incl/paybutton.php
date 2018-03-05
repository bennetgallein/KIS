<?php
if (isset($params->confirm)) {
    if ($params->confirm == "0") {
        if (isset($returnurl) && isset($cancelurl)) {
            echo "<form action='$returnurl' method='post'>";
            foreach ($_POST as $a => $b) {
                echo '<input type="hidden" name="' . htmlentities($a) . '" value="' . htmlentities($b) . '">';
            }
            echo '<button type="submit" class="btn" data-background-color="blue">Confirm</button>';
            echo '<a href="' . $cancelurl . '" class="btn btn-danger" >Cancel</a>';
            echo "</form>";
        } else {
            echo "NO RETURN URL SET = &dollar;returnurl or &dollar;cancelurl";
        }
    } else {
        //cancel
    }
}
