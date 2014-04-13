<?php
	/*** Autoload class files ***/ 
    $mysql_hostname = "localhost";
		$mysql_user = "test";
		$mysql_password = "test";
		$mysql_database = "stellus1";


		$mysqli = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

		if($mysqli->connect_errno > 0){
		    die('No se establecio conexion a la base de datos [' . $mysqli->connect_error . ']');
			};
	
	
    $req = "SELECT idclientes,nom_corto,razon_social,rfc,calleno,col,del,ciudad,estado,cp,nivel FROM clientes WHERE nom_corto like '" 
    . mysqli_real_escape_string($mysqli,$_GET['term']) . "%'"; 
	
    $query = mysqli_query($mysqli,$req);
    
    while($row = mysqli_fetch_array($query))
    {
    	$domi1 = $row['calleno']." COL.".$row['col']."  DEL.".$row['del']." C.P.".$row['cp']." ".$row['ciudad'].",".$row['estado'];
        $results[] = array('label' => $row['nom_corto'],'idclientes' 
        => $row['idclientes'],'razon'=>$row['razon_social'],'rfc'=>$row['rfc'],
		'domicilio'=> $domi1,'nivel'=>$row['nivel']);
    }
	
	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
    
    	echo json_encode($results);


?>