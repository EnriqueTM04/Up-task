<?php

foreach ($alertas as $key => $alerta) {
    foreach ($alerta as $mensaje => $value) {
        ?>

        <div class="alerta <?php echo $key; ?>"><?php echo $value; ?></div>

        <?php
    }
}

?>