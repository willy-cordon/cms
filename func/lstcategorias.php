<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
    $percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
    $pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
    $perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
    $percompan = (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
	
	$secsubcod = (isset($_POST['secsubcod']))? trim($_POST['secsubcod']) : 0;
       
    $JSTable	= '['; // nombre de la variable json
    
	if($secsubcod!=0){   
		$conn= sql_conectar();
		
		$query = "SELECT CATCODIGO,CATDESCRI
					FROM CAT_MAEST 
					WHERE ESTCODIGO=1 AND SECSUBCOD=$secsubcod 
					ORDER BY CATDESCRI ";
					
		$Table = sql_query($query,$conn);
		for($i=0; $i < $Table->Rows_Count; $i++){
			$row= $Table->Rows[$i];
			$catcodigo	= trim($row['CATCODIGO']); 
			$catdescri	= trim($row['CATDESCRI']);
		
			$JSTable .= '{	"catcodigo":"'.$catcodigo.'",
							"catdescri":"'.$catdescri.'"
							},';
		}
	   
		if($Table->Rows_Count >0){ //Si hay registros
			$JSTable	= substr($JSTable, 0, strlen($JSTable)-1);
		}
		
		sql_close($conn);
	}
            
    $JSTable.=']';
    echo $JSTable;
	
?>	
