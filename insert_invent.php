<?php

//esta hoja esta en desarrollo para insertar registros en bd con ajax

function post($key) {
    if (isset($_POST[$key]))
        return $_POST[$key];
    return false;
}

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
	
if (!post('my_value'))
    exit;

// let make sure we escape the data
$val = mysql_real_escape_string(post('my_value'), $cxn);

// lets setup our insert query
$sqlCommand = sprintf("INSERT INTO inventarios (idproductos,fecha,almacen,tipomov,cantidad,referencia,observaciones,usu,status)
	        VALUES ('$nombre',)"
	        or die('insercion cancelada '.$table)
);

// lets run our query
$result = mysql_query($sql, $cxn);

// setup our response "object"
$resp = new stdClass();
$resp->success = false;
if($result) {
    $resp->success = true;
}

print json_encode($resp);    
	
	
	/* liberar la serie de resultados */
	    mysqli_free_result($query);
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
    
    echo json_encode($results);


?>