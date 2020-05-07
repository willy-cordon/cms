<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../perimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 			= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$pernombre 			= (isset($_POST['pernombre']))? trim($_POST['pernombre']) : '';
	$perapelli 			= (isset($_POST['perapelli']))? trim($_POST['perapelli']) : '';
	$estcodigo 			= (isset($_POST['estcodigo']))? trim($_POST['estcodigo']) : 1;
	$percompan 			= (isset($_POST['percompan']))? trim($_POST['percompan']) : '';
	$percorreo 			= (isset($_POST['percorreo']))? trim($_POST['percorreo']) : '';
	$perrubcod		    = (isset($_POST['perrubcod']))? trim($_POST['perrubcod']) : '';
	$perciudad 			= (isset($_POST['perciudad']))? trim($_POST['perciudad']) : '';
	$perestado 			= (isset($_POST['perestado']))? trim($_POST['perestado']) : '';
	$percodpos 			= (isset($_POST['percodpos']))? trim($_POST['percodpos']) : '';
	$pertelefo 			= (isset($_POST['pertelefo']))? trim($_POST['pertelefo']) : '';
	$perurlweb 			= (isset($_POST['perurlweb']))? trim($_POST['perurlweb']) : '';
	$perusuacc 			= (isset($_POST['perusuacc']))? trim($_POST['perusuacc']) : '';
	$perpasacc 			= (isset($_POST['perpasacc']))? trim($_POST['perpasacc']) : '';
	$perpasaccant 		= (isset($_POST['perpasaccant']))? trim($_POST['perpasaccant']) : '';
	$perdirecc 			= (isset($_POST['perdirecc']))? trim($_POST['perdirecc']) : '';
	$percargo  			= (isset($_POST['percargo']))? trim($_POST['percargo']) : '';
	$paicodigo  		= (isset($_POST['paicodigo']))? trim($_POST['paicodigo']) : '';
	$pertipo  			= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : '0';
	$perclase 	 		= (isset($_POST['perclase']))? trim($_POST['perclase']) : '0';
	$perempdes 			= (isset($_POST['perempdes']))? trim($_POST['perempdes']) : '';
	$mescodigo			= (isset($_POST['mescodigo']))? trim($_POST['mescodigo']) : '';
	$mesnumero			= (isset($_POST['mesnumero']))? trim($_POST['mesnumero']) : '';
	$dataDisponibilidad	= (isset($_POST['dataDisponibilidad']))? trim($_POST['dataDisponibilidad']) : '';
	$dataClasificar		= (isset($_POST['dataClasificar']))? trim($_POST['dataClasificar']) : '';
	$peradmin 			= (isset($_POST['peradmin']))? trim($_POST['peradmin']) : 0;
	$peridioma 			= (isset($_POST['peridioma']))? trim($_POST['peridioma']) : '';
	$timreg				= (isset($_POST['timreg']))? trim($_POST['timreg']) : ''; 
	$perreuurl			= (isset($_POST['timreg']))? trim($_POST['perreuurl']) : ''; 
	$perindcod			= (isset($_POST['perindcod']))? trim($_POST['perindcod']) : '';	
	$perarecod			= (isset($_POST['perarecod']))? trim($_POST['perarecod']) : '';
	$percpf				= (isset($_POST['percpf']))? trim($_POST['percpf']) : '';
	$perfac				= (isset($_POST['perfac']))? trim($_POST['perfac']) : '';
	$pertwi				= (isset($_POST['pertwi']))? trim($_POST['pertwi']) : '';
	$perins				= (isset($_POST['perins']))? trim($_POST['perins']) : '';
	$percodeve			= (isset($_POST['percodeve']))? trim($_POST['percodeve']) : '';
	$encres=1;

	if ($perrubcod == '' || $perrubcod == 0) {
		$perrubcod = null;
	}
	//Zona Horaria Buenos Aires
	if($timreg=='') $timreg=86;
	
	//Controlo si el usuario logueado es administrador
	$peradminlog = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	if($peradminlog!=1) $peradmin=0;
	
	
	if($pernombre==''){
		$errcod=2;
		$errmsg='Falta el nombre';
	}
	if($perapelli==''){
		$errcod=2;
		$errmsg='Falta el apellido';
	}
	if($percompan==''){
		$errcod=2;
		$errmsg='Falta la compañia';
	}
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	
	$percodigo		= VarNullBD($percodigo		, 'N');
	$pernombre		= VarNullBD($pernombre		, 'S');
	$perapelli		= VarNullBD($perapelli		, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$percompan		= VarNullBD($percompan		, 'S');
	$percorreo		= VarNullBD($percorreo		, 'S');
	$perrubcod		= VarNullBD($perrubcod		, 'N');
	$perciudad		= VarNullBD($perciudad		, 'S');
	$perestado		= VarNullBD($perestado		, 'S');
	$percodpos		= VarNullBD($percodpos		, 'S');
	$pertelefo		= VarNullBD($pertelefo		, 'S');
	$perurlweb		= VarNullBD($perurlweb		, 'S');
	//$perpasacc		= VarNullBD($perpasacc		, 'S');
	//$perpasaccant 	= VarNullBD($perpasaccant	, 'S');
	$perdirecc 		= VarNullBD($perdirecc		, 'S');
	$percargo  		= VarNullBD($percargo 		, 'S');
	$paicodigo 		= VarNullBD($paicodigo		, 'N');
	$pertipo 		= VarNullBD($pertipo		, 'N');
	$perclase 		= VarNullBD($perclase		, 'N');
	$perempdes 		= VarNullBD($perempdes 		, 'S');
	$peradmin 		= VarNullBD($peradmin		, 'N');
	$mescodigo		= VarNullBD($mescodigo		, 'N');
	$mesnumero		= VarNullBD($mesnumero		, 'S');
	$peridioma		= VarNullBD($peridioma		, 'S');
	$perreuurl		= VarNullBD($perreuurl		, 'S');
	$perindcod		= VarNullBD($perindcod		, 'N');
	$perarecod		= VarNullBD($perarecod		, 'N');
	$encres			= VarNullBD($encres			, 'N');
	$percpf			= VarNullBD($percpf			, 'S');
	$perfac			= VarNullBD($perfac			, 'S');
	$pertwi			= VarNullBD($pertwi			, 'S');
	$perins			= VarNullBD($perins			, 'S');
	$percodeve		= VarNullBD($percodeve		, 'S');
	
	
	$updpass='';
	if(trim($perpasacc)!=($perpasaccant)){
		$perpasacc = md5('BenVido'.$perpasacc.'PassAcceso'.$perusuacc);
		$perpasacc = 'B#SD'.md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8)).'E##$F';
		
		$updpass=" ,PERPASACC= '$perpasacc' ";
	}
	if(trim($perpasacc=='')) $perpasacc='NULL';
	
	$perusuacc		= VarNullBD($perusuacc		, 'S');
	
	if($percodigo==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PERFILES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$percodigo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PER_MAEST(PERCODIGO,PERNOMBRE,PERAPELLI,ESTCODIGO,PERCOMPAN,PERRUBCOD,PERCORREO,PERCIUDAD,PERESTADO,
											PERCODPOS,PERTELEFO,PERURLWEB,PERUSUACC,PERPASACC,PERDIRECC,PERCARGO,
											PAICODIGO,PERTIPO,PERCLASE,MESCODIGO,PEREMPDES,PERADMIN,PERUSADIS,PERUSAREU,PERUSAMSG,PERIDIOMA,
											TIMREG,PERINDCOD,PERARECOD,PERREUURL,ENCRES,PERCPF,PERFAC,PERTWI,PERINS,PERCODEVE)
					VALUES( $percodigo, $pernombre, $perapelli, $estcodigo, $percompan,$perrubcod, 			$percorreo, $perciudad, $perestado,
							$percodpos, $pertelefo, $perurlweb, $perusuacc, '$perpasacc', $perdirecc, $percargo,
							$paicodigo, $pertipo, $mescodigo, $perclase, $perempdes, $peradmin,0,0,0,$peridioma,
							$timreg,$perindcod,$perarecod,$perreuurl,$encres,$percpf,$perfac,$pertwi,$perins,$percodeve) ";
							
						
	}else{
		$query = " 	UPDATE PER_MAEST SET 
					PERNOMBRE=$pernombre, PERAPELLI=$perapelli, ESTCODIGO=$estcodigo,
					PERCOMPAN=$percompan,PERRUBCOD=$perrubcod, PERCORREO=$percorreo, PERCIUDAD=$perciudad,
					PERESTADO=$perestado, PERCODPOS=$percodpos, PERTELEFO=$pertelefo,
					PERURLWEB=$perurlweb, PERDIRECC=$perdirecc, PERCARGO=$percargo,
					PAICODIGO=$paicodigo, PERUSUACC=$perusuacc, PERTIPO=$pertipo,
					PERIDIOMA=$peridioma, TIMREG=$timreg,
					MESCODIGO=$mescodigo, PERCLASE=$perclase, PEREMPDES=$perempdes, PERADMIN=$peradmin,PERINDCOD=$perindcod, PERARECOD=$perarecod,
					PERFAC   =$perfac,    PERTWI=$pertwi,	  PERINS=$perins,
					PERREUURL=$perreuurl,ENCRES=$encres, PERCPF=$percpf,
					PERCODEVE=$percodeve
					$updpass
					WHERE PERCODIGO=$percodigo ";
					
	}
	
	$err = sql_execute($query,$conn,$trans);	
	
	//Almaceno la Disponibilidad
	if($err == 'SQLACCEPT' && $errcod==0){
		$query=" DELETE FROM PER_DISP WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		
		if($dataDisponibilidad!=''){
			$dataDisponibilidad = json_decode($dataDisponibilidad);
			
			foreach($dataDisponibilidad as $ind => $data){
				$fecha 	= $data->fecha;
				$hora 	= $data->hora;
				
				$perdisfch 	= ConvFechaBD($fecha);
				$perdishor 	= VarNullBD($hora  , 'S');
				
				$query = "	INSERT INTO PER_DISP(PERCODIGO,PERDISFCH,PERDISHOR)
							VALUES($percodigo,$perdisfch,$perdishor)";
				$err = sql_execute($query,$conn,$trans);
			}
		}
	}
	
	//Almaceno la Clasificacion
	if($err == 'SQLACCEPT' && $errcod==0){
		$query=" DELETE FROM PER_SECT WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		$query=" DELETE FROM PER_SSEC WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		$query=" DELETE FROM PER_CATE WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		$query=" DELETE FROM PER_SCAT WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		
		//Recorro los sectores
		if($dataClasificar!=''){
			$dataClasificar = json_decode($dataClasificar);
			foreach($dataClasificar->sectores as $ind => $data){
				$codigo	= $data->seccodigo;
				
				$query = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO)
							VALUES($percodigo,$codigo)";
				$err = sql_execute($query,$conn,$trans);
			}
			
			//Recorro los subsectores
			if(isset($dataClasificar->subsectores)){
				foreach($dataClasificar->subsectores as $ind => $data){
					$codigo	= $data->secsubcod;
					
					$query = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD)
								VALUES($percodigo,$codigo)";
					$err = sql_execute($query,$conn,$trans);
				}
			}
			
			//Recorro las categorias
			if(isset($dataClasificar->categorias)){
				foreach($dataClasificar->categorias as $ind => $data){
					$codigo	= $data->catcodigo;
					
					$query = "	INSERT INTO PER_CATE(PERCODIGO,CATCODIGO)
								VALUES($percodigo,$codigo)";
					$err = sql_execute($query,$conn,$trans);
				}
			}
			//Recorro las subcategorias
			if(isset($dataClasificar->subcategorias)){
				foreach($dataClasificar->subcategorias as $ind => $data){
					$codigo	= $data->catsubcod;
					
					$query = "	INSERT INTO PER_SCAT(PERCODIGO,CATSUBCOD)
								VALUES($percodigo,$codigo)";
					$err = sql_execute($query,$conn,$trans);
				}
			}
		}
	}
	
	//Almaceno los archivos
	for($i=1; $i<=5; $i++){
		$perpronom 	= (isset($_POST['perpronom'.$i]))? trim($_POST['perpronom'.$i]) : '';
		$perprofil	= '';
		
		if(isset($_FILES['file'.$i])){ //Nuevo Archivo subido
			$query=" DELETE FROM PER_PROD WHERE PERCODIGO=$percodigo AND PERPROITM=$i ";
			$err = sql_execute($query,$conn,$trans);
			
			$ext 		= pathinfo($_FILES['file'.$i]['name'], PATHINFO_EXTENSION);
			$name = 'PP_'.$i.'.'.$ext;
			
			if (!file_exists($pathimagenes.$percodigo)) {
				mkdir($pathimagenes.$percodigo);	   				
			}
			if(file_exists($pathimagenes.$percodigo.'/'.$name)){
				unlink($pathimagenes.$percodigo.'/'.$name);
			}
			move_uploaded_file( $_FILES['file'.$i]['tmp_name'], $pathimagenes.$percodigo.'/'.$name);
			
			$query = "	INSERT INTO PER_PROD(PERCODIGO,PERPROITM,PERPRONOM,PERPROFIL)
						VALUES($percodigo,$i,'$perpronom','$name')";
			$err = sql_execute($query,$conn,$trans);
			
		}else{ //Actualizo los datos del archvio
			if($perpronom == ''){//Si esta en blanco, elimino el producto
				$query=" DELETE FROM PER_PROD WHERE PERCODIGO=$percodigo AND PERPROITM=$i ";
				$err = sql_execute($query,$conn,$trans);
				
				$name = 'PP_'.$i.'.*';
				array_map('unlink', glob($pathimagenes.$percodigo.'/'.$name));
			}else{
				$query = "	UPDATE PER_PROD SET PERPRONOM='$perpronom' WHERE PERCODIGO=$percodigo AND PERPROITM=$i ";
				$err = sql_execute($query,$conn,$trans);
			}
		}
	}
	
	//Archivo del Avatar ANCHOR  AVATAR

	//NOTE REDIMENCIONAR IMAGEN 200PX X 200PX


	if(isset($_FILES['fileavatar'])){
		$ext 	= pathinfo($_FILES['fileavatar']['name'], PATHINFO_EXTENSION);
	
		
		$name 	="P_".date("His.").$ext;
		
		if (!file_exists($pathimagenes.$percodigo)) {
			mkdir($pathimagenes.$percodigo);	   				
		}
		if(file_exists($pathimagenes.$percodigo.'/'.$name)){
			unlink($pathimagenes.$percodigo.'/'.$name);
		}

		move_uploaded_file( $_FILES['fileavatar']['tmp_name'], $pathimagenes.$percodigo.'/'.$name);

		$_SESSION[GLBAPPPORT.'PERAVATAR'] =  $pathimagenes.$percodigo.'/'.$name; //Actualizo la variable de Session del AVATAR
		
		$query = "	UPDATE PER_MAEST SET PERAVATAR='$name' WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);


//-------------Redimension de imagen----------------------------------//
		$imagen_optimizada = redimensionar_imagen($name,$pathimagenes.$percodigo.'/'.$name,200,200);
	
		//Guardado de imagen
		imagepng($imagen_optimizada, $pathimagenes.$percodigo.'/'.$name);
		
		
	

	}
	
	//Establezco la zona horaria
	if($percodigo == $_SESSION[GLBAPPPORT.'PERCODIGO']){ //Si el usuario es el logueado
		$query = "	SELECT TIMREG,TIMDESCRI,TIMOFFSET
					FROM TIM_ZONE 
					WHERE TIMREG=$timreg ";		
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		
		$_SESSION[GLBAPPPORT.'TIMREG'] 		= trim($row['TIMREG']);
		$_SESSION[GLBAPPPORT.'TIMDESCRI'] 	= trim($row['TIMDESCRI']);
		$_SESSION[GLBAPPPORT.'TIMOFFSET'] 	= trim($row['TIMOFFSET']);
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
		
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	

