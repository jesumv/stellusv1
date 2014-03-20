<?php
//ESTA HOJA MUESTRA CAMPOS DE CAPTURA PARA LA TABLA DE USUARIOS
 //directiva de la conexion a la base de datos
include_once "php/config.php";   
//directiva al archivo de funciones auxiliares
include_once "php/funaux.php"; 
//directiva a la revision de conexion
//include_once"php/lock.php";

//CONSULTAS SQL

$sql="SELECT * FROM usuarios WHERE 1" ;
$result=mysqli_query($mysqli,$sql)or die ("Error en la consulta de usuarios.".mysql_error());
$cols = mysqli_num_fields($result);
$rengs= mysqli_num_rows($result);

$result->free();

//alta del usuario en la base de datos
if(isset($_POST['enviou'])){
//VALIDACIONES

//CONVERSIONES
$nombre = $_POST['nombre'];
$user = mysqli_real_escape_string($mysqli,$_POST['usuario']) ;;

$pw=mysqli_real_escape_string($mysqli,$_POST['pw']) ;
$nivel = $_POST['nivel'];


//string de llenado de campos tabla admin
               $querya = sprintf("INSERT INTO usuarios (nombre,username,passcode,nivel) 
               VALUES ('$nombre','$user',(AES_ENCRYPT('%s','%s')),$nivel)",$pw,$pw);
         
//lenado de campos
               
                $resultal=mysqli_query($mysqli,$querya) or die("Error en alta usuario: ".mysqli_error());
				
                 if($resultal){
//el registro se inserto correctamente
                    echo '<script type="text/javascript">
                            window.alert("Usuario añadido correctamente!");
                        </script>';
                     
                  }
                 else{
                 //No se pudo lograr la insercion, crea una entrada en el log
                 
                        echo '<script type="text/javascript">
                            window.alert("Error. No se pudo dar de alta el usuario!");
                        </script>';
                        
                       echo "error en alta articulo".mysql_error(); 
                       creaLog(mysql_error());
                 }
    
  //TODO:REVISAR SI SE DEBE EMPLEAR FREE PARA resultal
}
    
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    
<head>
    
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />


<!--links a hojas de estilo ----------------------------------------------------->

<link rel="stylesheet" type="text/css" href="css/comun.css">
<link rel="stylesheet" type="text/CSS" href="css/plantilla1.css" />
<link rel="shortcut icon" href="img/logomin.gif" />
<!-- links a hojas javascript ---------------------------------------------------->
<script type="text/javascript" src="js/comunes.js"></script>
<title>INTRANET ZERBY</title>

</head>

<body>
  <!--LISTON DE ENCABEZADO ----------------------------------------------------------------------------------------> 
  
  <?php 
  $titulo = "ALTA DE USUARIOS";
  include_once "include/barrasup.php" 
  ?> 
  
<!--CONSTRUCCION DE LA PAGINA ----------------------------------------------------------------------------> 
<!--FORMA CON TABLA DE USUARIOS -------------------------------------------------------------------------->
<div id="centra" align="center">
    
    <form id="altausu" action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
        <table border = "1">
            <tr>
                <td class="celdacolor">NOMBRE</td>
                <td><input name ='nombre'/> </td>
            </tr>
            <tr>
                <td class="celdacolor">NIVEL</td>
                <td><input name ='nivel'/> </td>
            </tr>
            <tr>
                <td class="celdacolor">USUARIO</td>
                <td><input name ='usuario'/> </td>
            </tr>
            <tr>
                <td class="celdacolor">CONTRASEÃ‘A</td>
                <td><input type ="password" name ='pw'/> </td>
            </tr>
        </table>
        <br />
    <!--------el boton de enviar ------------->  
           <input type="submit" name ="enviou" value="Alta" /> 
           
    </form>
    
</div>


</body>

</html>
  
