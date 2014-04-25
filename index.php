<?php

 /*** Autoload class files ***/
    function __autoload($class){
      require('include/' . strtolower($class) . '.class.php');
    }
    //directiva a la conexion con base de datos
    $funcbase = new dbutils;
    $mysqli = $funcbase->conecta();
	
 /*** si se establecio la conexion***/
    if (is_object($mysqli)) {
        session_start();

        $error = "";

            if($_SERVER["REQUEST_METHOD"] == "POST")
                {
                // username and password sent from form 
                $myusername=mysqli_real_escape_string ($mysqli,$_POST['username']);
                $mypassword=mysqli_real_escape_string($mysqli,$_POST['password']); 
                $sql=sprintf("SELECT id,nombre,empresa,nivel FROM usuarios WHERE username='$myusername' 
                and passcode=(AES_ENCRYPT('%s','%s'))",$mypassword,$mypassword);
                $result=mysqli_query($mysqli,$sql);
                $row=mysqli_fetch_array($result);
                $nivel =$row[3];
                $username = $row[1];
                $empre = $row[2];
                
                $count=mysqli_num_rows($result);
                
                $result->free();
                
                $mysqli->close();
                
                // If result matched $myusername and $mypassword, table row must be 1 row
                if($count==1)
                {
                    
                $_SESSION['login_user']=$myusername;
                $_SESSION['username']=$username;
                $_SESSION['nivel']=$nivel;
                $_SESSION['empresa']=$empre;
                
                //seleccion de hoja según empresa
                    switch ($empre) {
                        case 0:
                            header("location: portal.php");
                            break;
                        
                        default:
                             header("location: php/logout.php");
                            break;
                    }
                
            
            }
        //los datos de acceso no son correctos    
        else 
            {
                $error="Su nombre de usuario o contraseña son invalidos";
            }
        }
        
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
    
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
        <meta name="viewport" content="width=device-width">
        <title>STELLUS MEDEVICES</title>
         <link rel="stylesheet" type="text/CSS" href="css/plantilla1.css" />
         <link rel="shortcut icon" href="img/logomin.gif" />
         
    </head>
     <body >

    		

            <div id="bandasup">
              
             
                
                <div id="loginbox">
            
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <label>Usuario  :</label><input type="text" name="username" class="box"/>
                        <label>Contraseña :</label><input type="password" name="password" class="box" />
                        <input type="submit" value=" Enviar "/><br />
                    </form>
                    
                        <div style="font-size:16px; color:#cc0000; margin-top:10px" align="center"> <?php echo $error; ?></div>
                
                </div>
                
                <h1 id="titprinc" >Stellus Medevices</h1>
                
                <div >
                  <img id="logoprinc1" src="img/nuevologosimp.jpg" alt="logo stellus" />  
                 </div>
           </div>      			
    	
       		<div id="footer"></div>
    </body>
</html>
