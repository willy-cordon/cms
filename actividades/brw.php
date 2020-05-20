<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------


$conn = sql_conectar(); //Apertura de Conexion
//Busco los parametros de configuracion
$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
}

$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
$diasdur = intval($params['SisEventoDuracionDias']); 	 	//Evento - Cantidad de Dias de duracion
$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)

$tmpl->setVariable('horaint', $horaint);

$inicio = substr($diasini, 6, 4) . '-' . substr($diasini, 3, 2) . '-' . substr($diasini, 0, 2); //Formato calendario (yyyy-mm-dd)



$hoy = date('Y-m-d');
$tmpl->setVariable('hoy', $inicio);


$coloReunion	= '#967ADC';
$colorBloqueado	= '#FFAD8F';
$where 	= '';


//Habilito las opciones del Menu
if (json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false) {
	$tmpl->setVariable('ParamMenuActividades', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false) {
	$tmpl->setVariable('ParamMenuAgenda', 'display:none;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false) {
	$tmpl->setVariable('ParamMenuMensajes', 'display:none;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false) {
	$tmpl->setVariable('ParamMenuNoticias', 'display:none;');
}




//--------------------------------------------------------------------------------------------------------------
//-----Seleccionamos los datos de la base de datos
// $query="SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR 
// 		FROM AGE_MAEST
// 		WHERE ESTCODIGO<>3";


// $Table = sql_query($query,$conn);
// for($i=0; $i<$Table->Rows_Count; $i++){
// 	$row = $Table->Rows[$i];
// 	$agereg 	= trim($row['AGEREG']);
// 	$agetitulo 	= trim($row['AGETITULO']);
// 	$agedescri 	= trim($row['AGEDESCRI']);
// 	$agelugar   = trim($row['AGELUGAR']);
// 	$agefch     = BDConvFch($row['AGEFCH']);
// 	$agehorini  = substr(trim($row['AGEHORINI']),0,5);
// 	$agehorfin  = substr(trim($row['AGEHORFIN']),0,5);

// 	$agefch	= substr($agefch,6,4).'-'.substr($agefch,3,2).'-'.substr($agefch,0,2); //Formato calendario (yyyy-mm-dd)
// 	$agehorini = ($agehorini!='')? 'T'.$agehorini: '';
// 	$agehorfin = ($agehorfin!='')? 'T'.$agehorfin: '';

// 	$tmpl->setCurrentBlock('actividadaes');
// 	$tmpl->setVariable('agereg'		, $agereg);
// 	$tmpl->setVariable('agetitulo'	, $agetitulo);
// 	$tmpl->setVariable('agedescri'	, $agedescri);
// 	$tmpl->setvariable('agelugar'	, $agelugar);
// 	$tmpl->setVariable('agehorini'	, $agefch.$agehorini);
// 	$tmpl->setVariable('agehorfin'	, $agefch.$agehorfin);
// 	$tmpl->setVariable('color'		, $coloReunion);
// 	$tmpl->parse('actividadaes');

// }


$active = 'active';

$diaInicial =  substr($diasini, 0, 2) + 1 - 1;
$finalEvento =  $diasdur +  $diaInicial;
$contadorDias = 1;

for ($i = $diaInicial; $i < $finalEvento; $i++) {

	//
	$tmpl->setCurrentBlock('tabs');
	$tmpl->setVariable('active', $active);

	$tmpl->setVariable('dia', 'Dia ' . $contadorDias);
	$tmpl->setVariable('id', $i);
	$tmpl->parse('tabs');



	$query = 'SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR 
				FROM AGE_MAEST
				WHERE  EXTRACT (DAY FROM AGEFCH) = ' . $i;


	$Table = sql_query($query, $conn);


	$tmpl->setCurrentBlock('dias');
	$tmpl->setVariable('active', $active);
	$tmpl->setVariable('numeroDia', $i + 1 - 1);
	if ($Table->Rows_Count != -1) {
		for ($u = 0; $u < $Table->Rows_Count; $u++) {

			$row = $Table->Rows[$u];
			$agereg 	= trim($row['AGEREG']);
			$agetitulo 	= trim($row['AGETITULO']);
			$agedescri 	= trim($row['AGEDESCRI']);
			$agelugar   = trim($row['AGELUGAR']);
			$agefch     = BDConvFch($row['AGEFCH']);
			$agehorini  = substr(trim($row['AGEHORINI']), 0, 5);
			$agehorfin  = substr(trim($row['AGEHORFIN']), 0, 5);

			$agefch	= substr($agefch, 6, 4) . '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 0, 2); //Formato calendario (yyyy-mm-dd)
			$agehorini = ($agehorini != '') ? 'T' . $agehorini : '';
			$agehorfin = ($agehorfin != '') ? 'T' . $agehorfin : '';




			if (substr($agefch, 8, 9) + 1 - 1  === $i) {
				$tmpl->setCurrentBlock('actividades');
				$tmpl->setVariable('display', 'flex');

				$tmpl->setVariable('agereg', $agereg);
				$tmpl->setVariable('active', $active);
				$tmpl->setVariable('agetitulo', $agetitulo);
				$tmpl->setVariable('agedescri', $agedescri);
				$tmpl->setvariable('agelugar', $agelugar);
				$tmpl->setVariable('agehorini', $agefch . $agehorini);
				$tmpl->setVariable('hora', substr(trim($row['AGEHORINI']), 0, 5));
				$tmpl->setVariable('agehorfin', $agefch . $agehorfin);

				$tmpl->setVariable('color', $coloReunion);
				$tmpl->parse('actividades');
			}
		}
	} else {
		$tmpl->setCurrentBlock('actividades');
		$tmpl->setVariable('msg', 'No se encontraron eventos para este dia');
		$tmpl->setVariable('display', 'none');

		$tmpl->parse('actividades');
	}
	$tmpl->parse('dias');
	$active = '';
	$contadorDias++;
}










sql_close($conn);
$tmpl->show();

?>	
