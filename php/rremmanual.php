<?php

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
		
//directiva a la clase fpdf

require_once ('fpdf.php');
//VARIABLES GLOBALES----------------------------------------------------------------------------------------------------------

//recepcion de variables desde la pagina de llamada. el numero de remision.
global $remision;
global $indice;

//CONSTANTES---------------------------------------------------------------------------------------------------------
    //arreglos para la lista de articulos
        $arts= array();
    //arreglo para las cantidades de articulos
        $cantar = array();
		

if(isset($_GET['r'])){
	$remision = $_GET['r'];
	
}else{$remision = "";}

//consultas sql -----------------------------------------------------------------------------------------------------
//definicion de datos a seleccionar por tipo de remision
	//datos de la remision

$query= "SELECT  t1.cliente,t2.rfc,t2.calleno,t2.col,t2.del,t2.ciudad,t2.estado,t2.cp,t2.nivel,t1.fecha,t1.sucursal,
t1.agente,t1.doctor,t1.procedimiento,t1.paciente,t1.registro,t1.subtotal,t1.iva,t1.total,t1.con_letra,t1.domicilio,t1.rfc
FROM remisiones AS t1 LEFT JOIN clientes AS t2 ON t1.idremitido = t2.idclientes
WHERE t1.idremisiones=".$remision ;
$datosrem= mysqli_query($mysqli,$query)or die ("Error en la consulta de datos de la remision.".mysqli_error($mysqli));


//definicion del nombre datos para la remision

$fila = mysqli_fetch_row($datosrem);
$razon = $fila[0];
$calle = $fila[2];
$col = $fila[3];
$del = $fila[4];
$ciudad = $fila[5];
$estado = $fila[6];
$cp = $fila[7];
$nivel = $fila[8];
$fecha = $fila[9];
$suc = $fila[10];
$agen = $fila[11];
$doc = $fila[12];
$proc = $fila[13];
$pac = $fila[14];
$reg = $fila[15];
$subt = $fila[16];
$iva = $fila[17];
$tot = $fila[18];
$letra = $fila[19];
$domicilio= $fila[20];
$rfc = $fila[21];

//obtencion del nombre del representante
$query = "SELECT nom_corto FROM representantes WHERE idrepresentantes=".$agen;
$nomrep = mysqli_query($mysqli,$query)or die ("Error en la consulta de tipo de nombre representante".mysqli_error($mysqli));
$filarep = mysqli_fetch_row($nomrep);
$nomagen= $filarep[0];

//datos de los articulos

$query= "SELECT  t1.codigo,t2.descripcion,t1.precio_unitario, t1.cantidad,t1.importe FROM artremision as t1 left join productos as t2
ON t1.codigo=t2.codigo WHERE  remision=".$remision ;
$datoart= mysqli_query($mysqli,$query)or die ("Error en la consulta de articulos de la remision.".mysqli_error($mysqli));
$indice = 0;
while ($fila = mysqli_fetch_row($datoart)) {
       $arts[$indice][0]= $fila[0];
       $arts[$indice][1] = $fila[1];
       $arts[$indice][2]= $fila[2];
	   $arts[$indice][3] = $fila[3];
	   $arts[$indice][4] = $fila[4];
       $indice++;
    }



//COSNTRUCCION DEL REPORTE----------------------------------------------------------------------------------------------------------

//definicion de constantes de formato--------------------------------------------------------------------------------
//ancho de la hoja
 define('ah', 280);
 //margenes
 	//derecho en mm.
 define('mr', 20);
 	//superior en mm.
 define('ms', 30);
 
 //la anchura de una celda
define('acel',14);
//encabezado de tabla
define('enc',7);
 //altura de celda
define('hcel', 10);
//la altura de un titulo
define('htit', 5);
//el numero de renglones de una página
define('rengt', 16);

 
 

//FUENTES------------------------------------------------------------------------------------------------------------
//la ruta a las fuentes
define('FPDF_FONTPATH','/font/');

//FUENTE normal
define('grande',18);
define('nor',12);
define('peq',10);
define('mini',8);
define('xmini',7);

//CLASE PDF--------------------------------------------------------------------------------------------------------------------
 class PDF extends FPDF
 
    {
        function Header(){
        
                    
        }
		
		function Footer(){
            
          
        }
        
        

}   
             
			

			

            

        
        
//FIN DE LA CLASE PDF-----------------------------------------------------------------------------------------------------------  

// Creación del objeto de la clase heredada
        $pdf = new PDF('P','mm','letter');
        $pdf->SetDisplayMode('fullpage','continuous');
		$pdf->SetFillColor(170,170,170);
        $pdf->SetLeftMargin(20);
        $pdf->AliasNbPages();
        $pdf->SetMargins(2,2);
        
        
        $pdf->AddPage();
        
