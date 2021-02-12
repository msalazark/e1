/*========================================================
	MAESTRIAS
=========================================================*/

jQuery(function ($) {
$('.span-acepto-form').on('click',function(){
 //$('.home-lnk-acepto').prop('checked', true);
  return false;
});
}); 

/******************/
/* Solicitar Información*/ 
function checkform(){
  jQuery(function ($) {
    $("#solicitud-de-informacion-mae input").keyup(function() {
      var form = $(this).parents("#solicitud-de-informacion-mae");
      var check = checkCampos(form);
      var politica=$('.home-lnk-acepto').val();
      var grado_academico=$("#grado_academico").val().trim();
      /*var industria_giro=$("#industria_giro").val().trim();
      var area=$("#area").val().trim();  */              
      if ( (check) && (grado_academico!='') /*&& (industria_giro!='') && (area!='')*/ && (politica=='1') ) {
        console.log('activa');
        $('.home-btn-masinformacion-solicitar').css({'background-color': '#d09b3e','border-color':'#d09b3e'});
        $('.home-btn-masinformacion-solicitar').removeAttr('disabled');
         }
      else {
        console.log('desactiva');
        $('.home-btn-masinformacion-solicitar').css({'background-color': '#666666'});
        $('.home-btn-masinformacion-solicitar').attr('disabled','disabled'); 
      }
    });
  });
}
//Función para comprobar los campos de texto
function checkCampos(obj) {
  jQuery(function ($) {
    var camposRellenados = true;
    obj.find("input").each(function() {
      var $this = $(this);
      if( $this.val().length <= 0 ) {
        camposRellenados = false;                        
        return false;
      }
    });

    if(camposRellenados == false ) {
      return false;
    }
    else {
      return true;
    }
  });
}

function validainputs(valor =null){
  jQuery(function ($) {
  var nombres=$("#nombres").val();
  var apaterno=$("#apaterno").val();
  var email=$("#email").val();
  var telefono=$("#telefono").val();
  var grado_academico=$("#grado_academico").val().trim();
  /*var empresa=$("#empresa").val();
  var industria_giro=$("#industria_giro").val().trim();
  var cargo=$("#cargo").val();
  var area=$("#area").val().trim();*/
  var terminos=$('input[name=policy]').is(':checked');
  if( (nombres!='') && (apaterno!='') && (email!='') && (telefono!='') && (grado_academico!='')/* && (empresa!='') && (industria_giro!='') && (cargo!='') && (area!='') */&& (terminos) ){
    //console.log('valida inputs');
              $('.home-btn-masinformacion-solicitar').css({'background-color': '#d09b3e','border-color':'#d09b3e'});
              $('.home-btn-masinformacion-solicitar').removeAttr('disabled');
            }else{         
              //console.log('NO valida inputs');
               $('.home-btn-masinformacion-solicitar').css({'background-color': '#666666'});
            $('.home-btn-masinformacion-solicitar').attr('disabled','disabled');
              
            }
  });
}

