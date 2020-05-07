<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('coordinar.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$percodsol 	= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	$tmpl->setVariable('percodsol'	, $percodsol	);
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
	
	//Busco si ya tengo reunion solicitada sin confirmar o confirmadas
	$query = "	SELECT S.REUFECHA,S.REUHORA
				FROM REU_CABE R 
				LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG
				WHERE R.PERCODSOL=$percodsol AND R.PERCODDST=$percodigo AND R.REUESTADO=1
				ORDER BY S.REUFECHA,S.REUHORA ";
	$TableReu = sql_query($query,$conn);
	
	//Busco los dias disponibles del perfil logueado
	$query = "	SELECT PERDISFCH,PERDISHOR
				FROM PER_DISP
				WHERE PERCODIGO=$percodigo 
				ORDER BY PERDISFCH,PERDISHOR ";
	$TableDisp = sql_query($query,$conn);
	
	//Busco los dias disponibles del perfil solicitante
	$query = "	SELECT PERDISFCH,PERDISHOR
				FROM PER_DISP
				WHERE PERCODIGO=$percodsol 
				ORDER BY PERDISFCH,PERDISHOR ";
	$TableDispSol = sql_query($query,$conn);
	
	//Busco si ya tengo reunion confirmadas o sin confirmar
	$query = "	SELECT S.REUFECHA,S.REUHORA
				FROM REU_CABE R 
				LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
				WHERE (R.PERCODSOL=$percodigo OR R.PERCODDST=$percodigo) AND R.REUESTADO IN(1,2) 
						AND R.REUREG<>$reureg 
				ORDER BY S.REUFECHA,S.REUHORA ";
	$TableReuConf = sql_query($query,$conn);
	
	//Busco si el solicitante tiene reuniones confirmadas o sin confirmar
	$query = "	SELECT S.REUFECHA,S.REUHORA
				FROM REU_CABE R 
				LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
				WHERE (R.PERCODSOL=$percodsol OR R.PERCODDST=$percodsol) AND R.REUESTADO IN(1,2) 
						AND R.REUREG<>$reureg
				ORDER BY S.REUFECHA,S.REUHORA ";
	$TableReuSolConf = sql_query($query,$conn);
	
	
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
			$tmpl->setVariable('hora'	, $zhora		);
			$tmpl->setVariable('fechabd', $fechabd	);
			$tmpl->setVariable('horabd'	, $horabd	);
			
			//Busco si el que acepta la reunion esta disponible
			$dispExists=0;
			for($j=0; $j<$TableDisp->Rows_Count; $j++){
				$rowDisp= $TableDisp->Rows[$j]; 
				$perdisfch = BDConvFch($rowDisp['PERDISFCH']);
				$perdishor = substr(trim($rowDisp['PERDISHOR']),0,5);
				if($fecha == $perdisfch && $hora == $perdishor){
					$dispExists=1;
				}
			}
			
			//Busco si tiene disponibilidad el usuario solicitante
			if($dispExists==1){
				$dispExists=2;
				for($j=0; $j<$TableDispSol->Rows_Count; $j++){
					$rowDispSol= $TableDispSol->Rows[$j]; 
					$perdisfch = BDConvFch($rowDispSol['PERDISFCH']);
					$perdishor = substr(trim($rowDispSol['PERDISHOR']),0,5);
					if($fecha == $perdisfch && $hora == $perdishor){
						$dispExists=1;
					}
				}
			}
			
			//Busco si en mis horarios disponibles, no tengo ninguna reunion confirmada o sin confirmar
			if($dispExists==1){
				for($j=0; $j<$TableReuConf->Rows_Count; $j++){
					$rowReuConf= $TableReuConf->Rows[$j]; 
					$reufecha = BDConvFch($rowReuConf['REUFECHA']);
					$reuhora = substr(trim($rowReuConf['REUHORA']),0,5);
					if($fecha == $reufecha && $hora == $reuhora){
						$dispExists=3;
					}
				}
			}
			//Buso en los horarios disponibles de solicitante, que no tenga ninguna reunion confirmada o sin confirmar
			if($dispExists==1){
				for($j=0; $j<$TableReuSolConf->Rows_Count; $j++){
					$rowReuSolConf= $TableReuSolConf->Rows[$j]; 
					$reufecha = BDConvFch($rowReuSolConf['REUFECHA']);
					$reuhora = substr(trim($rowReuSolConf['REUHORA']),0,5);
					if($fecha == $reufecha && $hora == $reuhora){
						$dispExists=3;
					}
				}
			}
			
			//logerror('Fecha:'.$fecha.'-'.$hora.'-'.$dispExists);
			
			if($dispExists==0){ //El perfil que acepta, no esta disponible en horario
				$tmpl->setVariable('datacolor'		, 'danger'	);
				$tmpl->setVariable('datajackcolor'	, 'danger'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else if($dispExists==2){ //El solicitante no tiene disponibilidad en el horario
				$tmpl->setVariable('datacolor'		, 'danger'	);
				$tmpl->setVariable('datajackcolor'	, 'danger'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else if($dispExists==3){ //Tengo reuniones en horario
				$tmpl->setVariable('datacolor'		, 'warning'	);
				$tmpl->setVariable('datajackcolor'	, 'warning'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else{//Esta disponible ambos
				$tmpl->setVariable('datacolor'		, 'success'	);
				$tmpl->setVariable('datajackcolor'	, 'success'	);
			}
			
			//Busco si es horario que solicito para reunirnos
			$reuExists=false;
			for($j=0; $j<$TableReu->Rows_Count; $j++){
				$rowReu= $TableReu->Rows[$j]; 
				$reufecha = BDConvFch($rowReu['REUFECHA']);
				$reuhora = substr(trim($rowReu['REUHORA']),0,5);
				if($fecha == $reufecha && $hora == $reuhora){
					$reuExists=true;
				}
			}
			if($reuExists){ //Fecha y Hora para la que el solicitante entrego las opciones de reuniones
				$tmpl->setVariable('horadisabled'	, 'checked');
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
