/*global $*/

$(document).ready(function(){
    $('#eliminar-evento').on('click', function(){
        if (confirm('¿Está seguro de que desea eliminar este evento?')){
            var idEvento = $('#id_evento').attr('valor');
            $.ajax({
                type: 'POST',
                url: '/lib/eliminar-evento.php',
                data: {
                    idEvento: idEvento,
                },
                success:function(){
                    window.location.replace("/eventos.php");
                }
            });
        }
    });

    $('#select-perfil').on('change', function(){
        var idPerfil = $('#select-perfil').val();
        var tipo = $('#select-tipo').val();
        if (idPerfil != "" && tipo != "")
            $('#boton-inscribir').prop("disabled", false);
        else
            $('#boton-inscribir').prop("disabled", true);
    });

    $('#select-tipo').on('change', function(){
        var idPerfil = $('#select-perfil').val();
        var tipo = $('#select-tipo').val();
        if (idPerfil != "" && tipo != "")
            $('#boton-inscribir').prop("disabled", false);
        else
            $('#boton-inscribir').prop("disabled", true);
    });

    $('#boton-inscribir').on('click', function(){
        var idEvento = $('#id_evento').attr('valor');
        var idPerfil = $('#select-perfil').val();
        var tipo = $('#select-tipo').val();
        $.ajax({
            type: 'POST',
            url: '/lib/inscribir.php',
            data: {
                idEvento: idEvento,
                idPerfil: idPerfil,
                tipo: tipo
            },
            success:function(){
            }
        });
    });
});