/**
 * esta hoja contiene las funciones de validacion en javascript
 * 
 * @author jmv
 */

function validanoblanco(texto,infotexto){
	if (texto.val() == "") {

		texto.addClass("error");

		infotexto.text("el campo debe ser llenado!");

		infotexto.addClass("req");

		return false;

		} else {

		texto.removeClass("req");

		infotexto.text("*");

		texto.removeClass("req");
		
		return true;
		}
}