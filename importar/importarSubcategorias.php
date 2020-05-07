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
$trans    = sql_begin_trans($conn);
$errmsg = '';


$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();



$subCategoriaError = array(
    "tipo" => 'subcategoria',
    "errmsg" => "",
    "errcod" => 1,
    "subcategorias" => [],


);




for ($row = 2; $row <=  $lastRow; $row++) {



    $CATSUBDES   =   $worksheet->getCell('A' . $row)->getValue();
    $CATCODIGO   =   $worksheet->getCell('B' . $row)->getValue();
    $ESTCODIGO   =   $worksheet->getCell('C' . $row)->getValue();
    $CATSUBDESING =   $worksheet->getCell('D' . $row)->getValue();

    $CATSUBDES   =   VarNullBD($CATSUBDES,  'S');
    $ESTCODIGO   =   VarNullBD($ESTCODIGO,  'N');
    $CATCODIGO   =   VarNullBD($CATCODIGO,  'N');
    $CATSUBDESING  = VarNullBD($CATSUBDESING,  'S');



    $query2 = "SELECT CATSUBDES FROM CAT_SUB WHERE CATSUBDES = $CATSUBDES";
    $Table = sql_query($query2, $conn, $trans);
    $rows = $Table->Rows_Count;


    if ($rows == -1) {

        if ($CATSUBDES != 'NULL') {

            $query1     = 'SELECT GEN_ID(GEN_SUBCAT,1) AS ID FROM RDB$DATABASE';
            $TblId    = sql_query($query1, $conn,$trans);
            $RowId    = $TblId->Rows[0];
            $setCodigo   = trim($RowId['ID']);


            $query = " 	INSERT INTO CAT_SUB(CATSUBCOD,CATSUBDES,CATCODIGO,ESTCODIGO,CATSUBDESING)
  
                VALUES($setCodigo,$CATSUBDES,$CATCODIGO,$ESTCODIGO,$CATSUBDESING) ";

            $err = sql_execute($query, $conn,$trans);

            $subcategoria = array(

                'nombre' => $CATSUBDES,
                'check' => 1,
                'log' =>  'N/A',
                'fila' => $row
    
            );
    
            array_push($subCategoriaError['subcategorias'], $subcategoria);
            
        } else {

            $subcategoria = array(

                'nombre' => 'N/A',
                'check' => 0,
                'log' =>  'Revisar Vacios',
                'fila' => $row

            );

            array_push($subCategoriaError['subcategorias'], $subcategoria);
            $errcod = 1;
        }
    } else {
        //EXISTENTE
        $subcategoria = array(

            'nombre' => $CATSUBDES,
            'check' => 0,
            'log' =>  'Ya existe subcategoria',
            'fila' => $row

        );

        array_push($subCategoriaError['subcategorias'], $subcategoria);
        $errcod = 1;
        
    }
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

$subCategoriaError['errcod'] = $errcod;
$subCategoriaError['errmsg'] = $errmsg;

echo json_encode($subCategoriaError, JSON_PRETTY_PRINT);



sql_close($conn);
?>