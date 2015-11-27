<?php
/**este script revisa que la remision y orden de compra asignadas a una factura no se **/
/**hayan usado antes **/

	/*** Autoload class files ***/ 
    function __autoload($class){
      require('../include/' . strtolower($class) . '.class.php');
    }
    
    $funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
	/*se inicializa el determinador de resultado */
	$result = 0;		
/**revision de orden de compra **/
 	$req2 = "SELECT idfacturas FROM facturas2 where oc = " . mysqli_real_escape_string($mysqli,$_GET['oc']) ; 
    $query2 = mysqli_query($mysqli,$req2);
    /* determinar el número de filas del resultado */	
		$filas2 = $query2->num_rows;
		if($filas2 > 0){$result=-1;}	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query2);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);		
/*regresar los resultados*/	    
    	echo json_encode($result);
		
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
	
?>