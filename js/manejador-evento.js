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
    })
});