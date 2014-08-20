<?php
/*recibir las variables a procesar */
$c = $_POST['c'];
$n = $_POST['n'];

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
	
	$rollo = "ESTA REMISION SE SUBSTITUYE X LA NO. ".$n;
	$req = "UPDATE remisiones SET status =5, obser = '$rollo' WHERE idremisiones = $c LIMIT 1";
    $query = mysqli_query($mysqli,$req)or die("error en ajax ".mysqli_error($mysqli));
    
/* cerrar la conexion */
	    mysqli_close($mysqli);
    

?>