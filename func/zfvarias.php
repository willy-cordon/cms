<?php
// FUNCIONES VARIAS
//***************************************************************************************************
function PaginarSql($inicio, $sql, $regxpag = GLBFilas)
{ //Paginacion de Sentencia SQL
	$sql	= strtoupper($sql);
	$vsql 	= substr($sql, 6, strlen($sql));
	if ($inicio == 1) $inicio = 0;
	$sqlfinal = "SELECT FIRST " . $regxpag . " SKIP " . $inicio . " " . $vsql;
	return $sqlfinal;
}
//***************************************************************************************************
function ChgTexto($idicodigo, $txt)
{ //Cambia Texto en Idiomas
	include $GLOBALS['GLBRutaTXT'] . "/txt$idicodigo.php";
	$chg = $txt;

	foreach ($txt_dias as $key => $value) {
		if (eregi($key, $txt)) {
			$chg = str_replace($key, $value, $txt);
		}
	}
	return $chg;
}
//***************************************************************************************************
function NewCmbSqlJs($SQL, $Dato, $NameObj, $Form, $conn)
{ //Debe contener los Campos como CODIGO y DESCRIPCION
	$i = 0;
	$selected = 0;

	$Table = sql_query($SQL, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$codigo = $row['CODIGO'];
		$descripcion = str_replace("'", "", $row['DESCRI']);

		if (trim($Dato) == trim($codigo)) $selected = $i + 1;
		echo  "<script> document.$Form.$NameObj.options[$i+1] = new Option('$descripcion','$codigo'); </script>";
	}
	echo  "<script> document.$Form.$NameObj.options[$selected].selected=true; </script>";
}
//***************************************************************************************************
function DateCmp($Pfecha1, $Pcmp, $Pfecha2)
{
	switch ($Pcmp) {
		case '>':
			if (strftime('%d/%m/%Y', strtotime($Pfecha1)) > strftime('%d/%m/%Y', strtotime($Pfecha2)))
				return true;
			break;
		case '<':
			if (strftime('%d/%m/%Y', strtotime($Pfecha1)) < strftime('%d/%m/%Y', strtotime($Pfecha2)))
				return true;
			break;
		case '=':
			if (strftime('%d/%m/%Y', strtotime($Pfecha1)) == strftime('%d/%m/%Y', strtotime($Pfecha2)))
				return true;
			break;
		case '>=':
			if (strftime('%d/%m/%Y', strtotime($Pfecha1)) <= strftime('%d/%m/%Y', strtotime($Pfecha2)))
				return true;
			break;
		case '<=':
			if (strftime('%d/%m/%Y', strtotime($Pfecha1)) >= strftime('%d/%m/%Y', strtotime($Pfecha2)))
				return true;
			break;
		default:
			return false;
	}
}
//***************************************************************************************************
function ReadConfig($Param)
{ //Lee la variable de Configuracion
	$Param	= strtoupper($Param);
	return $_SESSION[GLBAPPPORT . 'XXXCfg_' . $Param];
}
//***************************************************************************************************
function ConvFechaBD($fecha)
{ //Convierte Fecha a Formato "MM/DD/YYYY" - Base de Datos
	if (trim($fecha) != '') {
		$v = explode("/", $fecha);
		return "'" . $v[1] . '/' . $v[0] . '/' . $v[2] . "'";
	} else {
		return 'NULL';
	}
}
//***************************************************************************************************
function VarNullBD($dato, $tipo)
{ //Convierte a Nulo - Base de Datos
	$dato = str_replace('#MAS#', "+", $dato);

	if (strtoupper($tipo) == 'S') { //String
		if (trim($dato) == '') {
			$dato = "NULL";
		} else {
			$dato = str_replace("'", "''", $dato);
			$dato = " '$dato' ";
		}
	} else if (strtoupper($tipo) == 'N') { //Numerico
		if (trim($dato) == '') {
			$dato = "0";
		} else {
			$dato = str_replace('.', '', $dato);
			$dato = str_replace(',', '.', $dato);
		}
	}

	return $dato;
}
//***************************************************************************************************	
function ValDatoVacio($dato)
{ //Valida que el dato no este vacio o 0
	$error 	= 0;

	if (trim($dato) == '' || floatval(str_replace(',', '.', $dato)) == 0.00) {
		$error = 2;
	}

	return $error;
}
//***************************************************************************************************
function logError($value)
{
	//Genero la salida del Log
	$nomFil = date("Ymd"); 			//Nombre del Archivo
	$fchReg	= date("Y/m/d h:i:s"); 	//Fecha y Hora de Registro	
	file_put_contents(GLBRutaFUNC . "/errdb/log_$nomFil.err", "$fchReg --  $value \r\n", FILE_APPEND);
}
//***************************************************************************************************
function rowTitlesExcel($objPHPExcel, $jscols, $fila = 1, $hoja = 1)
{
	$JSColumnas = json_decode($jscols);
	foreach ($JSColumnas->cols as $ind => $config) {
		$objPHPExcel->setActiveSheetIndex($hoja)->setCellValue($config->col . $fila, $config->title);

		if (isset($config->align)) {
			switch ($config->align) {
				case 'R':
					$objPHPExcel->getActiveSheet()->getStyle($config->col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					break;
				case 'L':
					$objPHPExcel->getActiveSheet()->getStyle($config->col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					break;
				case 'C':
					$objPHPExcel->getActiveSheet()->getStyle($config->col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					break;
				case 'J':
					$objPHPExcel->getActiveSheet()->getStyle($config->col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
					break;
			}
		} else {
			$objPHPExcel->getActiveSheet()->getStyle($config->col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}

		if (isset($config->numformat)) {
			$objPHPExcel->getActiveSheet()->getStyle($config->col)->getNumberFormat()->setFormatCode($config->numformat);
		}

		$objPHPExcel->getActiveSheet()->getStyle($config->col . $fila)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle($config->col . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle($config->col . $fila)->getFill()->getStartColor()->setRGB('66A3FF');
		$objPHPExcel->getActiveSheet()->getColumnDimension($config->col)->setAutoSize(true);
	}

	return $JSColumnas;
}
//***************************************************************************************************
function repExportExcel($objPHPExcel, $wintitle)
{
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="' . $wintitle . '.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
}
function lastDayInMonth($month, $year)
{

	switch ($month) {
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			$lastdate = 31;
			break;

		case 4:
		case 6:
		case 9:
		case 11:
			$lastdate = 30;
			break;

		case 2:
			// for specific year
			$leap = date('L', mktime(0, 0, 0, 1, 1, $year));
			$lastdate = $leap ? 29 : 28;
			break;
		default:

			break;
	}

	return $lastdate;
}





//Corte de imagen centrado------------------------------------------------------------------------------------------------

function resizeCrop($image, $width, $height, $displ = 'center')
{
	/* Original dimensions */
	$origw = imagesx($image);
	$origh = imagesy($image);

	$ratiow = $width / $origw;
	$ratioh = $height / $origh;
	$ratio = max($ratioh, $ratiow); /* This time we want the bigger image */

	$neww = $origw * $ratio;
	$newh = $origh * $ratio;

	$cropw = $neww - $width;
	/* if ($cropw) */
	/*   $cropw/=2; */
	$croph = $newh - $height;
	/* if ($croph) */
	/*   $croph/=2; */

	if ($displ == 'center')
		$displ = 0.5;
	elseif ($displ == 'min')
		$displ = 0;
	elseif ($displ == 'max')
		$displ = 1;

	$new = imageCreateTrueColor($width, $height);

	imagecopyresampled($new, $image, -$cropw * $displ, -$croph * $displ, 0, 0, $width + $cropw, $height + $croph, $origw, $origh);
	return $new;
}

//-------------------------REDIMENSION DE IMAGEN-------------------------------------------------


function resizehorizontal($width, $height, $source)
{

	$newwidth = $width; //$width * $percent;
	$newheight = $height; //$height * $percent;

	if ($width >= $height) {
		$newheight = $width;
		$mayor = $height;
		// $pruba = 5;
	} else {
		$newwidth = $height;
		$mayor = $width;
		// $pruba = 5;
	}

	$img = imageCreateTrueColor($newwidth, $newheight);




	$aux = $mayor / 2;

	for ($x = 0; $x < $width; $x++) {
		for ($y = 0; $y < $height; $y++) {
			// pixel color at (x, y)
			$color = imagecolorat($source, $x, $y);
			imagesetpixel($img, round($x), round($y + $aux), $color);
		}
	}

	return $img;
}
function redimensionar_imagen($nombreimg, $rutaimg, $xmax, $ymax)
{

	$ext = pathinfo($nombreimg, PATHINFO_EXTENSION);


	//Creo imagen en el directorio-------------------
	if ($ext == "jpg" || $ext == "jpeg")
		$imagen = imagecreatefromjpeg($rutaimg);
	elseif ($ext == "png")
		$imagen = imagecreatefrompng($rutaimg);
	elseif ($ext == "gif")
		$imagen = imagecreatefromgif($rutaimg);
	//----------------------------------------------
	$widthOri = imagesx($imagen);
	$heightOri = imagesy($imagen);
	$ratio = $widthOri / $heightOri;

	if ($ratio > 1.5) {

		$imagen =	resizehorizontal($widthOri, $heightOri, $imagen);
	}

	// 	//Consulto los pixeles tanto en X como en Y
	// 	$x = imagesx($imagen);
	// 	$y = imagesy($imagen);
	// 	//-----------------------------------------

	// 	//Calculo el ratio para la recuccion de tamaño-------
	//   if ($x >= $y) {
	//  	 	$nuevax = $xmax;
	//  	 	$nuevay = $nuevax * $y / $x;
	//  	 } else {
	// 	 	$nuevax = $xmax;
	//  		$nuevay = $x / $y * $nuevay;
	//  	 }
	// 	//----------------------------------------------------

	// 	//Prueba
	$nuevax = $xmax;
	$nuevay = $ymax;

	//ANCHOR Recorte circular fondo png-------------------------------

	//Creo imagen-----------------------------------------
	$imagee = imagecreatetruecolor($nuevax, $nuevay);
	//----------------------------------------------------

	//Mezcla Alpha----------------------------------------
	imagealphablending($imagee, true);
	//----------------------------------------------------


	//Redimension de imagen---------------------------------------------------- 
	$imagee = resizeCrop($imagen, $xmax, $ymax, $displ = 'center');
	// imagecopyresampled($imagee, $imagen, 0,0 , 0, 0, $nuevax, $nuevay, $x, $y);

	//-------------------------------------------------------------------------

	//Ceo imagen mascara----------------------------------
	$mask = imagecreatetruecolor($nuevax, $nuevay);
	//----------------------------------------------------


	//creo color transparente//---------------------------
	$transparente = imagecolorallocate($mask, 255, 0, 0);
	imagecolortransparent($mask, $transparente);
	//----------------------------------------------------


	//Dibuja circulo--------------------------------------------------------------------------
	imagefilledellipse($mask, $nuevax / 2, $nuevay / 2, $nuevax, $nuevay, $transparente);
	//----------------------------------------------------------------------------------------

	//Creo color------------------------------------------
	$red = imagecolorallocate($mask, 0, 0, 0);
	//----------------------------------------------------

	//fusiona imagen---------------------------------------------------
	imagecopymerge($imagee, $mask, 0, 0, 0, 0, $nuevax + 200, $nuevay + 300, 100);
	imagecolortransparent($imagee, $red);
	//-----------------------------------------------------------------


	//rellena la imagen-----------------------------------
	imagefill($imagee, 0, 0, $red);
	//----------------------------------------------------


	//Liberamos memoria borrando la mascara---------------
	imagedestroy($mask);
	//----------------------------------------------------

	return $imagee;
}
//-------------------------------------------------------------------------------------------------------
