/**
 * esta hoja contiene las funciones de validacion en javascript
 * 
 * @author jmv
 */

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