<div id="perfil-<?php echo $perfil['id']; ?>" class="col-12 col-lg-6 mb-5 px-0">
    <div class="row item-perfil mx-auto border border-secondary">
        <div class="col-12 col-sm-4 contenedor-imagen px-0 py-3 py-sm-0">
            <a href="certificados.php?idPerfil=<?php echo $perfil['id']; ?>">
                <img class="imagen-perfil" alt="Perfil"
                    src=<?php
                        $avatar = URL."/media/perfiles-usuarios/" . $perfil['id'] . "-perfil";
                        if (file_exists($avatar))
                            echo $avatar;
                        else
                            echo URL."/media/perfiles-usuarios/0-perfil";
                    ?>>
            </a>
        </div>
        <div class="col-12 col-sm-8 px-0 contenedor-info">
            <div class="contenedor-nombre px-4">
                <a class="nombre-apellido" href="certificados.php?idPerfil=<?php echo $perfil['id']; ?>">
                    <h4 class="text-center"><?php echo $perfil['nombre'] . ' ' . $perfil['apellido']; ?></h4>
                </a>
            </div>
            <ul class="pl-0 mx-4">
                <li><i class="fa fa-map-marker mr-1"></i><?php echo $perfil['organismo'];?></li>
                <li><i class="fa fa-group mr-1"></i><?php echo $perfil['cargo']; ?></li>
                <li><i class="fa fa-envelope mr-1"></i><?php echo $perfil['email']; ?></li>
                <li><i class="fa fa-phone mr-1"></i><?php echo $perfil['telefono']; ?></li>
            </ul>
            <a class="btn btn-primary ml-3 mb-3" href="modificar-perfil.php?idPerfil=<?php echo $perfil['id']; ?>">
                Modificar
            </a>
            <button class="eliminar-perfil btn btn-danger ml-3 mb-3" valor="<?php echo $perfil['id']; ?>">
                Eliminar
            </button>
        </div>
    </div>
</div>
