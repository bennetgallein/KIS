<?php
if (isset($params->confirm)) {
    if ($params->confirm == "0") {
        if (isset($returnurl)) {
            echo "<form action='$returnurl' method='post'>";
            foreach ($_POST as $a => $b) {
                echo '<input type="hidden" name="' . htmlentities($a) . '" value="' . htmlentities($b) . '">';
            }
            echo '<button type="submit" class="btn" data-background-color="blue">Confirm</button>';
            echo "</form";
            echo '<a href="' . $returnurl . '" class="btn" data-background-color="red">Cancel</a>';
        } else {
            echo "NO RETURN URL SET = &dollar;returnurl";
        }
    } else {
        //cancel
    }
}
