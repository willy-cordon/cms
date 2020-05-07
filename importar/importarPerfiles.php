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
$errmsg = '';
$err    = 'SQLACCEPT';
$conn = sql_conectar();
$trans	= sql_begin_trans($conn);
// $trans  = sql_begin_trans($conn);


$userError = array(
  "tipo" => 'perfil',
  "errmsg" => "",
  "errcod" => 1,
  "users" => [],
  "check" => 0,
  "log" => ''

);




$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();


//  echo $worksheet->getCell('B'. 2)->getValue();


for ($row = 2; $row <= $lastRow; $row++) {


 
  $PERCODIGO      =   $worksheet->getCell('A' .  $row)->getValue();
  $PERNOMBRE      =   $worksheet->getCell('B' .  $row)->getValue();
  $PERAPELLI      =   $worksheet->getCell('C' .  $row)->getValue();
  $ESTCODIGO      =   $worksheet->getCell('D' .  $row)->getValue();
  $PERCOMPAN      =   $worksheet->getCell('E' .  $row)->getValue();
  $PERCORREO      =   $worksheet->getCell('F' .  $row)->getValue();
  $PERCIUDAD      =   $worksheet->getCell('G' .  $row)->getValue();
  $PERESTADO      =   $worksheet->getCell('H' .  $row)->getValue();
  $PAICODIGO      =   $worksheet->getCell('I' .  $row)->getValue();
  $PERCODPOS      =   $worksheet->getCell('J' .  $row)->getValue();
  $PERTELEFO      =   $worksheet->getCell('K' .  $row)->getValue();
  $PERURLWEB      =   $worksheet->getCell('L' .  $row)->getValue();
  $PERUSUACC      =   $worksheet->getCell('M' .  $row)->getValue();
  $PERPASACC      =   $worksheet->getCell('N' .  $row)->getValue();
  $PERDIRECC      =   $worksheet->getCell('O' .  $row)->getValue();
  $PERCARGO       =   $worksheet->getCell('P' .  $row)->getValue();
  $PERTIPO        =   $worksheet->getCell('Q' .  $row)->getValue();
  $PERCLASE       =   $worksheet->getCell('R' .  $row)->getValue();
  $PERADMIN       =   $worksheet->getCell('S' .  $row)->getValue();
  $PEREMPDES      =   $worksheet->getCell('T' .  $row)->getValue();
  $PERPARNOM1     =   $worksheet->getCell('U' .  $row)->getValue();
  $PERPARAPE1     =   $worksheet->getCell('V' .  $row)->getValue();
  $PERPARCARG1    =   $worksheet->getCell('W' .  $row)->getValue();
  $PERPARNOM2     =   $worksheet->getCell('X' .  $row)->getValue();
  $PERPARAPE2     =   $worksheet->getCell('Y' .  $row)->getValue();
  $PERPARCARG2    =   $worksheet->getCell('Z' .  $row)->getValue();
  $PERPARNOM3     =   $worksheet->getCell('AA' . $row)->getValue();
  $PERPARAPE3     =   $worksheet->getCell('AB' . $row)->getValue();
  $PERPARCARG3    =   $worksheet->getCell('AC' . $row)->getValue();
  $PERAVATAR      =   $worksheet->getCell('AD' . $row)->getValue();
  $ESTCODANT      =   $worksheet->getCell('AE' . $row)->getValue();
  $PERCOMENT      =   $worksheet->getCell('AI' . $row)->getValue();
  $MESCODIGO      =   $worksheet->getCell('AJ' . $row)->getValue();
  $PERIDIOMA      =   $worksheet->getCell('AK' . $row)->getValue();
  $PERRUBCOD      =   $worksheet->getCell('AL' . $row)->getValue();
  $TIMREG         =   $worksheet->getCell('AM' . $row)->getValue();
  $PERDESING      =   $worksheet->getCell('AN' . $row)->getValue();
  $PERREUURL      =   $worksheet->getCell('AO' . $row)->getValue();
  $PERINDCOD      =   $worksheet->getCell('AP' . $row)->getValue();
  $PERARECOD      =   $worksheet->getCell('AQ' . $row)->getValue();
  $ENCRES         =   $worksheet->getCell('AR' . $row)->getValue();


  $PERCODIGO      =   VarNullBD($PERCODIGO,  'N');
  $PERNOMBRE      =   VarNullBD($PERNOMBRE,  'S');
  $PERAPELLI      =   VarNullBD($PERAPELLI,  'S');
  $ESTCODIGO      =   VarNullBD($ESTCODIGO,  'N');
  $PERCOMPAN      =   VarNullBD($PERCOMPAN,  'S');
  $PERCORREO      =   VarNullBD($PERCORREO,  'S');
  $PERCIUDAD      =   VarNullBD($PERCIUDAD,  'S');
  $PERESTADO      =   VarNullBD($PERESTADO,  'S');
  $PAICODIGO      =   VarNullBD($PAICODIGO,  'N');
  $PERCODPOS      =   VarNullBD($PERCODPOS,  'S');
  $PERTELEFO      =   VarNullBD($PERTELEFO,  'S');
  $PERURLWEB      =   VarNullBD($PERURLWEB,  'S');
  $PERUSUACC      =   VarNullBD($PERUSUACC,  'S');
  $PERPASACC      =   VarNullBD($PERPASACC,  'S');
  $PERDIRECC      =   VarNullBD($PERDIRECC,  'S');
  $PERCARGO       =   VarNullBD($PERCARGO,   'S');
  $PERTIPO        =   VarNullBD($PERTIPO,    'N');
  $PERCLASE       =   VarNullBD($PERCLASE,   'N');
  $PERADMIN       =   VarNullBD($PERCLASE,   'N');
  $PEREMPDES      =   VarNullBD($PEREMPDES,  'S');
  $PERPARNOM1     =   VarNullBD($PERPARNOM1, 'S');
  $PERPARAPE1     =   VarNullBD($PERPARAPE1, 'S');
  $PERPARCARG1    =   VarNullBD($PERPARCARG1,'S');
  $PERPARNOM2     =   VarNullBD($PERPARNOM2, 'S');
  $PERPARAPE2     =   VarNullBD($PERPARAPE2, 'S');
  $PERPARCARG2    =   VarNullBD($PERPARCARG2,'S');
  $PERPARNOM3     =   VarNullBD($PERPARNOM3, 'S');
  $PERPARAPE3     =   VarNullBD($PERPARAPE3, 'S');
  $PERPARCARG3    =   VarNullBD($PERPARCARG3,'S');
  $PERAVATAR      =   VarNullBD($PERAVATAR,  'S');
  $ESTCODANT      =   VarNullBD($ESTCODANT,  'N');
  $PERCOMENT      =   VarNullBD($PERCOMENT,  'S');
  $MESCODIGO      =   VarNullBD($MESCODIGO,  'N');
  $PERIDIOMA      =   VarNullBD($PERIDIOMA,  'S');
  $PERRUBCOD      =   VarNullBD($PERRUBCOD,  'N');
  $TIMREG         =   VarNullBD($TIMREG,     'S');
  $PERDESING      =   VarNullBD($PERDESING,  'S');
  $PERREUURL      =   VarNullBD($PERREUURL,  'S');
  $PERINDCOD      =   VarNullBD($PERREUURL,  'N');
  $PERARECOD      =   VarNullBD($PERARECOD,  'N');
  $ENCRES         =   VarNullBD($ENCRES,     'N');

	if($ESTCODIGO==0) $ESTCODIGO=1;

  $query2 = "SELECT PERCODIGO FROM PER_MAEST WHERE PERCODIGO = $PERCODIGO";
  $Table = sql_query($query2, $conn,$trans);
  $rows = $Table->Rows_Count;


  if ($rows == -1 && $PERCODIGO != 0 && $PERCORREO != 'NULL') {

    $query = " 	INSERT INTO PER_MAEST(PERCODIGO,	PERNOMBRE,	PERAPELLI,	ESTCODIGO,	PERCOMPAN,	PERCORREO,	PERCIUDAD,	PERESTADO,	PAICODIGO,	PERCODPOS,	  PERTELEFO,	PERURLWEB,	PERUSUACC,	PERPASACC,	PERDIRECC,	PERCARGO,	PERTIPO,	PERCLASE,	PERADMIN,	PEREMPDES,	PERPARNOM1,	PERPARAPE1,	PERPARCARG1,	  PERPARNOM2,	PERPARAPE2,	PERPARCARG2,	PERPARNOM3,	PERPARAPE3,	PERPARCARG3,	PERAVATAR,	ESTCODANT,	PERUSADIS,	PERUSAREU,	PERUSAMSG,	PERCOMENT,	  MESCODIGO,	PERIDIOMA,	PERRUBCOD,	TIMREG,	PERDESING,	PERREUURL,	PERINDCOD,	PERARECOD,	ENCRES)

    VALUES( $PERCODIGO,	$PERNOMBRE,	$PERAPELLI,	$ESTCODIGO,	$PERCOMPAN,	$PERCORREO,	$PERCIUDAD,	$PERESTADO,	$PAICODIGO,	$PERCODPOS,	$PERTELEFO,	$PERURLWEB,	$PERUSUACC,	$PERPASACC,	$PERDIRECC,	$PERCARGO,	$PERTIPO,	$PERCLASE,	$PERADMIN,	$PEREMPDES,	$PERPARNOM1,	$PERPARAPE1,	$PERPARCARG1,	$PERPARNOM2,	$PERPARAPE2,	$PERPARCARG2,	$PERPARNOM3,	$PERPARAPE3,	$PERPARCARG3,	$PERAVATAR,	$ESTCODANT,	0,	0,	0,	$PERCOMENT,	$MESCODIGO,	$PERIDIOMA,	$PERRUBCOD,	$TIMREG,	$PERDESING,	$PERREUURL,	$PERINDCOD,	$PERARECOD,	$ENCRES) ";

    $err = sql_execute($query, $conn,$trans);

    $usuario = array(
        
      'id' => $PERCODIGO,
      'pernombre' => $PERNOMBRE,
      'perapelli' => $PERAPELLI,
      'percorreo' => $PERCORREO,
      'check' => 1,
      'log' => 'N/A'
      
    );

    array_push($userError['users'], $usuario);
    
  } else {


    if ($PERCORREO == 'NULL') {


      logerror($PERCORREO);
      $err = '';
      $errcod = 2;

      //En caso de que falte el campo email se devuelve error
      $usuario = array(
        
        'id' => $PERCODIGO,
        'pernombre' => $PERNOMBRE,
        'perapelli' => $PERAPELLI,
        'percorreo' => 'N/A',
        'check' => 0,
        'log' => 'Email'
      );

      array_push($userError['users'], $usuario);
    } else {
      
      $err = '';
      $errcod = 2;

      //En caso de que exista el usuario en la base de datos se inserta en el array para mostrar en el log
      $usuario = array(
        
        'id' => $PERCODIGO,
        'pernombre' => $PERNOMBRE,
        'perapelli' => $PERAPELLI,
        'percorreo' => $PERCORREO,
        'check' => 0,
        'log' => 'Ya existe usuario'
      );

      array_push($userError['users'], $usuario);
    }


    //---------------------------------------------------------------------------------------------------
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


//echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';



$userError['errcod'] = $errcod;
$userError['errmsg'] = $errmsg;


echo json_encode($userError, JSON_PRETTY_PRINT);
sql_close($conn);
?>