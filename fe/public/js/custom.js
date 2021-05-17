/******************************************
 /*    Procesar pedido
/* ******************************************/

jQuery("#btn_mp").click(function () {

    $('.page-loader.l-cart').fadeIn();

    var datosForm = $('#procesar_compra_form').serialize();

    jQuery('#alerta_envio').text('');
    var html = '<div class="alert alert-warning alert-dismissable" style="margin-top:20px;">'
        + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
        + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
        + '<strong>Espere un momento por favor</strong> Su solicitud está siendo porcesada'
        + '</div>';
    jQuery('#alerta_envio').html(html);

    $.ajax({
        url: 'procesar_pedido?id=1',
        type: 'POST',
        data: datosForm,
        dataType: 'json',
        success: function (json) {
            $.ajax({
                url: 'procesar_pedido?id=2',
                type: 'POST',
                data: datosForm,
                dataType: 'json',
                success: function (json) {
                    var direccion = json['preference_data']['response']['init_point'];
                    window.location.href = direccion;
                    jQuery('#alerta_envio').text('');
                    var html = '<div class="alert alert-success alert-dismissable" style="margin-top:20px;">'
                        + '<span class="alert-icon"><i class="fa fa-success"></i></span>'
                        + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                        + '<strong>Solicitud Enviada!</strong>'
                        + '</div>';
                    jQuery('#alerta_envio').html(html);
                    $('.page-loader.l-cart').fadeOut();
                },
                error: function (xhr, status) {
                    alert('Error al solicitar credenciales');
                }
            });
        },
        error: function (xhr, status) {
            alert('Error al enviar pedido');
        }
    });
});

jQuery("#btn_tp").click(function () {

    $('.page-loader.l-cart').fadeIn();
    var datosForm = $('#procesar_compra_form').serialize();
    jQuery('#alerta_envio').text('');
    var html = '<div class="alert alert-warning alert-dismissable" style="margin-top:20px;">'
        + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
        + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
        + '<strong>Espere un momento por favor</strong> Su solicitud está siendo porcesada'
        + '</div>';
    jQuery('#alerta_envio').html(html);

    $.ajax({
        url: 'procesar_pedido?id=1',
        type: 'POST',
        data: datosForm,
        dataType: 'json',
        success: function (json) {
            $.ajax({
                url: 'procesar_pedido?id=2',
                type: 'POST',
                data: datosForm,
                dataType: 'json',
                success: function (json) {
                    $('.page-loader.l-cart').fadeOut();
                    var direccion = "todopago?id_pedido=" + json['data']['id_pedido'] + "&total=" + json['data']['total']['precio'];
                    window.location.href = direccion;
                },
                error: function (xhr, status) {
                    alert('Error al solicitar credenciales');
                }
            });
        },
        error: function (xhr, status) {
            alert('Error al enviar pedido');
        }
    });
});