//CONSTRUCCION DE PAGINA--------------------------------------------------------------------------------------       
          
            $pdf->SetFont('Arial','',10);
			$pdf->Ln(29);
			$pdf->Image('../img/logoremis.jpg',15,null,85);
			$pdf->SetXY(15,30);
			$pdf->Cell(83,37,'','TBL',0,'C',false);
			$pdf->Cell(38,37,'','TB',0,'C',false);
			$pdf->SetFontSize(grande);
            $pdf->Cell(65,15,'REMISION',1,2,'C',true);
			$pdf->SetFont('Times');
			$pdf->SetTextColor(44,4,105);
			$pdf->Cell(65,15,'N.'.$remision,1,2,'C',false);
			$pdf->SetTextColor('BLACK');
			$pdf->SetFont('Arial','B');
			$pdf->SetFontSize(peq);
			$pdf->Cell(65,7,'',1,1,'C',true);
			$pdf->SetX(15);
			$pdf->SetFontSize(mini);
			$pdf->Cell(15,hcel,'CLIENTE: ','TBL',0,'L',false);
			$pdf->SetFont('Arial');
			$pdf->Cell(106,hcel,$razon,'TBR',0,'L',false);
			$pdf->SetFont('Arial','B');
			$pdf->Cell(10,hcel,'FECHA: ','TBL',0,'L',false);
			$pdf->SetFont('Arial');
			$pdf->Cell(25,hcel,$fecha,'TBR',0,'R',false);
			$pdf->SetFont('Arial','B');
			$pdf->Cell(7,hcel,'RFC: ','TBL',0,'C',false);
			$pdf->SetFont('Arial');
			$pdf->Cell(23,hcel,$rfc,'TBR',1,'C',false);
			$pdf->SetX(15);
			$pdf->SetFont('Arial','B');
			$pdf->Cell(15,hcel,'SUCURSAL: ','TBL',0,'L',false);
			$pdf->SetFont('Arial');
			$pdf->Cell(106,hcel,$suc,'TBR',0,'L',false);
			$pdf->SetFont('Arial','B');
			$pdf->Cell(20,hcel,'AGENTE: ','TBL',0,'L',false);
			$pdf->SetFont('Arial');
			$pdf->Cell(45,hcel,$nomagen,'TBR',1,'L',false);
			$pdf->SetX(15);
			$pdf->SetFontSize(xmini);
			$pdf->SetFont('Arial','B');
			$pdf->Cell(20,hcel,'DOMICILIO: ','TBL',0,'L',false);
			$pdf->SetFont('Arial');
			$pdf->Cell(166,hcel,$domicilio,'TBR',1,'L',false);
			$pdf->SetFontSize(mini);
			$pdf->Ln(2);
			$pdf->SetX(15);
			$pdf->Cell(28,hcel,'DOCTOR: ',1,0,'L',TRUE);
			$pdf->Cell(62,hcel,$doc,1,0,'L',FALSE);
			$pdf->Cell(32,hcel,'PROCEDIMIENTO: ',1,0,'L',TRUE);
			$pdf->Cell(64,hcel,$proc,1,1,'L',FALSE);
			$pdf->SetX(15);
			$pdf->Cell(28,hcel,'PACIENTE: ',1,0,'L',TRUE);
			$pdf->Cell(62,hcel,$pac,1,0,'L',FALSE);
			$pdf->Cell(32,hcel,'REGISTRO: ',1,0,'L',TRUE);
			$pdf->Cell(64,hcel,$reg,1,1,'L',FALSE);
			$pdf->Ln(2);
			$pdf->SetX(15);
//TABLA DE REGISTROS
			$pdf->SetFillColor(204,209,180);
			$pdf->Cell(30,enc,'CODIGO','LTB',0,'L',TRUE);
			$pdf->Cell(90,enc,'DESCRIPCION','TB',0,'L',TRUE);
			$pdf->Cell(30,enc,'PRECIO UNITARIO','TB',0,'L',TRUE);
			$pdf->Cell(19,enc,'CANTIDAD','TB',0,'L',TRUE);
			$pdf->Cell(17,enc,'IMPORTE','TBR',1,'L',TRUE);
 
 //la tabla
 	for($i=0;$i<15;$i++){
 		if($i<$indice){
 			$pdf->SetX(15);
 			$pdf->Cell(30,enc,$arts[$i][0],'LTB',0,'L',FALSE);
			$pdf->Cell(90,enc,$arts[$i][1],'TB',0,'L',FALSE);
			$pdf->Cell(30,enc,number_format($arts[$i][2],2),'TB',0,'L',FALSE);
			$pdf->Cell(19,enc,$arts[$i][3],'TB',0,'L',FALSE);
			$pdf->Cell(17,enc,number_format($arts[$i][4],2),'TBR',1,'L',FALSE);
 		}else{
 			

 			$pdf->SetX(15);
 			$pdf->Cell(30,enc,'','LTB',0,'L',FALSE);
			$pdf->Cell(90,enc,'','TB',0,'L',FALSE);
			$pdf->Cell(30,enc,'','TB',0,'L',FALSE);
			$pdf->Cell(19,enc,'','TB',0,'L',FALSE);
			$pdf->Cell(17,enc,'','TBR',1,'L',FALSE);
			 		}
 	}
			$pdf->SetX(15);
			$pdf->Cell(143,enc,'',1,0,'L',FALSE);
 			$pdf->Cell(20,enc,'SUBTOTAL',1,0,'L',TRUE);
			$pdf->Cell(23,enc,number_format($subt,2),1,1,'L',FALSE);
			$pdf->SetX(15);
			$pdf->Cell(143,enc,'',1,0,'L',FALSE);
 			$pdf->Cell(20,enc,'IVA',1,0,'L',TRUE);
			$pdf->Cell(23,enc,number_format($iva,2),1,1,'L',FALSE);
			$pdf->SetX(15);
			$pdf->Cell(34,enc,'TOTAL CON LETRA',1,0,'L',TRUE);
			$pdf->Cell(109,enc,$letra,1,0,'L',FALSE);
			$pdf->Cell(20,enc,'TOTAL',1,0,'L',TRUE);
			$pdf->Cell(23,enc,number_format($tot,2),1,1,'L',FALSE);
 //fin del ciclo de cita      
        
        $pdf->Output();  
//FIN DE PAGINA -----------------------------------------------------------------------------------


   
 
?>