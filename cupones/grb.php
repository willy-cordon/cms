<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../cupimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$cupcod 		= (isset($_POST['cupcod']))? trim($_POST['cupcod']) : 0;

	$cuptitulo 		= (isset($_POST['cuptitulo']))? trim($_POST['cuptitulo']) : '';
	$cupdescri 		= (isset($_POST['cupdescri']))? trim($_POST['cupdescri']) : '';
	$cupclase		= $_POST['cupclase'];
	$cuptipo		= $_POST['cuptipo'];
	$estcodigo 		= 1;
	
	if($cupcod==''){
		$cupcod=0;
	}
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$cupcod			= 	VarNullBD($cupcod			, 'N');
	$cuptitulo		= 	VarNullBD($cuptitulo		, 'S');
	$cupdescri      =	VarNullBD($cupdescri		, 'S');
	$cupclase		= 	VarNullBD($cupclase			, 'N');
	$cuptipo		= 	VarNullBD($cuptipo			, 'N');
	$estcodigo		=	VarNullBD($estcodigo		, 'N');
	logerror($cupclase.$cuptipo);
	if($cupcod==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(CUP_G,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$cupcod 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO CUP_MAEST (CUPCOD,CUPTITULO,CUPDESCRI,ESTCODIGO, CUPTIPO,CUPCLASE)
					VALUES ($cupcod,$cuptitulo,$cupdescri,$estcodigo,$cuptipo,$cupclase) ";
	}else{
		$query = " 	UPDATE CUP_MAEST SET 
					CUPCOD=$cupcod,CUPTITULO=$cuptitulo,CUPDESCRI=$cupdescri,  ESTCODIGO=$estcodigo,CUPTIPO = $cuptipo,CUPCLASE=$cupclase
					WHERE CUPCOD=$cupcod ";
	}
	//logerror($query);
	//die();
	$err = sql_execute($query,$conn,$trans);	
	date_default_timezone_set('UTC');
	//--------------------------------------------------------------------------------------------------------------
	if(isset($_FILES['cupimg'])){
		
		$ext 	= pathinfo($_FILES['cupimg']['name'], PATHINFO_EXTENSION);
		$name 	= 'CUPIMAGEN'.date(mktime(0, 0, 0, 7, 1, 2000)).'.'.$ext;
		
		if (!file_exists($pathimagenes.$cupcod)) {
			mkdir($pathimagenes.$cupcod);	   				
		}
		if(file_exists($pathimagenes.$cupcod.'/'.$name)){
			unlink($pathimagenes.$cupcod.'/'.$name);
		}
		move_uploaded_file( $_FILES['cupimg']['tmp_name'], $pathimagenes.$cupcod.'/'.$name);

		$imagen_optimizada = redimensionar_imagen($name,$pathimagenes.$cupcod.'/'.$name,200,200);
	
		//Guardado de imagen
		imagepng($imagen_optimizada, $pathimagenes.$cupcod.'/'.$name);
		
		 
		$_SESSION[GLBAPPPORT.'cupimg'] =  $pathimagenes.$cupcod.'/'.$name; //Actualizo la variable de Session del AVATAR
		
		$query = "	UPDATE CUP_MAEST SET CUPIMG='$name' WHERE CUPCOD=$cupcod ";

		logerror("IMAGEN".$query);
		$err = sql_execute($query,$conn,$trans);
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Guardado correctamente!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
