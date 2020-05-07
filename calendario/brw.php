<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma			
$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli = (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc = (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$percorreo = (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';


$orientacionSwitch = 1;

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
$colorDisponible = '#58BA9B';
$where 	= '';

//Reuniones que solicitaron
$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERCORREO,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUHORA+1800 AS HORAFIN,R.REUREG
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE R.PERCODDST=$percodigo AND R.REUESTADO=2
				ORDER BY P.PERNOMBRE ";
$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percod 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$percompan	= trim($row['PERCOMPAN']);
	$percorreo	= trim($row['PERCORREO']);
	$reufecha	= BDConvFch($row['REUFECHA']);
	$reuhoraini	= substr(trim($row['REUHORA']), 0, 5);
	$reuhorafin	= substr(trim($row['HORAFIN']), 0, 5);
	$reuestado	= trim($row['REUESTADO']);
	$reureg		= trim($row['REUREG']);
	/**
	 * @function substr (Orden fecha)
	 */
	$reufecha	= substr($reufecha, 6, 4) . '-' . substr($reufecha, 3, 2) . '-' . substr($reufecha, 0, 2); //Formato calendario (yyyy-mm-dd)
	$reuhoraini = ($reuhoraini != '') ? 'T' . $reuhoraini : '';
	$reuhorafin = ($reuhorafin != '') ? 'T' . $reuhorafin : '';


	$tmpl->setCurrentBlock('reuniones');
	logerror("	SOLCITARON: " . $percodigo);
	$tmpl->setVariable('reureg', $reureg);
	$tmpl->setVariable('percodigo', $percod);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->setVariable('perapelli', $perapelli);
	$tmpl->setVariable('percompan', $percompan);
	$tmpl->setVariable('reufchini', $reufecha . $reuhoraini);
	$tmpl->setVariable('reufchfin', $reufecha . $reuhorafin);
	$tmpl->setVariable('color', $coloReunion);

	if ($orientacionSwitch == 1) {

		$orientacion = 'left';
		$tmpl->setVariable('orientacion', $orientacion);
		$orientacionSwitch = 0;
	} else {
		$orientacion = 'right';
		$tmpl->setVariable('orientacion', $orientacion);
		$orientacionSwitch = 1;
	}
	$tmpl->parse('reuniones');
	$tmpl->parse('reuniones');
}

//Reuniones que solicite
$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERCORREO,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUHORA+1800 AS HORAFIN,R.REUREG
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODDST
				WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=2
				ORDER BY P.PERNOMBRE ";
$Table = sql_query($query, $conn);


for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percod 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$percompan	= trim($row['PERCOMPAN']);
	$percorreo	= trim($row['PERCORREO']);
	$reufecha	= BDConvFch($row['REUFECHA']);
	$reuhoraini	= substr(trim($row['REUHORA']), 0, 5);
	$reuhorafin	= substr(trim($row['HORAFIN']), 0, 5);
	$reuestado	= trim($row['REUESTADO']);
	$reureg		= trim($row['REUREG']);

	$reufecha	= substr($reufecha, 6, 4) . '-' . substr($reufecha, 3, 2) . '-' . substr($reufecha, 0, 2); //Formato calendario (yyyy-mm-dd)
	$reuhoraini = ($reuhoraini != '') ? 'T' . $reuhoraini : '';
	$reuhorafin = ($reuhorafin != '') ? 'T' . $reuhorafin : '';

	$tmpl->setCurrentBlock('reuniones');
	logerror("	SOLICITE: ");
	$tmpl->setVariable('reureg', $reureg);
	$tmpl->setVariable('percodigo', $percod);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->setVariable('perapelli', $perapelli);
	$tmpl->setVariable('percompan', $percompan);
	$tmpl->setVariable('reufchini', $reufecha . $reuhoraini);
	$tmpl->setVariable('reufchfin', $reufecha . $reuhorafin);
	$tmpl->setVariable('color', $coloReunion);

	if ($orientacionSwitch == 1) {

		$orientacion = 'left';
		$tmpl->setVariable('orientacion', $orientacion);
		$orientacionSwitch = 0;
	} else {
		$orientacion = 'right';
		$tmpl->setVariable('orientacion', $orientacion);
		$orientacionSwitch = 1;
	}
	$tmpl->parse('reuniones');
	$tmpl->parse('reuniones');
}

//Recorro la disponibilidad para encontar los horarios bloqueados

$queryDisp = "SELECT PERDISFCH,PERDISHOR 
				FROM PER_DISP
				WHERE PERCODIGO=$percodigo 
				ORDER BY PERDISFCH,PERDISHOR ";
$TableDet = sql_query($queryDisp, $conn);
$dataDisp = array();
for ($j = 0; $j < $TableDet->Rows_Count; $j++) {
	$rowDet = $TableDet->Rows[$j];
	$perdisfch 	= BDConvFch($rowDet['PERDISFCH']);
	$perdishor 	= substr(trim($rowDet['PERDISHOR']), 0, 5);

	$itm = array();
	$itm['fecha'] = $perdisfch;
	$itm['hora'] = $perdishor;

	array_push($dataDisp, $itm);
}


$vdia 	= explode('/', $diasini);
$tdia	= $vdia[2] . '-' . $vdia[1] . '-' . $vdia[0]; //Formato: 2018-12-31

$vhora	= explode('-', $horaini); //Ej: 09:00-15:30 (inicio - fin)
$hini	= trim($vhora[0]);
$hfin	= trim($vhora[1]);

//Busco que dia de la semana corresponde el primer dia del evento 
//$diasini

//Asigno el inicio y fin del Evento
$horafin	= date('G:i', strtotime('+' . ($horaint) . ' minutes', strtotime($hfin . ':00'))); //Sumo el intervalo, por visualizacion en el calendario
$tmpl->setVariable('eventoInicio', $hini);
$tmpl->setVariable('eventoFin', $horafin);

//Limite de horario del calendario
$minlimit	= date('G:i', strtotime('-2 hours', strtotime($hini . ':00')));
$maxlimit	= date('G:i', strtotime('+2 hours', strtotime($hfin . ':00')));
$tmpl->setVariable('minTimeEvent', $minlimit);
$tmpl->setVariable('maxTimeEvent', $maxlimit);

$vhini 	= explode(':', $hini);
$vhfin 	= explode(':', $hfin);
$minini = intval($vhini[0]) * 60 + intval($vhini[1]); //Guardo la duracion en minutos (inicio) 
$minfin = intval($vhfin[0]) * 60 + intval($vhfin[1]); //Guardo la duracion en minutos (fin)

$horaid = 1;
for ($i = 0; $i < $diasdur; $i++) {
	$fecha 		= date('d/m/Y', strtotime($tdia . ' + ' . $i . ' day'));

	$minaux = $minini;

	while ($minaux <= $minfin) {
		$hora 		= date('G:i', strtotime('+' . ($minaux - $minini) . ' minutes', strtotime($hini . ':00')));

		//Busco si la info ya esta checkeada
		$bloqueado = true;
		foreach ($dataDisp as $ind => $data) {
			if ($fecha == $data['fecha'] && $hora == $data['hora']) {
				$bloqueado = false;
			}
		}

		//if (!$bloqueado) {
		if (1 == 2) {
			$horafin	= date('G:i', strtotime('+' . ($horaint) . ' minutes', strtotime($hora . ':00')));

			$blqfecha	= substr($fecha, 6, 4) . '-' . substr($fecha, 3, 2) . '-' . substr($fecha, 0, 2); //Formato calendario (yyyy-mm-dd)
			$blqhoraini = ($hora != '') ? 'T' . $hora : '';
			$blqhorafin = ($horafin != '') ? 'T' . $horafin : '';



			$tmpl->setCurrentBlock('reuniones');
			logerror("	BLOQUEADO");
			$tmpl->setVariable('reureg', 9999);
			$tmpl->setVariable('percodigo', 9999);
			$tmpl->setVariable('pernombre', 'Disponible');
			$tmpl->setVariable('perapelli', '');
			$tmpl->setVariable('percompan',  'Disponible');
			$tmpl->setVariable('percorreo', '');
			$tmpl->setVariable('reufchini', $blqfecha . $blqhoraini);
			$tmpl->setVariable('reufchfin', $blqfecha . $blqhorafin);
			$tmpl->setVariable('color', $colorDisponible);

			if ($orientacionSwitch == 1) {

				$orientacion = 'left';
				$tmpl->setVariable('orientacion', $orientacion);
				$orientacionSwitch = 0;
			} else {
				$orientacion = 'right';
				$tmpl->setVariable('orientacion', $orientacion);
				$orientacionSwitch = 1;
			}
			$tmpl->parse('reuniones');
		}


		$minaux += $horaint; //Incremento el intervalo
		$horaid++;
	}
}
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
//Reuniones solicitadas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantEnviados = trim($row['CANTIDAD']);
if ($cantEnviados == 0)	$cantEnviados = '';

//Reuniones recibidas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE  R.PERCODDST=$percodigo AND R.REUESTADO=1  ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantRecibidos = trim($row['CANTIDAD']);
if ($cantRecibidos == 0)	$cantRecibidos = '';

$tmpl->setVariable('cantEnviados', $cantEnviados);
$tmpl->setVariable('cantRecibidos', $cantRecibidos);
// logerror($cantRecibidos);


$colors = array('#0BE000', '#E0D500', '#E02C00', '#0068E0', '#00E0D8', '#DD00E0');


/* ------------------------------------ x ----------------------------------- */

sql_close($conn);
$tmpl->show();

?>	
