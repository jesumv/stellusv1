<?php
/*** OJO ESTE SCRIPT OBTIENE EL NUMERO DE SUCURSAL MAS ALTO PARA UN CLIENTE. DEBE SER EL MISMO QUE ALMACEN***/ 
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
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
	
    $req = "SELECT MAX(no_suc) FROM succliente where cliente= " . mysqli_real_escape_string($mysqli,$_GET['cliente']) ; 
    $query = mysqli_query($mysqli,$req);
    
		while($row = mysqli_fetch_array($query))
    	{
        	$results[] = array('no_max' => $row[0]);
    	}

		
		
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
    
    	echo json_encode($results);


?>