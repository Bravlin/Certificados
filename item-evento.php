<?php
    $enlaceEvento = "evento.php?id_evento=" . $evento['id_evento'];
?>

<div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-5 d-flex align-items-stretch">
    <div class="card item-consulta">
        <div class="contenedor-portada">
            <a href="<?php echo $enlaceEvento; ?>">
                <img class="card-img-top" alt="Card image cap"
                    src=<?php
                        $portada = "/media/portadas-eventos/" . $evento['id_evento'] . "-p";
                        if (file_exists($portada))
                            echo $portada;
                        else
                            echo "/media/portadas-eventos/0-p";
                    ?>
                >
            </a>
            <div class="contenedor-nombre px-4">
                <a class="enlace-evento" href="<?php echo $enlaceEvento; ?>">
                    <h5 class="card-title my-1"><?php echo $evento['nombre_evento']; ?></h5>
                </a>
            </div>
        </div>
        <div class="card-body">
            <ul class="contenedor-info pl-0">
                <li class="mb-2">
                    <i class="fa fa-plus"></i>
                    <?php echo $evento['fecha_creacion']?>
                </li>
                <li class="mb-2">
                    <i class="fa fa-map-marker eventu-pink-text"></i>
                    <?php echo $evento['direccion_calle'].' '.$evento['direccion_altura'].', '.$evento['nombre_ciudad'].', '.$evento['nombre_provincia']; ?>
                </li>
                <li>
                    <i class="fa fa-calendar"></i>
                    <?php
                        $fechaHora = strtotime($evento['fecha_realizacion']);
                        echo date('d/m/Y', $fechaHora);
                    ?>
                    <i class="fa fa-clock-o pl-3"></i>
                    <?php echo date('H:i', $fechaHora); ?>
                </li>
            </ul>
        </div>
    </div>
</div>