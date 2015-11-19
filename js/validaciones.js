/**
 * esta hoja contiene las funciones de validacion en javascript
 * 
 * @author jmv
 */

function validafact(clave){
//esta funcion regresa el mensaje de validacion adecuado estado de la forma de facturas
	switch(clave) {
    case -1 :
        mensaje = "LA FACTURA YA HA SIDO REGISTRADA PREVIAMENTE. POR FAVOR VERIFIQUE."
        break;
    case -2 :
    	mensaje = "EL CLIENTE NO HA SIDO DADO DE ALTA. PROCEDA A HACERLO ANTES DE SUBIR LA FACTURA."
        break;
    case -3 :
    	mensaje = "EL EMISOR DE LA FACTURA NO ES STELLUS. VERIFIQUE."
        break;
    case -4 :
    	mensaje = "UNO DE LOS PRODUCTOS NO ESTA DADO DE ALTA. VERIFIQUE."
        break;
    default:
    	mensaje = "ERROR NO DEFINIDO EN REVISION DE FACTURA."
    	break;
	} 
	
	document.getElementById('footer').innerHTML = mensaje;
}

function validaforma(){
    $.validator.addMethod("requerido", $.validator.methods.required,"Llenar este campo");
    $.validator.addMethod("numerico", $.validator.methods.number,"el campo deber ser num&eacuterico");           
    $.validator.addClassRules("requer", { requerido: true});
    $.validator.addClassRules("reqnum", { requerido: true, numerico:true});
    $.validator.addClassRules("numer", {numerico:true});
    
$("form").validate({   
    invalidHandler: function(event, validator) {
       // 'this' refers to the form
       var errors = validator.numberOfInvalids();
       if (errors) {
           var message = errors == 1
           ? 'Hay 1 campo err&oacuteneo. Se ha resaltado.'
           : 'Hay ' + errors + ' campos err&oacuteneos. Se han se&ntildealado a continuaci&oacuten:';
           $("div.error span").html(message);
           $("div.error").show();
       } else {
               $("div.error").hide();
               }
   }
});
	
};