jQuery(function ($) {
$(document).ready(function(){
  $('.home-btn-masinformacion-solicitar').attr('disabled','disabled'); 

    $('#nombres').on('keyup change',function(){    
      
       var nombres=$(this).val().length;
       if(nombres>0){
         $('.valida-campo-nombre').hide();
         validainputs();
       }else{
         //validacionform('Nombre','inputvacio');
         $('.valida-campo-nombre').show();
         $('.valida-campo-nombre').html('Favor de ingresar su Nombre');
       }  
    });  

    $('#apaterno').on('keyup change',function(){
       //var apaterno=$(this).val().length;
       var nombres=$("#nombres").val().length;
       if(nombres>0){
         $('.valida-campo-nombre').hide();
         $('.valida-campo-apellido').hide();
         validainputs();
       }else{
         //validacionform('Nombre','inputvacio');
         $('.valida-campo-nombre').html('Favor de ingresar su Nombre');
         $('.valida-campo-nombre').show();
         $("#nombres").focus(); 
       }
        
    });
  
     $('#dni').on('keyup change',function(){
       //var dni=$(this).val().length;
       var apaterno=$("#apaterno").val().length;
       if(apaterno>0){
         $('.valida-campo-apellido').hide();
         $('.valida-campo-dni').hide(); 
       }else{
         //validacionform('Apellido','inputvacio');
         $("#apaterno").focus();
         $('.valida-campo-apellido').show();
         $('.valida-campo-apellido').html('Favor de ingresar su Apellido');
       } 
    });
  
  
  	$('#email').on('keyup change',function(){
       
       //var dni=$(this).val().length;
       var dni=$("#dni").val().length;
       if(dni>0){         
         if(dni>7){
           $('.valida-campo-dni').hide();  
           $('.valida-campo-email').hide();
         }else{
           $("#dni").focus();
           $('.valida-campo-dni').show();
           $('.valida-campo-dni').html('Favor de ingresar su DNI de forma correcta');
           
         }        
       }else{
         //validacionform('Dni','inputvacio');
         $("#dni").focus();
         $('.valida-campo-dni').show();
         $('.valida-campo-dni').html('Favor de ingresar su DNI');
       } 
    });  
  
    $('#telefono').keyup(function() {
       var email=$("#email").val().length;
       var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
       if(email>0){
           if (regex.test($('#email').val().trim())) { 
                 $('.valida-campo-email').hide();
                 $('.valida-campo-telefono').hide();
             	 validainputs();
            } else {
                 //$('.div-form-error').show();
                 //validacionform('','validaemail');
                 $("#email").focus();
                 $('.valida-campo-email').show();
         		 $('.valida-campo-email').html('Favor de ingresar su email de forma correcta');
            }
            //$('.div-form-error').hide();
       }else{
         //validacionform('Email','inputvacio');
          $("#email").focus();
         $('.valida-campo-email').show();
         $('.valida-campo-email').html('Favor de ingresar su email');
       }
    });
  
    $('#grado_academico').on('change',function(){ 
       var telefono=$("#telefono").val().length;
       if(telefono>0){         
         if(telefono>6){
           $('.valida-campo-telefono').hide();
           $('.valida-campo-grado').hide();
         }else{
           $("#telefono").focus();
           $('.valida-campo-telefono').show();
           $('.valida-campo-telefono').html('Favor de ingresar su telefono de forma correcta');
         }        
       }else{
         $("#telefono").focus();
         $('.valida-campo-telefono').show();
         $('.valida-campo-telefono').html('Favor de ingresar su telefono');
       }    
    });
  
  
    $('#empresa').on('keyup change',function(){  
       var grado=$("#grado_academico").val().length;
       if(grado>0){
         $('.valida-campo-grado').hide();
         $('.valida-campo-empresa').hide();
         validainputs();
       }else{
         //$('.div-form-error').show();
         //validacionform('Grado Académico','inputvacio');
         $("#grado_academico").focus();
         $('.valida-campo-grado').show();
         $('.valida-campo-grado').html('Favor de ingresar su grado académico');
       }  
    });   
  
    $('#industria_giro').on('change',function(){        
      var empresa=$("#empresa").val().length;
       if(empresa>0){
         $('.valida-campo-empresa').hide();
         $('.valida-campo-industria').hide();
         validainputs();
       }else{
         //validacionform('Empresa','inputvacio');
         $("#empresa").focus();
         $('.valida-campo-empresa').show();
         $('.valida-campo-empresa').html('Favor de ingresar su empresa');
       }
    });     
  

    $('#cargo').on('keyup change',function(){   
      var industria_giro=$("#industria_giro").val().length;
       if(industria_giro>0){
         $('.valida-campo-industria').hide();
         $('.valida-campo-cargo').hide();
         validainputs();
       }else{
         //$('.div-form-error').show();
         //validacionform('Giro de la empresa','inputvacio');
         $("#industria_giro").focus();
         $('.valida-campo-industria').show();
         $('.valida-campo-industria').html('Favor de ingresar el giro de su empresa');
       }   
    });    

    $('#area').on('change',function(){
      var cargo=$("#cargo").val().length;
       if(cargo>0){
         $('.valida-campo-cargo').hide();
         $('.valida-campo-area').hide();
         validainputs();
       }else{
         //validacionform('Cargo','inputvacio');
         $("#cargo").focus();
         $('.valida-campo-cargo').show();
         $('.valida-campo-cargo').html('Favor de ingresar su cargo');
       } 
    });
         
    $('.home-lnk-acepto').on('change', function(){
       console.log($(this).val());
          if($(this).val() =='1'){            
            validainputs();
            //console.log('cambia color');
          }else{
             $('.home-btn-masinformacion-solicitar').css({'background-color': '#666666'});
            $('.home-btn-masinformacion-solicitar').attr('disabled','disabled');
          }
    }); 

  
    $('#dni').on('keypress',function(tecla) {
        var dni=$(this).val().length;
        if(dni>7) return false;
        if(tecla.charCode < 48 || tecla.charCode > 57) return false;
    });   
    
    $('#telefono').on('keypress',function(tecla) {
        var telefono=$(this).val().length;
        if(telefono>=9) return false;
        if(tecla.charCode < 48 || tecla.charCode > 57) return false;
    });
  
  

  /** Bloqueo de letras para telefono y dni **/
  
$('#dni').on('input', function (event) {
this.value = this.value.replace(/[^0-9.]/g, '');
});  
  
$('#telefono').on('input', function (event) {
this.value = this.value.replace(/[^0-9.]/g, '');
});  
  
  

  $("#enviar").on("click",function(e){
    e.preventDefault();    
    
    if($("#nombres").val().length < 1) {
      	$("#nombres").focus();
        //$('.div-form-error span').show();
        validacionform('Nombre','inputvacio');
        return false;  
    } else   
    if($("#apaterno").val().length < 1) {
      	$("#apaterno").focus();
        validacionform('Apellido','inputvacio');
        return false;  
    } else
    if($("#email").val().length < 1) {
        $("#email").focus();
      	validacionform('Email','inputvacio');
        return false;  
    } else
    if($("#telefono").val().length < 1) {
      	$("#telefono").focus();
        validacionform('Telefono','inputvacio');
    } else     
    if ($('#grado_academico').val().trim() === ''){
        $("#grado_academico").focus();
        validacionform('grado académico','inputvacio');
        return false;  
    }/* else   
    if($("#empresa").val().length < 1) {
        $("#empresa").focus();
      	validacionform('empresa','inputvacio');
        return false;  
    } else
    if($("#industria_giro").val().trim() === '') {
      	validacionform('giro de la empresa','inputvacio');
        return false;  
    } else
    if($("#cargo").val().length < 1) {
      	$("#cargo").focus();
       validacionform('cargo','inputvacio');
        return false;  
    } else
    if($("#area").val().trim() === '') {
      	$("#area").focus();
        validacionform('área','inputvacio');
        return false;  
    } */else {
    $('.div-form-error span').hide();
    $('.msj-error-check').hide();
    var programa;
    var gracias;
    var local_ciudad;
    var local_modalidad;
    local_ciudad=localStorage.getItem("Ciudad");
    local_modalidad= localStorage.getItem("Modalidad");
    var posicion_modalidad=local_modalidad.indexOf(':');
    var url_formulario=window.location;  

    if (local_ciudad=='Lima'){
      if (posicion_modalidad > 0)  { 
          programa=local_modalidad.substring(0,posicion_modalidad); 
      }else{
          programa=local_modalidad;
      }

      if (programa=='Tiempo completo'){
        gracias="https://www.esan.edu.pe/maestrias/direccion-de-tecnologias-de-informacion/solicitud-de-informacion/gracias/";
      } /*else if (programa=='Tiempo Parcial'){
        gracias="https://www.esan.edu.pe/mba/tiempo-parcial/solicitud-de-informacion/gracias";
      } else {
        gracias="https://www.esan.edu.pe/mba/weekends/solicitud-de-informacion/gracias";
      */
    }else{
      programa="en el Perú "+local_ciudad;
      gracias="https://www.esan.edu.pe/maestrias/direccion-de-tecnologias-de-informacion/solicitud-de-informacion/gracias/";

    }


    $("#ciudad").val(local_ciudad);
    $("#Programa").val("MBA "+programa);
    $("#Gracias").val(gracias);
    $("#Url_formulario").val(url_formulario);
  
    
    //var vardom = document.querySelector(".seccion-principal-solicitainformacion form");//comentado por nueva funcion
    window.sendFormDataToESAN_SolicitudDeInformacionMaestria();    
   
    }

  });
 });
});

