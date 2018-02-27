<?php
if (isset($params->confirm)) {
    if ($params->confirm == "1") {
        echo "KAUFEN!!";
    } else if ($params->confirm == "0") {
        echo '<a href="module.php?module=productmanager/products/configure_vserver.php&params=base|1_confirm|1" class="btn" data-background-color="blue">Confirm</a>';
        echo '<a href="module.php?module=productmanager/products/configure_vserver.php&params=base|1_confirm|cancel" class="btn" data-background-color="red">Cancel</a>';
        //echo "Hi, bezahl mal du Hure.";
    } else {
        //cancel

    }
}
?>
<button type="submit" class="btn" data-background-color="blue">Checkout</button>