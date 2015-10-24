<?php
	/*** Autoload class files ***/ 
     function __autoload($class){
      require('include/' . strtolower($class) . '.class.php');
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
	
	
	
    $req = "SELECT idclientes  FROM clientes WHERE razon_social = '" 
    . mysqli_real_escape_string($mysqli,$_GET['term'])."'"; 
	
    $query = mysqli_query($mysqli,$req);
    
    while($row = mysqli_fetch_array($query))
    {
        $idcliente[] = array_map('utf8_encode',array('idcliente' 
        => $row['idclientes']));
    }
	
	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
		/*funcion de conversion de caracteres */
		
    	echo json_encode($idcliente);


?>