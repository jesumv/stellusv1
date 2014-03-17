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
   
		

if(isset($_GET['r']) && isset($_GET['c'])){
	$remision = $_GET['r'];
	$cantremi =	$_GET['c'];
}else{$remision = ""; $cantremi= 0;}

//consultas sql -----------------------------------------------------------------------------------------------------
//definicion de datos a seleccionar
$nummax= $remision + $cantremi+1;
//datos de la remision

$query= "SELECT  idremisiones FROM remisiones WHERE idremisiones >=".$remision." AND idremisiones <".$nummax;
$datosrem= mysqli_query($mysqli,$query)or die ("Error en la consulta de datos de las remisiones.".mysqli_error($mysqli));
//variables dummy para datos en blanco
	$razon = "";
	$rfc  = "";
	$calle = "";
	$col = "";
	$del = "";
	$ciudad = "";
	$estado = "";
	$cp = "";
	$nivel = "";
	$fecha = "";
	$suc = "";
	$agen = "";
	$doc = "";
	$proc = "";
	$pac = "";
	$reg = "";
	$subt = "";
	$iva = "";
	$tot = "";
	$letra = "";
	$domicilio = "";

//CONSTRUCCION DEL REPORTE----------------------------------------------------------------------------------------------------------

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

 // Creacion del objeto de la clase heredada
        $pdf = new PDF('P','mm','letter');
        $pdf->SetDisplayMode('fullpage','continuous');
		$pdf->SetFillColor(170,170,170);
        $pdf->SetLeftMargin(20);
        $pdf->AliasNbPages();
        $pdf->SetMargins(2,2);
        
        
        $pdf->AddPage();
            
while ($fila = mysqli_fetch_row($datosrem)) {
			$remcorr = $fila[0];
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
			$pdf->Cell(65,15,'N.'.$remcorr,1,2,'C',false);
			$pdf->SetTextColor('BLACK');
			$pdf->SetFont('Arial');
			$pdf->SetFontSize(peq);
			$pdf->Cell(65,7,'',1,1,'C',true);
			$pdf->SetX(15);
			$pdf->SetFontSize(mini);
			$pdf->Cell(121,hcel,'CLIENTE: '.$razon,1,0,'L',false);
			$pdf->Cell(35,hcel,'SUCURSAL: '.$suc,1,0,'L',false);
			$pdf->Cell(30,hcel,'RFC: '.$rfc,1,1,'C',false);
			$pdf->SetX(15);
			$pdf->Cell(121,hcel,'FECHA: '.$fecha,1,0,'L',false);
			$pdf->Cell(65,hcel,'AGENTE: '.$agen,1,1,'L',false);
			$pdf->SetX(15);
			$pdf->SetFontSize(xmini);
			$pdf->Cell(186,hcel,'DOMICILIO: '.$domicilio,1,1,'L',false);
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
			$pdf->Cell(23,enc,$subt,1,1,'L',FALSE);
			$pdf->SetX(15);
			$pdf->Cell(143,enc,'',1,0,'L',FALSE);
 			$pdf->Cell(20,enc,'IVA',1,0,'L',TRUE);
			$pdf->Cell(23,enc,$iva,1,1,'L',FALSE);
			$pdf->SetX(15);
			$pdf->Cell(34,enc,'TOTAL CON LETRA',1,0,'L',TRUE);
			$pdf->Cell(109,enc,$letra,1,0,'L',FALSE);
			$pdf->Cell(20,enc,'TOTAL',1,0,'L',TRUE);
			$pdf->Cell(23,enc,$tot,1,1,'L',FALSE);
 //fin del ciclo de cita      
	       
    }
        
        
        
//CONSTRUCCION DE PAGINA--------------------------------------------------------------------------------------       
          
            
        
        $pdf->Output();  
//FIN DE PAGINA -----------------------------------------------------------------------------------


   
 
?>