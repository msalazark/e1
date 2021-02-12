/*========================================================
	MAESTRIAS
=========================================================*/
jQuery(function ($) {
 /* 
 validaFormulario('informacion','btn-solicitarmasinformacion','d09b3e','666666');
 soloNumeros('nroDocumento');
 soloNumeros('telefono'); 
 cantidadMaxCampo('nroDocumento',null,8);
 cantidadMaxCampo('telefono',null,9);
  *//*
  $("#enviar-maestria").on("click",function(e){
    e.preventDefault();    
	alert('pruebas custom');
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

      gracias="https://www.esan.edu.pe/maestrias/direccion-de-tecnologias-de-informacion/solicitud-de-informacion/gracias";
      
    }else{
      programa="en el Perú ";
      gracias="https://www.esan.edu.pe/maestrias/direccion-de-tecnologias-de-informacion/solicitud-de-informacion/gracias";

    }


    $("#ciudad").val(local_ciudad);
    $("#Programa").val("MAESTRIAS "+programa);
    $("#Gracias").val(gracias);
    $("#Url_formulario").val(url_formulario);
  
    
    //var vardom = document.querySelector(".seccion-principal-solicitainformacion form");//comentado por nueva funcion
    window.sendFormDataToESAN_SolicitudDeInformacionMaestria();    

  });*/
});

/*=========================================================
MBA
==========================================================*/

/*Bloquear esc modales*/

/* Popups Solicitud de Informacion de los banners - internas , se movio al ajax*/
/*jQuery(function ($) {
  $('.sppb-modal-selector').magnificPopup({
  	type:'inline',
    closeOnBgClick :false,
    enableEscapeKey : false,
  	midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  });
});
*/


/* Suscripción, enviar*/
jQuery(function ($) {
  $(".footer-btn-suscripcion #btn-1562992862411").on("click",function(e){
    e.preventDefault(); 

    var programa;
    var gracias="";
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

    }else{
      programa="en el Perú "+local_ciudad;      

    }


    $("#ciudad").val(local_ciudad);
    $("#Programa").val("MBA "+programa);
    $("#Gracias").val(gracias);
    $("#Url_formulario").val(url_formulario);
  
    
    //var vardom = document.querySelector(".seccion-principal-solicitainformacion form");//comentado por nueva funcion
    window.sendFormDataToESAN_Suscripcion();      

  });
});


jQuery(function ($) {
$('.span-acepto-form').on('click',function(){
 //$('.home-lnk-acepto').prop('checked', true);
  return false;
});
}); 
/***** boton flotante responsive *******/
jQuery(function ($) {
/*
  $(function () {
  $('[data-toggle="tooltip"]').tooltip({ trigger: 'click' });*/
  
  $('div[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    trigger: 'click'
});
});


/******** activar boton footer *********/
jQuery(function ($) {
  $(document).ready(function(){

	   $('.footer-btn-suscripcion button').attr('disabled','disabled');
       $('#form-1562992862413-checkbox-0').removeAttr("required");
       $('.footer-bloque-suscripcion input[type="email"]').keypress(function(){
              var politica = $('#policy-1562992862411').val();
              if($(this).val() != ''){  
                 if(politica=='1'){
                    $('.footer-btn-suscripcion button').css({'background-color': '#d09b3e'});
                    $('.footer-btn-suscripcion button').removeAttr('disabled');      
                 }
              }
       });
       $('#policy-1562992862411').on('change', function(){
           var email = $('.footer-bloque-suscripcion input[type="email"]').val();
           if($(this).val() == '1'){
              if(email!=''){
                $('.footer-btn-suscripcion button').css({'background-color': '#d09b3e'});
                $('.footer-btn-suscripcion button').removeAttr('disabled');
              }/*else{
                 $('.footer-btn-suscripcion button').css({'background-color': '#999999'});
                 $('.footer-btn-suscripcion button').attr('disabled','disabled');
              }*/
           }else{
               $('.footer-btn-suscripcion button').css({'background-color': '#999999'});
               $('.footer-btn-suscripcion button').attr('disabled','disabled');
           }
       });  

    });
});

/***********/
jQuery(function ($) {
  
$(".tab-solcitud-informacion ul li:first-child").on('click',function(){
});
  
$(".tab-solcitud-informacion ul li:last-child").on('click',function(){    
    $('.tab-solcitud-informacion .tab1 input').prop('checked', true);
});
  

  });


/***********/

jQuery(function ($) {
  
$(".tab-solcitud-informacion ul li a").before('<div class="round"> <input type="checkbox" id="checkbox" /> <label for="checkbox"></label></div>');

  $(".tab-solcitud-informacion ul li input").addClass("formulario-internas-checkbox-tab");
  $(".tab-solcitud-informacion ul li:last-child").addClass('tab2');
    $(".tab-solcitud-informacion ul li:first-child").addClass('tab1');

  });

/* Ajusta imagen solicitud informacion - Home*/

jQuery(function ($) {
   //$(document).ready(function(){
   $(window).on( "load", function() {
          var height = $('.seccion-principal-solicitainformacion').height();
  		  var formulario = $('#formulario').outerHeight();
  		  var imgsolh = $('.fotos-derecha .imgsolicitud2').height();          
          var imgsolw = $('.fotos-derecha').width();
  		  var imgcustom = ((height - 80)*imgsolw)/imgsolh;
          var margincustom= imgcustom/3;
          var fotoresph=$('.imagen-fomulario-responsive img').height();   
  		  var margincustomresp= fotoresph/3;
          //var marginimg= height - (height/2);	 
  			/*console.log("imgsolh :"+imgsolh);
  			console.log("height :"+height);
  			console.log("width :"+imgsolw);*/
  		  if (screen.width >= 992) {
            $('.fotos-derecha .imgsolicitud2').css({
                 'margin-top':'40px',
                 'margin-bottom':'40px',
                 'height':height-80,
                 'width':imgcustom,
                 'max-width':imgcustom,
                 'margin-left' : -margincustom
            });
            $('.formulario-izquierda').css({'padding-right': margincustom+10});            
          }else{
             $('.imagen-fomulario-responsive .sppb-addon-single-image-container').css({
                 'margin-bottom' : -margincustomresp
            });
            $('#formulario').css({'padding-top': margincustomresp});  
          }
});
});


/********************/

