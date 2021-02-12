jQuery(function ($) {
$('#website-feedback input[name=bienomal]').change(function(){
var opinionWebsite = $( 'input[name=bienomal]:checked' ).val();
	$('#website-feedback .bien-o-mal').css("display", "none");
if (opinionWebsite=="No") {
	$('#website-feedback .mas-feedback').css("display", "block");
	$('#website-feedback .no-mas-feedback').css("display", "none");
	ga('create', 'UA-920085-1', 'auto');
	ga('send', 'event', 'website-feedback', 'opinar', opinionWebsite, 1);
} else {
	$('#website-feedback .mas-feedback').css("display", "none");	
	$('#website-feedback .no-mas-feedback').css("display", "block");	
	ga('create', 'UA-920085-1', 'auto');
	ga('send', 'event', 'website-feedback', 'opinar', opinionWebsite, 1);
}
});

function getCookie(name) { //Gets the value of traffic_source 
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}
		
function addTrafficSourceToForm(){  //injects the traffic_source value to the form
	var elValorDeLaProcedencia = getCookie("traffic_source");
	$("#Procedencia").val(elValorDeLaProcedencia);
}

// addTrafficSourceToForm()

$('form#suscribe_interesados input').focus(function() {
  addTrafficSourceToForm();
});

$('#aceptar-politica-de-proteccion-de-datos').click(function() {
  $('#aceptacion-de-politica-de-proteccion-de-datos').prop('checked', true);
  $('#politica-de-proteccion-de-datos').modal('hide');
});

$('a[href^="http:\/\/inscripciononline"]').attr('target','_blank');
$('a[href^="http:\/\/inscripciononline"]').click(function(e) {

	var href = $(this).attr('href');
	ga('create', 'UA-920085-1', 'auto');
	ga('send', 'event', 'tools', 'saltar-a', 'preinscripcion-' + href.split('/').pop(), 1);
      
});

$('a[href$=".pdf"]').attr('target','_blank');
$('a[href$=".pdf"]').click(function() {

	var href = $(this).attr('href');
	ga('create', 'UA-920085-1', 'auto');
	ga('send', 'event', 'tools', 'descargar', href, 1);
      
});

$(function() {
  $('a[href*="#"]:not([href="#"]).smoothscroll').click(function() {

	var href = $(this).attr('href');
	ga('create', 'UA-920085-1', 'auto');
	ga('send', 'event', 'navegacion-interna', 'scroll', href, 1);
      
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);

      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 400);
        return false;		
      }
    }
  });
});

});



