/*global $*/

$(document).ready(function(){
    $('#seguir').on('click', function(){
        var idEtiqueta = $('#idElemento').attr('valor');
        $.ajax({
            type: 'POST',
            url: 'php-scripts/etiquetas-usuarios.php',
            data: {
                idEtiqueta: idEtiqueta,
                accion: 'seguir'
            },
            success:function(html){
                $('#seguir').prop('hidden', true);
                $('#dejar-seguir').prop('hidden', false);
            }
        });
    });
    
    $('#dejar-seguir').on('click', function(){
        var idEtiqueta = $('#idElemento').attr('valor');
        $.ajax({
            type: 'POST',
            url: 'php-scripts/etiquetas-usuarios.php',
            data: {
                idEtiqueta: idEtiqueta,
                accion: 'dejar'
            },
            success:function(html){
                $('#seguir').prop('hidden', false);
                $('#dejar-seguir').prop('hidden', true);
            }
        });
    });
});