/* Popups descarga - malla curricular - Comentado temporalmente el 26012020 para que funciene maestrías*/
jQuery(function ($) {
/*$('.home-lnk-duracion-descargar-flotante').magnificPopup({
  type:'inline',
  midClick: true
});
  */
$('.btn-custom-flotante1').on('click',function(){
  var local_ciudad = localStorage.getItem("Ciudad");
  var local_modalidad = localStorage.getItem("Modalidad"); 
  var margintop=80;
  if ( ((local_ciudad=='') || (local_ciudad==null)) && ((local_modalidad=='') || (local_modalidad==null)) ){
    margintop=$('.seccion-principal-inicio').height() + 80;
  }
  $('html,body').animate({
                  scrollTop: $(".seccion-principal-solicitainformacion").offset().top - margintop
  },2000);
}); 
  
});

/******* titulo banner principal - h1 *****/

jQuery(function ($) {
  $(document).ready(function(){
    var resolucion=screen.width;
    var titulo;
    titulo="MBA en ESAN";
    $(".titulo-banner-h1").after("<h1 class='sppb-addon-title'>MBA en ESAN</h1>");
    if(resolucion>=992){
    	$(".titulo-banner-h1").html("<h1 class='sppb-addon-title'>MBA en ESAN</h1>");
    }else {
      $(".titulo-banner-h1-res h1").val(titulo);
    }
 });   
});

/* Solicitar de credito, enviar*/
jQuery(function ($) {
  $("#enviar-solicitud-credito").on("click",function(e){
    e.preventDefault(); 
    if($("#email").val().length < 1) {
      	$("#email").focus();
        $('.div-form-error span').show();
        return false;  
    }/* else if($("#aceptacion-de-politica-de-proteccion-de-datos").val()==0) {
      	$("input[name^=policy]")[0].focus();
        $('.msj-error-check').show();
    }*/ else {         
    $('.div-form-error span').hide();
    $('.msj-error-check').hide();
    var programa;
    var gracias="";
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

    }else{
      programa="en el Perú "+local_ciudad;      

    }


    $("#ciudad").val(local_ciudad);
    $("#Programa").val("MBA "+programa);
    $("#Gracias").val(gracias);
    $("#Url_formulario").val(url_formulario);
  
    
    //var vardom = document.querySelector(".seccion-principal-solicitainformacion form");//comentado por nueva funcion
    window.sendFormDataToESAN_SolicitudDeCredito();    
   
    }

  });
});


/* Popups Solicitud de Credito de la sección de Inversión - Home - Comentado temporalmente el 26012020 para que funciene maestrías*/
/*jQuery(function ($) {
  $('.popup-link-solicitud-credito').magnificPopup({
  	type:'inline',
  	midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  });
});*/

/******************/
/********** solicitud de informacion independiente en la plantilla **************/

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

/* Popups Footer - Terminos y condiciones - Comentado temporalmente el 26012020 para que funciene maestrías*/
/*jQuery(function ($) {
  $('.open-popup-terminos').magnificPopup({
  	type:'inline',
    midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  });
});*/
/******************/

/*Agregar href a la interna de requisitos*/
jQuery(function ($) {
 $(document).ready(function(){
  var codigo_modalidad;
  var local_ciudad = localStorage.getItem("Ciudad");
  var local_modalidad = localStorage.getItem("Modalidad");
  if (local_modalidad=='Tiempo completo') {codigo_modalidad='TCO';}
  if (local_modalidad=='Tiempo Parcial: (Miércoles y sábado)') {codigo_modalidad='TPA';}
  if (local_modalidad=='Weekends: fines de semana cada quince días') {codigo_modalidad='WFS';}
  if (local_modalidad=='Weekends: Regiones') {codigo_modalidad='WRE';}
  
  if( (local_ciudad=='Lima') && (codigo_modalidad='TCO') ){
     $(".inversion-monto-dinamico").html('S/72,500');
     $(".requisitos-btn-preinscribirme-dinamico").attr("href", "http://inscripciononline.esan.edu.pe/postulante/inscripcion/index/matc");
  }else
   
  if( (local_ciudad=='Lima') && (codigo_modalidad='TPA') ){
     $(".inversion-monto-dinamico").html('S/91,900');
     $(".requisitos-btn-preinscribirme-dinamico").attr("href", "http://inscripciononline.esan.edu.pe/postulante/inscripcion/index/matp");
  }else
   
  if( (local_ciudad=='Lima') && (codigo_modalidad='WFS') ){
     $(".inversion-monto-dinamico").html('S/94,500');
     $(".requisitos-btn-preinscribirme-dinamico").attr("href", "http://inscripciononline.esan.edu.pe/postulante/inscripcion/index/matp-we");
  }else
  
  if( (local_ciudad=='Arequipa') ){
     $(".inversion-monto-dinamico").html('S/72,500');
     $(".requisitos-btn-preinscribirme-dinamico").attr("href", "http://inscripciononline.esan.edu.pe/postulante/inscripcion/index/matp-we");
  } else
   
  if( (local_ciudad=='Huancayo') ){
     $(".inversion-monto-dinamico").html('S/72,500');
     $(".requisitos-btn-preinscribirme-dinamico").attr("href", "http://inscripciononline.esan.edu.pe/postulante/inscripcion/index/matp-we");
  } else
   
  if( (local_ciudad=='Trujillo') ){
     $(".inversion-monto-dinamico").html('S/72,500');
     $(".requisitos-btn-preinscribirme-dinamico").attr("href", "http://inscripciononline.esan.edu.pe/postulante/inscripcion/index/matp-we");
  }  else
   
  if( (local_ciudad=='Cusco') ){
     $(".inversion-monto-dinamico").html('S/72,500');
     $(".requisitos-btn-preinscribirme-dinamico").attr("href", "http://inscripciononline.esan.edu.pe/postulante/inscripcion/index/matp-we");
  }  else
    
  {
    $(".requisitos-btn-preinscribirme-dinamico").attr("href", "#");
  }  
   
   
   
});
});


/************** Validacion maestrias y mbas -v2 ***********/

/*Agregar href al logo y al inicio del breadcrumbs*/
jQuery(function ($) {
  $(".logo a").attr("href", "./");
  $('.header-back').append('<a href="./" class="linkhome"></a>');
  $('.header-back').append('<a href="/mbas" class="linkback">b</a>');
  $('.linkback').css({'background-image':'url("/images/mba/general-flecha_superior.png")','background-repeat':'no-repeat','position':'absolute','top':'35px','height':'20px','color':'transparent','left':'10%'})
});


