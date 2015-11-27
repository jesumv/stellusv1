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
		
//directiva a la clase para rotacion de la marca de agua
require_once('rotation.php');

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
$query = "SELECT tiporem, agente FROM remisiones WHERE idremisiones=".$remision;
$tiporemision = mysqli_query($mysqli,$query)or die ("Error en la consulta de tipo de remision.".mysqli_error($mysqli));
$fila1 = mysqli_fetch_row($tiporemision);
$tiporem= $fila1[0];
$idrepresentantes = $fila1[1];
//obtencion del nombre del representante
$query = "SELECT nom_corto FROM representantes WHERE idrepresentantes=".$idrepresentantes;
$nomrep = mysqli_query($mysqli,$query)or die ("Error en la consulta de tipo de nombre representante".mysqli_error($mysqli));
$filarep = mysqli_fetch_row($nomrep);
$agen= $filarep[0];
//si es remision cliente
if($tiporem==0){
	//datos de la remision

	$query= "SELECT  t2.razon_social,t2.rfc,t2.calleno,t2.col,t2.del,t2.ciudad,t2.estado,t2.cp,t2.nivel,t1.fecha,t1.sucursal,
	t1.agente,t1.doctor,t1.procedimiento,t1.paciente,t1.registro,t1.subtotal,t1.iva,t1.total,t1.con_letra,t1.idremitido
	FROM remisiones AS t1 LEFT JOIN clientes AS t2 ON t1.idremitido = t2.idclientes
	WHERE t1.idremisiones=".$remision ;
	$datosrem= mysqli_query($mysqli,$query)or die ("Error en la consulta de datos de la remision.".mysqli_error($mysqli));
	
	$fila = mysqli_fetch_row($datosrem);
	$razon = $fila[0];
	$rfc  = $fila[1];
	$calle = $fila[2];
	$col = $fila[3];
	$del = $fila[4];
	$ciudad = $fila[5];
	$estado = $fila[6];
	$cp = $fila[7];
	$nivel = $fila[8];
	$fecha = $fila[9];
	$suc = $fila[10];
	$doc = $fila[12];
	$proc = $fila[13];
	$pac = $fila[14];
	$reg = $fila[15];
	$subt = $fila[16];
	$iva = $fila[17];
	$tot = $fila[18];
	$letra = $fila[19];
	$remitido = $fila[20];

$domicilio = $calle." ".$col." ".$del." C.P.".$cp." ".$ciudad.", ".$estado;
$preciorev= "t2.precio".$nivel;

}else{//si es remision vendedor
	//datos de la remision

		$query= "SELECT  t2.paterno,t2.materno,t2.nombre,t1.fecha,t1.subtotal,t1.iva,t1.total,t1.con_letra,t1.idremitido
		FROM remisiones AS t1 LEFT JOIN representantes AS t2 ON t1.idremitido = t2.idrepresentantes
		WHERE t1.idremisiones=".$remision ;
		$datosrem= mysqli_query($mysqli,$query)or die ("Error en la consulta de datos de la remision.".mysqli_error($mysqli));
		
		$fila = mysqli_fetch_row($datosrem);
		
		$paterno = $fila[0];
		$materno  = $fila[1];
		$nombre = $fila[2];
		$razon = $nombre." ".$paterno." ".$materno;
		$rfc  = "";
		$fecha = $fila[3];
		$suc = "";
		$agen = "";
		$doc = "";
		$proc = "";
		$pac = "";
		$reg = "";
		$subt = $fila[4];;
		$iva = $fila[5];
		$tot = $fila[6];;
		$letra = $fila[7];
		$remitido = $fila[8];
		$domicilio = "";
		$preciorev= "t2.preciost";
	}




//datos de los articulos

$query= "SELECT  t1.codigo,t2.descripcion,".$preciorev.", t1.cantidad,t1.importe,t2.alg FROM artremision as t1 
left join productos as t2 on t1.codigo=t2.codigo
WHERE  remision=".$remision ;
$datoart= mysqli_query($mysqli,$query)or die ("Error en la consulta de articulos de la remision.".mysqli_error($mysqli));
$indice = 0;
while ($fila = mysqli_fetch_row($datoart)) {
       $arts[$indice][0]= $fila[0];
	if ($remitido==2 && $tiporem==0){
		$arts[$indice][1] = $fila[1]." ALG:".$fila[5];
	}else{
		$arts[$indice][1] = $fila[1];
	}
      
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
//el letrero de material en custodia
define('custodia','material en custodia');

define('tiporem',$tiporem);
define('norem',$remision);

 
 

//FUENTES------------------------------------------------------------------------------------------------------------
//la ruta a las fuentes
define('FPDF_FONTPATH','c:/xampp/htdocs/stellusv1/php/font');

//FUENTE normal
define('grande',18);
define('nor',12);
define('peq',10);
define('mini',8);
define('xmini',7);

//CLASE PDF--------------------------------------------------------------------------------------------------------------------

 class PDF extends PDF_Rotate
 
    {
        function Header(){
        	//mostrar marca de agua si el tipo de remision es vendedor
        	if(tiporem == 1)
			{
				$this->SetFont('Arial', 'B', 30);
    			$this->SetTextColor(135, 132, 132);
    			$this->RotatedText(50, 235, 'MATERIAL EN CUSTODIA', 45);
			}
    		
                    
        }
		
		function RotatedText($x, $y, $txt, $angle)
			{
			    //Text rotated around its origin
			    $this->Rotate($angle, $x, $y);
			    $this->Text($x, $y, $txt);
			    $this->Rotate(0);
			}
		
		
		
		function Footer(){
            
          
        }
        
        

}//FIN DE LA CLASE PDF-----------------------------------------------------------------------------------------------------------  

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
			$pdf->Cell(65,15,'N.'.norem,1,2,'C',false);
			$pdf->SetTextColor('BLACK');
			$pdf->SetFont('Arial');
			$pdf->SetFontSize(peq);
			$pdf->Cell(65,7,'',1,1,'C',true);
			$pdf->SetX(15);
			$pdf->SetFontSize(mini);
			$pdf->Cell(121,hcel,'CLIENTE: '.$razon,1,0,'L',false);
			$pdf->Cell(35,hcel,'FECHA: '.$fecha,1,0,'L',false);
			$pdf->Cell(30,hcel,'RFC: '.$rfc,1,1,'C',false);
			$pdf->SetX(15);
			$pdf->Cell(121,hcel,'SUCURSAL: '.$suc,1,0,'L',false);
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
			$pdf->SetFontSize(xmini);		
			$pdf->Cell(90,enc,$arts[$i][1],'TB',0,'L',FALSE);
			$pdf->SetFontSize(mini);
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

