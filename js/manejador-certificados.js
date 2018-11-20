/*global $*/

$(document).ready(function(){

    $('#body-certificados').on('click', '.eliminar-certificado', function(){
        if (confirm("¿Está seguro que desea eliminar el certificado indicado?")){
            var idCertif = $(this).attr('valor');
            $.ajax({
                type: 'POST',
                url: 'lib/eliminar-certificado.php',
                data: {
                    idCertif: idCertif,
                },
                success:function(){
                    var certif = "#certificado-" + idCertif;
                    $(certif).remove();
                }
            });
        }
    });
});