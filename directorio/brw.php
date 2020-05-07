<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	//Diccionario de idiomas
	DDIdioma($tmpl);
	
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 	= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 	= (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc 	= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo 	= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$perusareu 	= (isset($_SESSION[GLBAPPPORT.'PERUSAREU']))? trim($_SESSION[GLBAPPPORT.'PERUSAREU']) : '';
	$pertipolog = (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) 	  : '';
	$perclaselog= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE'])   : '';
	
	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	
	$fltbuscar 		= (isset($_POST['fltbuscar']))? trim($_POST['fltbuscar']) : '';
	$sectores 		= (isset($_POST['sectores']))? $_POST['sectores'] : '0';
	
	$subsectores 	= (isset($_POST['subsectores']))? $_POST['subsectores'] : '0';
	$categorias 	= (isset($_POST['categorias']))? $_POST['categorias'] : '0';
	$subcategorias 	= (isset($_POST['subcategorias']))? $_POST['subcategorias'] : '0';
	$fltrecomendado	= (isset($_POST['fltrecomendado']))? trim($_POST['fltrecomendado']) : 0;
	$fltfavoritos	= (isset($_POST['fltfavoritos']))? trim($_POST['fltfavoritos']) : 0;
	$fltpertipo 	= (isset($_POST['fltpertipo']))? trim($_POST['fltpertipo']) : '';
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//Filtro de Recomendados aplicados
	if($fltrecomendado==1){
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
		$perfilLog = array();
		//Cargo la Clasificacion de productos del Perfil logueado
		$query = "	SELECT S.SECCODIGO,S.SECDESCRI
					FROM PER_SECT PS
					LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
					WHERE PS.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 ";
		$TableSect = sql_query($query,$conn);
		for($i=0; $i<$TableSect->Rows_Count; $i++){
			$rowSect= $TableSect->Rows[$i];
			$seccodigo = trim($rowSect['SECCODIGO']);
			$secdescri = trim($rowSect['SECDESCRI']);
			
			$perfilLog[$seccodigo]['EXISTS'] = 1;
			
			//Busco si tiene un siguiente nivel / SubSector
			$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES
								FROM PER_SSEC PSB
								LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
								WHERE PSB.PERCODIGO=$percodlog AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 ";
			$TableSSect = sql_query($querySectSub,$conn);
			for($j=0; $j<$TableSSect->Rows_Count; $j++){
				$rowSSect= $TableSSect->Rows[$j];
				$secsubcod = trim($rowSSect['SECSUBCOD']);
				$secsubdes = trim($rowSSect['SECSUBDES']);
				
				$perfilLog[$seccodigo][$secsubcod]['EXISTS'] = 1;
				
				//Busco si tiene un siguiente nivel / Categorias
				$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI
								FROM PER_CATE PC
								LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
								WHERE PC.PERCODIGO=$percodlog AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 ";

				$TableCat = sql_query($queryCat,$conn);
				for($k=0; $k<$TableCat->Rows_Count; $k++){
					$rowCat= $TableCat->Rows[$k];
					$catcodigo = trim($rowCat['CATCODIGO']);
					$catdescri = trim($rowCat['CATDESCRI']);
					
					$perfilLog[$seccodigo][$secsubcod][$catcodigo]['EXISTS'] = 1;
					
					//Busco si tiene un siguiente nivel / SubCategorias
					$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
										FROM PER_SCAT PSC
										LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
										WHERE PSC.PERCODIGO=$percodlog AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 ";

					$TableCatSub = sql_query($queryCatSub,$conn);
					for($m=0; $m<$TableCatSub->Rows_Count; $m++){
						$rowCatSub= $TableCatSub->Rows[$m];
						$catsubcod = trim($rowCatSub['CATSUBCOD']);
						$catsubdes = trim($rowCatSub['CATSUBDES']);
						
						$perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod]['EXISTS'] = 1;
					}
				}
			}
		}
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	}
	
	$where = '';
	
	//Tipo: Expositor + Clase: Vendedor -> Ve a todos menos a ellos mismos
	//if($fltrecomendado==1){ //Si esta dentro de recomendados, los expositores vendedores no se ven entre ellos.
	//	if($pertipolog==1 && $perclaselog==1){
	//		$where .= " AND  (P.PERTIPO<>1 OR P.PERCLASE<>1) ";
	//	}
	//}
	////Tipo: Expositor + Clase: Comprador/Vendedor -> Ve a todos
	//if($pertipolog==1 && $perclaselog==3){
	//	//$where .= " ";
	//}
	////Tipo: Contrastista o Productores o Invitado de ronda o CompInternacionales -> Ve a todas las clases pero 1-Expositores
	//if($pertipolog==2 || $pertipolog==3 || $pertipolog==5 || $pertipolog==4){
	//	$where .= " AND P.PERTIPO=1 ";
	//}
	
	
	if($fltbuscar!=''){
		$where .= " AND (P.PERNOMBRE CONTAINING '$fltbuscar' OR P.PERAPELLI CONTAINING '$fltbuscar' OR P.PERCOMPAN CONTAINING '$fltbuscar') ";
	}
	if($sectores!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SECT S WHERE S.PERCODIGO=P.PERCODIGO AND S.SECCODIGO IN ($sectores) ) ";
	}
	if($subsectores!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SSEC SS WHERE SS.PERCODIGO=P.PERCODIGO AND SS.SECSUBCOD IN ($subsectores) ) ";
	}
	if($categorias!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_CATE C WHERE C.PERCODIGO=P.PERCODIGO AND C.CATCODIGO IN ($categorias) ) ";
	}
	if($subcategorias!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SCAT CS WHERE CS.PERCODIGO=P.PERCODIGO AND CS.CATSUBCOD IN ($subcategorias) ) ";
	}
	if($fltfavoritos==1){
		$where .= " AND EXISTS(SELECT 1 FROM PER_FAVO F WHERE F.PERCODIGO=$percodlog AND F.PERCODFAV=P.PERCODIGO) ";
	}
	if($fltpertipo!=''){
		$where .= " AND P.PERTIPO=$fltpertipo ";
	}

		
	$campo = ($IdiomView=='ING')? 'P.PERDESING' : 'P.PEREMPDES';
	$query = "	SELECT P.PERFAC,P.PERTWI,P.PERINS, P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,SUBSTRING($campo FROM 1 FOR 100) AS PEREMPDES,P.PERAVATAR,P.PERTIPO,
						COALESCE((	SELECT 1
									FROM PER_FAVO F
									WHERE F.PERCODIGO=$percodlog AND F.PERCODFAV=P.PERCODIGO),0) AS ESFAVO
				FROM PER_MAEST P
				WHERE P.ESTCODIGO=1 AND P.PERCODIGO<>$percodlog $where
				ORDER BY P.PERNOMBRE ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
		$percompan	= trim($row['PERCOMPAN']);
		$perempdes	= trim($row['PEREMPDES']);
		$peravatar	= trim($row['PERAVATAR']);
		$esfavo		= trim($row['ESFAVO']);
		$pertipo	= trim($row['PERTIPO']);
		$perins		= trim($row['PERINS']);
		$pertwi		= trim($row['PERTWI']);
		$perfac		= trim($row['PERFAC']);
		
		$match 		= true;
		
		
		if($fltrecomendado==1){
			$match = false;
		
			//Busco todos los sectores que tiene el perfil
			$query = "	SELECT S.SECCODIGO,S.SECDESCRI
						FROM PER_SECT PS
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
						WHERE PS.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 ";
			$TableSect = sql_query($query,$conn);
			for($n=0; $n<$TableSect->Rows_Count; $n++){
				$rowSect= $TableSect->Rows[$n];
				$seccodigo = trim($rowSect['SECCODIGO']);
				$secdescri = trim($rowSect['SECDESCRI']);
				
				//Busco si tiene un siguiente nivel / SubSector
				$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES
									FROM PER_SSEC PSB
									LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
									WHERE PSB.PERCODIGO=$percodigo AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 ";
				$TableSSect = sql_query($querySectSub,$conn);
				for($j=0; $j<$TableSSect->Rows_Count; $j++){
					$rowSSect= $TableSSect->Rows[$j];
					$secsubcod = trim($rowSSect['SECSUBCOD']);
					$secsubdes = trim($rowSSect['SECSUBDES']);
					
					//Busco si tiene un siguiente nivel / Categorias
					$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI
									FROM PER_CATE PC
									LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
									WHERE PC.PERCODIGO=$percodigo AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 ";

					$TableCat = sql_query($queryCat,$conn);
					for($k=0; $k<$TableCat->Rows_Count; $k++){
						$rowCat= $TableCat->Rows[$k];
						$catcodigo = trim($rowCat['CATCODIGO']);
						$catdescri = trim($rowCat['CATDESCRI']);
						
						//Busco si tiene un siguiente nivel / SubCategorias
						$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
											FROM PER_SCAT PSC
											LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
											WHERE PSC.PERCODIGO=$percodigo AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 ";

						$TableCatSub = sql_query($queryCatSub,$conn);
						for($m=0; $m<$TableCatSub->Rows_Count; $m++){
							$rowCatSub= $TableCatSub->Rows[$m];
							$catsubcod = trim($rowCatSub['CATSUBCOD']);
							$catsubdes = trim($rowCatSub['CATSUBDES']);
							
							if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod])){
								$match = true;
							}
						}
						if($TableCatSub->Rows_Count==-1){
							if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo])){
								$match = true;
							}
						}
					}
					if($TableCat->Rows_Count==-1){
						if(isset($perfilLog[$seccodigo][$secsubcod])){
							$match = true;
						}
					}
				}
				
				if($TableSSect->Rows_Count==-1){
					if(isset($perfilLog[$seccodigo])){
						$match = true;
					}
				}
			}
		}

		if($perfac ==""){
			$perfac = "";
		}else{
			$perfac = "href='". $perfac."'";
		}
		if($perins ==""){
			$perins = "";
		}else{
			$perins = "href='". $perins."'";
		}
		if($pertwi ==""){
			$pertwi = "";
		}else{
			$pertwi = "href='". $pertwi."'";
		}
		
		if($match){
			$tmpl->setCurrentBlock('browser');
			$tmpl->setVariable('percodigo'	, $percodigo);
			$tmpl->setVariable('pernombre'	, $pernombre);
			$tmpl->setVariable('perapelli'	, $perapelli);
			$tmpl->setVariable('percompan'	, $percompan);
			$tmpl->setVariable('perempdes'	, $perempdes);
			$tmpl->setVariable('pertipo'	, $pertipo);
			$tmpl->setVariable('perfac'	,	$perfac);
			$tmpl->setVariable('pertwi'	, 	$pertwi);
			$tmpl->setVariable('perins'	, 	$perins);
			
			//Es favorito
			$tmpl->setVariable('esfavorito'	, $esfavo);
			if($esfavo==1){
				//logerror('entrocorazon lleno');
				$tmpl->setVariable('colorfavo'	, 'fa-heart');
			
			}else{
				//logerror('entrocorazon vacio');
				
				$tmpl->setVariable('colorfavo'	, 'fa-heart-o');
			}

			//Busco si ya tengo una reunion con este perfil
			$queryReu = " 	SELECT REUREG 
							FROM REU_CABE 
							WHERE (PERCODSOL=$percodlog OR PERCODDST=$percodlog) AND (PERCODSOL=$percodigo OR PERCODDST=$percodigo) AND REUESTADO<>3 ";
			$TableReu = sql_query($queryReu,$conn);
			if($TableReu->Rows_Count>0){
				$tmpl->setVariable('btnviewreunion'	, 'display:none;'	);
			}

			if($perusareu!=1){
				$tmpl->setVariable('btnviewreunion'	, 'display:none;'	);
			}

			if($peravatar!=''){
				$tmpl->setVariable('peravatar'	, $pathimagenes.$percodigo.'/'.$peravatar);
			}else{
				$tmpl->setVariable('peravatar'	, $imgAvatarNull);
			}
			
			
			
			$tmpl->parse('browser');
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
