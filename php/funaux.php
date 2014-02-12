<?php
//version 2.0 Julio 23, 2013.

function exisarch($nomarchivo){
  // esta rutina verifica si un archivo existe en el directorio especificado
     

    if (file_exists($nomarchivo)) {
       return 0;
    } else {
        return -1;
    }
}

//--------------------------------------------------------------------------------
function uploader($archabrir){
    //Esta funcion toma los datos de un archivo y lo sube al forlder uploads
    
        $target_path = "../uploads/";
        
        /* Add the original filename to our target path.  
        Result is "uploads/filename.extension" */
        $target_path = $target_path . basename( $_FILES[$archabrir]['name']); 
        
        
        //call to the move:uploadedfile function
        // java srcipt from http://forums.phpfreaks.com/topic/213143-php-message-box-popup/
        
        if(move_uploaded_file($_FILES[$archabrir]['tmp_name'], $target_path)) {
            
                return true;
                
        } else{
            
               return false;
        }
        
    }
//-------------------------------------------------------------------------------------------

function creaLog($data){
//esta funcion crea un log de errores cuando no se puede insertar una orden de compra.
//solo funciona si la rutina de llamado esta en el directorio raiz.
    $file = "logs/erroressql.txt";
    $fh = fopen($file, 'a') or die("can't open file");
    fwrite($fh,date("c").$data);
    fwrite($fh, "\n");
    
    fclose($fh);
}

//-------------------------------------------------------------------------------------------


/**
 * sum values in array optional index that is to be summed
 *
 * @param array $arr
 * @param string [optional]$index
 * @return int result
 */
function array_sum_key( $arr, $index = null ){
    if(!is_array( $arr ) || sizeof( $arr ) < 1){
        return 0;
    }
    $ret = 0;
    foreach( $arr as $id => $data ){
        if( isset( $index )  ){
            $ret += (isset( $data[$index] )) ? $data[$index] : 0;
        }else{
            $ret += $data;
        }
    }
    return $ret;
}


function ponnombres($query) {
    //esta funcion crea una tabla con los datos del query pasado como argumento
    //se ha modificado para que la primera columna sea una imagen con link.
    $numfields = mysqli_num_fields($query);
    echo '<table class="db-table"><tr>';
    echo '<th></th>';
    for ($i = 0; $i<$numfields; $i += 1) {
        $field = mysqli_fetch_field($query);
        echo '<th>' . $field->name . '</th>';
    }
    echo '</tr>';
    while ($fielddata = mysqli_fetch_array($query, MYSQLI_NUM)) {
        echo '<tr>';
        echo '<td><a href = "altaarticulo.php"><img src="img/edita.jpg" ALT="editar"></a></td>';
        for ($i = 0; $i<$numfields; $i += 1) {
            $field = mysqli_fetch_field($query, $i);
            echo '<td>' . $fielddata[$field->name] . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>'; 

}
    
    
function ArrayFromCsv($file,$delimiter) {
    //esta funcion recibe el nombre de un archivo csv, y su delimitador y devuelve un arreglo con los datos del archivo.
        if (($handle = fopen($file, "r")) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter)) !== FALSE) {
                for ($j=0; $j<count($lineArray); $j++) {
                    $data2DArray[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        return $data2DArray;
    } 



function colorestado($orden){
  //esta funcion regresa el color de fondo y el texto para la casilla de estado, dependiendo del estado de confirmacion de una orden
  //consulta SQL del estado de la orden
  $querya=mysql_query("SELECT status_maestro FROM orden_resumen WHERE orden = $orden ")
  or die ("Error en la consulta de estado de la orden".mysql_error());
  $queryb=mysql_fetch_array($querya);
  $estado= $queryb[0];
  
  //mensaje segun el estado de la orden
  
  switch ($estado) {
      case 0:
          
          return array("POR CONFIRMAR","uno") ;
          
      case 4:
          
          return array("SURTIDA PARCIAL","dos") ;
          
      case 5:
          
          return array("SURTIDA PARCIAL","dos") ;
          
      case 6:
          
          return array("SURTIDA","dos") ;
          
      case 7:
          
          return array("SURTIDA","dos") ;
          
          
      case 9:
          
          return array("CONFIRMADA PARCIAL","dos");
      
      default:
          
          return array("ERROR","");
  }
    
}

?>