/* Swipe*/
jQuery(function ($) {
  $('.sppb-carousel').each(function (i, el) {
    $(el).on("touchstart",{ passive: true }, function(event){
      var xClick = event.originalEvent.touches[0].pageX;
      $(this).one("touchmove", function(event){
        var xMove = event.originalEvent.touches[0].pageX;
         if( Math.floor(xClick - xMove) > 5 ){
            $(this).sppbcarousel('next');
         } else if( Math.floor(xClick - xMove) < -5 ){
            $(this).sppbcarousel('prev');
         }
      });
    });
  });
});


/*Agregar clase identificador a los Popups y formularios*/
jQuery(function ($) {
  //Popups
  	$(".white-popup-block").addClass('modal-popup-general');
  //Capturar valores de input - formularios
	var id_nombres=$("input[name='form-builder-item-[nombres_apellidos*]']").attr('id');
    var id_email=$("input[name='form-builder-item-[email*]']").attr('id');
    var id_nrodoc=$("input[name='form-builder-item-[nro_doc*]']").attr('id');
    var id_celular=$("input[name='form-builder-item-[celular*]']").attr('id');
    var id_tipodoc=$("select[name='form-builder-item-[tipo_doc*]']").attr('id');
    var id_gradoacademico=$("select[name='form-builder-item-[grado_academico*]']").attr('id');
    var id_programa=$("select[name='form-builder-item-[programa*]']").attr('id');
    var id_conferencia=$("input[name='form-builder-item-[asistire]']").attr('id');
    $('#'+id_nombres).addClass('form-input-nombresapellidos');
    $('#'+id_email).addClass('form-input-email');
    $('#'+id_nrodoc).addClass('form-input-nrodoc');
    $('#'+id_celular).addClass('form-input-celular');
    $('#'+id_tipodoc).addClass('form-select-tipodoc');
    $('#'+id_gradoacademico).addClass('form-select-gradoacademico');
    $('#'+id_programa).addClass('form-select-programa');
    $('#'+id_conferencia).addClass('form-input-conferencia');
  //Capturar valores del bloque de Suscripción
    var id_suscribirme=$("input[name='form-builder-item-[*]']").attr('id');
 	$('#'+id_suscribirme).addClass('form-chk-suscribirme');
  //Agregar un span antes del checkbox de conferencia informativa.form-input-conferencia
    $('.sppb-form-builder-field-10 .form-builder-checkbox-item').prepend('<span>Conferencia informativa</span>');
});

/*Agregar href a los iconos de las internas de la parte superior*/
/* comentados temporalmente 191119 */
jQuery(function ($) {
  $("#sp-title").click(function() {
    url = "./";
    $(location).attr('href',url);
  });
});

/* Pagina Principal - Banner - resize seccion principal y ajustar div a toda la pantalla - Responsive*/
jQuery(function ($) {
          var heightres = $(window).height();
		  var heightInicioResponsive = $('.principal-inicio-filtros-responsive').outerHeight();
		  $('.principal-inicio-logo-responsive').css({'height':heightres-heightInicioResponsive});
		  $('.principal-inicio-logo-responsive .sppb-container-inner').css({
			   left: ($('.principal-inicio-logo-responsive').width() - $('.principal-inicio-logo-responsive .sppb-container-inner').outerWidth())/2,
			   top: ($('.principal-inicio-logo-responsive').height() - $('.principal-inicio-logo-responsive .sppb-container-inner').outerHeight())/2
		  });
          $('.seccion-principal-inicio-mobile').height(heightres);
});

/* Pagina Principal - Banner - resize seccion principal y ajustar div a toda la pantalla - Desktop */

