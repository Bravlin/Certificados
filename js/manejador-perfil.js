/*global $*/

$(document).ready(function(){
    $('#perfiles').on('click', '.eliminar-perfil', function(){
        if (confirm("¿Está seguro que desea eliminar el perfil indicado?")){
            var idPerfil = $(this).attr('valor');
            $.ajax({
                type: 'POST',
                url: 'lib/eliminar-perfil.php',
                data: {
                    idPerfil: idPerfil,
                },
                success:function(){
                    var perfil = "#perfil-" + idPerfil;
                    $(perfil).remove();
                }
            });
        }
    })
});