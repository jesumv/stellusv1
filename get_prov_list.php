<?php
	/*** Selecciona el no. de proveedor y la razon social ***/ 
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
	
    
	$req = "SELECT idproveedores, nom_corto FROM proveedores WHERE nom_corto like '" . mysqli_real_escape_string($mysqli,$_GET['term']) . "%'"; 

    $query = mysqli_query($mysqli,$req)or die ("error en ajax proveedores ".mysqli_error($mysqli));
    
    while($row = mysqli_fetch_array($query))
    {
        $results[] = array('label' => $row['nom_corto'], 'idprov' => $row['idproveedores']);
    }
    
    echo json_encode($results);


?>