/*
   jQuery("button#procesar_compra").click(function () {
    var dir_envio = jQuery('select#direccion_envio').val();
   // var dir_fact = jQuery('select#direccion_fact').val();
    var tipo_envio = jQuery('select#tipo_envio').val();
    var sucursal = jQuery('select#sucursal').val();
    var nombre = jQuery('input#nombre').val();
    var dni = jQuery('input#dni').val();
    if(dni == ''){
        jQuery('#alerta_envio').text('');
                    var html = '<div class="alert alert-danger alert-dismissable" style="margin-top:20px;">'
                        + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
                        + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                        + '<strong>Atención!</strong> Falta completar el DNI'
                        + '</div>';
                    jQuery('#alerta_envio').html(html);
    }else{
        if (dir_envio == null  && dir_fact == null ) {
            $('#dni').val(dni);
            jQuery("#procesar_compra_form").submit();
        } else {

            switch(dir_envio) {
                case "0":
                    jQuery('#alerta_envio').text('');
                    var html = '<div class="alert alert-danger alert-dismissable" style="margin-top:20px;">'
                        + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
                        + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                        + '<strong>Atención!</strong> Debe seleccionar una dirección de envío'
                        + '</div>';
                    jQuery('#alerta_envio').html(html);
                  break;
                case "-1":
                        if (sucursal != '') {
                            $('#dni_data').val(dni);
                            jQuery("#procesar_compra_form").submit();
                        }else{
                            jQuery('#alerta_envio').text('');
                                var html = '<div class="alert alert-danger alert-dismissable" style="margin-top:20px;">'
                                    + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                                    + '<strong>Atención!</strong> Debe seleccionar la Sucursal'
                                    + '</div>';
                                jQuery('#alerta_envio').html(html);
                        }
                  break;
                default:
                    if (tipo_envio != '' ) {
                        $('#dni_data').val(dni);
                        jQuery("#procesar_compra_form").submit();
                    } else {
                        jQuery('#alerta_envio').text('');
                        var html = '<div class="alert alert-danger alert-dismissable" style="margin-top:20px;">'
                            + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
                            + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                            + '<strong>Atención!</strong> Debe seleccionar el tipo de envío'
                            + '</div>';
                        jQuery('#alerta_envio').html(html);
                    }
              }

            if(dir_envio == -1){
                if (sucursal != '') {
                    console.log('pasa3');
                    $('#dni_data').val(dni);
                    jQuery("#procesar_compra_form").submit();
                }else{
                    jQuery('#alerta_envio').text('');
                        var html = '<div class="alert alert-danger alert-dismissable" style="margin-top:20px;">'
                            + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
                            + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                            + '<strong>Atención!</strong> Debe seleccionar la Sucursal'
                            + '</div>';
                        jQuery('#alerta_envio').html(html);
                }
            }

            if (dir_envio != 0  && dir_envio != -1) {
                if (tipo_envio != '' ) {
                    console.log('pasa2');
                    $('#dni_data').val(dni);
                    jQuery("#procesar_compra_form").submit();
                } else {
                    jQuery('#alerta_envio').text('');
                    var html = '<div class="alert alert-danger alert-dismissable" style="margin-top:20px;">'
                        + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
                        + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                        + '<strong>Atención!</strong> Debe seleccionar el tipo de envío'
                        + '</div>';
                    jQuery('#alerta_envio').html(html);
                }
            } else {
                jQuery('#alerta_envio').text('');
                var html = '<div class="alert alert-danger alert-dismissable" style="margin-top:20px;">'
                    + '<span class="alert-icon"><i class="fa fa-warning"></i></span>'
                    + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'
                    + '<strong>Atención!</strong> Debe seleccionar una dirección de envío'
                    + '</div>';
                jQuery('#alerta_envio').html(html);
            }; 
        };
    }
    
});*/

