<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';

			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	
	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	
	$fltbuscar 	= (isset($_POST['fltbuscar']))? $_POST['fltbuscar']:1;
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//Busco si hay una encuesta publicada, para poder presentar el boton de encuestas
	$encExists = false;
	$query = "	SELECT EC.ENCREG
				FROM ENC_CABE EC
				WHERE EC.ESTCODIGO=1 AND EC.ENCPUBLIC='S' ";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
		$encExists = true;
	}
	
	$where = '';
	$relacion = '';
	if($fltbuscar == 1){ //Ver reuniones que envie
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
	}
	if($fltbuscar == 2){ //Ver reuniones que me enviaron
		$relacion = ' P.PERCODIGO=R.PERCODSOL ';
		$where .= "  R.PERCODDST=$percodigo AND R.REUESTADO=1 ";
	}
	if($fltbuscar == 3){ //Ver reuniones que envie y me enviaron, pero ya estan confirmadas
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo) AND R.REUESTADO=2 ";
	}
	if($fltbuscar == 4){ //Ver reuniones que envie y me enviaron, pero ya estan canceladas
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo) AND R.REUESTADO=3 ";
	}
	
	//Reuniones que solicitaron
	$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
						PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,R.MESCODIGO,
						R.AGEREG,A.AGETITULO,A.AGELUGAR,
						PD.PERREUURL AS PERREUURLDST,PS.PERREUURL AS PERREUURLSOL
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
				LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=R.AGEREG
				WHERE $where
				ORDER BY R.REUREG ";
				
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percoddst 		= trim($row['PERCODDST']);
		$pernomdst		= trim($row['PERNOMDST']);
		$perapedst		= trim($row['PERAPEDST']);
		$percomdst		= trim($row['PERCOMDST']);
		$percordst		= trim($row['PERCORDST']);
		$peravadst		= trim($row['PERAVADST']);		
		$percodsol 		= trim($row['PERCODSOL']);
		$pernomsol		= trim($row['PERNOMSOL']);
		$perapesol		= trim($row['PERAPESOL']);
		$percomsol		= trim($row['PERCOMSOL']);
		$percorsol		= trim($row['PERCORSOL']);
		$peravasol		= trim($row['PERAVASOL']);
		$agereg			= trim($row['AGEREG']);
		$agetitulo		= trim($row['AGETITULO']);
		$agelugar		= trim($row['AGELUGAR']);		
		$reufecha		= BDConvFch($row['REUFECHA']);
		$reuhora		= substr(trim($row['REUHORA']),0,5);
		$reuhoraorig	= substr(trim($row['REUHORA']),0,5);
		$reuestado		= trim($row['REUESTADO']);
		$reureg			= trim($row['REUREG']);
		$mescodigo  	= trim($row['MESCODIGO']);
		$perreuurldst 	 = trim($row['PERREUURLDST']);
		$perreuurlsol 	 = trim($row['PERREUURLSOL']);
		
		if($percoddst==$percodigo){
			$percod 	= $percodsol;
			$pernombre	= $pernomsol;
			$perapelli	= $perapesol;
			$percompan	= $percomsol;
			$percorreo	= $percorsol;
			$peravatar	= $peravasol;
			$perreuurl	= $perreuurlsol;
		}else{
			$percod 	= $percoddst;
			$pernombre	= $pernomdst;
			$perapelli	= $perapedst;
			$percompan	= $percomdst;
			$percorreo	= $percordst;
			$peravatar	= $peravadst;
			$perreuurl	= $perreuurldst;
		}
		
		$tmpl->setCurrentBlock('browser');
		if($reuestado==2){
			//Hora segun Zona Horaria
			$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$reuhora = $haux;
			
			$reuestdes = "Reunión confirmada para el <b>$reufecha</b> a las <b>$reuhora</b> <br> Mesa N° <b>$mescodigo</b> ";
		}else{
			$reuestdes = "Reunión sin confirmar";
			
			//Busco los horarios solicitados para reunion
			$query = "	SELECT S.REUFECHA,S.REUHORA
						FROM REU_CABE R 
						INNER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
						WHERE R.REUREG=$reureg 
						ORDER BY S.REUFECHA,S.REUHORA ";
						
			$TableReu = sql_query($query,$conn);
			for($j=0; $j<$TableReu->Rows_Count; $j++){
				$rowReu		= $TableReu->Rows[$j]; 
				$reufecha 	= BDConvFch($rowReu['REUFECHA']);
				$reuhora 	= substr(trim($rowReu['REUHORA']),0,5);
				
				//Hora segun Zona Horaria
				$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
				$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
				$reuhora = $haux;
				
				$reuestdes .= '<br> * Fecha: '.$reufecha.' a las '.$reuhora;
			}
		}
		
		if($fltbuscar==1 || $fltbuscar==3){ //Si son enviadas o confirmadas
			$tmpl->setVariable('btnconfirmarstyle'	, 'display:none;');
		}else if($fltbuscar==4){ //Si son canceladas
			$tmpl->setVariable('btnconfirmarstyle'	, 'display:none;');
			$tmpl->setVariable('btncancelarstyle'	, 'display:none;');
		}
		
		//Si es un evento de agenda
		if($agereg!=0 && $agereg!=''){
			$percompan = $agetitulo;
			$pernombre = '';
			$perapelli = $agelugar;
			$reuestdes = "Evento: <b>$reufecha</b> a las <b>$reuhora</b>";
			$tmpl->setVariable('btnverperfil'	, 'display:none;');
		}
		
		$tmpl->setVariable('reureg'		, $reureg	);
		$tmpl->setVariable('percodigo'	, $percod	);
		$tmpl->setVariable('pernombre'	, $pernombre);
		$tmpl->setVariable('perapelli'	, $perapelli);
		$tmpl->setVariable('percompan'	, $percompan);
		$tmpl->setVariable('percorreo'	, $percorreo);
		$tmpl->setVariable('reuestdes'	, $reuestdes);	
		
		$tmpl->setVariable('percordst'	, $percordst);	
		$tmpl->setVariable('percorsol'	, $percorsol);	

		//Link de Reuniones
		if($perreuurl==''){
			$tmpl->setVariable('btnvercontact'	, 'display:none;');
		}else{
			$tmpl->setVariable('perreuurl', $perreuurl);
		}

		if($peravatar!=''){
			$tmpl->setVariable('peravatar'	, $pathimagenes.$percod.'/'.$peravatar);
		}else{
			$tmpl->setVariable('peravatar'	, $imgAvatarNull);
		}
		
		//Encuesta, solo para reuninones Confirmadas
		if($encExists){
			if($fltbuscar==3){
				//Verifico si la reunion ya paso, para poder mostrar el boton de encuesta
				$diaaux = explode('/',$reufecha);
				$diahor = strtotime($diaaux[1].'/'.$diaaux[0].'/'.$diaaux[2].' '.$reuhoraorig);
				$hoy 	= strtotime(date('m/d/Y H:i:s'));
				
				if($hoy > $diahor){
					//Busco si la encuesta esta completa
					$queryEnc = "SELECT REUREG FROM ENC_RESP WHERE REUREG=$reureg AND PERCODIGO=$percodigo";
					$TableEnc = sql_query($queryEnc,$conn);
                    logerror($TableEnc);
                    logerror($TableEnc->Rows_Count>0);
					if($TableEnc->Rows_Count>0){
                        
						$tmpl->setVariable('btnverencuesta'	, 'display:none;');
					}else{
						$tmpl->setVariable('btnverencuesta'	, '');
					}
					
				}else{ //Aun no llego el momento de la reunion
					$tmpl->setVariable('btnverencuesta'	, 'display:none;');
				}
				
				
			}else{
				$tmpl->setVariable('btnverencuesta'	, 'display:none;');
			}
		}else{ //No hay ninguna encuesta en el sistema
			$tmpl->setVariable('btnverencuesta'	, 'display:none;');
		}
		
		
		$tmpl->parse('browser');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
