<?php
	/*** este script trae la sucursal del cliente elegido***/ 
	/*** la elige por numero de cliente y nombre de sucursal***/ 
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
	
    $req = "SELECT no_suc,nom_sucursal,no_almacen FROM succliente where nom_sucursal like '" . 
    mysqli_real_escape_string($mysqli,$_GET['term']) . "%' && status <>2 && cliente =".mysqli_real_escape_string($mysqli,$_GET['cte']); 
    $query = mysqli_query($mysqli,$req);
    
    while($row = mysqli_fetch_array($query))
    {
        $results[] = array('label' => $row['nom_sucursal'],'idsuccliente' => $row['no_suc'],'noalmacen' => $row['no_almacen']);
    }
	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
    
    	echo json_encode($results);


?>