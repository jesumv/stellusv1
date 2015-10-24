<?php
if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
{
	############ Edit settings ##############
	$UploadDirectory	= '../uploads/'; //specify upload directory ends with / (slash)
	##########################################
	
	/*
	Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
	Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
	and set them adequately, also check "post_max_size".
	*/
	
	//check if this is an ajax request
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		die();
	}
	
	
	//Is file size is less than allowed size.
	if ($_FILES["FileInput"]["size"] > 5242880) {
		die("El archivo es demasiado grande!");
	}
	
	//allowed file type Server side check
	switch(strtolower($_FILES['FileInput']['type']))
		{
			//allowed file types
            case 'text/xml': 
				break;
			default:
				die('No es un archivo XML!'); //output error
	}
	
	$File_Name          = strtolower($_FILES['FileInput']['name']);
	$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
	$Random_Number      = rand(0, 9999999999); //Random number to be added to name.
	$NewFileName 		= $Random_Number.$File_Ext; //new file name
	
	if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
	   {
	   	//procesar el archivo xml
	   		$factura = simplexml_load_file($UploadDirectory.$NewFileName );
			$ns = $factura->getNamespaces(true);
			$factura->registerXPathNamespace('c', $ns['cfdi']);
			foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
				$nombre = $Receptor['nombre'];	
			}
	//se inicializa el array de resultados
	
		$resul = array('razon' => $nombre); 
			foreach ($factura->xpath('//cfdi:Comprobante') as $fact){  
				$factura = $fact['folio'];
		$resul['nofact'] = $factura;
				$fecha = $fact['fecha'];
		$resul['fecha'] = $fecha;
				$subtotal = $fact['subTotal'];	
		$resul['subt'] = $subtotal;	
			$total = $fact['total'];	
		$resul['total'] = $total;	
		 		
			}
								
			 $numerador = 0; 
			foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){ 
				${'cant'. $numerador} =  $concepto['cantidad']; 
				${'clave'. $numerador} =  $concepto['noIdentificacion'];
				${'desc'.$numerador} = $concepto['descripcion'];
				${'punit'. $numerador} = $concepto['valorUnitario'];
	   			${'impor'. $numerador} = $concepto['importe'];
				$resul['cant'. $numerador] = ${'cant'.$numerador};
				$resul['clave'. $numerador] = ${'clave'.$numerador};
				$resul['desc'. $numerador] = ${'desc'.$numerador};
				$resul['punit'. $numerador] = ${'punit'.$numerador};
				$resul['impor'. $numerador] = ${'impor'.$numerador};
				$numerador++;		 	 		
			} 
				$resul['arts'] = $numerador;
			foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $impuesto){  
			$imp =  $impuesto['totalImpuestosTrasladados'];
			$resul['imp'] = $imp;			 	 		
		}
		 $results[] = array_map('utf8_encode',$resul);	
		echo json_encode($results); 
		
	}else{
		die('Error al cargar archivo!');
	}
	
}
else
{
	die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}