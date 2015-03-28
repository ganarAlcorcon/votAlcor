
function cambiaId(metodo) {
	if (metodo=="dni" || metodo=="nie") {
		jQuery("#id_nif").show();
		jQuery("#id_padron").hide();
		jQuery("#nif").attr("required",true);
	} else if (metodo=="padron") {
		jQuery("#id_nif").hide();
		jQuery("#id_padron").show();
		jQuery("#nif").attr("required",false);
	} else {
		jQuery("#id_nif").hide();
		jQuery("#id_padron").hide();
		jQuery("#nif").attr("required",false);
	}
}

function inicio() {
	cambiaId(jQuery("#identificacion").val());
	jQuery("#privacidad").attr("href","javascript:return void;");
	jQuery("#privacidad").removeAttr("target");
	jQuery("#privacidad").click(polPriv);
	
	if (typeof(mensajeError) != "undefined") {
		jQuery("#divError").html(mensajeError);
		jQuery("#divError").show();
	} else {
		jQuery("#divError").hide();
	}
}

function polPriv() {
	window.open("privacidad.html","_blank","height=340, width=733, menubar=no, resizable=no, status=no, toolbar=no" );
}