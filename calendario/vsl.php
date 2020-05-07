<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('vsl.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	
	$colormatch = 'red';
	
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$perestcod = 1; //Activo por defecto
	$pernombre = '';
	$perapelli = '';
	$percorreo = '';
	$pertelefo = '';
	$perdirecc = '';
	$perciudad = '';
	$perestado = '';
	$paicodigo = '';
	$pathimagenes = '../perimg/'.$percodigo.'/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($percodigo!=0){
		$perfilLog = array();
		
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
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
		
		$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.ESTCODIGO,P.PERCOMPAN,P.PERCORREO,P.PERCIUDAD,P.PERESTADO,
							P.PERCODPOS,P.PERTELEFO,P.PERURLWEB,P.PERUSUACC,P.PERPASACC,P.PERDIRECC,P.PERCARGO,
							P.PAICODIGO,I.PAIDESCRI,P.PEREMPDES,P.PERAVATAR
					FROM PER_MAEST P
					LEFT OUTER JOIN TBL_PAIS I ON I.PAICODIGO=P.PAICODIGO
					WHERE P.PERCODIGO=$percodigo ";
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$estcodigo 	= trim($row['ESTCODIGO']);
			$percompan 	= trim($row['PERCOMPAN']);
			$percorreo 	= trim($row['PERCORREO']);
			$perciudad 	= trim($row['PERCIUDAD']);
			$perestado 	= trim($row['PERESTADO']);
			$percodpos 	= trim($row['PERCODPOS']);
			$pertelefo 	= trim($row['PERTELEFO']);
			$perurlweb 	= trim($row['PERURLWEB']);
			$perusuacc 	= trim($row['PERUSUACC']);
			$perpasacc 	= trim($row['PERPASACC']);
			$perdirecc 	= trim($row['PERDIRECC']);
			$percargo  	= trim($row['PERCARGO']);
			$paicodigo 	= trim($row['PAICODIGO']);
			$paidescri 	= trim($row['PAIDESCRI']);
			$perempdes 	= trim($row['PEREMPDES']);
			$peravatar 	= trim($row['PERAVATAR']);
			
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('percompan'	, $percompan	);
			$tmpl->setVariable('percorreo'	, $percorreo	);
			$tmpl->setVariable('perciudad'	, $perciudad	);
			$tmpl->setVariable('perestado'	, $perestado	);
			$tmpl->setVariable('percodpos'	, $percodpos	);
			$tmpl->setVariable('pertelefo'	, $pertelefo	);
			$tmpl->setVariable('perurlweb'	, $perurlweb	);
			$tmpl->setVariable('perusuacc'	, $perusuacc	);
			$tmpl->setVariable('perpasacc'	, $perpasacc	);
			$tmpl->setVariable('perdirecc'	, $perdirecc	);
			$tmpl->setVariable('percargo'	, $percargo 	);
			$tmpl->setVariable('paicodigo'	, $paicodigo	);
			$tmpl->setVariable('paidescri'	, $paidescri	);
			$tmpl->setVariable('perempdes'	, $perempdes	);
			
			if($peravatar!=''){
				$tmpl->setVariable('peravatar'	, $pathimagenes.$peravatar);
			}else{
				$tmpl->setVariable('peravatar'	, $imgAvatarNull);
			}
			
			//Busco todos los sectores que tiene el perfil
			$query = "	SELECT S.SECCODIGO,S.SECDESCRI
						FROM PER_SECT PS
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
						WHERE PS.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 ";
			$TableSect = sql_query($query,$conn);
			for($i=0; $i<$TableSect->Rows_Count; $i++){
				$rowSect= $TableSect->Rows[$i];
				$seccodigo = trim($rowSect['SECCODIGO']);
				$secdescri = trim($rowSect['SECDESCRI']);
				
				$tmpl->setCurrentBlock('sectores');
				$tmpl->setVariable('seccodigo'	, $seccodigo	);
				$tmpl->setVariable('secdescri'	, $secdescri	);
				
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
					
					$tmpl->setCurrentBlock('subsectores');
					$tmpl->setVariable('secsubcod'	, $secsubcod	);
					$tmpl->setVariable('secsubdes'	, $secsubdes	);
					
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
						
						$tmpl->setCurrentBlock('categorias');
						$tmpl->setVariable('catcodigo'	, $catcodigo	);
						$tmpl->setVariable('catdescri'	, $catdescri	);
						
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
							
							$tmpl->setCurrentBlock('subcategorias');
							$tmpl->setVariable('catsubcod'	, $catsubcod	);
							$tmpl->setVariable('catsubdes'	, $catsubdes	);
							
							if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod])){
								$tmpl->setVariable('colorsubcategoria'	, $colormatch);
							}
							
							$tmpl->parse('subcategorias');
						}
						if($TableCatSub->Rows_Count==-1){
							if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo])){
								$tmpl->setVariable('colorcategoria'	, $colormatch);
							}
						}
						
						$tmpl->parse('categorias');
					}
					//logerror($queryCat);
					//logerror($seccodigo.'-'.$secsubcod.'-'.$TableCat->Rows_Count);
					if($TableCat->Rows_Count==-1){
						if(isset($perfilLog[$seccodigo][$secsubcod])){
							$tmpl->setVariable('colorsubsector'	, $colormatch);
						}
					}
					
					$tmpl->parse('subsectores');
				}
				
				if($TableSSect->Rows_Count==-1){
					if(isset($perfilLog[$seccodigo])){
						$tmpl->setVariable('colorsector'	, $colormatch);
					}
				}
				
				$tmpl->parse('sectores');
				
			}
			
			$query = "	SELECT PERPROITM,PERPRONOM,PERPROFIL
						FROM PER_PROD
						WHERE PERCODIGO=$percodigo
						ORDER BY PERPROITM ";
			$Table = sql_query($query,$conn);
			for($i=0; $i<$Table->Rows_Count;$i++){
				$row= $Table->Rows[$i];
				$perproitm = trim($row['PERPROITM']);
				$perpronom = trim($row['PERPRONOM']);
				$perprofil = trim($row['PERPROFIL']);
				
				$tmpl->setCurrentBlock('productos');
				$tmpl->setVariable('perpronom'	, $perpronom	);
				$tmpl->setVariable('perprofil'	, $pathimagenes.$perprofil	);
				
				$tmpl->parse('productos');
			}
			
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
	
	
	/*
	
	<table>
											<tbody>
												<!-- BEGIN categorias -->
												<tr>
													<td style="background:white;">
														{catdescri}
														<table>
															<tbody>
																<!-- BEGIN subcategorias -->
																<tr>
																	<td>{catsubdes}</td>
																</tr>
																<!-- END subcategorias -->
															</tbody>
														</table>
													</td>
												</tr>
												<!-- END categorias -->
											</tbody>
										</table>
										*/
?>	
