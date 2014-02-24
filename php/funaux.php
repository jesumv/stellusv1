<?php

function compledom($calleno,$col,$del,$ciudad,$estado,$cp){
/*toma los elementos del domicilio y los une en una cadena */
			$completo = $calleno." ".$col." ".$del." c.p.".$cp." ".$ciudad.", ".$estado;
			return $completo;
			
		}


?>


