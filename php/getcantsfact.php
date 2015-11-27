<?php

/*** Autoload class files ***/ 
    function __autoload($class){
      require('../include/' . strtolower($class) . '.class.php');
    }
    
    $funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
/**1 -revision de remision **/
    if (is_object($mysqli)) {
    	$sqlCommand = "SELECT codigo,cantidad FROM artfactura WHERE idfactura=".$_GET['fact'];		
	 // Execute the query here now
			 $query1=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN CONSULTA DE CANTIDADES ARTICULOS ".mysqli_error($mysqli));
//inicializacion de arreglo
			 while($tempo=mysqli_fetch_array($query1, MYSQLI_ASSOC)){
			 	$result[] = array('codigo' => $tempo['codigo'],'cant' => $tempo['cantidad']);
			 };

	/* liberar la serie de resultados */
			  mysqli_free_result($query1);			  
	/* cerrar la conexi�n */
	  mysqli_close($mysqli);
	  
	 echo json_encode($result);
	 
	}else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
?>