jQuery(function ($) {
	var height = $(window).height();
	var imgHeightReal=550;
	var imgWidthReal=945;
	var imgWidthResol=screen.width;
	var widthDefecto=imgWidthResol*0.52;
    var imgHeightCustom=(widthDefecto*imgHeightReal)/imgWidthReal;
	
	if (imgWidthResol<=1024){
		$('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'14px','padding':'5px 6px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'10px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'10px 0px 35px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'20px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'20px'});
	}else if( (imgWidthResol>1024) && (imgWidthResol<=1200) ){
		$('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'14px','padding':'5px 6px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'20px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'20px 0px 25px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'25px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'25px'});
	}else if( (imgWidthResol>1200) && (imgWidthResol<1365) ){
		$('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'15px','padding':'5px 6px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'30px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'30px 0px 25px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'30px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'30px'});
	}
	else{
		$('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'16px','padding':'8px 12px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'50px 0px 30px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'50px 0px 30px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'30px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'30px'});
	}
	
	$('.seccion-principal-inicio-filtros').css({'height':imgHeightCustom});
	
    $(window).resize(function(){
	  var heightResize=$(window).height();
      var imgWidthResize=$(window).width();
      var widthDefectoResize=imgWidthResize*0.52;
      var imgHeightCustomResize=(widthDefectoResize*imgHeightReal)/imgWidthReal;
      /*console.log("Ancho screen: "+screen.width+" ancho Resize:"+imgWidthResize);*/
	
	  if (imgWidthResize<=1024){
		$('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'14px','padding':'5px 6px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'10px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'10px 0px 35px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'20px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'20px'});
	  }else if( (imgWidthResize>1024) && (imgWidthResize<=1200) ){
	    $('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'14px','padding':'5px 6px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'20px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'20px 0px 25px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 15px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'25px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'25px'});
	  }else if( (imgWidthResize>1200) && (imgWidthResize<1365) ){
		$('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'15px','padding':'5px 6px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'30px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'30px 0px 25px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'30px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'30px'});
	  }else {
		$('.seccion-principal-inicio-filtros .sppb-btn-custom').css({'font-size':'16px','padding':'8px 12px'});
		$('.seccion-principal-inicio-filtros .titulo-ciudad').css({'margin':'50px 0px 30px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-modalidad').css({'margin':'50px 0px 30px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-ciudad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .home-btngroup-modalidad').css({'margin':'0px 0px 20px 0px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio1').css({'font-size':'30px'});
		$('.seccion-principal-inicio-filtros .titulo-inicio2').css({'font-size':'30px'});
	  }

      $('.seccion-principal-inicio-filtros').css({'height':imgHeightCustomResize});
	  var heightInicioResize = $('.seccion-principal-inicio-filtros').height();
	  $('.principal-inicio-logo').css({'height':heightResize-heightInicioResize});
		  $('.icono-logo').css({
			   position:'absolute',
			   left: ($('.principal-inicio-logo').width() - $('.icono-logo img').outerWidth())/2,
			   top: ($('.principal-inicio-logo').height() - $('.icono-logo').outerHeight())/2
		  });
	  $('.seccion-principal-inicio').height(heightResize);

    });
	
	var heightInicio = $('.seccion-principal-inicio-filtros').height();
	$('.principal-inicio-logo').css({'height':height-heightInicio});
	  $('.icono-logo').css({
		   position:'absolute',
		   left: ($('.principal-inicio-logo').width() - $('.icono-logo img').outerWidth())/2,
		   top: ($('.principal-inicio-logo').height() - $('.icono-logo').outerHeight())/2
	  });
	$('.seccion-principal-inicio').height(height);
});

/************* Validacion mbas y maestrias - v1 *******************/
/* Pagina Principal - Banner - resize texto y foto*/

jQuery(function ($) {
  $(document).ready(function(){
	var imgHeightReal=962;
	var imgWidthReal=1900;
    var imgWidthResol=screen.width;
    var widthDefecto=imgWidthResol*0.50;
    var imgHeightCustom=(widthDefecto*imgHeightReal)/imgWidthReal;
	
	
	if (imgWidthResol>=1450){
		$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
        $('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'10%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'0px','margin-bottom':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'0px','margin-top': '0px'});	
        $('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'100%'});
        $('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'16px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'0px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'16px','margin-top':'0px'});
		$('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'16px'});
	}else if ( (imgWidthResol>=1200) && (imgWidthResol<1450) ){
		$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
        $('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'10%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'0px','margin-bottom':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'-10px','margin-top': '-15px'});	
        $('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'80%'});
        $('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'15px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'0px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'15px','margin-top':'0px'});
		$('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'15px'});
	}else if ( (imgWidthResol>=1024) && (imgWidthResol<1200) ){
		$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
        $('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'5%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'-10px','margin-bottom':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'-20px','margin-top': '-15px'});	
        $('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'70%'});
        $('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'14px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'-10px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'14px','margin-top':'-10px'});
	    $('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'14px'});
	}else{
		$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
		$('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'5%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'-10px','margin-bottom':'0px'});
		$('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'-15px','margin-top': '-20px'});	
		$('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'50%'});
		$('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'14px'});
		$('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'-10px'});
		$('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'-15px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'14px','margin-top':'-10px'});
		$('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'14px'});
    }
    
    /* Resize*/
    $(window).resize(function(){

      //aqui el codigo que se ejecutara cuando se redimencione la ventana
      var alto=$(window).height();
	  var heightResize=$(window).height();
      var imgWidthResize=$(window).width();
      var widthDefectoResize=imgWidthResize*0.50;
      var imgHeightCustomResize=(widthDefectoResize*imgHeightReal)/imgWidthReal;
      /*console.log("Ancho screen: "+screen.width+" ancho Resize:"+imgWidthResize);*/

	if (imgWidthResize>=1450){
		//$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
        $('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'10%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'0px','margin-bottom':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'0px','margin-top': '0px'});	
        $('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'100%'});
        $('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'16px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'0px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'16px','margin-top':'0px'});
		$('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'16px'});   	
	}else if ( (imgWidthResize>=1200) && (imgWidthResize<1450) ){
		//$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
        $('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'10%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'0px','margin-bottom':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'-10px','margin-top': '-15px'});	
        $('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'80%'});
        $('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'15px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'0px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'15px','margin-top':'-10px'});
		$('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'15px'});    	
	}else if ( (imgWidthResize>=1024) && (imgWidthResize<1200) ){
		//$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
        $('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'5%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'-10px','margin-bottom':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'-20px','margin-top': '-15px'});	
        $('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'70%'});
        $('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'14px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'0px'});
        $('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'-10px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'14px','margin-top':'-10px'});  
		$('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'14px','margin-top':'-10px'});
	}else{
		//$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustom});
		$('.seccion-bannerprincipal-informacion-desktop').css({'padding-right':'5%'});
		$('.seccion-bannerprincipal-desktop .breadcrumb').css({'margin-top':'-10px','margin-bottom':'0px'});
		$('.seccion-bannerprincipal-informacion-desktop-logo').css({'margin-bottom':'-15px','margin-top': '-20px'});	
		$('.seccion-bannerprincipal-informacion-desktop .sppb-img-responsive').css({'width':'50%'});
		$('.seccion-bannerprincipal-informacion-desktop .sppb-addon-content').css({'font-size':'14px'});
		$('.seccion-bannerprincipal-informacion-desktop-texto1').css({'margin-top':'-10px'});
		$('.seccion-bannerprincipal-informacion-desktop-texto2').css({'margin-top':'-15px'});
      	$('.seccion-bannerprincipal-desktop .sppb-btn-custom').css({'font-size':'14px','margin-top':'-10px'});
		$('.seccion-bannerprincipal-desktop .breadcrumb .pathway').css({'font-size':'14px'});
    }
	
	$('.seccion-bannerprincipal-desktop').css({'height':imgHeightCustomResize});

    });
  
    var BackgroundSizeCustom='50%'+' 100%';
    
    $('.seccion-bannerprincipal-desktop').css({'background-size':BackgroundSizeCustom});
    
  });
});


/* Paginas Internas - Banner - resize texto y foto*/

jQuery(function ($) {
  $(document).ready(function(){
	var imgHeightReal=962;
	var imgWidthReal=1900;
    var imgWidthResol=screen.width;
    var widthDefecto=imgWidthResol*0.50;
    var imgHeightCustom=(widthDefecto*imgHeightReal)/imgWidthReal;
	
	if (imgWidthResol>=1290){
		$('.seccion-internas-banner-desktop').css({'height':imgHeightCustom});
		$('.seccion-internas-banner-desktop .breadcrumb').css({'margin-top':'0px'});
		$('.seccion-internas-banner-informacion-desktop .sppb-addon-content').css({'font-size':'16px'});
		$('.seccion-internas-banner-desktop .sppb-btn-custom').css({'font-size':'16px','margin-top':'0px','padding':'13px 0px'});      	
	}else{
		$('.seccion-internas-banner-desktop').css({'height':imgHeightCustom});
		$('.seccion-internas-banner-desktop .breadcrumb').css({'margin-top':'-10px'});
		$('.seccion-internas-banner-informacion-desktop .sppb-addon-content').css({'font-size':'15px'});
		$('.seccion-internas-banner-desktop .sppb-btn-custom').css({'font-size':'15px','margin-top':'-10px','padding':'8px 0px'});
    }
    
    /* Resize*/
    $(window).resize(function(){	

      //aqui el codigo que se ejecutara cuando se redimencione la ventana
      var alto=$(window).height();
      var imgWidthResize=$(window).width();
      var widthDefectoResize=imgWidthResize*0.50;
      var imgHeightCustomResize=(widthDefectoResize*imgHeightReal)/imgWidthReal;

      	if (imgWidthResize>=1290){
			$('.seccion-internas-banner-desktop .breadcrumb').css({'margin-top':'0px'});
			$('.seccion-internas-banner-informacion-desktop .sppb-addon-content').css({'font-size':'16px'});
			$('.seccion-internas-banner-desktop .sppb-btn-custom').css({'font-size':'16px','margin-top':'0px','padding':'13px 0px'});      	
		}else{
			$('.seccion-internas-banner-desktop .breadcrumb').css({'margin-top':'-10px'});
			$('.seccion-internas-banner-informacion-desktop .sppb-addon-content').css({'font-size':'15px'});
			$('.seccion-internas-banner-desktop .sppb-btn-custom').css({'font-size':'15px','margin-top':'-10px','padding':'8px 0px'});
		}
		
        $('.seccion-internas-banner-desktop').css({'height':imgHeightCustomResize});

    });
  
    var BackgroundSizeCustom='50%'+' 100%';
    
    $('.seccion-internas-banner-desktop').css({'background-size':BackgroundSizeCustom});
    
  });
});

/* Insertar un span al menu de "Solicita Informacion"*/
jQuery(function ($) {
	$( ".sp-menu-item .principal-menu-solicitaInformacion" ).html( "<span>Solicita Información</span>" );
});


/**********************************/

/* Quitar la validación de HTML5 en los formularios*/
jQuery(function ($) {
	$("form").attr("novalidate","true");
});


/* Agregar label despues del input*/

/*Formulario Footer*/

/*Formulario - Pagina Principal e internas*/

jQuery(function ($) {
	$( document ).ready(function() {
	  /*Desktop*/  
	  $( "<label>Déjenos su correo</label>" ).insertAfter( "#section-id-1562992862397  #sppb-form-builder-field-0" );
	   /*Responsive*/
	   $( "<label>Déjenos su correo</label>" ).insertAfter( ".seccion-modulo-footer-responsive #sppb-form-builder-field-0" );
	});
});

/*Formulario - Pagina Principal*/

jQuery(function ($) {
	$( document ).ready(function() {
	  /*Desktop*/  
	  $( "<label class='form-label'>Nombres</label>" ).insertAfter( ".seccion-principal-solicitainformacion #sppb-form-builder-field-0" );
	  $( "<label class='form-label'>Apellidos</label>" ).insertAfter( ".seccion-principal-solicitainformacion #sppb-form-builder-field-1" );
	  $( "<label class='form-label'>Número de documento</label>" ).insertAfter( ".seccion-principal-solicitainformacion #sppb-form-builder-field-2" );
	   $( "<label class='form-label'>Correo electrónico</label>" ).insertAfter( ".seccion-principal-solicitainformacion #sppb-form-builder-field-3" );
       $( "<label class='form-label'>Número de celular</label>" ).insertAfter( ".seccion-principal-solicitainformacion #sppb-form-builder-field-4" );
	   $( "<label class='form-label'>Empresa</label>" ).insertAfter( ".seccion-principal-solicitainformacion #sppb-form-builder-field-6" );
       $( "<label class='form-label'>Cargo</label>" ).insertAfter( ".seccion-principal-solicitainformacion #sppb-form-builder-field-8" );	   

      
	});
});



    


jQuery(function ($) { 

/* Formulario footer - Desktop*/
    /* Campo - Email */
	$('.seccion-modulo-footer #sppb-form-builder-field-0').focusin(function() { 
	  $('.seccion-modulo-footer #sppb-form-builder-field-0 ~ label').animate({
		'fontSize': '12px',
		'top': '-1.2rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-modulo-footer #sppb-form-builder-field-0 ~ label').css({"color":"#d09b3e","background":'linear-gradient(to bottom, #cccccc 0%,#cccccc 65%,#cccccc 65%,white 50%,white 100%)'});
	});
	$('.seccion-modulo-footer #sppb-form-builder-field-0').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-modulo-footer #sppb-form-builder-field-0 ~ label').animate({
		  'fontSize': '14px',
		  'top': '15px',
		  'padding': 0
		}, 80);
	  }
         $('.seccion-modulo-footer #sppb-form-builder-field-0 ~ label').css({"color":"#a2a2a2","background":"#ffffff"});
	});

/*===============================================================================
Formulario - Desktop - comentado para incorporar un formulario propio de la ESAN 
=================================================================================*/
if(screen.width < 992){
  var topinput=-1;
}else{
  var topinput=-1.2;
}  
  // Campo - Nombres
	$('.seccion-principal-solicitainformacion #nombres').focusin(function() {
	  $('.seccion-principal-solicitainformacion #nombres ~ label').animate({
		'fontSize': '12px',
		'top': topinput+'rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-principal-solicitainformacion #nombres ~ label').css({"color":"#d09b3e",'background': 'linear-gradient(180deg, rgba(212, 156, 17, 0) 50%, rgb(255, 255, 255) 50%)'});
	});
	$('.seccion-principal-solicitainformacion #nombres').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-principal-solicitainformacion #nombres ~ label').animate({
		  'fontSize': '14px',
		  'top': '18px',
		  'padding': 0
		}, 80);
        $('.white-popup-solicitud .seccion-principal-solicitainformacion #nombres ~ label').animate({
		  'fontSize': '14px',
		  'top': '10px',
		  'padding': 0
		}, 80);
	  }
         $('.seccion-principal-solicitainformacion #nombres ~ label').css("color","#a2a2a2");
	});

// Campo - Apellido
	$('.seccion-principal-solicitainformacion #apaterno').focusin(function() {
	  $('.seccion-principal-solicitainformacion #apaterno ~ label').animate({
		'fontSize': '12px',
		'top': topinput+'rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-principal-solicitainformacion #apaterno~ label').css({"color":"#d09b3e",'background': 'linear-gradient(180deg, rgba(212, 156, 17, 0) 50%, rgb(255, 255, 255) 50%)'});
	});
	$('.seccion-principal-solicitainformacion #apaterno').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-principal-solicitainformacion #apaterno ~ label').animate({
		  'fontSize': '14px',
		  'top': '18px',
		  'padding': 0
		}, 80);
        $('.white-popup-solicitud .seccion-principal-solicitainformacion #apaterno ~ label').animate({
		  'fontSize': '14px',
		  'top': '10px',
		  'padding': 0
		}, 80);
	  }	  
         $('.seccion-principal-solicitainformacion #apaterno ~ label').css("color","#a2a2a2");
	});
	
// Campo - Numero de documento
	  $('.seccion-principal-solicitainformacion #dni').focusin(function() {
	  $('.seccion-principal-solicitainformacion #dni ~ label').animate({
		'fontSize': '12px',
		'top': topinput+'rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-principal-solicitainformacion #dni~ label').css({"color":"#d09b3e",'background': 'linear-gradient(180deg, rgba(212, 156, 17, 0) 50%, rgb(255, 255, 255) 50%)'});
	});
	$('.seccion-principal-solicitainformacion #dni').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-principal-solicitainformacion #dni~ label').animate({
		  'fontSize': '14px',
		  'top': '18px',
		  'padding': 0
		}, 80);
        $('.white-popup-solicitud .seccion-principal-solicitainformacion #dni~ label').animate({
		  'fontSize': '14px',
		  'top': '10px',
		  'padding': 0
		}, 80);
	  }
         $('.seccion-principal-solicitainformacion #dni ~ label').css("color","#a2a2a2");
	});	
	
// Campos - Correo
	$('.seccion-principal-solicitainformacion #email').focusin(function() {
	  $('.seccion-principal-solicitainformacion #email ~ label').animate({
		'fontSize': '12px',
		'top': topinput+'rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-principal-solicitainformacion #email ~ label').css({"color":"#d09b3e",'background': 'linear-gradient(180deg, rgba(212, 156, 17, 0) 50%, rgb(255, 255, 255) 50%)'});
	});
	$('.seccion-principal-solicitainformacion #email').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-principal-solicitainformacion #email ~ label').animate({
		  'fontSize': '14px',
		  'top': '18px',
		  'padding': 0
		}, 80);
        $('.white-popup-solicitud .seccion-principal-solicitainformacion #email ~ label').animate({
		  'fontSize': '14px',
		  'top': '10px',
		  'padding': 0
		}, 80);
	  }
         $('.seccion-principal-solicitainformacion #email ~ label').css("color","#a2a2a2");
	});	
	
//Campo - Telefono
	$('.seccion-principal-solicitainformacion #telefono').focusin(function() {
	  $('.seccion-principal-solicitainformacion #telefono ~ label').animate({
		'fontSize': '12px',
		'top': topinput+'rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-principal-solicitainformacion #telefono ~ label').css({"color":"#d09b3e",'background': 'linear-gradient(180deg, rgba(212, 156, 17, 0) 50%, rgb(255, 255, 255) 50%)'});
	});
	$('.seccion-principal-solicitainformacion #telefono').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-principal-solicitainformacion #telefono ~ label').animate({
		  'fontSize': '14px',
		  'top': '18px',
		  'padding': 0
		}, 80);
        $('.white-popup-solicitud .seccion-principal-solicitainformacion #telefono ~ label').animate({
		  'fontSize': '14px',
		  'top': '10px',
		  'padding': 0
		}, 80);
	  }
         $('.seccion-principal-solicitainformacion #telefono ~ label').css("color","#a2a2a2");
	});
	