$('select[name=direccion_envio]').on('change', function () {
    $('#costos_envio').html('');
    $('.paso3').addClass('disabled');
    $('.paso2').addClass('disabled');
    $('.paso1').removeClass('disabled');
    $('#medios_pago').removeClass('disabled');
    $('#medios_pago').addClass('disabled');
    $('#btn_mp').attr('disabled', 'disabled');
    $('#btn_tp').attr('disabled', 'disabled');


    $('#dni').attr('disabled', 'disabled');
    $('#telefono').attr('disabled', 'disabled');
    $('#nombre').attr('disabled', 'disabled');

    if ($(this).val() == 'nueva') {
        window.location = 'agregar_direccion?returnTo=procesar_pedido&id=1';
    } else {
        if ($(this).val()) {
            if ($(this).val() == -1) { //retiro en sucursal

                $('.envio-col span').text('$0');
                $('input#envio_db').val('0');
                $('input#id_tipo_envio').val(3);
                $('.total-row span').text($('.subtotal-t span').text());
                $('input#id_direccion_envio').val(0);

                $('#divnombre').show();

                $('#divtipo_envio').hide();
                $('#tipo_envio').attr('disabled', 'disabled');
                $('#tipo_envio').text('');

                $('.costo_envio_producto').text('');

                $('#divsucursal').show();
                $('#sucursal').removeAttr('disabled');
                $('#sucursal').text('');
                $('#sucursal').append($('<option>', {
                    value: '',
                    text: 'Cargando...'
                }));
                $.ajax({
                    url: 'sucursalEnvio',
                    type: 'GET',
                    dataType: 'json',
                    success: function (json) {
                        $('#sucursal option[value=""]').text('Seleccionar');
                        $.each(json, function (i, item) {
                            $('#sucursal').append($('<option>', {
                                value: item.id,
                                text: item.name
                            }));
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'es_' + item.id,
                                value: item.cost,
                                'data-id_sucursal': item.id,
                                'data-fecha': item.fecha.fecha
                            }).appendTo('#sucursal_envio');
                        });
                    },
                    error: function (xhr, status) {
                        alert('Disculpe, hubo un error inesperado');
                    }
                });
            } else {
                $('.envio-col span').text('$');
                $('input#id_tipo_envio').val('');
                $('input#id_direccion_envio').val($(this).val());

                $('#divsucursal').hide();
                $('#sucursal').attr('disabled', 'disabled');

                $('#divnombre').show();
                //$('#divnombre').hide();
                //$('#nombre').attr('disabled','disabled');

                $('#divtipo_envio').show();
                $('#tipo_envio').removeAttr('disabled');

                $('#sucursal').text('');
                $('#tipo_envio').text('');
                $('#tipo_envio').append($('<option>', {
                    value: '',
                    text: 'Cargando...'
                }));
                $.ajax({
                    url: 'costoEnvio',
                    data: { id: $(this).val() },
                    type: 'GET',
                    dataType: 'json',
                    success: function (json) {
                        $('#tipo_envio option[value=""]').text('Seleccionar');
                        $.each(json, function (i, item) {
                            $('#tipo_envio').append($('<option>', {
                                value: item.id,
                                text: item.name
                            }));
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'e_' + item.id,
                                value: item.cost,
                                'data-id_tipo_envio': item.id_tipo_envio
                            }).appendTo('#costos_envio');

                            $('<input>').attr({
                                type: 'hidden',
                                id: 'ea_' + item.id,
                                value: item.cost_andreani,
                            }).appendTo('#costos_envio_andreani');
                        });
                    },
                    error: function (xhr, status) {
                        alert('Disculpe, hubo un error inesperado');
                    }
                });
            }
        } else {
            $('.envio-col span').text('$');
            $('input#envio_db').val('0');
            $('input#id_tipo_envio').val('');
            $('.total-row span').text($('.subtotal-t span').text());
            $('input#id_direccion_envio').val(0);

            $('#divtipo_envio').hide();
            $('#tipo_envio').attr('disabled', 'disabled');

            $('#tipo_envio').text('');
            $('.costo_envio_producto').text('');
            $('#divsucursal').hide();
            $('#sucursal').attr('disabled', 'disabled');
            $('#sucursal').text('');

            $('#divnombre').hide();

            $('.paso1').removeClass('disabled');
            $('.paso2').addClass('disabled');
            $('.paso3').addClass('disabled');

        }
    }
});

$('select[name=tipo_envio]').on('change', function () {

    if ($(this).val()) {
        $('.paso1').addClass('disabled');
        $('.paso2').removeClass('disabled');

        $('#nombre').removeAttr('disabled');
        $('#dni').removeAttr('disabled');
        $('#telefono').removeAttr('disabled');

        if ($(this).val()==73328||$(this).val()==73330){
            $('#btn_tp').attr('disabled', 'disabled')
        }
    } else {
        $('.paso1').removeClass('disabled');
        $('.paso2').addClass('disabled');
        $('.paso3').addClass('disabled');

        $('#nombre').attr('disabled', 'disabled');
        $('#dni').attr('disabled', 'disbled');
        $('#telefono').attr('disabled', 'disbled');

        $('#medios_pago').addClass('disabled');
        $('#btn_mp').attr('disabled', 'disabled');
        $('#btn_tp').attr('disabled', 'disabled');
    }

    var id = $(this).val();
    var id_tipo_envio = $('#costos_envio').find('#e_' + id).data('id_tipo_envio');
    var precio_envio_db = Number($('#costos_envio').find('#e_' + id).val());
    var precio_envio_db_a = Number($('#costos_envio_andreani').find('#ea_' + id).val());

    $('input#id_tipo_envio').val(id_tipo_envio);
    $('input#envio_db').val(precio_envio_db);
    $('input#cost_andreani').val(precio_envio_db_a);
    var locale = 'es';
    var formatter = new Intl.NumberFormat(locale);
    $('.envio-col span').text('$' + formatter.format(precio_envio_db));

    var subtotal = Number($('input#precio_db').val());
    var total = subtotal + precio_envio_db;
    $('.total-row span').text('$' + formatter.format(total));
});

