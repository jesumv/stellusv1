<?php
/*** este script inserta en la bd remisiones las remisiones que se emitieron en blanco***/ 
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
		
/*** insercion a la base de datos ***/
		$custodio = $_POST['asignadoa'];
		$remact =  $_POST['remiact'];
		$numremi = $_POST['numero'];
		$table = 'remisiones';
		$usu = $_SESSION['login_user'];
/**loop para las remisiones solicitadas**/
	for($i=0; $i <$numremi;$i++){
		$sqlCommand= "INSERT INTO $table (idremisiones,tiporem,usu,status,custodio)
	      				VALUES ($remact,2,'$usu',0,'$custodio')";
		// Execute the query here now
	    $query = mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
		$remact++;
	}
		
		
	    /* cerrar la conexion */
	    
	    mysqli_close($mysqli);
			
		return 0;
	}else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
  
?>