//Campo - Empresa
	$('.seccion-principal-solicitainformacion #empresa').focusin(function() {
	  $('.seccion-principal-solicitainformacion #empresa ~ label').animate({
		'fontSize': '12px',
		'top': topinput+'rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-principal-solicitainformacion #empresa ~ label').css({"color":"#d09b3e",'background': 'linear-gradient(180deg, rgba(212, 156, 17, 0) 50%, rgb(255, 255, 255) 50%)'});
	});
	$('.seccion-principal-solicitainformacion #empresa').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-principal-solicitainformacion #empresa ~ label').animate({
		  'fontSize': '14px',
		  'top': '18px',
		  'padding': 0
		}, 80);
        $('.white-popup-solicitud .seccion-principal-solicitainformacion #empresa ~ label').animate({
		  'fontSize': '14px',
		  'top': '10px',
		  'padding': 0
		}, 80);
	  }
         $('.seccion-principal-solicitainformacion #empresa ~ label').css("color","#a2a2a2");
	});
	
//Campo - Cargo
	$('.seccion-principal-solicitainformacion #cargo').focusin(function() {
	  $('.seccion-principal-solicitainformacion #cargo ~ label').animate({
		'fontSize': '12px',
		'top': topinput+'rem',
		'padding': '0.25rem'
	  }, 80);
         $('.seccion-principal-solicitainformacion #cargo ~ label').css({"color":"#d09b3e",'background': 'linear-gradient(180deg, rgba(212, 156, 17, 0) 50%, rgb(255, 255, 255) 50%)'});
	});
	$('.seccion-principal-solicitainformacion #cargo').focusout(function() {
	  if ($(this).val() === '') {
		$('.seccion-principal-solicitainformacion #cargo ~ label').animate({
		  'fontSize': '14px',
		  'top': '18px',
		  'padding': 0
		}, 80);
        $('.white-popup-solicitud .seccion-principal-solicitainformacion #cargo ~ label').animate({
		  'fontSize': '14px',
		  'top': '10px',
		  'padding': 0
		}, 80);
	  }
         $('.seccion-principal-solicitainformacion #cargo ~ label').css("color","#a2a2a2");
	});
  
  

});

