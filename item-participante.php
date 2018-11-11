<div class="col-12 col-lg-6 mb-5 px-0">
    <div class="row item-usuario mx-auto border border-secondary">
        <div class="col-12 col-sm-4 contenedor-imagen px-0 py-3 py-sm-0">
            <a href="perfil.php?idUsuario=<?php echo $usuario['idUsuario']; ?>">
                <img class="imagen-perfil" alt="Perfil"
                    src=<?php
                        $avatar = "../media/perfiles-usuarios/" . $usuario['idUsuario'] . "-perfil";
                        if (file_exists($avatar))
                            echo $avatar;
                        else
                            echo "../media/perfiles-usuarios/0-perfil";
                    ?>>
            </a>
        </div>
        <div class="col-12 col-sm-8 px-0">
            <div class="contenedor-nombre px-4">
                <a class="nombre-apellido" href="perfil.php?idUsuario=<?php echo $usuario['idUsuario']; ?>">
                    <h4 class="text-center"><?php echo $usuario['nombres'] . ' ' . $usuario['apellidos']; ?></h4>
                </a>
            </div>
            <ul class="pl-0 mx-4">
                <li>
                    <i class="fa fa-group mr-1"></i><?php 
                        switch ($usuario['tipo']){
                            case 'C':
                                echo "Usuario común";
                                break;
                            case 'A':
                                echo "Administrador";
                                break;
                            default:
                                echo "Desconocido";
                        }
                    ?>
                </li>
                <li>
                    <i class="fa fa-map-marker mr-1"></i><?php
                        echo $usuario['calle'] . ' ' . $usuario['altura'] . ', ' . $usuario['nombreCiudad'] . ', ' . $usuario['nombreProvincia'];
                    ?>
                </li>
                <li><i class="fa fa-birthday-cake mr-1"></i><?php echo date("d/m/Y", strtotime($usuario['fechaNac'])); ?></li>
                <li><i class="fa fa-envelope mr-1"></i><?php echo $usuario['email']; ?></li>
            </ul>
        </div>
    </div>
</div>