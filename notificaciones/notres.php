<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
    
    
   $conn= sql_conectar();#ejecutamos la conexion

    $notregs= '';
    #Recorremos el post recibido 
	foreach ($_POST['notificaciones'] as $index => $data) {
		$notregs .= $data['notreg'].',';
	}
	if(trim($notregs)!=''){
		#Realizamos la actualizacion en la base de datos 	
		$query=" UPDATE NOT_CABE SET NOTESTADO=2, NOTFCHLEI=CURRENT_TIMESTAMP WHERE NOTREG IN ($notregs 0) ";
		$execute = sql_execute($query,$conn);
	}
	sql_close($conn);#cerramos la conexion
  
    
?>	
