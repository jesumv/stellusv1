<?php
   /*** Autoload class files ***/ 
    function __autoload($class){
      require('../include/' . strtolower($class) . '.class.php');
    }
	
    $funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
	
    if (is_object($mysqli)) {
	/*** checa login***/
	        $funcbase->checalogin($mysqli);
				$otrabd = new otrasdbutils;
				$result= $otrabd->ultcliente2($mysqli);
				$cliente = $result[0];
                $corto = $result[1];
		//insercion en la tabla de sucursales    	
			$usu = $_SESSION['login_user'];			
		//construccci�n de numero de sucursal
		
			$almac = $cliente.'000';
			$suc = 0;
			$table="succliente";
	   		$sqlCommand= "INSERT INTO $table (cliente,no_suc, no_almacen,nom_sucursal,usu,status)
	    	VALUES ($cliente, $suc, $almac,'$corto', '$usu', 0)";

	    	$query=mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
					
			/* cerrar la conexion */
	    	mysqli_close($mysqli);  
			/*abrir la hoja de clientes */
			header("Location: ../clientes.php","_self");

			
	} else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
?>

