<?php
	function nalmac($cliente,$noalmac){
		/*** este script contiene las reglas para * **/ 
	/*** la construccion del numero de almacen***/ 
		$longi = strlen($noalmac);
		
		if($longi ==1){
			$no = $cliente.'0'.$noalmac;

		}else{
			$no = $cliente.$noalmac;
		}
		return $no;
	}
	
	function decidesuc($cliente,$noalmac){
		/*** este regresa el numero de almacen matriz * **/ 
		/*** si no se proporciono $noalmac * **/ 
		if(empty($noalmac)){
			$no = $cliente.'00';
		}else{
			$no = $noalmac;
		}
		return $no;
	}

?>