/***************************** EFECTOS FUNCIONAN CON MBA Y MAESTRIAS *************************/

/*
Modificado/Implementado por Attach (2019-09-25)
Nevegacion y animacion de menu principal click/on-scroll (desktop/responsive)
*/

// MAIN MENU NAVIGATION
/*
jQuery(function ($) {
	var map = {
	//	'class-menu-item'                    : [ 'class-secccion' , 'class-active-item' ],
		'principal-ventajas'                 : ['seccion-principal-ventajas',             'principal-menu-custom'],
		'principal-internacionalizacion'     : ['seccion-principal-internacionalizacion', 'principal-menu-custom'],
		'principal-malla'                    : ['seccion-principal-mallacurricular',      'principal-menu-custom'],
		'principal-duracion'                 : ['seccion-principal-duracion',             'principal-menu-custom'],
		'principal-facultad'                 : ['seccion-principal-docentes',             'principal-menu-custom'],
		'principal-inversion'                : ['seccion-principal-inversion',            'principal-menu-custom'],
		'principal-menu-solicitaInformacion' : ['seccion-principal-solicitainformacion',  'principal-menubuton-custom'],
	},

	map_keys = Object.keys(map),

	scroll_duration = 1200,
	scroll_running = false,

	active_item = null,

	dom = {
		window       : $(window),
		body         : $('html,body'),

		menu_wrapper : $('.table-responsive'),
		menu_content : $('.table-responsive > .table'),

		regular      : {items: {}, sections: {}, suffix: '',            offset_top: 80},
		responsive   : {items: {}, sections: {}, suffix: '-responsive', offset_top: screen.width < 768 ? 130 : 120},
	},

	markItem = function (type, k, is_active) {
		dom[type].items[k][is_active ? 'addClass' : 'removeClass'](map[k][1]);

		if (is_active && active_item !== k) {
			active_item = k;

			if (type == 'responsive') {
				centerItem(k);
			}
		}
	},

	centerItem = function (k) {
		var
		distance = 0,
		menu_width = 0,
		mask_width = dom.menu_wrapper.innerWidth(),
		found_item = false,
		item_width, i, l;

		for (i = 0, l = map_keys.length; i < l; i++) {
			item_width = dom.responsive.items[map_keys[i]].parent().innerWidth();
			menu_width += item_width;

			if (map_keys[i] === k) {
				distance -= (mask_width - item_width) / 2;
				found_item = true;
			} else if (!found_item) {
				distance += item_width;
			}
		}

		distance = Math.max(distance, 0);
		distance = Math.min(distance, menu_width - mask_width);

		dom.menu_wrapper.stop(true, false).animate({scrollLeft: distance}, scroll_duration * 0.5);
	},

	getSectionTopOffset = function (type, k) {
		return dom[type].sections[k].offset().top - dom[type].offset_top;
	};


	// on-click
	var sections_exists = true;

	['regular', 'responsive'].forEach(function (type, i) {
		map_keys.forEach(function (k) {
			var section = $('.' + map[k][0] + dom[type].suffix);
			var item = $('.' + k + dom[type].suffix);

			if (!section.length || !item.length) {
				sections_exists = false;
				return;
			}

			item.on('click', function (event) {
				event.preventDefault();

				if (scroll_running) return;

				scroll_running = true;

				dom.body.animate({
					scrollTop: getSectionTopOffset(type, k)
				}, scroll_duration, 'swing', function () { scroll_running = false; });

				for (var j in map) {
					markItem(type, j, j === k);
				}
			});

			dom[type].sections[k] = section;
			dom[type].items[k] = item;
		});
	});

	if (!sections_exists) {
		return;
	}


	// on-scroll
	dom.window.scroll(function () {
		if (scroll_running) return;

		var
		type = screen.width < 992 ? 'responsive' : 'regular',
		selected_item = null,
		section_offset;

		for (var i = map_keys.length - 1; i >= 0; i--) {
			section_offset  = getSectionTopOffset(type, map_keys[i]);
			section_offset -= window.innerHeight * 0.2;

			if (dom.window.scrollTop() > section_offset) {
				if (selected_item !== map_keys[i]) {
					selected_item = map_keys[i];
				}

				break;
			}
		}

		map_keys.forEach(function (k) {
			markItem(type, k, k === selected_item);
		});
	});
});
*/


