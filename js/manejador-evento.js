/*global $*/

$(document).ready(function(){
    $('#eliminar-evento').on('click', function(){
        if (confirm('¿Está seguro de que desea eliminar este evento?')){
            var idEvento = $('#id_evento').attr('valor');
            $.ajax({
                type: 'POST',
                url: 'lib/eliminar-evento.php',
                data: {
                    idEvento: idEvento,
                },
                success:function(){
                    window.location.replace("eventos.php");
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
            url: 'lib/manejador-inscripciones.php',
            data: {
                accion: "A",
                idEvento: idEvento,
                idPerfil: idPerfil,
                tipo: tipo
            },
            success:function(html){
                $('#body-inscripciones').prepend(html);
                $('#boton-inscribir').prop("disabled", true);
            }
        });
    });

    $('#body-inscripciones').on('click', '.eliminar-inscripcion', function(){
        if (confirm("¿Está seguro que desea eliminar la inscripción indicada?")){
            var idInscrip = $(this).attr('valor');
            $.ajax({
                type: 'POST',
                url: 'lib/manejador-inscripciones.php',
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
    });

    $('#body-inscripciones').on('click', '.emitir-cert', function(){
        var idInscrip = $(this).attr('valor');
        $.ajax({
            type: 'POST',
            url: 'lib/manejador-certificados.php',
            data: {
                accion: "I",
                idInscrip: idInscrip,
            },
            success:function(){
                $.ajax({
                    type: 'POST',
                    url: 'lib/manejador-email.php',
                    data: {
                        accion: "I",
                        idInscrip: idInscrip,
                    },
                    success:function(){
                        alert("Certificado enviado con éxito.")
                    }
                });
            }
        })
    });

    $('#body-inscripciones').on('change', '.select-asistencia', function(){
        var idInscrip = $(this).attr('valor');
        var asistencia = $(this).val();
        $.ajax({
            type: 'POST',
            url: 'lib/manejador-inscripciones.php',
            data: {
                accion: "M",
                idInscrip: idInscrip,
                asistencia: asistencia,
            },
            success:function(){
                var emitirBoton = "#emitir-i" + idInscrip;
                $(emitirBoton).prop("disabled", asistencia != 1);
            }
        });
    });

    $('#subir-template').on('click', function(){
        var idEvento = $('#id_evento').attr('valor');
        var formData = new FormData();
        formData.append("accion", "T");
        formData.append("idEvento", idEvento);
        formData.append('template', $('input[type=file]')[0].files[0]);
        $.ajax({
            type: 'POST',
            url: 'lib/manejador-certificados.php',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(html){
                alert(html);
            }
        });
    });

    $('#generar-todos').on('click', function(){
        var idEvento = $('#id_evento').attr('valor');
        $.ajax({
            type: 'POST',
            url: 'lib/manejador-certificados.php',
            data: {
                accion: "A",
                idEvento: idEvento,
            },
            success:function(){
                alert("Certificados generados.");
            }
        });
    });

    $('#emitir-todos').on('click', function(){
        var idEvento = $('#id_evento').attr('valor');
        $.ajax({
            type: 'POST',
            url: 'lib/manejador-email.php',
            data: {
                accion: 'T',
                idEvento: idEvento,
            },
            success:function(){
                alert("Certificados enviados.");
            }
        });
    });
});