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
		
	$req= "SELECT  t1.codigo,t1.cantidad, t2.idproductos  FROM artremision as t1 
		INNER JOIN productos as t2 ON t1.codigo = t2.codigo WHERE  remision=". mysqli_real_escape_string($mysqli,$_GET['rem']);
    $query = mysqli_query($mysqli,$req);
    
    while($row = mysqli_fetch_array($query))
    {
        $results[] = array_map('utf8_encode',array('cod' => $row[0],'cant' 
        => $row[1], 'idprod' => $row[2]));
    }
	
	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
		/*funcion de conversion de caracteres */
		
    	
		
    	echo json_encode($results);


?>