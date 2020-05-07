<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once "Classes/PHPExcel.php";
$PERADMIN = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
//verificamos is es administrador
if ($PERADMIN != 1) {
    header('Location: ../index');
}


$errcod = 0;
$conn = sql_conectar();
// $trans  = sql_begin_trans($conn);



$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();


//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <=  $lastRow; $row++) {



    $SECSUBDES   =   $worksheet->getCell('A' . $row)->getValue();
    $ESTCODIGO   =   $worksheet->getCell('B' . $row)->getValue();
    $SECCODIGO  =   $worksheet->getCell('C' . $row)->getValue();
    $SECSUBDESING = $worksheet->getCell('D'.$row)->getValue();

    $SECSUBDES   =   VarNullBD($SECSUBDES,  'S');
    $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
    $SECCODIGO   =   VarNullBD($SECCODIGO,  'N');
    $SECSUBDESING   =   VarNullBD($SECSUBDESING,  'S');

    logerror($SECSUBDES);
    if ($SECSUBDES != 'NULL') {

        $query1     = 'SELECT GEN_ID(G_SUBSECTOR,1) AS ID FROM RDB$DATABASE';
        $TblId    = sql_query($query1, $conn);
        $RowId    = $TblId->Rows[0];
        $setCodigo   = trim($RowId['ID']);


        $query = " 	INSERT INTO SEC_SUB(SECSUBCOD,SECSUBDES,ESTCODIGO,SECCODIGO,SECSUBDESING)
  
                VALUES($setCodigo,$SECSUBDES,$ESTCODIGO,$SECCODIGO,$SECSUBDESING) ";
    }


    $err = sql_execute($query, $conn);
    // sql_commit_trans($trans);

    //

}


if ($err == 'SQLACCEPT' && $errcod == 0) {
    $errcod = 0;
    $errmsg =  'Improtacion Exitosa'      /*TrMessage('Permiso actualizado!')*/;
} else {
    $errcod = 2;
    $errmsg = ($errmsg == '') ? 'Error al importar' /*TrMessage('Error al actualizar el permiso!')*/ : $errmsg;
}

echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';



sql_close($conn);
?>