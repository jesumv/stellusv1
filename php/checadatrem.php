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
/**1 -revision de remision **/
    if (is_object($mysqli)) {
 	$req = "SELECT idfacturas FROM facturas2 where remision = " . mysqli_real_escape_string($mysqli,$_GET['rem']) ; 
    $query = mysqli_query($mysqli,$req);
	/*se inicializa el determinador de resultado */
	$result = 0;
    /* determinar el número de filas del resultado */	
		$filas = $query->num_rows;
		if($filas > 0){$result = -1;}	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);	
	    /* cerrar la conexion */
	    mysqli_close($mysqli);		
/*regresar los resultados*/	    
    	echo json_encode($result);
		
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
	
?>