$('select[name=direccion_fact]').on('change', function () {
    if ($(this).val() == 'nueva') {
        window.location = 'agregar_direccion?returnTo=procesar_pedido&id=1';
    } else {
        if ($(this).val() != '') {
            $('input#id_direccion_fact').val($(this).val());
        };
    }
});

$('select[name=provincia]').on('change', function () {
    $('#ciudad').html('<option value="" selected="selected">Cargando...</option>');
    $.ajax({
        url: 'getLocalidad',
        data: { id: $(this).val() },
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            if (jQuery.isEmptyObject(json)) {
                $('#ciudad').hide();
                $('#ciudad').prop('required', false);
                $('#ciudad_text').show();
                $('#ciudad_text').prop('required', true);
            } else {
                $('#ciudad').show();
                $('#ciudad').prop('required', true);
                $('#ciudad_text').hide();
                $('#ciudad_text').prop('required', false);
                $('#ciudad').html('<option value="" selected="selected">Seleccionar ciudad</option>');
                $.each(json, function (i, item) {
                    $('#ciudad').append($('<option>', {
                        value: item.id,
                        text: item.nombre
                    }));
                });
            }
        },
        error: function (xhr, status) {
            alert('Disculpe, hubo un error inesperado');
        }
    });
});

$('select[name=sucursal]').on('change', function () {

    if ($(this).val()) {
        $('.paso1').addClass('disabled');
        $('.paso2').removeClass('disabled');

        $('#nombre').removeAttr('disabled');
        $('#dni').removeAttr('disabled');
        $('#telefono').removeAttr('disabled');
    } else {
        $('.paso1').removeClass('disabled');
        $('.paso2').addClass('disabled');

        $('#nombre').attr('disabled', 'disabled');
        $('#dni').attr('disabled', 'disbled');
        $('#telefono').attr('disabled', 'disbled');
    }


    var id = $(this).val();
    var id_sucursal = $('#sucursal_envio').find('#es_' + id).data('id_sucursal');
    var fecha_sucursal = $('#sucursal_envio').find('#es_' + id).data('fecha');
    var precio_envio_db = Number($('#sucursal_envio').find('#es_' + id).val());

    $('input#id_sucursal').val(id_sucursal);
    $('input#fecha_sucursal').val(fecha_sucursal);
    $('input#envio_db').val(precio_envio_db);
    var locale = 'es';
    var formatter = new Intl.NumberFormat(locale);
    $('.envio-col span').text('$' + formatter.format(precio_envio_db));

    var subtotal = Number($('input#precio_db').val());
    var total = subtotal + precio_envio_db;
    $('.total-row span').text('$' + formatter.format(total));
});

