/*global $*/

$(document).ready(function(){
    $('#provincia').on('change',function(){
        var idProvincia = $(this).val();
        if (idProvincia){
            $.ajax({
                type: 'POST',
                url: 'lib/recupera-ciudades.php',
                data: {
                    id_provincia: idProvincia
                },
                success:function(html){
                    $('#ciudad').html(html); 
                }
            }); 
        }
        else
            $('#ciudad').html('<option value="">Primero elija una provincia</option>');
    });
});