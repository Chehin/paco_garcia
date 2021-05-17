var appCustom = appCustom || {};


appCustom.REST_URL = 'rest/v1/';


//Wrapper  $.ajax for REST requests
appCustom.ajaxRest = function(url, method, data, successCallBack, async) {
    if (typeof async !== 'undefined') {
        if ('sync' === async.toLowerCase()) {
            async = false;
        } else {
            async = true;
        }
    } else {
        async = true;
    }
	
	appCustom.openModalPreloader();
    
    $.ajax( {
		"dataType": 'json',
		"type": method,
		"url": url,
		"data": data,
		"async": async,
		"success":  successCallBack,
		"error":function(xhr, status, error) {
			//(possibly) one user starts more than one session
			if (401 === xhr.status) {
				window.location = 'logout';
			} else { //another error code
				appCustom.smallBox(
					'nok', 
					'Error interno. No se pudo completar la operaci&oacute;n',
					'',
					'NO_TIME_OUT'
				);
			}


		},
		"complete":function() {
			appCustom.closeModalPreloader();
		}
	});
 };
 
 appCustom.smallBox = function (type, msg, title, timeout){
     
    var title = 'La operaci&oacute;n se complet&oacute; exitosamente!'; 
    var color = "#659265";
    var icon = 'check';
	
	type = type.toLowerCase(type);
    if ('nok' === type) {
        title = 'Se ha producido un error'; 
        color = "#C46A69";
        icon = 'warning';
    } else if ('warn' === type) {
        title = 'El proceso se ha completado con advertencias'; 
        color = "#DBB334";
        icon = 'warning';
    }
    
    var timeoutValue = 1500;
    if ('undefined' !== typeof timeout) {
        if ('NO_TIME_OUT' === timeout) {
            timeoutValue = null;
        } else {
            timeoutValue = timeout;
        }
    }
	//removes previous smallboxes
	$("#divSmallBoxes .SmallBox").remove();
     
    $.smallBox({
          title : title,
          content : "<i class='fa fa-clock-o'></i> <i>" + msg + "</i>",
          color : color,
          iconSmall : "fa fa-" + icon + " fa-2x fadeInRight animated",
          timeout : timeoutValue
    });
 };
 
 
 appCustom.hideModal = function  () {
    $('.modal').modal('hide');
    $('.modal').remove();
    $('.modal-backdrop').remove();
};

/**
 * Confirm Dialog
 *
 * yes/no Box Dialog, custom implementation.
 * 
 * @param string mensaje texto a mostrar en el cuadro de diálog
 * @param function yesCallback Función para llamar si eligió SI 
 * @param function noCallback Función para llamar si eligió NO (opcional)
 */
appCustom.confirmAction = function(msg,yesCallback,noCallback) {
	
	$.SmartMessageBox({
		title : "¡Atención!",
		content : msg || "This is a confirmation box. Can be programmed for button callback",
		buttons : '[No][Si]',
		placeholder : "Placeholder?"
	}, function(ButtonPressed) {
		if (ButtonPressed === "Si") {
			yesCallback();
		}
		if (ButtonPressed === "No") {
			if(noCallback){
				noCallback();	
			}
		}
	});
	
};

appCustom.openModalPreloader = function() {
	document.getElementById('modalPreloader').style.display = 'block';
	document.getElementById('fadePreloader').style.display = 'block';
};

appCustom.closeModalPreloader = function () {
    document.getElementById('modalPreloader').style.display = 'none';
    document.getElementById('fadePreloader').style.display = 'none';
};

appCustom.showModalRest = function(url, callback, data) {
	
	$('.modal').remove();
	
    appCustom.ajaxRest(
        url,
        'GET',
        data,
        function(result) {
            if (0 == result.status) {
				var modalOptions = {backdrop:'static', keyboard:false};
                var modal = $('<div class="modal fade">' + result.html + '</div>').modal(modalOptions);
                // Elimina del DOM el popup al cerrarlo   		
                modal.find('[data-dismiss="modal"]').click(function() {
                        setTimeout(function(){
                                $('.modal').remove();
                            }, 1000);
                    });

            } else {
				var type = 'nok';
				if (2 == result.status) {
					type = 'warn';
				}

				 appCustom.smallBox(
					 type,
					 result.msg,
					 null, 
					 'NO_TIME_OUT'
				 );
			}
            
            if ('function' === typeof callback) {
                callback();
            }
            
        }
    )
    ;
};

$(function(){
	 
	 // Modal window handler
     $(document).off('click','[data-toggle="modal-custom"]');
     $(document).on('click','[data-toggle="modal-custom"]', function(event) {
		event.preventDefault();

		var url = event.target.dataset.href;

		if (url) {

			// Se deshabilita el elemento para evitar la iteracion de solicitudes
			// en caso de que se haga más de un click
			var element = $(event.target);

			element
				.removeAttr('data-toggle')
				.removeAttr('data-href');

			appCustom.showModalRest(url, function(){
				element
					.attr('data-toggle', 'modal-custom')
					.attr('data-href', url)
				;
			}); 
		}
	});
});

//google map
appCustom.map = null;
//Fix google maps on boostrap modal 
//http://stackoverflow.com/questions/32216547/google-map-appear-with-grey-inside-modal-in-bootstrap
//http://stackoverflow.com/questions/8558226/recenter-a-google-map-after-container-changed-width
$(document).off('shown.bs.modal');
$(document).on('shown.bs.modal', function () {
	if ('undefined' !== typeof appCustom.map && appCustom.map) {
		var currCenter = appCustom.map.getCenter();
		google.maps.event.trigger(appCustom.map, "resize");
		appCustom.map.setCenter(currCenter);
	}
});

appCustom.num = new Intl.NumberFormat('es', {maximumFractionDigits: 2});

// add custom format to number js objects
Number.prototype.format = function(decPlaces, thouSeparator, decSeparator) {
	
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

appCustom.numberDecimalFormat = function(n) {
	
	if (!n) {
		return '0,00';
	} 
	
	var n = parseFloat(n);
	
	return n.format(2, '.', ',');
};


//image
appCustom.userImg = {};

appCustom.userImg.STORE = {'url':appCustom.REST_URL + 'userImg', 'verb':'POST'};
appCustom.userImg.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'userImg/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.userImg.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'userImg/' + id;
    },
    'verb': 'PUT'
};

appCustom.userImg.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'userImg/' + id;
    },
    'verb': 'DELETE'
};