/*******************************/

/******* Cambio de valores en los checkbox ***********/

jQuery(function ($) {
$('#aceptacion-de-politica-de-proteccion-de-datos').on('change', function(){
   this.value = this.checked ? 1 : 0;
   
}).change();
$('#estaConferencia').on('change', function(){
   this.value = this.checked ? 1 : 0;
   // alert(this.value);
}).change();  
  
$('input[type="checkbox"]').on('change', function(){
   this.value = this.checked ? 1 : 0;
   // alert(this.value);
}).change();  
  
});



/* Solicitud de Informacion
window.sendFormDataToESAN = function (form, is_async) {}
Envía datos del formulario a: https://staging.esanbackoffice.com/websites/products/information-request/
*/
jQuery(function ($) {
(function () {
	var getXHR = function () {
		if (window.XMLHttpRequest) {
			return new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			return new ActiveXObject('Microsoft.XMLHTTP');
		}
	};

	var sendData = function (data, is_async) {
		var xhr = getXHR();
		var url_gracias=$("#Gracias").val();
		xhr.open('POST', 'https://staging.esanbackoffice.com/websites/products/information-request/', is_async);
		xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
		xhr.send(JSON.stringify({timestamp: new Date().toJSON(), payload: data}));
		if (xhr.status == 200){
		//location.href=url_gracias;
          console.log('redireccionando :'+url_gracias);
		}
	};

	var collectData = function (fields) {
		var data = {};

		var fields_optional = [
			'apellido_materno',
			'tipo_de_id',
			'edad',
			'exalumno',
			'curso',
			'meses_para_llevarlo',
			'procedencia'
		];

		var fields_int = [
			'edad',
			'academic_degree_code',
			'meses_para_llevarlo'
		];

		var getValue = function (config) {
			if (config === '' || config === null || config === undefined) {
				return '';
			}

			if (typeof config == 'string') {
				return config;
			}

			var source    = config[0];
			var selector  = config[1];
			var attribute = config[2] || 'value';

			// FROM COOKIE
			if (source == 'COOKIE') {
				var pattern = '(^|;\\s*)' + selector + '=([^;]+)';
				var matches = document.cookie.match(new RegExp(pattern));

				if (!matches) {
					console.log('NOTICE (sendFormDataToESAN): "' + selector + '" cookie not found');
				}

				return matches ? matches[2] : '';

			// FROM LOCAL-STORAGE
			} else if (source == 'LOCAL-STORAGE') {
				var value = [];

				selector.forEach(function (k) {
					var v = localStorage.getItem(k);

					if (v) {
						value.push(v);
					} else {
						console.log('NOTICE (sendFormDataToESAN): "' + selector + '" item not found in localStorage');
					}
				});

				return value.join(' - ');

			// FROM DOM ELEMENT
			} else {
				var el = source.querySelector(selector);

				if (el && el.type && el.type == 'checkbox') {
					return el.checked ? '1' : '0';
				}

				if (!el) {
					console.log('NOTICE (sendFormDataToESAN): "' + selector + '" element not found');
				}

				return el ? el[attribute] : '';
			}
		};


		// ---------------------------------------------------------------------

		var k, value;

		for (k in fields) {
			value = getValue(fields[k])

			// optional field
			if (value === '' && fields_optional.includes(k)) {
				continue;
			}

			// int fields
			if (fields_int.includes(k)) {
				value = parseInt(value) || 0;

			} else {
				value = value .replace(/[^\u{0020}-\u{007E}\u{00A0}-\u{00FF}\u{000A}\u{000D}]/gu, '').replace(/^\s+|\s+$/, '');
			}

			//
			data[k] = value;
		}

		data.curso = 'MBA - ' + data.curso;

		if (data.conferencia == '0') {
			data.datos_de_la_conferencia = '';
		}

		// ---
		return data;
	};


	// ---------------------------------------------------------------------------------------------


	/*window.sendFormDataToESAN = function (fields, is_async) {
		sendData(collectData(fields), is_async);
	};*/

	window.sendFormDataToESAN_SolicitudDeInformacionMaestria = function (is_async) {
		var form = document.querySelector('#solicitud-de-informacion-mae');

		var fields = {
		//	campo_json                    : [ objecto-de-donde-obtener-campo, selector-de-campo, attributo-de-campo-a-usar]

			nombres                       : [form, 'input[name="nombres"]'],
			apellido_paterno              : [form, 'input[name="apaterno"]'],
			apellido_materno              : null,
			tipo_de_id                    : null,
			numero_de_id                  : [form, 'input[name="dni"]'],
			user_agent_uuid               : ['COOKIE', 'user_agent_uuid'],

			edad                          : null,
			academic_degree_code          : [form, 'select[name="grado_academico"]'],
			exalumno                      : null,

			correo_electrnico             : [form, 'input[name="email"]'],
			telefono                      : [form, 'input[name="telefono"]'],

			acepta_politica_de_privacidad : [form, 'input[name="policy"]'],

			empresa                       : null,//[form, 'input[name="empresa"]'],
			job_industry_code             : null,//[form, 'select[name="industria_giro"]'],
			cargo                         : null,//[form, 'input[id="cargo"]'],
			job_function_code             : null,//[form, 'select[name="area"]'],

			ciudad                        : [document, 'input[name="ciudad"]'],
			programa                      : [document, 'input[name="programa"]'],
			curso                         : ['LOCAL-STORAGE', ['Ciudad', 'Modalidad']],
			//consulta                      : [form, 'textarea[id="consulta"]'],

			meses_para_llevarlo           : null,

			conferencia                   : null,//[form, 'input[name="estaConferencia"]'],
			datos_de_la_conferencia       : null,//[form, 'input[id="DatosConferencia"]'],

			como_te_enteraste             : [form, 'select[name="encuesta"]'],

			url_del_formulario            : [document, 'input[name="url_formulario"]'],

			procedencia                   : ['COOKIE', 'traffic_source']
		};

		sendData(collectData(fields), is_async);
	};

})();



});