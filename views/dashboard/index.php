<?php include_once __DIR__ . '/header-dashboard.php'; ?>

    <?php if( count($proyectos) === 0 ) { ?>
        <p class="no-proyectos">Aún no hay proyectos <a href="/create-proyect">Crea uno ya mismo</a></p>
    <?php } else { ?>
        <ul class="listado-proyectos">
            <?php foreach ($proyectos as $proyecto) { ?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                        <?php echo $proyecto->proyecto  ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>