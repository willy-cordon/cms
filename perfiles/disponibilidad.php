<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('disponibilidad.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}
	
	$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
	$diasdur = intval($params['SisEventoDuracionDias']); 	 	//Evento - Cantidad de Dias de duracion
	$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
	$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)
	
	
	$vdia 	= explode('/',$diasini);
	$tdia	= $vdia[2].'-'.$vdia[1].'-'.$vdia[0]; //Formato: 2018-12-31
	
	$vhora	= explode('-',$horaini); //Ej: 09:00-15:30 (inicio - fin)
	$hini	= trim($vhora[0]);
	$hfin	= trim($vhora[1]);
	
	$vhini 	= explode(':',$hini);
	$vhfin 	= explode(':',$hfin);
	$minini = intval($vhini[0])*60 + intval($vhini[1]); //Guardo la duracion en minutos (inicio) 
	$minfin = intval($vhfin[0])*60 + intval($vhfin[1]); //Guardo la duracion en minutos (fin)
	
	//$selectedTime = "9:15:00";
	//$endTime = strtotime("+15 minutes", strtotime($selectedTime));
	//echo date('h:i:s', $endTime);
	
	$horaid = 1;
	for($i=0; $i<$diasdur; $i++){
		
		$fechatexto	= 'DIA '.($i+1);
		$fecha 		= date('d/m/Y', strtotime($tdia. ' + '.$i.' day'));
		
		$tmpl->setCurrentBlock('dias');
		$tmpl->setVariable('fechatexto'	, $fechatexto	);
		$tmpl->setVariable('fecha'		, $fecha	);
		
		$minaux = $minini;
		while($minaux <= $minfin){
			$hora 		= date('H:i', strtotime('+'.($minaux-$minini).' minutes', strtotime($hini.':00')));
			
			$fechabd 	= $fecha;
			$horabd		= $hora;
			
			//Hora segun Zona Horaria
			$haux = date('H:i', strtotime('+10800 seconds', strtotime($hora))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$zhora = $haux;

			$tmpl->setCurrentBlock('horas');
			$tmpl->setVariable('horaid'	, $horaid	);
			$tmpl->setVariable('hora'	, $zhora	);
			$tmpl->setVariable('fechabd', $fechabd	);
			$tmpl->setVariable('horabd'	, $horabd	);
			
			//Busco si la info ya esta checkeada
			if(isset($_POST['dataDisponibilidad'])){
				foreach($_POST['dataDisponibilidad'] as $ind => $data){
					if($fecha == $data['fecha'] && $hora == $data['hora']){
						
						$tmpl->setVariable('horachecked'	, 'checked'	);
					}
				}
			}else{
				$tmpl->setVariable('horachecked'	, 'checked'	);
			}
			$tmpl->parse('horas');
			
			$minaux += $horaint; //Incremento el intervalo
			$horaid++;
		}
		
		
		$tmpl->parse('dias');
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
