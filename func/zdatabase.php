<?php
// VARIABLES GLOBALES
if(DEVELOPER==0)
	error_reporting(0);
else{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}


//***************************************************************************************************
class SQLTable{ //Clase de Tabla de Datos
	var $Fields_Count 	= 0;
	var $Fields_Names 	= '';	
	var $Rows_Count   	= -1;		
	var $Rows         	= array();
	var $isError		= false;
	var $ErrorMsg		= '';
		
	//-------------------------------------------------------------------------------	
	function TakeFieldsNames($row){ //Separa los Nombres de los Campos por , (coma)
		if($this->Fields_Names==''){
			foreach ($row as $col=>$val){
				$this->Fields_Names .= "$col,";				
				$this->Fields_Count++;
			}	
			$this->Fields_Names= substr($this->Fields_Names,0,strlen($this->Fields_Names)-1);			
		}				
	}
	//-------------------------------------------------------------------------------
	function NewRow($row){
		if($this->Rows_Count==-1) $this->Rows_Count=0;		
		foreach ($row as $col=>$val){
			//$val= iconv("UTF-8", "ISO-8859-1",$val);
			//Reemplazo de Comillas dobles por sigma error (&rdquo;)						
			$val = str_replace('"','&quot;',$val);
			$val = str_replace("'",'&#39;',$val);
			$val = str_replace("<",'&lt;',$val);
			$val = str_replace(">",'&gt;',$val);
			$RowData[$col]=$val;
		}	
		$this->Rows[$this->Rows_Count]=$RowData;		
		$this->Rows_Count++;	
	}
	//-------------------------------------------------------------------------------
}
//***************************************************************************************************
function sql_conectar(){   
  $conn = ibase_connect(FBrutadatos,FBuser,FBpass,'UTF8', 0, 3);
  if (!$conn) {
      echo "Acceso Prohibido";
      exit;
    }
  return $conn;
}
//***************************************************************************************************
function sql_begin_trans($conn){ //Inicio de Transaccion  
  $trans=ibase_trans(IBASE_DEFAULT,$conn);
  return $trans;		
}
//***************************************************************************************************
function sql_commit_trans($trans){ //Confirmacion de Datos de Transaccion
	$v=ibase_commit($trans);	
}
//***************************************************************************************************
function sql_rollback_trans($trans){ //Retorno de Datos de Transaccion
	$v=ibase_rollback($trans);
}
//***************************************************************************************************
function sql_close($conn){
	ibase_close($conn);
}
//***************************************************************************************************
function sql_execute($query,$conn,$trn='N',$errXML='S'){  //Ejecuta Consultas (Insert, Update, Delete, ....) 
	
	//$query 	= iconv("UTF-8", "ISO-8859-1",$query);     

  //Sin Transaccion, abro una transaccion.
  if($trn=='N') $trans= sql_begin_trans($conn);
  else $trans= $trn;

  $query = str_replace('#MAS#', "+", $query);
  
  $prep = ibase_prepare($trans,$query);
  if (ibase_errmsg() !== false){       
       $errmsg= ibase_errmsg();
       sql_rollback_trans($trans);

		//Genero la salida del Log
		$nomFil = date("Ymd"); 			//Nombre del Archivo
		$fchReg	= date("Y/m/d h:i:s"); 	//Fecha y Hora de Registro
		$qryReg	= str_replace(chr(13).chr(10)," ", $query);		//Query de Registro
		file_put_contents(GLBRutaFUNC."/errdb/exe_$nomFil.err", "$fchReg -- $errmsg -- $qryReg \r\n", FILE_APPEND);
	
		 if($errXML == 'S'){
		   echo "<errdb>                 
					<errmsg style='visibility:hidden;'>2.$errmsg</errmsg>
					<sqlerr style='visibility:hidden;'>$query</sqlerr>				 
					<htmlcode> <script> alert('2.Error: $errmsg') </script> </htmlcode>
				</errdb>";  
		   return "SQLERROR";
		 }else{
			 file_put_contents(GLBRutaFUNC."/errdb/exe_$nomFil.err", "$fchReg -- $errmsg -- $qryReg \r\n", FILE_APPEND);
		 }
  }else{
    $errmsg = ibase_execute($prep);   
    if (ibase_errmsg() !== false){       
       $errmsg=ibase_errmsg();       
       sql_rollback_trans($trans);
          
	   if(strpos($errmsg,'E_CUSTOM_ERR') > 0){
			$posini = strpos($errmsg,'E_CUSTOM_ERR')+12;			
			$errmsg = substr($errmsg,$posini,strlen($errmsg));
			$posfin = strlen($errmsg);
			if(strpos($errmsg,'At trigger') > 0){
				$posfin = strpos($errmsg,'At trigger');
			}
			if(strpos($errmsg,'At procedure') > 0){
				$posfin = strpos($errmsg,'At procedure');
			}
			$errmsg = substr($errmsg,0,$posfin);
	   }else{
			//Genero la salida del Log
			$nomFil = date("Ymd"); 			//Nombre del Archivo
			$fchReg	= date("Y/m/d h:i:s"); 	//Fecha y Hora de Registro
			$qryReg	= str_replace(chr(13).chr(10)," ", $query);		//Query de Registro
			file_put_contents(GLBRutaFUNC."/errdb/exe_$nomFil.err", "$fchReg -- $errmsg -- $qryReg \r\n", FILE_APPEND);
		
	   }
	   if($errXML == 'S'){
		   echo "<errdb>                 
					<errmsg style='visibility:hidden;'>$errmsg</errmsg>
					<sqlerr style='visibility:hidden;'>$query</sqlerr>				 
					<htmlcode> <script>  </script> </htmlcode>
				 </errdb>";
	   }else{
			//Genero la salida del Log
			$nomFil = date("Ymd"); 			//Nombre del Archivo
			$fchReg	= date("Y/m/d h:i:s"); 	//Fecha y Hora de Registro
			$qryReg	= str_replace(chr(13).chr(10)," ", $query);		//Query de Registro
			file_put_contents(GLBRutaFUNC."/errdb/exe_$nomFil.err", "$fchReg -- $errmsg -- $qryReg \r\n", FILE_APPEND);
		}
       return "SQLERROR";
    }else{ //Todo se Proceso con Exito
	   if($trn=='N') sql_commit_trans($trans);
       return "SQLACCEPT";
    }               
  }
}
//***************************************************************************************************
function sql_execute_script($query,$conn,$trn='N'){
	if($trn=='N') $trans= sql_begin_trans($conn);
    else $trans= $trn;
    	
	$vq= explode(';',$query);	
	for($i=0; $i<count($vq)-1; $i++){
       	$result= sql_execute($vq[$i],$conn,$trans);      	
       	if($result=='SQLERROR'){
       		return 'SQLERROR';
       		break;	
       	}
	}
	if($trn=='N') sql_commit_trans($trans);	
		
	return $result;
}
//***************************************************************************************************
function sql_query($query,$conn,$trn='N',$errXML='S'){
	//$query 	= iconv("UTF-8", "ISO-8859-1",$query);
	
  $errmsg = '';
  //Sin Transaccion, abro una transaccion.
  if($trn=='N') $trans= sql_begin_trans($conn);
  else $trans= $trn;
  
  $Table = new SQLTable();    
  $result= ibase_query($trans, $query); //Ejecucion de Consulta 

  if (ibase_errmsg() !== false){ //Captura de Error       
       $errmsg=ibase_errmsg();
       sql_rollback_trans($trans);  

		if(strpos($errmsg,'E_CUSTOM_ERR') > 0){
			$posini = strpos($errmsg,'E_CUSTOM_ERR')+12;			
			$errmsg = substr($errmsg,$posini,strlen($errmsg));
			$posfin = strlen($errmsg);
			if(strpos($errmsg,'At trigger') > 0){
				$posfin = strpos($errmsg,'At trigger');
			}
			if(strpos($errmsg,'At procedure') > 0){
				$posfin = strpos($errmsg,'At procedure');
			}
			$errmsg = substr($errmsg,0,$posfin);
	   }else{
			//Genero la salida del Log
			$nomFil = date("Ymd"); 			//Nombre del Archivo
			$fchReg	= date("Y/m/d h:i:s"); 	//Fecha y Hora de Registro
			$qryReg	= str_replace(chr(13).chr(10)," ", $query);		//Query de Registro
			file_put_contents(GLBRutaFUNC."/errdb/sqlquery_$nomFil.err", "$fchReg -- $errmsg -- $qryReg \r\n", FILE_APPEND);		
	   }	 
		if($errXML == 'S'){
		   echo "<errdb>                 
					<errmsg style='visibility:hidden;'>$errmsg</errmsg>
					<sqlerr style='visibility:hidden;'>$query</sqlerr>				 
					<htmlcode> <script>  </script> </htmlcode>
				 </errdb>";
		}
       return "SQLERROR";            
  }else{//Genero la tabla en memoria (objeto)	
	while($row= sql_fetch_rows($result,$trans,$query,$errXML)){			
		if(!isset($row->ErrorMsg)){
			$Table->TakeFieldsNames($row); //Nombres de Campos	
			$Table->NewRow($row);
		}else{
			$Table->isError		= true;
			$Table->ErrorMsg 	= $row->ErrorMsg;
		}
	}
  	if($trn=='N') sql_commit_trans($trans);  //Sin transaccion, commit consulta
  }
  return $Table;
}
//***************************************************************************************************
function sql_fetch_rows($result,$trans,$query,$errXML='S'){ //Realiza la lectura de las filas  		
  	$row=ibase_fetch_object($result);
	
	if(ibase_errmsg() !== false){         
		   $errmsg= ibase_errmsg(); 
		   sql_rollback_trans($trans);    
	
		   if(strpos($errmsg,'E_CUSTOM_ERR') > 0){
				$posini = strpos($errmsg,'E_CUSTOM_ERR')+12;			
				$errmsg = substr($errmsg,$posini,strlen($errmsg));
				$posfin = strlen($errmsg);
				if(strpos($errmsg,'At trigger') > 0){
					$posfin = strpos($errmsg,'At trigger');					
				}
				$errmsg = substr($errmsg,0,$posfin);
				if(strpos($errmsg,'At procedure') > 0){
					$posfin = strpos($errmsg,'At procedure');
				}				
				$errmsg = substr($errmsg,0,$posfin);
		   }else{
				//Genero la salida del Log
				$nomFil = date("Ymd"); 			//Nombre del Archivo
				$fchReg	= date("Y/m/d h:i:s"); 	//Fecha y Hora de Registro
				$qryReg	= str_replace(chr(13).chr(10)," ", $query);		//Query de Registro
				file_put_contents(GLBRutaFUNC."/errdb/sqlfetch_$nomFil.err", "$fchReg -- $errmsg -- $qryReg \r\n", FILE_APPEND);		
		   }			
			 file_put_contents(GLBRutaFUNC."/errdb/tmp.err", $errmsg."\r\n", FILE_APPEND);
			if($errXML == 'S'){
			   echo "<errdb> 
						<errmsg style='visibility:hidden;'>$errmsg</errmsg>
						<sqlerr style='visibility:hidden;'></sqlerr> 
						<htmlcode> <script> </script> </htmlcode>
					 </errdb>";
			}
				 
		   $row = '{"ErrorMsg":"'.$errmsg.'"}';
		   $row = json_decode($row);
		  
		   return $row;
	  }else{
		 return $row;  
		}
	
}
//***************************************************************************************************
function clean_errmsg($errmsg){
	   $errmsg=str_replace('exception 1','',$errmsg);
       $errmsg=str_replace('E_CUSTOM_ERR','',$errmsg);	   
       $errmsg=str_replace('password required','',$errmsg);
       $errmsg=str_replace('At procedure','',$errmsg);                      
       $errmsg=substr($errmsg,0,strpos($errmsg,"'"));
       $errmsg=str_replace("'",'-',$errmsg);       
       
       return $errmsg;
}
//***************************************************************************************************
function BDConvFch($fch){
		//DATE 2012-08-01 
		//TIMESTAMP 2012-08-01 09:15:07
		$fchaux = '';
		if(trim($fch) != ''){
			$aux 	= strtotime($fch);
			$pos 	= strpos($fch,':');
			if($pos === false){ //Si solo es DATE
				$fchaux = date("d/m/Y",$aux);
			}else{ //Si es TIMESTAMP, contiene Hora:Min:Seg
				$fchaux = date("d/m/Y H:i:s",$aux);
			}
		}
		return $fchaux;
}
//***************************************************************************************************
?>