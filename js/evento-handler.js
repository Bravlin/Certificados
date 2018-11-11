/*global $*/
        
$(document).ready(function(){
    $('#ingresar-comentario').bind('input', function(){
        if ($('#ingresar-comentario').val() != "")
            $('#enviar-comentario').prop("disabled", false);
        else
            $('#enviar-comentario').prop("disabled", true);
    });
    
    $('#enviar-comentario').on('click', function(){
        var contenido = $('#ingresar-comentario').val();
        var idEvento = $('#idEvento').attr('valor');
        $.ajax({
            type: 'POST',
            url: '/php-scripts/agregar-comentario.php',
            data: {
                idEvento: idEvento,
                contenido: contenido
            },
            success:function(html){
                $('#ingresar-comentario').val("");
                $('#enviar-comentario').prop("disabled", true);
                $('#comentarios').prepend(html);
            }
        }); 
    });
    
    $('#comentarios').on('click', '.eliminar-comentario', function(){
        var idComentario = $(this).attr('idcoment');
        $.ajax({
            type: 'POST',
            url: '/php-scripts/eliminar-comentario.php',
            data: {
                idComentario: idComentario
            },
            success:function(html){
                var comentario = "#comentario-" + idComentario;
                $(comentario).remove();
            }
        });
    })
    
    $('#inscribirse').on('click', function(){
        var idEvento = $('#idEvento').attr('valor');
        $.ajax({
            type: 'POST',
            url: '/php-scripts/gestor-inscripciones.php',
            data: {
                idEvento: idEvento,
                accion: 'inscribir'
            },
            success:function(html){
                $('#inscribirse').prop("hidden", true);
                $('#desinscribirse').prop("hidden", false);
            }
        }); 
    });
    
    $('#desinscribirse').on('click', function(){
        var idEvento = $('#idEvento').attr('valor');
        $.ajax({
            type: 'POST',
            url: '/php-scripts/gestor-inscripciones.php',
            data: {
                idEvento: idEvento,
                accion: 'desinscribir'
            },
            success:function(html){
                $('#inscribirse').prop("hidden", false);
                $('#desinscribirse').prop("hidden", true);
            }
        }); 
    });
});