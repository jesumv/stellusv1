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

if(isset($_POST['altaprod'])){
    
   header('location:modifprod.php?nid=-99');
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<link rel="stylesheet" type="text/CSS" href="css/plantilla2.css" />
<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
<link rel="shortcut icon" href="img/logomin.gif" />
<script src="js/jquery-1.10.2.js"></script>
<title>STELLUS MEDEVICES</title>

  <script>
  $( document ).ready(function() {
            $( ".ed" ).click(function(eventObject ) {
                eventObject.preventDefault();
                var currentId = $(this).attr('id');
                window.open('modifprod.php?nid='+currentId,'_self')
            });
            
            $( ".el" ).click(function(eventObject ) {
                eventObject.preventDefault();
                var currentId = $(this).attr('id');
                window.open('elimprod.php?nid='+currentId,'_self')
            });
        });
  </script>
  

</head>

<body

<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
  $titulo = "CATALOGO DE PRODUCTOS";
  include_once "include/barrasup.php";
 //------consulta a la base de datos------
  

//-----CONSTRUCCION DE LA TABLA------------------------------------------------------------------------
 $table = 'productos';
 $table2 = 'proveedores';
 $sql= "SELECT t1.idproductos,t1.descripcion,t1.unidad,t1.precio1,t1.precio2,t1.precio3,t1.precio4,t1.precio5,t1.precio6,
 t1.preciost,t1.codigo,t2.nom_corto FROM $table as t1 LEFT JOIN $table2 as t2 ON t1.idproveedores = t2.idproveedores 
 WHERE t1.status != 2 ";
 $result2 = mysqli_query($mysqli,$sql) or die('no hay resultados para '.$table);

    if(mysqli_num_rows($result2)) {
        echo '<table cellpadding="0" cellspacing="0" class="db-table">';
        echo '<tr>
        <th>Editar</th><th>Eliminar</th><th>No.</th><th>Descripcion</th><th>unidad</th><th>Precio 1</th><th>Precio 2</th><th>Precio 3</th>
        <th>Precio 4</th><th>Precio 5</th><th>Precio 6</th><th>Precio st</th><th>Codigo</th><th>proveedor</th>
        </tr>';
        //inicializacion de contador de renglon
        while($row2 = mysqli_fetch_row($result2)) {
            $id = $row2[0];
            $elid = -$row2[0];
            echo '<tr>';
            echo '<td class= ed id='.$id.'><a href ="modifprod.php?nid='.$id.'"><img src="img/edita.jpg" ALT="editar"></a></td>';
            echo '<td class = el id='.$elid.'><a href ="elimprod.php?nid='.$elid.'"><img src="img/elimina.jpg" ALT="eliminar"></a></td>';
            foreach($row2 as $key=>$value) {
                echo '<td>',$value,'</td>';
            }
            echo '</tr>';

        }
        echo '</table><br />';
    }
 
 
  /* liberar la serie de resultados */
  mysqli_free_result($result2);
  /* cerrar la conexi�n */
  mysqli_close($mysqli);
  
  ?> 
  
	<div class="centraelem">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST"> 
		   <input type="submit" name ="altaprod" value="nuevo producto" /> 
		</form>
	</div>


  
<div id="footer"></div>


</body>


</html>

