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

if(isset($_POST['altaprov'])){
    
   header('location:modifprov2.php?nid=-99');
    
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<link rel="stylesheet" type="text/CSS" href="css/plantilla2.css" />
<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
 <script src="js/jquery-1.10.2.js"></script>
<title>STELLUS MEDEVICES</title>


  <script>
  $( document ).ready(function() {
            $( ".ed" ).click(function(eventObject ) {
                eventObject.preventDefault();
                var currentId = $(this).attr('id');
                window.open('modifprov2.php?nid='+currentId,'_self')
            });
            
            $( ".el" ).click(function(eventObject ) {
                eventObject.preventDefault();
                var currentId = $(this).attr('id');
                window.open('elimprov.php?nid='+currentId,'_self')
            });
        });
  </script>
  

</head>

<body

<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
  $titulo = "CATALOGO DE PROVEEDORES";
  include_once "include/barrasup.php";
 //------consulta a la base de datos------
  

//-----CONSTRUCCION DE LA TABLA------------------------------------------------------------------------
 $table = 'proveedores';
 $sql= "SELECT idproveedores,razon_social,nom_corto,direccion FROM $table WHERE status != 2 ";
 $result2 = mysqli_query($mysqli,$sql) or die('no hay resultados para '.$table);

    if(mysqli_num_rows($result2)) {
        echo '<table cellpadding="0" cellspacing="0" class="db-table">';
        echo '<tr><th>Editar</th><th>Eliminar</th><th>No.</th><th>Nombre</th><th>Nombre Corto</th><th>Dirección</th></tr>';
        //inicializacion de contador de renglon
        $reng = 1;
        while($row2 = mysqli_fetch_row($result2)) {
            $id = $row2[0];
            $elid = -$row2[0];
            echo '<tr>';
            echo '<td class= ed id='.$id.'><a href ="modifprov2.php?nid='.$id.'"><img src="img/edita.jpg" ALT="editar"></a></td>';
            echo '<td class = el id='.$elid.'><a href ="elimprov.php?nid='.$elid.'"><img src="img/elimina.jpg" ALT="eliminar"></a></td>';
            foreach($row2 as $key=>$value) {
                echo '<td>',$value,'</td>';
            }
            echo '</tr>';
        $reng= $reng++;
        }
        echo '</table><br />';
    }
 
 
  /* liberar la serie de resultados */
  mysqli_free_result($result2);
  /* cerrar la conexión */
  mysqli_close($mysqli);
  
  ?> 
  
<div class="cajacentrada"><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST"> 
   <input type="submit" name ="altaprov" value="nuevo proveedor" /> 
   </form>
</div>
  
<div id="footer"></div>


</body>


</html>

