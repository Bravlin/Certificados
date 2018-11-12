<div class="col-12 col-lg-6 mb-5 px-0">
    <div class="row item-participante mx-auto border border-secondary">
        <div class="col-12 col-sm-4 contenedor-imagen px-0 py-3 py-sm-0">
            <a href="perfil.php?id=<?php echo $participante['id']; ?>">
                <img class="imagen-perfil" alt="Perfil"
                    src=<?php
                        $avatar = "/media/perfiles-usuarios/" . $participante['id'] . "-perfil";
                        if (file_exists($avatar))
                            echo $avatar;
                        else
                            echo "/media/perfiles-usuarios/0-perfil";
                    ?>>
            </a>
        </div>
        <div class="col-12 col-sm-8 px-0">
            <div class="contenedor-nombre px-4">
                <a class="nombre-apellido" href="perfil.php?id=<?php echo $participante['id']; ?>">
                    <h4 class="text-center"><?php echo $participante['nombre'] . ' ' . $participante['apellido']; ?></h4>
                </a>
            </div>
            <ul class="pl-0 mx-4">
                <li>
                    <i class="fa fa-check-square mr-1"></i><?php
                        if (isset($participante['asistencia']))
                            echo $participante['asistencia'];
                        else
                            echo "Sin definir";
                    ?>
                </li>
                <li>
                    <i class="fa fa-map-marker mr-1"></i><?php
                        echo $participante['facultad'] . ', ' . $participante['universidad'];
                    ?>
                </li>
                <li><i class="fa fa-calendar mr-1"></i><?php echo date("d/m/Y", strtotime($participante['fecha'])); ?></li>
                <li><i class="fa fa-envelope mr-1"></i><?php echo $participante['email']; ?></li>
                <li><i class="fa fa-phone mr-1"></i><?php echo $participante['telefono']; ?></li>
            </ul>
        </div>
    </div>
</div>