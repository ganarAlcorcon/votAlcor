
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
	
	if (typeof(mensajeOk) != "undefined") {
		jQuery("#divOk").html(mensajeOk);
		jQuery("#divOk").show();
	} else {
		jQuery("#divOk").hide();
	}
	
	jQuery("#fechaNacimiento").datepicker({
		dateFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		yearRange: "-120:-15"
	});
}

function polPriv() {
	window.open("privacidad.html","_blank","height=340, width=733, menubar=no, resizable=no, status=no, toolbar=no" );
}

function validar() {

    if (validaNif(jQuery('#nif').val()) == false) {
        if (validaNie(jQuery('#nif').val()) == false) {
            alert("Compruebe el campo NIF/NIE: cero delante si hace falta, sin separadores");
            return false;
        }
    }

   return true;

}

function validaNif(value) {
	value = value.toUpperCase();

	// Basic format test

	if (!value
			.match('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)')) {

		return false;
	}

	// Test NIF
	if (/^[0-9]{8}[A-Z]{1}$/.test(value)) {
		return ("TRWAGMYFPDXBNJZSQVHLCKE".charAt(value.substring(8, 0) % 23) === value
				.charAt(8));
	}
	// Test specials NIF (starts with K, L or M)
	if (/^[KLM]{1}/.test(value)) {
		return (value[8] === String.fromCharCode(64));
	}

	return false;
}

function validaNie(value) {
	value = value.toUpperCase();

	// Basic format test
	if (!value
			.match('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)')) {
		return false;
	}

	// Test NIE

	if (/^[T]{1}/.test(value)) {
		return (value[8] === /^[T]{1}[A-Z0-9]{8}$/.test(value));
	}

	//XYZ
	if (/^[XYZ]{1}/.test(value)) {
		return (value[8] === "TRWAGMYFPDXBNJZSQVHLCKE"
				.charAt(value.replace('X', '0').replace('Y', '1').replace('Z',
						'2').substring(0, 8) % 23));
	}

	return false;
}