/*********************************************************************/

/* PRINCIPAL - MODULO IDIOMA - select*/


jQuery(function ($) {
function DropDown(el) {
				this.dd = el;
				this.placeholder = this.dd.children('.optiondefect');
				this.opts = this.dd.find('ul.dropdown > li');
				this.val = '';
				this.index = -1;
				this.initEvents();
			}
			DropDown.prototype = {
				initEvents : function() {
					var obj = this;

					obj.dd.on('click', function(event){
						$(this).toggleClass('active');
						return false;
					});

					obj.opts.on('click',function(){
						var opt = $(this);
						obj.val = opt.text();
						obj.index = opt.index();
						obj.placeholder.text(obj.val);
					});
				},
				getValue : function() {
					return this.val;
				},
				getIndex : function() {
					return this.index;
				}
			}
			
			

			$(function() {

				var dd = new DropDown( $('#dd') );

				$(document).click(function() {
					// all dropdowns
					$('.wrapper-dropdown-3').removeClass('active');
				});

			});
});


/*
window.sendFormDataToESAN = function (form, is_async) {}
Envía datos del formulario a: https://staging.esanbackoffice.com/websites/products/information-request/
Por: Attach (lgarcia) 2019-10-04
*/
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
        console.log('enviando mbas');
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
				// filter values
				// \u{0020}-\u{007E} => Basic Latin (https://en.wikipedia.org/wiki/List_of_Unicode_characters#Basic_Latin)
				// \u{00A0}-\u{00FF} => Latin-1 Supplement (https://en.wikipedia.org/wiki/List_of_Unicode_characters#Latin-1_Supplement)
				// \u{000A}          => LF
				// \u{000D}          => CR
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

	window.sendFormDataToESAN_SolicitudDeInformacion = function (is_async) {
		var form = document.querySelector('#solicitud-de-informacion');

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

			empresa                       : [form, 'input[name="empresa"]'],
			job_industry_code             : [form, 'select[name="industria_giro"]'],
			cargo                         : [form, 'input[id="cargo"]'],
			job_function_code             : [form, 'select[name="area"]'],

			ciudad                        : [document, 'input[name="ciudad"]'],
			programa                      : [document, 'input[name="programa"]'],
			curso                         : ['LOCAL-STORAGE', ['Ciudad', 'Modalidad']],
			consulta                      : [form, 'textarea[id="consulta"]'],

			meses_para_llevarlo           : null,

			conferencia                   : [form, 'input[name="estaConferencia"]'],
			datos_de_la_conferencia       : [form, 'input[id="DatosConferencia"]'],

			como_te_enteraste             : [form, 'select[name="encuesta"]'],

			url_del_formulario            : [document, 'input[name="url_formulario"]'],

			procedencia                   : ['COOKIE', 'traffic_source']
		};

		sendData(collectData(fields), is_async);
	};

	/*
	var fields = {
	//	campo_json                    : [ objecto-de-donde-obtener-campo, selector-de-campo, attributo-de-campo-a-usar]

		nombres                       : [form, 'input[name="form-builder-item-[nombres*]"]'],
		apellido_paterno              : [form, 'input[name="form-builder-item-[apaterno]"]'],
		apellido_materno              : null,
		tipo_de_id                    : null,
		numero_de_id                  : [form, 'input[name="form-builder-item-[dni*]"]'],
		user_agent_uuid               : ['COOKIE', 'user_agent_uuid'],

		edad                          : null,
		academic_degree_code          : [form, 'select[name="form-builder-item-[grado_academico*]"]'],
		exalumno                      : null,

		correo_electrnico             : [form, 'input[name="form-builder-item-[email*]"]'],
		telefono                      : [form, 'input[name="form-builder-item-[telefono]"]'],

		acepta_politica_de_privacidad : [form, 'input[name="policy"]'],

		empresa                       : [form, 'input[name="form-builder-item-[empresa]"]'],
		job_industry_code             : [form, 'select[name="form-builder-item-[industria_giro]"]'],
		cargo                         : [form, 'input[name="form-builder-item-[cargo*]"]'],
		job_function_code             : [form, 'select[name="form-builder-item-[area]"]'],

		ciudad                        : [document, 'input[name="ciudad"]'],
		programa                      : [document, 'input[name="programa"]'],
		curso                         : ['LOCAL-STORAGE', ['Ciudad', 'Modalidad']],
		consulta                      : [form, 'textarea[name="form-builder-item-[consulta]"]'],

		meses_para_llevarlo           : null,

		conferencia                   : [form, 'input[name="form-builder-item-[asistire]"]'],
		datos_de_la_conferencia       : [form, 'label[for="form-1570117522018-checkbox-0"]', 'textContent'],

		como_te_enteraste             : [form, 'select[name="form-builder-item-[programa*]"]'],

		url_del_formulario            : [document, 'input[name="url_formulario"]'],

		procedencia                   : ['COOKIE', 'traffic_source']
	};*/

})();



