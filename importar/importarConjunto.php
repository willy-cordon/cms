
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
$err = '';
$conn = sql_conectar();
$trans  = sql_begin_trans($conn);
$errmsg = '';
$trans  = sql_begin_trans($conn);




$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();

$sectorError = array(
    "tipo" => 'sector',
    "errmsg" => "",
    "errcod" => 1,
    "sectores" => [],


);


//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <=  $lastRow; $row++) {



    //SECTION SECTOR----------------------------------------------------------------------------------------------------
    $SECDESCRI   =   $worksheet->getCell('A' . $row)->getValue();
    $SECDESING   =   $worksheet->getCell('D' . $row)->getValue();
    $ESTCODIGO   =   1;

    $SECDESCRI   =   VarNullBD($SECDESCRI,  'S');
    $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
    $SECDESING   =   VarNullBD($SECDESING,  'S');

    //ANCHOR COMPROBAMOS SI EXISTE O NO EL SECTOR
    $query2 = "SELECT SECDESCRI, SECCODIGO FROM SEC_MAEST WHERE SECDESCRI = $SECDESCRI";
    $Table = sql_query($query2, $conn, $trans);
    $rows = $Table->Rows_Count;

    $setCodigo = (isset($Table->Rows[0]['SECCODIGO'])) ?  trim($Table->Rows[0]['SECCODIGO']) : 0;
    logerror($setCodigo);

    if ($rows == -1) {


        if ($SECDESCRI != 'NULL') {

            $query1     = 'SELECT GEN_ID(G_SECTOR,1) AS ID FROM RDB$DATABASE';
            $TblId    = sql_query($query1, $conn, $trans);
            $RowId    = $TblId->Rows[0];
            $setCodigo   = trim($RowId['ID']);


            $query = " 	INSERT INTO SEC_MAEST(SECCODIGO,SECDESCRI,ESTCODIGO,SECDESING)
  
                VALUES($setCodigo,$SECDESCRI,$ESTCODIGO,$SECDESING) ";

            $err = sql_execute($query, $conn, $trans);
            
        } else {
            //ANCHOR  NULO
            $sector = array(

                'nombre' => 'N/A',
                'check' => 0,
                'log' =>  'Revisar vacios',
                'fila' => $row

            );

            array_push($sectorError['sectores'], $sector);
            $errcod = 1;
        }
    }else{
         //ANCHOR  NULO
         $sector = array(

            'nombre' => $SECDESCRI,
            'check' => 0,
            'log' =>  'Ya existe sector',
            'fila' => $row

        );

        array_push($sectorError['sectores'], $sector);
       
    }

    //SECTION SUBSECTOR---------------------------------------------------------------------------------------------------- 
    $SECSUBDES   =   $worksheet->getCell('B' . $row)->getValue();
    $ESTCODIGO   =   1;
    $SECCODIGO  =   $setCodigo;
    $SECSUBDESING = $worksheet->getCell('E' . $row)->getValue();


    $SECSUBDES   =   VarNullBD($SECSUBDES,  'S');
    $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
    $SECCODIGO   =   VarNullBD($SECCODIGO,  'N');
    $SECSUBDESING   =   VarNullBD($SECSUBDESING,  'S');



    $query2 = "SELECT SECSUBDES,SECSUBCOD FROM SEC_SUB WHERE SECSUBDES = $SECSUBDES";
    $Table = sql_query($query2, $conn, $trans);


    $setCodigoSub = (isset($Table->Rows[0]['SECSUBCOD'])) ?  trim($Table->Rows[0]['SECSUBCOD']) : 0;
    $rows = $Table->Rows_Count;

    if ($rows == -1) {


        if ($SECSUBDES != 'NULL') {

            $query1     = 'SELECT GEN_ID(G_SUBSECTOR,1) AS ID FROM RDB$DATABASE';
            $TblId    = sql_query($query1, $conn, $trans);
            $RowId    = $TblId->Rows[0];
            $setCodigoSub   = trim($RowId['ID']);


            $query = " 	INSERT INTO SEC_SUB(SECSUBCOD,SECSUBDES,ESTCODIGO,SECCODIGO,SECSUBDESING)
    
                  VALUES($setCodigoSub,$SECSUBDES,$ESTCODIGO,$SECCODIGO,$SECSUBDESING) ";

            $err = sql_execute($query, $conn, $trans);
           
        }
    }


    //SECTION CATEGORIA---------------------------------------------------------------------------------------------------- 

    $CATDESCRI   =   $worksheet->getCell('C' . $row)->getValue();
    $ESTCODIGO   =   1;
    $SECSUBCOD   =   $setCodigoSub;
    $CATDESING   =   $worksheet->getCell('F' . $row)->getValue();

    $CATDESCRI   =   VarNullBD($CATDESCRI,  'S');
    $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
    $SECSUBCOD   =   VarNullBD($SECSUBCOD,  'N');
    $CATDESING   =   VarNullBD($CATDESING,  'S');

    $query2 = "SELECT CATDESCRI FROM CAT_MAEST WHERE CATDESCRI = $CATDESCRI";
    $Table = sql_query($query2, $conn, $trans);

    $rows = $Table->Rows_Count;
    if ($rows == -1) {

        if ($CATDESCRI != 'NULL') {

            $query1     = 'SELECT GEN_ID(GEN_CAT,1) AS ID FROM RDB$DATABASE';
            $TblId    = sql_query($query1, $conn, $trans);
            $RowId    = $TblId->Rows[0];
            $setCodigoSub   = trim($RowId['ID']);


            $query = " 	INSERT INTO CAT_MAEST(CATCODIGO,CATDESCRI,ESTCODIGO,SECSUBCOD,CATDESING)
  
                VALUES($setCodigoSub,$CATDESCRI,$ESTCODIGO,$SECSUBCOD,$CATDESING) ";




            $err = sql_execute($query, $conn, $trans);
           
        }


        //END  SECTION  RECORRIDA ECXEL
    }
}

logerror($errcod . ' ' . $err);
if ($err == 'SQLACCEPT' && $errcod == 0) {
    sql_commit_trans($trans);
    $errcod = 0;
    $errmsg =  'Improtacion Exitosa'      /*TrMessage('Permiso actualizado!')*/;
} else {
    sql_rollback_trans($trans);
    $errcod = 2;
    $errmsg = ($errmsg == '') ? 'Error al importar' /*TrMessage('Error al actualizar el permiso!')*/ : $errmsg;
}

$sectorError['errcod'] = $errcod;
$sectorError['errmsg'] = $errmsg;

echo json_encode($sectorError, JSON_PRETTY_PRINT);



sql_close($conn);
?>