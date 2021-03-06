<?
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
    
    $tmpl= new HTML_Template_Sigma();
    	
	$tmpl->loadTemplateFile('agendaMobile.html');
	//--------------------------------------------------------------------------------------------------------------
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
	
	$tmpl->setVariable('horaint', $horaint);
	
	$inicio = substr($diasini,6,4).'-'.substr($diasini,3,2).'-'.substr($diasini,0,2); //Formato calendario (yyyy-mm-dd)
	
	$hoy = date('Y-m-d');
	$tmpl->setVariable('hoy', $inicio);
	
	
	
	
	
	//--------------------------------------------------------------------------------------------------------------
	//-----Seleccionamos los datos de la base de datos
	$query="SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR 
			FROM AGE_MAEST
            WHERE ESTCODIGO<>3";
            logerror($query);
					
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$agereg 	= trim($row['AGEREG']);
		$agetitulo 	= trim($row['AGETITULO']);
		$agedescri 	= trim($row['AGEDESCRI']);
		$agelugar   = trim($row['AGELUGAR']);
		$agefch     = BDConvFch($row['AGEFCH']);
		$agehorini  = substr(trim($row['AGEHORINI']),0,5);
		$agehorfin  = substr(trim($row['AGEHORFIN']),0,5);
		
		$agefch	= substr($agefch,6,4).'-'.substr($agefch,3,2).'-'.substr($agefch,0,2); //Formato calendario (yyyy-mm-dd)
		$agehorini = ($agehorini!='')? 'T'.$agehorini: '';
		$agehorfin = ($agehorfin!='')? 'T'.$agehorfin: '';
		
		$tmpl->setCurrentBlock('actividades');
		$tmpl->setVariable('agereg'		, $agereg);
		$tmpl->setVariable('agetitulo'	, $agetitulo);
		$tmpl->setVariable('agedescri'	, $agedescri);
		$tmpl->setvariable('agelugar'	, $agelugar);
		$tmpl->setVariable('agehorini'	, $agefch.$agehorini);
		$tmpl->setVariable('agehorfin'	, $agefch.$agehorfin);
		$tmpl->parse('actividades');
       
	}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
