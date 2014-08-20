<?php
	/* este script recibe por ajax un no. de remision y devuelve el articulo y la cantidad asociados a esa remision */
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
		
	$nivel = $_GET['nivel'];
	$prod= $_GET['idproductos'];
	
	$req= "SELECT descripcion,alg,precio$nivel FROM productos WHERE
	idproductos=$prod";
	
    $query = mysqli_query($mysqli,$req);
    
    while($row = mysqli_fetch_array($query))
    {
        $results[] = array_map('utf8_encode',array('desc' => $row[0],'alg' 
        => $row[1], 'precio' => $row[2]));
    }
	
	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
		/*funcion de conversion de caracteres */
		
    	
		
    	echo json_encode($results);


?>