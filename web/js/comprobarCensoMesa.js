function mostrarMensajeOk(mensaje) {
	jQuery("#divOk").html(mensaje);
	jQuery("#divOk").show();
}

function mostrarMensajeError(mensaje) {
	jQuery("#divError").html(mensaje);
	jQuery("#divError").show();
}

function inicio() {
	if (typeof(mensajeError) != "undefined") {
		mostrarMensajeError(mensajeError);
	}
	if (typeof(mensajeOk) != "undefined") {
		mostrarMensajeOk(mensajeOk);
	}
}