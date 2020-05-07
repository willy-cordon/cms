<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma

	$tmpl= new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	//$tmpl->setVariable('pernombre'	, $pernombre	);
	//$tmpl->setVariable('perapelli'	, $perapelli	);
	//$tmpl->setVariable('perusuacc'	, $perusuacc	);
	//$tmpl->setVariable('percorreo'	, $percorreo	);

	if($peradmin!=1){
		header('Location: ../index');
	}

	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';

	$conn= sql_conectar();//Apertura de Conexion

	$fltnombre 		= (isset($_POST['fltnombre']))? trim($_POST['fltnombre']):'';
	$fltapelli 		= (isset($_POST['fltapelli']))? trim($_POST['fltapelli']):'';
	$fltcompan 		= (isset($_POST['fltcompan']))? trim($_POST['fltcompan']):'';
	$fltcorreo 		= (isset($_POST['fltcorreo']))? trim($_POST['fltcorreo']):'';
	$fltorden 		= (isset($_POST['fltorden']))? trim($_POST['fltorden']):'';
	$fltordentipo 	= (isset($_POST['fltordentipo']))? trim($_POST['fltordentipo']):'';
	$fltestado 		= (isset($_POST['fltestado']))? trim($_POST['fltestado']):'';
	$fltpertipo 	= (isset($_POST['fltpertipo']))? trim($_POST['fltpertipo']):'';
	$fltperclase 	= (isset($_POST['fltperclase']))? trim($_POST['fltperclase']):'';

	$colorpermSI 	= '#0BE000';
	$colorpermNO 	= '#FF581B';

	$tmpl->setVariable('colorPermisoSI'	, $colorpermSI);
	$tmpl->setVariable('colorPermisoNO'	, $colorpermNO);

	$where = ' 1=1 ';
	//Nombre
	if($fltnombre!=''){
		$where .= " AND P.PERNOMBRE CONTAINING '$fltnombre' ";
	}
	//Correo
	if($fltcorreo!=''){
		$where .= " AND P.PERCORREO CONTAINING '$fltcorreo' ";
	}
	//Apellido
	if($fltapelli!=''){
		$where .= " AND P.PERAPELLI CONTAINING '$fltapelli' ";
	}
	//Compañia
	if($fltcompan!=''){
		$where .= " AND P.PERCOMPAN CONTAINING '$fltcompan' ";
	}
	//Estado
	if($fltestado!=''){
		$where .= " AND P.ESTCODIGO=$fltestado ";
	}
	//Tipo de Perfiles
	if($fltpertipo!=''){
		$where .= " AND P.PERTIPO=$fltpertipo ";
	}
	//Clase de Perfiles
	if($fltperclase!=''){
		$where .= " AND P.PERCLASE=$fltperclase ";
	}

	$orden = ' ORDER BY PERNOMBRE ';
	switch($fltorden){
		case 1: //Nombre
			$orden = ' ORDER BY P.PERNOMBRE ';
			break;
		case 2: //Apellido
			$orden = ' ORDER BY P.PERAPELLI ';
			break;
		case 3: //Empresa
			$orden = ' ORDER BY P.PERCOMPAN ';
			break;
		case 4: //Reuniones
			$orden = ' ORDER BY 7 ';
			break;
	}

	//TIpo de Orden: 2=Descendente / 1=Ascendente
	if($fltordentipo==2){
		$orden .= ' DESC';
	}


	$query = "	SELECT P.PERCODIGO,SUBSTRING(P.PERNOMBRE FROM 1 FOR 15) AS PERNOMBRE,SUBSTRING(P.PERAPELLI FROM 1 FOR 15) AS PERAPELLI,SUBSTRING(P.PERCOMPAN FROM 1 FOR 15) AS PERCOMPAN,P.ESTCODIGO,P.PERAVATAR,
						(SELECT COUNT(*)
						FROM REU_CABE R
						WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO=2) AS REUCANT,
						(SELECT COUNT(*)
						FROM REU_CABE R
						WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO<>3) AS REUTOTAL,
						COALESCE(PERUSADIS,0) AS PERUSADIS,COALESCE(PERUSAREU,0) AS PERUSAREU,COALESCE(PERUSAMSG,0) AS PERUSAMSG
				FROM PER_MAEST P
				WHERE $where
				$orden ";
	$Table = sql_query($query,$conn);

	

	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
		$percompan	= trim($row['PERCOMPAN']);
		$estcodigo	= trim($row['ESTCODIGO']);
		$peravatar	= trim($row['PERAVATAR']);
		$reucant	= trim($row['REUCANT']);
		$reutotal	= trim($row['REUTOTAL']);
		$perusadis	= trim($row['PERUSADIS']);
		$perusareu	= trim($row['PERUSAREU']);
		$perusamsg	= trim($row['PERUSAMSG']);

		$viewlibbtn = 'none';
		$viewmailbtn = 'none';
		if($estcodigo==9){ //Sin mail confirmado
			$viewmailbtn='';
		}else if($estcodigo==8){ //Apto para liberar
			$viewlibbtn='';
		}

	
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('percodigo'	, $percodigo);
		$tmpl->setVariable('pernombre'	, $pernombre);
		$tmpl->setVariable('perapelli'	, $perapelli);
		$tmpl->setVariable('percompan'	, $percompan);
	
		if($peravatar!=''){
			$tmpl->setVariable('peravatar'	, $pathimagenes.$percodigo.'/'.$peravatar);
		}else{
			$tmpl->setVariable('peravatar'	, $imgAvatarNull);
		}

		if($estcodigo==3){
			$tmpl->setVariable('btneliminar'	, 'display:none;');
		}else{
			$tmpl->setVariable('btnactivar'	, 'display:none;');
		}

	
		//ANCHOR Seteo de permisosz
		//Permisos
		$tmpl->setVariable('perusadis'	, $perusadis);
		$tmpl->setVariable('perusareu'	, $perusareu);
		$tmpl->setVariable('perusamsg'	, $perusamsg);

		if($perusadis==1){ //Permiso de Disponibilidad
			$tmpl->setVariable('permdispcolor'	, $colorpermSI);
		}else{
			$tmpl->setVariable('permdispcolor'	, $colorpermNO);
		}
		if($perusareu==1){ //Permiso de Reuniones
			$tmpl->setVariable('permreuncolor'	, $colorpermSI);
		}else{
			$tmpl->setVariable('permreuncolor'	, $colorpermNO);
		}
		if($perusamsg==1){ //Permiso de Mensajeria
			$tmpl->setVariable('permmsgcolor'	, $colorpermSI);
		}else{
			$tmpl->setVariable('permmsgcolor'	, $colorpermNO);
		}


		$tmpl->setVariable('viewmailbtn', $viewmailbtn);
		$tmpl->setVariable('viewlibbtn'	, $viewlibbtn);
		$tmpl->setVariable('reucant'	, $reucant.'/'.$reutotal);
		$tmpl->parse('browser');
	}

	if ($IdiomView=='esp') {
		$tmpl->setVariable('ACTIVAR','ACTIVAR');
	}else{
		$tmpl->setVariable('ACTIVAR','ACTIVATE');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>