$('input[name=dni]').keyup(function () {
    if (($(this).val().length > 6)&& ($(this).val().length <9) && ($('input[name=nombre]').val() != "") && ($('input[name=telefono]').val().length > 6) && ($('input[name=telefono]').val().length < 18)) {

        $('.paso3').removeClass('disabled');
        $('.paso2').removeClass('disabled');
        $('.paso2').addClass('disabled');
        $('#medios_pago').removeClass('disabled');
        $('#btn_mp').removeAttr('disabled', 'disabled');
        if ($('select[name=tipo_envio]').val()==73328||$('select[name=tipo_envio]').val()==73330){
            $('#btn_tp').attr('disabled', 'disabled');
            
        }else{
            $('#btn_tp').removeAttr('disabled', 'disabled');
        }

    } else {
        $('.paso2').removeClass('disabled');
        $('.paso3').removeClass('disabled');
        $('.paso3').addClass('disabled');


        $('#medios_pago').removeClass('disabled');
        $('#medios_pago').addClass('disabled');
        $('#btn_mp').attr('disabled', 'disabled');
        $('#btn_tp').attr('disabled', 'disabled');
    }

    $dni = $('input[name=dni]').val();
    $('input#dni_data').val($dni);
});

$('input[name=dni]').blur(function () {    
    if($(this).val().length>6&&$(this).val().length<9){ 
        jQuery('#alerta_datos').hide();
        $('#dni_add').removeClass('has-error');
    }else{
        jQuery('#alerta_datos').show();
        $('#dni_add').removeClass('has-error');
        $('#dni_add').addClass('has-error');
    }
});

$('input[name=nombre]').keyup(function () {
    if (($('input[name=dni]').val().length > 6) && ($('input[name=dni]').val().length <9) && ($('input[name=nombre]').val() != "") && ($('input[name=telefono]').val().length > 6) && ($('input[name=telefono]').val().length < 18)) {
        $('.paso3').removeClass('disabled');
        $('.paso2').removeClass('disabled');
        $('.paso2').addClass('disabled');
        $('#medios_pago').removeClass('disabled');
        $('#btn_mp').removeAttr('disabled', 'disabled');
        if ($('select[name=tipo_envio]').val()==73328||$('select[name=tipo_envio]').val()==73330){
            $('#btn_tp').attr('disabled', 'disabled')
        }else{
            $('#btn_tp').removeAttr('disabled', 'disabled');
        }  
    } else {
        $('.paso2').removeClass('disabled');
        $('.paso3').removeClass('disabled');
        $('.paso3').addClass('disabled');

        $('#medios_pago').removeClass('disabled');
        $('#medios_pago').addClass('disabled');
        $('#btn_mp').attr('disabled', 'disabled');
        $('#btn_tp').attr('disabled', 'disabled');
    }
    $nombre = $('input[name=nombre]').val();
    $('input#nombre_data').val($nombre);
});

$('input[name=nombre]').blur(function () {
    if($(this).val()!=""){ 
        jQuery('#alerta_datos').hide();
        $('#divnombre').removeClass('has-error');
    }else{
        jQuery('#alerta_datos').show();
        $('#divnombre').removeClass('has-error');
        $('#divnombre').addClass('has-error');
    }
});

$('input[name=telefono]').keyup(function () {
    if (($('input[name=dni]').val().length > 6) && ($('input[name=dni]').val().length <9) && ($('input[name=nombre]').val() != "") && ($('input[name=telefono]').val().length > 6) && ($('input[name=telefono]').val().length < 18)) {
        $('.paso3').removeClass('disabled');
        $('.paso2').removeClass('disabled');
        $('.paso2').addClass('disabled');
        $('#medios_pago').removeClass('disabled');
        $('#btn_mp').removeAttr('disabled', 'disabled');
        if ($('select[name=tipo_envio]').val()==73328||$('select[name=tipo_envio]').val()==73330){
            $('#btn_tp').attr('disabled', 'disabled')
        }else{
            $('#btn_tp').removeAttr('disabled', 'disabled');
        }
    } else {
        $('.paso2').removeClass('disabled');
        $('.paso3').removeClass('disabled');
        $('.paso3').addClass('disabled');

        $('#medios_pago').removeClass('disabled');
        $('#medios_pago').addClass('disabled');
        $('#btn_mp').attr('disabled', 'disabled');
        $('#btn_tp').attr('disabled', 'disabled');
    }
    $telefono = $('input[name=telefono]').val();
    $('input#telefono_data').val($telefono);
});

