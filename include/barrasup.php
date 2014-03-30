
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
     <div id="bandasup">

	        <div id="div1">
	         	<h1>Stellus Medevices</h1> 
	         </div>
	         <div id="div2">        
	         	<h3>fecha: <?php echo date("d-m-Y") ?></h3> 
	        </div> 

	       <div id="logodiv">
	          <img id="logoprinc" src="img/nuevologosimp.jpg" alt="logo stellus">  
	       </div>
	       <div id= "saludo">
	       		<h3>Bienvenido(a), <?php echo $_SESSION['username']; ?></h3>
	       </div>

	 </div>

<p></p>
     
    
<!--INCLUSION DE LA BARRA DE MENU -->
<?php
    	include_once "menu1.php";
?>



<!--SECCION DE CONTENIDO-->
        
            <?php 
            echo "<h1 id='titpag' align='center'>";
                if(!isset($titulo)){
                   
                   echo "NO HAY TITULO PARA ESTA PAGINA" ;
                }
                else {
                 echo $titulo; 
                }
             echo "</h1>"  ; 
            ?>

        
 
     
        
        
        
