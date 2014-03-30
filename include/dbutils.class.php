<?php
	/**
	 * esta clase se usa para operaciones de base de datos
	 */
	 
	 /**
	  * 
	  */ 
	
	class dbutils  {
		/*** la tabla a leer ***/
		public $table;
		function __construct() {
			
		}
		
	   public function conecta() {
	    /***esta funcion establece la conexion a sql***/
		/***variables de conexion ***/
		$mysql_hostname = "localhost";
		$mysql_user = "test";
		$mysql_password = "test";
		$mysql_database = "stellus1";


		$mysqli = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

		if($mysqli->connect_errno > 0){
		    die('No se establecio conexion a la base de datos [' . $mysqli->connect_error . ']');
			return -1;
		}else{ return $mysqli;}
		
	}
    
        public function checalogin($mysqli){
         //***checa si el cliente esta registrado ***/
            session_start();
    
            $user_check=$_SESSION['login_user'];
            
            $ses_sql=mysqli_query($mysqli,"select username from usuarios where username='$user_check' ");
            $empre = mysqli_query($mysqli,"select empresa from usuarios where username='$user_check' ");
            
            $row=mysqli_fetch_array($ses_sql);
            
            $login_session=$row['username'];
    
            if(!isset($login_session))
            {
                  header("Location: index.php"); 
               
            }
        }
        
        public function leetodos($mysqli,$table,$filtro='1'){
          //***lee todos los datos de una tabla, un registro o todos los registros, de acuerdo con el argumento $filtro ***/
            $sql= "SELECT * FROM $table WHERE ".$filtro;
            $result = mysqli_query($mysqli,$sql);
            $result2 = mysqli_fetch_row($result);
            /* liberar la serie de resultados */
                  mysqli_free_result($result);
                  /* cerrar la conexion */
                  mysqli_close($mysqli);
            if($result2){
              return $result2;  
            }
            else {
                 die('no hay resultados para '.$table);
            }
        }
        
        public function numremi($mysqli){
        	/*lee el ultimo numero de remision emitido*/
        	$sql= "SELECT MAX(idremisiones) FROM remisiones";
            $result = mysqli_query($mysqli,$sql);
            $result2 = mysqli_fetch_row($result);
			$dato = $result2[0];
            if($result2){
              return $dato;  
            }
            else {
                 die('no hay resultados para el numero de remision');
            }
        }
		
		
		
		
		public function numinv($mysqli){
			//lee el ultimo movimiento del inventario. esta funcion no ha sido aplicada, porque marco inexistente, revisar
			$sql= "SELECT MAX(idinventarios) FROM inventarios";
            $result = mysqli_query($mysqli,$sql);
            $result2 = mysqli_fetch_row($result);
			$dato = $result2[0];
            if($result2){
              return $dato;  
            }
            else {
                 die('no hay resultados para el numero de movimiento inventario');
            }
			
		}
		
		
		public function compledom($calleno,$col,$del,$ciudad,$estado,$cp){
			/*toma los elementos del domicilio y los une en una cadena */
			$completo = $calleno." ".$col." ".$del." c.p.".$cp." ".$ciudad.", ".$estado;
			return $completo;
			
		}
        	
		public function llenaarts($mysqli,$remision,$codigo,$descripcion,$precio,$cantidad,$importe){
			$table = 'artremision';
			/*llena la tabla artremision con los articulos de cada remision*/
			$sqlCommand= "INSERT INTO $table (codigo,remision,descripcion,precio_unitario,cantidad,importe)
	    	VALUES ($codigo,$remision,'$descripcion',$precio,$cantidad,$importe)"
	    or die('insercion cancelada '.$table);
			
	    // Execute the query here now
	    if($query=mysqli_query($mysqli, $sqlCommand)){
	    	return 0;
	    }else{
	    	return $mysqli->connect_error;
	    }
	    
			
		}
		
	
	}/*** fin de la clase ***/
	
	class otrasdbutils{
		function __construct() {
			
		}
		
		public function ultcliente($mysqli){
			/*esta funcion trae el ultimo numero de cliente registrado */
			$sql= "SELECT MAX(idclientes) FROM clientes";
			$result = mysqli_query($mysqli,$sql);
            $result2 = mysqli_fetch_row($result);
			$dato = $result2[0];
            if($result2){
              return $dato;  
			}
			
			 /* liberar la serie de resultados */
	   				mysqli_free_result($result); 	
		}
		
		
		
	}/*** fin de la clase ***/
	
?>