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
	
	
	
    $req = "SELECT idclientes,nom_corto,razon_social,rfc,calleno,col,del,ciudad,estado,cp,nivel FROM clientes WHERE nom_corto like '" 
    . mysqli_real_escape_string($mysqli,$_GET['term']) . "%' && status <> 2"; 
	
    $query = mysqli_query($mysqli,$req);
    
    while($row = mysqli_fetch_array($query))
    {
    	$domi1 = $row['calleno']." COL.".$row['col']."  DEL.".$row['del']." C.P.".$row['cp']." ".$row['ciudad'].",".$row['estado'];
        $results[] = array_map('utf8_encode',array('label' => $row['nom_corto'],'idclientes' 
        => $row['idclientes'],'razon'=>$row['razon_social'],'rfc'=>$row['rfc'],
		'domicilio'=> $domi1,'nivel'=>$row['nivel']));
    }
	
	
	 /* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
		/*funcion de conversion de caracteres */
		
    	
		
    	echo json_encode($results);


?>