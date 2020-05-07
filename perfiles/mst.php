<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	$peradminlog = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	

	
	if($peradminlog!=1){ 
		$tmpl->setVariable('peradminstyle'	, 'display:none;'	);
		$tmpl->setVariable('viewpercoment'	, 'display:none;'	);
		$tmpl->setVariable('cmbpertipo'		, 'disabled'	);
		$tmpl->setVariable('cmbperclase'	, 'disabled'	);
		$tmpl->setVariable('cmbmescodigo'         ,'disabled'    );
		$tmpl->setVariable('cmbrucod'         ,'disabled'    );
		
	}
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 			= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$fltviewproductos 	= (isset($_POST['fltviewproductos']))? trim($_POST['fltviewproductos']) : 0;
	$perestcod 			= 1; //Activo por defecto
	$pernombre 			= '';
	$perapelli 			= '';
	$percorreo 			= '';
	$pertelefo 			= '';
	$perdirecc 			= '';
	$perciudad 			= '';
	$perestado 			= '';
	$paicodigo 			= '';
	$dataSectores 		= '';
	$dataSubsectores 	= '';
	$dataCategorias		= '';
	$dataSubcategorias	= '';
	$perempdes			= '';
	$pertipo			= '';
	$perclase			= '';
	$mescodigo        	= '';
	$perrubcod          = '';
	$mesnumero          = '';
	$peridioma			= '';
	$perreuurl			= '';
	$percpf				= '';
	$timreg				= 86; //Buenos Aires
	$perindcod          = 0;
	$perarecod          =0;
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../perimg/'.$percodigo.'/';
	$imgProductoNull	= '../app-assets/img/pages/sativa.png';
	$tmpl->setVariable('imgProductoNull'	, $imgProductoNull 	);
	
	$imgAvatarNull = '../app-assets/img/avatar.png';
	$tmpl->setVariable('imgAvatarNull'	, $imgAvatarNull 	);
	//--------------------------------------------------------------------------------------------------------------
	//Acceso directo a clasificar productos
	$tmpl->setVariable('fltviewproductos'	, $fltviewproductos 	);
	//--------------------------------------------------------------------------------------------------------------
	//Avatar Inicial
	$tmpl->setVariable('peravatar'	, $imgAvatarNull 	);
	//--------------------------------------------------------------------------------------------------------------
	
	$conn= sql_conectar();//Apertura de Conexion
	
	if($percodigo!=0){
		$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.ESTCODIGO,P.PERCOMPAN,P.PERRUBCOD,P.PERCORREO,P.PERCIUDAD,P.PERESTADO,
							P.PERCODPOS,P.PERTELEFO,P.PERURLWEB,P.PERUSUACC,P.PERPASACC,P.PERDIRECC,P.PERCARGO,
							P.PAICODIGO,P.PERTIPO,P.PERCLASE,P.PEREMPDES,P.PERAVATAR,P.PERADMIN,P.PERUSADIS,P.PERUSAREU,P.PERUSAMSG,
							P.PERCOMENT,P.MESCODIGO,P.PERIDIOMA,
							P.TIMREG,P.PERREUURL,P.PERINDCOD,P.PERARECOD,P.PERCPF,P.PERFAC,P.PERTWI,P.PERINS,P.PERCODEVE
					FROM PER_MAEST P
					WHERE P.PERCODIGO=$percodigo ";
				
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$estcodigo 	= trim($row['ESTCODIGO']);
			$percompan 	= trim($row['PERCOMPAN']);
			$perrubcod 	= trim($row['PERRUBCOD']);
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
			$pertipo 	= trim($row['PERTIPO']);
			$perclase 	= trim($row['PERCLASE']);
			$mescodigo  = trim($row['MESCODIGO']);
			$perempdes 	= trim($row['PEREMPDES']);
			$peravatar 	= trim($row['PERAVATAR']);
			$peradmin 	= trim($row['PERADMIN']);
			$perusadis 	= trim($row['PERUSADIS']);
			$perusareu 	= trim($row['PERUSAREU']);
			$perusamsg 	= trim($row['PERUSAMSG']);
			$percoment 	= trim($row['PERCOMENT']);
			$peridioma  = trim($row['PERIDIOMA']);
			$timreg 	= trim($row['TIMREG']);
			$perreuurl 	= trim($row['PERREUURL']);
			$perindcod 	= trim($row['PERINDCOD']);
			$perarecod 	= trim($row['PERARECOD']);
			$perfac 	= trim($row['PERFAC']);
			$pertwi 	= trim($row['PERTWI']);
			$perins 	= trim($row['PERINS']);
			$percodeve 	= trim($row['PERCODEVE']);
			
			if($peradmin=='') $peradmin=0;
			
			if($peravatar==''){ 
				$peravatar = $imgAvatarNull;
			}else{
				$peravatar = $pathimagenes.$peravatar;
			}
			
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('percompan'	, $percompan	);
			$tmpl->setVariable('perrubcod'	, $perrubcod	);

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
			$tmpl->setVariable('perempdes'	, $perempdes 	);
			$tmpl->setVariable('peravatar'	, $peravatar 	);
			$tmpl->setVariable('percoment'	, $percoment 	);
			$tmpl->setVariable('peridioma'	, $peridioma 	);
			$tmpl->setVariable('perreuurl'	, $perreuurl 	);
			$tmpl->setVariable('perindcod'	, $perindcod 	);
			$tmpl->setVariable('perarecod'	, $perarecod 	);
			$tmpl->setVariable('percpf'		, $percpf 		);
			$tmpl->setVariable('perfac'		, $perfac 		);
			$tmpl->setVariable('pertwi'		, $pertwi 		);
			$tmpl->setVariable('perins'		, $perins 		);
			$tmpl->setVariable('percodeve'	, $percodeve 	);
			
			$tmpl->setVariable('peradminsel_'.$peradmin	, 'selected' 	);
			
			if($perusadis!=1){
				$tmpl->setVariable('btnviewdisp' , 'display:none;' 	);
			}
			
			
			//Busco la disponibilidad
			$queryDisp="SELECT PERDISFCH,PERDISHOR 
						FROM PER_DISP
						WHERE PERCODIGO=$percodigo 
						ORDER BY PERDISFCH,PERDISHOR ";
			$TableDet = sql_query($queryDisp,$conn);
			$dataDisp = '';
			for($j=0; $j<$TableDet->Rows_Count; $j++){
				$rowDet= $TableDet->Rows[$j];
				$perdisfch 	= BDConvFch($rowDet['PERDISFCH']);
				$perdishor 	= substr(trim($rowDet['PERDISHOR']),0,5);
				$dataDisp .= '{"fecha":"'.$perdisfch.'","hora":"'.$perdishor.'"},';
								
			}
			$dataDisp = substr($dataDisp,0,strlen($dataDisp)-1);
			$tmpl->setVariable('dataDisp'	, $dataDisp	);
			
			//Cargo los Sectores
			$queryClas = "	SELECT S.SECCODIGO
							FROM PER_SECT C
							LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=C.SECCODIGO
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$seccodigo 	= trim($rowClas['SECCODIGO']);
				
				$dataSectores .= '{"seccodigo":"'.$seccodigo.'"},';
			}
			$dataSectores = substr($dataSectores,0,strlen($dataSectores)-1);
			
			//Cargo los SubSectores
			$queryClas = "	SELECT S.SECSUBCOD
							FROM PER_SSEC C
							LEFT OUTER JOIN SEC_SUB S ON S.SECSUBCOD=C.SECSUBCOD
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3";
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$secsubcod 	= trim($rowClas['SECSUBCOD']);
				
				$dataSubsectores .= '{"secsubcod":"'.$secsubcod.'"},';
			}
			$dataSubsectores = substr($dataSubsectores,0,strlen($dataSubsectores)-1);
			
			//Cargo las Categorias
			$queryClas = "	SELECT S.CATCODIGO
							FROM PER_CATE C
							LEFT OUTER JOIN CAT_MAEST S ON S.CATCODIGO=C.CATCODIGO
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$catcodigo 	= trim($rowClas['CATCODIGO']);
				
				$dataCategorias .= '{"catcodigo":"'.$catcodigo.'"},';
			}
			$dataCategorias = substr($dataCategorias,0,strlen($dataCategorias)-1);
			
			//Cargo las SubCategorias
			$queryClas = "	SELECT S.CATSUBCOD
							FROM PER_SCAT C
							LEFT OUTER JOIN CAT_SUB S ON S.CATSUBCOD=C.CATSUBCOD
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$catsubcod 	= trim($rowClas['CATSUBCOD']);
				
				$dataSubcategorias .= '{"catsubcod":"'.$catsubcod.'"},';
			}
			$dataSubcategorias = substr($dataSubcategorias,0,strlen($dataSubcategorias)-1);
		}
		
		//Solo los Expositores pueden subir productos
		if($pertipo!=1){
			$tmpl->setVariable('viewproddestacados'		, 'display:none;' 		);
		}
	}
	
	$tmpl->setVariable('dataSectores'		, $dataSectores 		);
	$tmpl->setVariable('dataSubsectores'	, $dataSubsectores 		);
	$tmpl->setVariable('dataCategorias'		, $dataCategorias 		);
	$tmpl->setVariable('dataSubcategorias'	, $dataSubcategorias 	);
	
	//--------------------------------------------------------------------------------------------------------------
	$query = "	SELECT PERPROITM,PERPRONOM,PERPROFIL
				FROM PER_PROD
				WHERE PERCODIGO=$percodigo
				ORDER BY PERPROITM ";
	$productos = array();
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$perproitm = trim($row['PERPROITM']);
		$perpronom = trim($row['PERPRONOM']);
		$perprofil = trim($row['PERPROFIL']);
		
		$productos[$perproitm]['perpronom'] = $perpronom;
		$productos[$perproitm]['perprofil'] = $pathimagenes.$perprofil;
	}
	
	//Productos
	for($i=1; $i<=5; $i++){
		$perpronom 	= (isset($productos[$i]))? $productos[$i]['perpronom'] : '';
		$perprofil 	= (isset($productos[$i]))? $productos[$i]['perprofil'] : $imgProductoNull;
		$readonly 	= (isset($productos[$i]))? '' : 'readonly';
		
		
		$tmpl->setCurrentBlock('productos');
		$tmpl->setVariable('perproitm'	, $i	);
		$tmpl->setVariable('perpronom'	, $perpronom 	);
		$tmpl->setVariable('perprofil'	, $perprofil 	);
		$tmpl->setVariable('perpronomreadonly'	, $readonly	);
		$tmpl->parseCurrentBlock();
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	//Listado de Paises
	$query = "	SELECT PAICODIGO,PAIDESCRI
				FROM TBL_PAIS
				ORDER BY PAIDESCRI ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$paicod = trim($row['PAICODIGO']);
		$paides = trim($row['PAIDESCRI']);
		
		$tmpl->setCurrentBlock('paises');
		$tmpl->setVariable('paicodigo'	, $paicod 	);
		$tmpl->setVariable('paidescri'	, $paides 	);
		
		if($paicodigo==$paicod){
			$tmpl->setVariable('paiselected', 'selected' );
		}
		
		$tmpl->parseCurrentBlock();
	}
	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO			
				ORDER BY PERTIPO";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipcod 	= trim($row['PERTIPO']);
		$pertipdes	= trim($row['PERTIPDES']);
		
		//SI el usuario no es admin, cargo solo los registros asignados
		if($peradminlog!=1){ 
			if($pertipo==$pertipcod){
				$tmpl->setCurrentBlock('pertipos');
				$tmpl->setVariable('pertipcod'	, $pertipcod 		);
				$tmpl->setVariable('pertipdes'	, $pertipdes	);
				$tmpl->setVariable('pertipsel', 'selected' );
				$tmpl->parse('pertipos');
			}
		}else{
			$tmpl->setCurrentBlock('pertipos');
			$tmpl->setVariable('pertipcod'	, $pertipcod 		);
			$tmpl->setVariable('pertipdes'	, $pertipdes	);
			
			if($pertipo==$pertipcod){
				$tmpl->setVariable('pertipsel', 'selected' );
			}
			
			$tmpl->parse('pertipos');
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	//Clase de Perfiles
	if($pertipo!=''){
		$query = "	SELECT PERCLASE,PERCLADES
					FROM PER_CLASE	
					WHERE PERTIPO=$pertipo
					ORDER BY PERCLASE ";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$perclacod 	= trim($row['PERCLASE']);
			$perclades	= trim($row['PERCLADES']);
			
			//SI el usuario no es admin, cargo solo los registros asignados
			if($peradminlog!=1){ 
				if($perclase==$perclacod){
					$tmpl->setCurrentBlock('perclases');
					$tmpl->setVariable('perclacod'	, $perclacod 	);
					$tmpl->setVariable('perclades'	, $perclades	);	
					$tmpl->setVariable('perclasel', 'selected' );
					$tmpl->parse('perclases');
				}
			}else{
				$tmpl->setCurrentBlock('perclases');
				$tmpl->setVariable('perclacod'	, $perclacod 	);
				$tmpl->setVariable('perclades'	, $perclades	);	

				if($perclase==$perclacod){
					$tmpl->setVariable('perclasel', 'selected' );
				}
				
				$tmpl->parse('perclases');
			}
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	//Seleccionamos las mesas
	$query = "	SELECT MESCODIGO,MESNUMERO,ESTCODIGO
				FROM MES_MAEST	
				ORDER BY MESCODIGO";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$mesclacod 	= trim($row['MESCODIGO']);
		$mesnumero	= trim($row['MESNUMERO']);
		//$estcodigo = trim($row['ESTCODIGO']);
		
		
		//SI el usuario no es admin, cargo solo los registros asignados
		if($peradminlog!=1){ 
			if($mescodigo == $mesclacod){
				$tmpl->setCurrentBlock('mesclases');
				$tmpl->setVariable('mesclacod'	, $mesclacod 	);
				$tmpl->setVariable('mesnumero'	, $mesnumero	);	
				$tmpl->setVariable('mesclasel', 'selected' );
				$tmpl->parse('mesclases');
			}
		}else{
			$tmpl->setCurrentBlock('mesclases');
			$tmpl->setVariable('mesclacod'	, $mesclacod 	);
			$tmpl->setVariable('mesnumero'	, $mesnumero	);	

			if($mescodigo == $mesclacod){
				$tmpl->setVariable('mesclasel', 'selected' );
			}
			
			$tmpl->parse('mesclases');
		}
	}
	//--------------------------------------------------------------------------------------------------------------

	//Seleccionamos los rubros
	 $query = "	SELECT PERRUBCOD,PERRUBDES$IdiomView AS PERRUBDES
	 			FROM PER_RUBR 
	 			ORDER BY PERRUBDES$IdiomView ";			
	 $Table = sql_query($query,$conn);
	 for($i=0; $i<$Table->Rows_Count; $i++){
	 	$row = $Table->Rows[$i];
	 	$rubcod 	= trim($row['PERRUBCOD']);
	 	$perrubdes	= trim($row['PERRUBDES']);
		
	 	if($peradminlog!=1){ 
	 		if($perrubcod==$rubcod){
	 			$tmpl->setCurrentBlock('rubros');
	 			$tmpl->setVariable('rubcod'		, $rubcod 		);
	 			$tmpl->setVariable('perrubdes'	, $perrubdes	);
	 			$tmpl->setVariable('rubsel'		, 'selected' 	);
	 			$tmpl->parse('rubros');
	 		}
	 	}else{
	 		$tmpl->setCurrentBlock('rubros');
	 		$tmpl->setVariable('rubcod'	, $rubcod 		);
	 			$tmpl->setVariable('perrubdes'	, $perrubdes	);
			
	 		if($perrubcod==$rubcod){
	 			$tmpl->setVariable('rubsel', 'selected' );
	 		}
			
	 		$tmpl->parse('rubros');
	 	}
		
	 }

	//--------------------------------------------------------------------------------------
	//Seleccionamos los rubros

	$queryA="SELECT PERARECOD,PERAREDESESP FROM PER_AREA";
	$TableA=sql_query($queryA,$conn);
	for($i=0; $i<$TableA->Rows_Count; $i++){
		$row = $TableA->Rows[$i];
		$perareacod 	= trim($row['PERARECOD']);
		$peraredes		= trim($row['PERAREDESESP']);
		

		$tmpl->setCurrentBlock('area');
			$tmpl->setVariable('perareacod'	, $perareacod );
			$tmpl->setVariable('peraredes'	, $peraredes );
			 if ($perarecod==$perareacod) {
				$tmpl->setVariable('areasel', 'selected' );
			 }
		$tmpl->parse('area');
	}


	//--------------------------------------------------------------------------------------
	//Seleccion de Industrias
	$query1="SELECT PERINDCOD, PERINDDESESP FROM PER_IND ";

	$Table1=sql_query($query1,$conn);
	for($i=0; $i<$Table1->Rows_Count; $i++){
		$row = $Table1->Rows[$i];
		$perinducod 	= trim($row['PERINDCOD']);
	
		$perinddes	= trim($row['PERINDDESESP']);
		
		$tmpl->setCurrentBlock('industria');
			$tmpl->setVariable('perinducod'	, $perinducod 		);
			$tmpl->setVariable('perinddes'	, $perinddes 		);
			if ($perindcod==$perinducod) {
				$tmpl->setVariable('indusel', 'selected' );
			 }
		$tmpl->parse('industria');

	}

	//--------------------------------------------------------------------------------------------------------------
	//Seleccionamos las Zonas Horarias
	$query = "	SELECT TIMREG,TIMDESCRI,TIMOFFSET
				FROM TIM_ZONE 
				ORDER BY TIMREG ";			
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$tim 	= trim($row['TIMREG']);
		$timdescri	= trim($row['TIMDESCRI']);
		$timoffset	= trim($row['TIMOFFSET']);
		
		$timoffset 	= $timoffset / 60.0 /60.0;
		$signo		= ($timoffset>0)? '+':'-';
		$timoffset	= abs($timoffset);
		$horas 		= floor($timoffset);
		$minutos 	= ($timoffset - $horas) * 60;
		
		$horas = str_pad($horas, 2, "0", STR_PAD_LEFT);
		$minutos = str_pad($minutos, 2, "0", STR_PAD_LEFT);
		
		$tmpl->setCurrentBlock('zonahoraria');
		$tmpl->setVariable('timregcod'	, $tim 		);
		$tmpl->setVariable('timdescri'	, "$timdescri (GMT$signo$horas:$minutos)"	);
		if($tim==$timreg){
			$tmpl->setVariable('timsel'	, 'selected' 	);
		}
		$tmpl->parse('zonahoraria');
		
	}
	
	
	//--------------------------------------------------------------------------------------------------------------

	$query1="SELECT IDICODIGO, IDIDESCRI FROM IDI_MAEST ";

	$Table1=sql_query($query1,$conn);
	for($i=0; $i<$Table1->Rows_Count; $i++){
		$row = $Table1->Rows[$i];
		$idicodigo 	= trim($row['IDICODIGO']);
		$ididescri 	= trim($row['IDIDESCRI']);
	
		$tmpl->setCurrentBlock('idiomas');
		$tmpl->setVariable('idicodigo'	, $idicodigo	);
		$tmpl->setVariable('ididescri'	, $ididescri	);
		if ($idicodigo==$peridioma) {
			$tmpl->setVariable('idiselected', 'selected' );
		 }
		$tmpl->parse('idiomas');

	}

	sql_close($conn);
	
	$tmpl->show();
	
?>	
