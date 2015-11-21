<?php


//funciones auxiliares

require '../include/funciones.php';

	function __autoload($class){
      		require('../include/' . strtolower($class) . '.class.php');
	}
			
//funcion para ver si la factura ya existe
function hayfactura($factura){
	$funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
		 $req = "SELECT idfacturas FROM facturas2 WHERE no_factura = '" 
    	.$factura."'"; 
		$query=mysqli_query($mysqli, $req);
		/* determinar el n�mero de filas del resultado */	
		$filas = $query->num_rows;
 	
    } else {
        die ("<h1>'No se establecio la conexion a bd para revisar facturas'</h1>");
    }	

 /* liberar la serie de resultados */
 /* cerrar la conexion */
	 mysqli_close($mysqli);
	return $filas;
}

function haycliente($rfc){
	$funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
		 $req = "SELECT idclientes FROM clientes WHERE rfc= '" 
    	.$rfc."'"; 
		$query=mysqli_query($mysqli, $req);
		/* determinar el n�mero de filas del resultado */	
		$filas = $query->num_rows;
 	
    } else {
        die ("<h1>'No se establecio la conexion a bd para revisar clientes'</h1>");
    }	

 /* liberar la serie de resultados */
 /* cerrar la conexion */
	 mysqli_close($mysqli);
	return $filas;
	
}

function hayproducto($codigo){
	$funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
		 $req = "SELECT codigo FROM productos WHERE codigo = '" 
    	.$codigo."'"; 
		$query=mysqli_query($mysqli, $req);
		/* determinar el n�mero de filas del resultado */	
		$filas = $query->num_rows;
 	
    } else {
        die ("<h1>'No se establecio la conexion a bd para revisar clientes'</h1>");
    }	

 /* liberar la serie de resultados */
 /* cerrar la conexion */
	 mysqli_close($mysqli);
	return $filas;
	
}

function validaemisor($emisor){
//se valida que el emisor sea stellus
	if($emisor!="SME100125GR4"){return -3;}else{return 0;}
}

function validafactura($emisor,$factura,$cliente){			
//validaciones previas
//el emisor es stellus
	$validacion= 0;
	if(validaemisor($emisor)!=0){
		 $validacion=-3;
		 return $validacion;	
		 }
//la factura no existe
	if (hayfactura($factura)!=0) {
		 $validacion=-1;
		 return $validacion;	
		 }
//el cliente existe
	if (haycliente($cliente)==0){
		 $validacion=-2;
		 return $validacion;	
		 }
	
}

//funcion para obtener el numero de producto
function obtenidprod($clave){
	$funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
		 $req = "SELECT idproductos  FROM productos WHERE codigo = '" 
    	.$clave."'"; 
   		$idproducto = $mysqli->query($req)->fetch_object()->idproductos;
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
	
	
 /* liberar la serie de resultados */
 /* cerrar la conexion */
	 mysqli_close($mysqli);
		
	return $idproducto;
}

//funcion para obtener el idcliente con los datos de la factura
function obtenidcliente($rfc){
	$funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
		$req = "SELECT idclientes  FROM clientes WHERE rfc = '" 
    	.$rfc."'"; 
    
   		$idcliente = $mysqli->query($req)->fetch_object()->idclientes;
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
		
 /* liberar la serie de resultados */
 /* cerrar la conexion */
	 mysqli_close($mysqli);
		
	return $idcliente;
}
function haysuc($idcliente){
//funcion para determinar si hay sucursales
	$funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
		 $req = "SELECT idsuccliente FROM succliente WHERE cliente =" 
    	.$idcliente; 
		$query=mysqli_query($mysqli, $req);
		/* determinar el n�mero de filas del resultado */	
		$filas = $query->num_rows;
 	
    } else {
        die ("<h1>'No se establecio la conexion a bd para revisar clientes'</h1>");
    }	

 /* liberar la serie de resultados */
 /* cerrar la conexion */
	 mysqli_close($mysqli);
	return $filas;
}


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
				$rfc =	$Receptor['rfc'];
			}
			
			foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
				$emisor =	$Emisor['rfc'];
			}
			
	//se inicializa el array de resultados	
		$resul = array('razon' => $nombre); 
		$resul['emisor'] = $emisor;
		
	//los demas datos de la factura
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
//revision del resultado de validaciones.
				$resultadofin = validafactura($emisor,$factura,$rfc);
//si la validacion no es exitosa, no se sigue adelante.
				if($resultadofin!=0){($resul['valida']= $resultadofin);
					/* borrar el archivo trabajado */
					if(!unlink($UploadDirectory.$NewFileName))
						{$resul['escrit']=-1;}else{
						$resul['escrit']=0;
					}
				}else{
				$resul['valida']= 0;
//se obtiene el numero de cliente	
  				$resul['idcliente'] = obtenidcliente($rfc);	
				$nocliente = $resul['idcliente'] ;
//se determina si hay sucursales
				$resul['haysuc'] = haysuc($nocliente);			
//los datos de los articulos
				$numerador = 0;								
			foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){ 
				${'cant'. $numerador} =  $concepto['cantidad']; 
				${'clave'.$numerador} =  $concepto['noIdentificacion'];
//se valida la existencia de cada producto
				$valprod = hayproducto(${'clave'.$numerador});
				if($valprod==0){$resul['valida']= -4;}else{
					${'idproducto'.$numerador} =  obtenidprod(${'clave'.$numerador});
					${'desc'.$numerador} = $concepto['descripcion'];
					${'punit'. $numerador} = $concepto['valorUnitario'];
		   			${'impor'. $numerador} = $concepto['importe'];
					$resul['cant'. $numerador] = ${'cant'.$numerador};
					$resul['clave'. $numerador] = ${'clave'.$numerador};
					$resul['idprod'. $numerador] = ${'idproducto'.$numerador};
					$resul['desc'. $numerador] = ${'desc'.$numerador};
					$resul['punit'. $numerador] = ${'punit'.$numerador};
					$resul['impor'. $numerador] = ${'impor'.$numerador};
					$numerador++;
				}		 	 		
			} 
				$resul['arts'] = $numerador;
				foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $impuesto){  
				$imp =  $impuesto['totalImpuestosTrasladados'];
				$resul['imp'] = $imp;			 	 		
			}
			 

    
	/* borrar el archivo trabajado */
			if(!unlink($UploadDirectory.$NewFileName))
				{$resul['escrit']=-1;}else{
				$resul['escrit']=0;
					}
				
		}
			$results = $resul;
			echo json_encode($results);	
	
	}else{
		die('Error al cargar archivo!');
			}
				
}
