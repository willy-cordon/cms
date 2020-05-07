
<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$PERADMIN = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
//verificamos is es administrador
if ($PERADMIN != 1) {
    header('Location: ../index');
}


$errcod = 0;
$err = '';
$conn = sql_conectar();
$trans    = sql_begin_trans($conn);
$errmsg = '';

// $trans  = sql_begin_trans($conn);
//SECTION  VALORES DEL FORMULARIO 

$pdf = $_FILES["presentacion"]["tmp_name"];
$encreg     =   $_POST["encreg"];
$pdfname    =   $_FILES["presentacion"]["name"];
$encprenom  = $_POST['namepre'];
$encprereg  = $_POST['encprereg'];
if (empty($encprenom)) {
    $encprenom = $pdfname;
}
//END SECTION /////////////////////////////////


logerror(empty($encprenom));
//TODO GUARDAR PDF EN PREIMG


//NOTE CREACION DE LA CARPETA PREIMG
$pathimagenes = '../preimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	//

//NOTE EL ARCHIVO RECIBIDO POR POST SE GUARDA EN LA CARPETA PREIMG --------------------------


if(isset($_FILES['presentacion'])){
    $ext 	= pathinfo($_FILES['presentacion']['name'], PATHINFO_EXTENSION);
    $name 	= $pdfname;
    if (!file_exists($pathimagenes.$encreg)) {
        mkdir($pathimagenes.$encreg);	   				
    }
    if(file_exists($pathimagenes.$encreg.'/'.$name)){
        unlink($pathimagenes.$encreg.'/'.$name);
    }

    move_uploaded_file( $_FILES['presentacion']['tmp_name'], $pathimagenes.$encreg.'/'.$name);

}
//-------------------------------------------------------------------------------------------

//NOTE UNA VEZ GUARDADO LE PEGAMOS A LA BASE DE DATOS 


if($encprereg == 0){

//GENERO ID PARA PRESENTACIONES/////////////////////////////////////////
$query1     = 'SELECT GEN_ID(G_ENC_PRES,1) AS ID FROM RDB$DATABASE';
$TblId    = sql_query($query1, $conn, $trans);
$RowId    = $TblId->Rows[0];
$reg   = trim($RowId['ID']);
///////////////////////////////////////////////////////////////////////

$pdfname   =   VarNullBD($pdfname,  'S');

$encprenom   =   VarNullBD($encprenom,  'S');


$query = " 	INSERT INTO ENC_PRES(ENCPREREG,ENCPRENOM,ENCPREFIL,ENCCOD)
        
                        VALUES($reg,$encprenom,$pdfname,$encreg) ";


$err = sql_execute($query, $conn, $trans);


  

}else{

$pdfname   =   VarNullBD($pdfname,  'S');

$encprenom   =   VarNullBD($encprenom,  'S');

    $query = "UPDATE ENC_PRES SET ENCPRENOM =$encprenom , ENCPREFIL = $pdfname, ENCCOD = $encreg 
    WHERE ENCPREREG = $encprereg";
    $err = sql_execute($query,$conn,$trans);
}

if ($err == 'SQLACCEPT' && $errcod == 0) {
    sql_commit_trans($trans);
    $errcod = 0;
    $errmsg =  'Improtacion Exitosa'      /*TrMessage('Permiso actualizado!')*/;
} else {
    sql_rollback_trans($trans);
    $errcod = 2;
    $errmsg = ($errmsg == '') ? 'Error al importar' /*TrMessage('Error al actualizar el permiso!')*/ : $errmsg;
}
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
sql_close($conn);
