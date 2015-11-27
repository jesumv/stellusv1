<?php

	/*** Autoload class files ***/ 
    function __autoload($class){
      require('../include/' . strtolower($class) . '.class.php');
    }
    
    $funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();

    if (is_object($mysqli)) {
/**obtención de datos de facturas **/
    	$sqlCommand = "SELECT DISTINCT t1.no_factura,t1.remision,t1.oc,t1.fecha,t4.razon_social,t1.agente,t1.subtotal,t1.iva,t1.total,t3.descripcion,
    	t1.idclientes FROM facturas2 AS t1 INNER JOIN artfactura AS t2 ON t1.no_factura=t2.idfactura INNER JOIN almacenes as t3 ON t1.idsuccliente = t3.no_almacen
    	INNER JOIN clientes as t4 ON t1.idclientes = t4.idclientes
		ORDER BY t1.no_factura DESC LIMIT 40 ";		
	 // Execute the query here now
			 $query1=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN CONSULTA DE RENGLONES FACTURAS ".mysqli_error($mysqli));
			 while($row=mysqli_fetch_array($query1, MYSQLI_ASSOC)){
//se examina si el cliente tiene sucursales
				$idcte = $row['idclientes'];
				$sqlCommand2 ="SELECT idsuccliente FROM succliente where cliente = ".$idcte;
			  	$query2=mysqli_query($mysqli, $sqlCommand2)or die ("ERROR EN CONSULTA DE EXIST SUC ".mysqli_error($mysqli));
			  	$filas = $query2->num_rows;
				if($filas > 0){$haysuc= 0;} else {$haysuc= -1;};
			  	mysqli_free_result($query2);
//se llena el array de resultados
				 $results[] = array('fact' => $row['no_factura'],'rem' => $row['remision'],
        		'oc' => $row['oc'],'fecha' =>$row['fecha'],'cte' =>$row['razon_social'],'ag' =>$row['agente'],'st' =>$row['subtotal'],
				'iva' => $row['iva'],'tot' =>$row['total'],'suc' =>$row['descripcion'],'haysuc'=>$haysuc);
			 };
			 sort($results);
	/* liberar la serie de resultados */
			  mysqli_free_result($query1);	
			  		  
	/* cerrar la conexi�n */
	  mysqli_close($mysqli);
	  
	 echo json_encode($results);
	 
	}else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
?>