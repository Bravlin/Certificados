/*global $*/

$(document).ready(function(){

    $('#body-certificados').on('click', '.eliminar-certificado', function(){
        /*
        if (confirm("¿Está seguro que desea eliminar la inscripción indicada?")){
            var idInscrip = $(this).attr('valor');
            $.ajax({
                type: 'POST',
                url: '/lib/manejador-inscripciones.php',
                data: {
                    accion: "B",
                    idInscrip: idInscrip,
                },
                success:function(){
                    var inscrip = "#inscripcion-" + idInscrip;
                    $(inscrip).remove();
                }
            });
        }
        */
    });
});