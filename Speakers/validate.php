<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';		
	

	

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
    $spkpos 	= (isset($_POST['spkpos']))? trim($_POST['spkpos']) : '';
    
    $spkpos	= VarNullBD($spkpos		, 'N');
   //Consultamos a la base de datos si el usuario existe
   $querycor="SELECT SPKPOS FROM SPK_MAEST WHERE SPKPOS=$spkpos";
   //logerror($querycor);
   $executecor=sql_query($querycor,$conn);
  
   if ($executecor->Rows_Count>0) {
       $errcod2=1; 
       $errmsg2='Orden ya asignado';
    //    echo('Espacio ya asignado');
       
   }else{
       $errcod2=0;
       $errmsg2='Orden disponible';
    //    echo('Espacio Disponible');
       
   }
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	
	echo '{"errcod":"'.$errcod2.'","errmsg":"'.$errmsg2.'"}';
?>	
