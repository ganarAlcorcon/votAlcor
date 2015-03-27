
function cambiaId(metodo) {
	if (metodo=="dni" || metodo=="nie") {
		jQuery("#id_nif").show();
		jQuery("#id_padron").hide();
	} else if (metodo="padron") {
		jQuery("#id_nif").hide();
		jQuery("#id_padron").show();
	}
}