/*
Envía datos del formulario de solicitud de credito a: https://staging.esanbackoffice.com/websites/products/information-request/
Por: (ccasas) 2019-11-04
*/
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
		xhr.open('POST', 'https://staging.esanbackoffice.com/websites/products/information-request/', is_async);
		xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
		xhr.send(JSON.stringify({timestamp: new Date().toJSON(), payload: data}));
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
				// filter values
				// \u{0020}-\u{007E} => Basic Latin (https://en.wikipedia.org/wiki/List_of_Unicode_characters#Basic_Latin)
				// \u{00A0}-\u{00FF} => Latin-1 Supplement (https://en.wikipedia.org/wiki/List_of_Unicode_characters#Latin-1_Supplement)
				// \u{000A}          => LF
				// \u{000D}          => CR
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


	window.sendFormDataToESAN_SolicitudDeCredito = function (is_async) {
		var form = document.querySelector('#solicitud-de-credito');

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

			empresa                       : [form, 'input[name="empresa"]'],
			job_industry_code             : [form, 'select[name="industria_giro"]'],
			cargo                         : [form, 'input[id="cargo"]'],
			job_function_code             : [form, 'select[name="area"]'],

			ciudad                        : [document, 'input[name="ciudad"]'],
			programa                      : [document, 'input[name="programa"]'],
			curso                         : ['LOCAL-STORAGE', ['Ciudad', 'Modalidad']],
			consulta                      : [form, 'textarea[id="consulta"]'],

			meses_para_llevarlo           : null,

			conferencia                   : [form, 'input[name="estaConferencia"]'],
			datos_de_la_conferencia       : [form, 'input[id="DatosConferencia"]'],

			como_te_enteraste             : [form, 'select[name="encuesta"]'],

			url_del_formulario            : [document, 'input[name="url_formulario"]'],

			procedencia                   : ['COOKIE', 'traffic_source']
		};

		sendData(collectData(fields), is_async);
	};

	

})();


/******************************* Solicitud de datos - suscripcion ************************/

/*
Envía datos del formulario de solicitud de credito a: https://staging.esanbackoffice.com/websites/products/information-request/
Por: (ccasas) 2020-01-24
*/
(function () {
	var getXHR = function () {
		if (window.XMLHttpRequest) {
			return new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			return new ActiveXObject('Microsoft.XMLHTTP');
		}
	};

	var sendDataSuscripcion = function (data, is_async) {
		var xhr = getXHR();
		xhr.open('POST', 'https://staging.esanbackoffice.com/websites/products/subscription-request/', is_async);
		xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
		xhr.send(JSON.stringify({timestamp: new Date().toJSON(), payload: data}));
        if (xhr.status == 200){ console.log('correcto info');
           $(".sppb-ajax-contact-status").html("<p>Gracias por tu suscripción.</p><p>Recibirás un mensaje de confirmación en la dirección de correo que proporcionaste.</p>");
          $(".sppb-ajax-contact-status").css({'display':'block'});
		}
	};

	var collectData = function (fields) {
		var data = {};

		var fields_optional = [
			'apellido',
			'exalumno',
			'curso',
			'procedencia'
		];

		var fields_int = [
			/* comentado - 24012020
            'edad',
			'academic_degree_code',
			'meses_para_ llevarlo'*/
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
					console.log('NOTICE (sendFormDataToESAN_Suscripcion): "' + selector + '" cookie not found');
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
						console.log('NOTICE (sendFormDataToESAN_Suscripcion): "' + selector + '" item not found in localStorage');
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
					console.log('NOTICE (sendFormDataToESAN_Suscripcion): "' + selector + '" element not found');
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
				// filter values
				// \u{0020}-\u{007E} => Basic Latin (https://en.wikipedia.org/wiki/List_of_Unicode_characters#Basic_Latin)
				// \u{00A0}-\u{00FF} => Latin-1 Supplement (https://en.wikipedia.org/wiki/List_of_Unicode_characters#Latin-1_Supplement)
				// \u{000A}          => LF
				// \u{000D}          => CR
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


	window.sendFormDataToESAN_Suscripcion = function (is_async) {
		var form = document.querySelector('.form-suscripcion .sppb-addon-form-builder-form');

		var fields = {
		//	campo_json                    : [ objecto-de-donde-obtener-campo, selector-de-campo, attributo-de-campo-a-usar]

			nombre		                  : null,
			apellido	                  : null,

			email		                  : [form, 'input[name="form-builder-item-[email*]"]'],
            diploma						  : null,

			ciudad                        : [document, 'input[name="ciudad"]'],
			programa                      : [document, 'input[name="programa"]'],
			curso                         : null,
			area			              : null,
			sector			              : null,
			temas_transversales			  : null,
			newsletters                   : null,

			path_del_formulario           : [document, 'input[name="url_formulario"]'],
			url_gracias					  : null,
			procedencia                   : ['COOKIE', 'traffic_source'],
          	procedencia                   : null,
            acepta_politica_de_privacidad : [form, 'input[name="policy"]'],
          	subs_user_agent_uuid          : ['COOKIE', 'user_agent_uuid']
		};

		sendDataSuscripcion(collectData(fields), is_async);
	};

	

})();