$('input[name=telefono]').blur(function () {
    if($(this).val().length>=7&&$(this).val().length<18){ 
        jQuery('#alerta_datos').hide();
        $('#telefono_add').removeClass('has-error');
    }else{
        jQuery('#alerta_datos').show();
        $('#telefono_add').removeClass('has-error');
        $('#telefono_add').addClass('has-error');
    }
});

$('.add-to-cart, .pro-add-to-cart').on('click', function () {
    add_to_cart(this);
});
$('.add-to-buy').on('click', function () {
    add_to_cart(this);
    var id = parseInt($(_this).parent().find('input[name=id]').val());
    window.location = 'cart?idProd=' + id;
});

function add_to_cart(_this) {
    $(_this).prop('disabled', true);
    var cart = $('#cart-icon');
    var imgtodrag = $(_this).closest('.item-producto').find("img").eq(0);
    var qty = parseInt($(_this).parent().find('.qty').val());
    var cantidad = 1;
    var id = parseInt($(_this).parent().find('input[name=id]').val());
    var id_color = parseInt($(_this).parent().find('input[name=id_color]').val());
    var id_talle = parseInt($(_this).parent().find('input[name=id_talle]').val());
    var nombre = $('#nombre').val();
    var sumar_cant = true;

    if (imgtodrag) {
        if ($('#product_id_' + id).length > 0) {
            sumar_cant = false;
        }
        cart_update(id, 'add', qty, id_color, id_talle, nombre);

        var imgclone = imgtodrag.clone()
            .offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            })
            .css({
                'opacity': '0.5',
                'position': 'absolute',
                'height': '150px',
                'width': '150px',
                'z-index': '1051'
            })
            .appendTo($('body'))
            .animate({
                'top': cart.offset().top,
                'left': cart.offset().left - 35,
                'width': 75,
                'height': 75
            }, 1000, 'easeInOutExpo');

        setTimeout(function () {
            cart.effect("shake", {
                times: 2
            }, 200);
            $(_this).prop('disabled', false);
        }, 1500);

        imgclone.animate({
            'width': 0,
            'height': 0,
            'margin-left': 75
        }, function () {
            $(this).detach()
        });
    }
}

function remove_to_cart(id) {
    cart_update(id, 'remove');
}

$(document).ready(function () {
    cart_update(0, 'get');
});

function cart_update(id, method, cantidad, id_color = 0, id_talle = 0, nombre = '') {
    /* event.preventDefault(); */
    $('.page-loader.l-cart').fadeIn();
    $.ajax({
        method: "POST",
        url: 'update_cart',
        data: { id: id, cantidad: cantidad, id_color: id_color, id_talle: id_talle, nombre: nombre, method: method },
        dataType: "json"
    })
        .done(function (data) {
            $('#product_id_' + id + '_' + id_color + '_' + id_talle).remove();
            $('.cart_box ul#cart-product-list').html(data.data);
            $('.cart_box .cart-total .subtotal').html(data.subtotal);
            $('.cart_box .cart-envio .price').html(data.envio);
            $('.cart_box .cart-total .total').html(data.total);
            $('.cart_box').find('.badge').html(data.cantidad);
            $('.cart-items').html(data.cantidad);
            $('#idProd').val(data.id);
            if (data.cantidad > 0) {
                $('.cart_box').find('.actions .btn-checkout').fadeIn();
                $('.cart_box').find('.box_prices').fadeIn();
                $('#idProd').val(data.id);
            } else {
                $('.cart_box').find('.actions .btn-checkout').fadeOut();
                $('.cart_box').find('.box_prices').fadeOut();
                $('#medios_pago').hide();
                $('#idProd').val(data.id);
            }

            if(method=='remove'){
                var url = window.location.pathname;
                console.log(url);
                var filename = url.substring(url.lastIndexOf('/')+1);
                console.log(filename);
                if(filename == 'procesar_pedido'){
                    window.location='procesar_pedido?id=1';
                }
            }

            $('.page-loader.l-cart').fadeOut();
        });
}