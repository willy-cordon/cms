
<?
//--------------------------------------------------------------------------------------------------------------
define('APPID', '510ea7f5667974d55f99c4de6a8271f4');
define('PathPlano', 'http://businessrevolution.onlife.com.ar/app/');
//define('UrlWebMails', 'http://cmseventos.com/2019/argentina/17-congreso-credito/');


define('SendMailNombre', 'Argentina Fintech Forum');
define('SendMailUsuario', 'argentina@cmspeople.com');
define('SendMailPass', 'argentina*cms');

define('LinkPlayStore', 'https://play.google.com/store/apps/details?id=com.nextar.cms&hl=es_419');
define('LinkAppStore', 'https://apps.apple.com/us/app/argentina-fintech-forum/id1465806972');


//define('SendMailUsuario', 'no_reply@benvidosistemas.com');
//define('SendMailPass', 'elefanterino');

require_once '../func/zglobals.php';
require_once '../func/zdatabase.php';
require_once '../func/zfvarias.php';
require 	 '../func/Slim/Slim/Slim.php';
require_once '../func/sendfcmmessage.php';
require_once '../func/sendiosmessage.php';
require_once '../func/class.phpmailer.php';
require_once '../func/class.smtp.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();


//-----------------------------------------------------------------------------------------------
// PARAMETROS DE CONFIGURACION
$app->post('/config', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 				= false;
	$response["MESSAGE"] 			= '';
	$response["EXCEPTION"] 			= '';
	$response["Configuracion"]		= array();

	$fltappid 	= trim($data->APPID);

	try {
		$reg = array();
		$conn = sql_conectar(); //Apertura de Conexion		

		$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF ";
		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$reg[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
		}
		array_push($response["Configuracion"], $reg);

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// LOGIN
$app->post('/login', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["User"] 		= array();

	$fltnombre 	= trim($data->PERUSUACC);
	$fltcorreo 	= trim($data->PERUSUCOR);
	$fltcpf 	= trim($data->PERCPF);
	$tipologin 	= (isset($data->TIPOLOGIN)) ? $data->TIPOLOGIN : 0;
	$provider 	= (isset($data->provider)) ? $data->provider : '';
	$id 		= (isset($data->id)) ? $data->id : '';
	$uid 		= (isset($data->uid)) ? $data->uid : '';

	try {
		$reg = array();
		$conn = sql_conectar(); //Apertura de Conexion		
		$regExists = false;

		//$perpasacc = md5('BenVido'.$perpasacc.'PassAcceso'.$perusuacc);
		//$perpasacc = md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8));

		if ($tipologin == 1 || $tipologin == 0) {  //Login Registrados
			if ($fltcorreo != '') {
				$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERAVATAR,
									P.PERTIPO,T.PERTIPDESESP,
									P.PERCLASE,C.PERCLADES,
									A.PERARECOD,A.PERAREDESESP,
									I.PERINDCOD,I.PERINDDESESP,
									P.PERCARGO,P.PERCORREO,P.PEREMPDES,
									P.PERTELEFO,P.PERCPF,P.PERINGLOG,P.PERULTLOG
							FROM PER_MAEST P
							LEFT OUTER JOIN PER_TIPO T ON T.PERTIPO=P.PERTIPO
							LEFT OUTER JOIN PER_CLASE C ON C.PERCLASE=P.PERCLASE
							LEFT OUTER JOIN PER_AREA A ON P.PERARECOD=A.PERARECOD
							LEFT OUTER JOIN PER_IND I ON P.PERINDCOD=I.PERINDCOD
							WHERE P.ESTCODIGO=1 AND UPPER(P.PERCORREO)=UPPER('$fltcorreo') ";

				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$row = $Table->Rows[0];
					$regExists = true;

					$percodigo = trim($row['PERCODIGO']);
					$perarecod = (trim($row['PERARECOD']) != '') ? trim($row['PERARECOD']) : 0;
					$perindcod = (trim($row['PERINDCOD']) != '') ? trim($row['PERINDCOD']) : 0;
					$chatsinleer = 0;
					$secsubcod	= '';
					$peringlog = trim($row['PERINGLOG']);

					//Busco si hay chats sin leer
					//$query = "	SELECT COUNT(*) AS CHATSINLEER
					//			FROM TBL_CHAT
					//			WHERE ESTCODIGO=1 AND PERCODDST=$percodigo AND CHALEIDO=0 ";
					//$TableChats = sql_query($query,$conn);
					//if($TableChats->Rows_Count>0){
					//	$rowChats = $TableChats->Rows[0];
					//	$chatsinleer = trim($rowChats['CHATSINLEER']);
					//}

					$querySec = "	SELECT CAST(LIST(PS.SECSUBCOD) AS VARCHAR(10000)) AS SECSUBCOD
									FROM PER_SSEC PS
									WHERE PS.PERCODIGO=$percodigo ";
					$TableSec = sql_query($querySec, $conn);
					for ($i = 0; $i < $TableSec->Rows_Count; $i++) {
						$rowSec = $TableSec->Rows[$i];
						$secsubcod = trim($rowSec['SECSUBCOD']);
					}
					if ($secsubcod != '') {
						$secsubcod .= ',0';
					}

					$reg['PerCodigo'] 				= $percodigo;
					$reg['Nombre'] 					= trim($row['PERNOMBRE']);
					$reg['Apellido'] 				= trim($row['PERAPELLI']);
					$reg['Compania'] 				= trim($row['PERCOMPAN']);
					$reg['Cargo'] 					= trim($row['PERCARGO']);
					$reg['Tipo'] 					= trim($row['PERTIPO']);
					$reg['Clase'] 					= trim($row['PERCLASE']);
					$reg['TipoDescripcion'] 		= trim($row['PERTIPDESESP']);
					$reg['ClaseDescripcion'] 		= trim($row['PERCLADES']);
					$reg['ImgAvatar'] 				= trim($row['PERAVATAR']);
					$reg['Correo'] 					= trim($row['PERCORREO']);
					$reg['Telefono'] 				= trim($row['PERTELEFO']);
					$reg['CPF'] 					= trim($row['PERCPF']);
					$reg['DescripcionCompania'] 	= trim($row['PEREMPDES']);
					$reg['Area'] 					= $perarecod;
					$reg['AreaDescripcion'] 		= trim($row['PERAREDESESP']);
					$reg['Industria'] 				= $perindcod;
					$reg['IndustriaDescripcion'] 	= trim($row['PERINDDESESP']);
					$reg['ChatsSinLeer'] 			= $chatsinleer;
					$reg['SubSectores'] 			= $secsubcod;
					$reg['Sectores']				= array();

					$querySec = "	SELECT PS.SECCODIGO,S.SECDESCRI 
									FROM PER_SECT PS
									LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
									WHERE PS.PERCODIGO=$percodigo ";
					$TableSec = sql_query($querySec, $conn);
					for ($i = 0; $i < $TableSec->Rows_Count; $i++) {
						$rowSec = $TableSec->Rows[$i];
						$seccodigo = trim($rowSec['SECCODIGO']);
						$secdescri = trim($rowSec['SECDESCRI']);

						$regSec = array();
						$regSec['SecCodigo'] 		= $seccodigo;
						$regSec['SecDescripcion'] 	= $secdescri;

						array_push($reg["Sectores"], $regSec);
					}

					$response["User"] = $reg;

					//Actualizo el PerCodigo del ID notifiaciones
					if ($id != '' && $provider != '') {
						$query = "	UPDATE NOT_REGI SET PERCODIGO=$percodigo 
									WHERE PROVIDER='$provider' AND ID='$id' ";
						$err	= sql_execute($query, $conn);
					}

					//Si es la primera vez que ingreso, inserto la estampa
					if ($peringlog == '') {
						$query = "	UPDATE PER_MAEST SET PERINGLOG=CURRENT_TIMESTAMP 
									WHERE PERCODIGO=$percodigo ";
						$err	= sql_execute($query, $conn);
					}

					//Marco la fecha de ingreso como estampa de ultimo login
					$query = "	UPDATE PER_MAEST SET PERULTLOG=CURRENT_TIMESTAMP 
								WHERE PERCODIGO=$percodigo ";
					$err	= sql_execute($query, $conn);
				}
			}
		} elseif ($tipologin == 2) { //Login NO Registrados
			//El perfil no existe
			$query = " INSERT INTO ACC_PERF (ACCREG,ACCNOMBRE,ACCCORREO,ACCFCHREG) VALUES (GEN_ID(G_ACCESOS,1),'$fltnombre','$fltcorreo',CURRENT_TIMESTAMP) ";
			$err	= sql_execute($query, $conn);
			$reg['PerCodigo'] 				= 9999999;
			$reg['Nombre'] 					= $fltnombre;
			$reg['Apellido'] 				= '';
			$reg['Compania'] 				= '';
			$reg['Cargo'] 					= '';
			$reg['Tipo'] 					= 3;
			$reg['Clase'] 					= '';
			$reg['TipoDescripcion'] 		= '';
			$reg['ClaseDescripcion'] 		= '';
			$reg['ImgAvatar'] 				= '';
			$reg['Correo'] 					= $fltcorreo;
			$reg['Area'] 					= '';
			$reg['AreaDescripcion'] 		= '';
			$reg['Industria'] 				= '';
			$reg['IndustriaDescripcion'] 	= '';
			$reg['ChatsSinLeer'] 			= 0;
			$reg['Sectores']				= array();

			$response["User"] = $reg;
		}


		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// DIRECTORIO
$app->post('/directorio', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Directorio"]	= array();

	$cantPagina = 20;
	$percodigo 	= $data->PERCODIGO;
	$tipobsq 	= $data->TIPOBSQ; //1:Directorio, 2:Recomendados
	$fltbuscar 	= (isset($data->FLTBUSCAR)) ? $data->FLTBUSCAR : '';
	$esfavorito	= (isset($data->ESFAVORITO)) ? $data->ESFAVORITO : 0;
	$area		= (isset($data->AREA)) ? $data->AREA : 0;
	$industria	= (isset($data->INDUSTRIA)) ? $data->INDUSTRIA : 0;
	$fltactivos	= (isset($data->FLTACTIVOS)) ? $data->FLTACTIVOS : 0;
	$fltclase 	= (isset($data->FLTCLASE)) ? trim($data->FLTCLASE) : 0;


	if (isset($data->PAGINA)) {
		$pagina 	= $data->PAGINA * $cantPagina;
	} else {
		$pagina		= 0;
		$cantPagina = 9000;
	}

	if ($esfavorito == 1) {
		$pagina		= 0;
		$cantPagina = 9000;
	}

	if ($tipobsq == 2) { //Si es recomendados, busco todos para poder aplicar bien los filtros de muestra
		$pagina		= 0;
		$cantPagina = 9000;
	}

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		//Guardo la busqueda realizada
		$query = "INSERT INTO DIR_BUSQ(DIRBSQREG,DIRBSQDES,PERCODIGO,DIRBSQFCH,DIRBSQHRA)
				VALUES(GEN_ID(G_DIRBUSQUEDA,1),'$fltbuscar',$percodigo,CURRENT_DATE,CURRENT_TIME) ";
		$err = sql_execute($query, $conn);

		//Filtro de Recomendados aplicados
		$wherelista = '';
		if ($tipobsq == 2) {
			//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
			$perfilLog = array();

			$percodlist = '';

			//Cargo la Clasificacion de productos del Perfil logueado
			$query = "	SELECT S.SECCODIGO,S.SECDESCRI
						FROM PER_SECT PS
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
						WHERE PS.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 ";
			$TableSect = sql_query($query, $conn);
			for ($i = 0; $i < $TableSect->Rows_Count; $i++) {
				$rowSect = $TableSect->Rows[$i];
				$seccodigo = trim($rowSect['SECCODIGO']);
				$secdescri = trim($rowSect['SECDESCRI']);

				//Hago una lista con todos los perfiles que correspondan a estas 3 de interes seleccionadas
				$query = "	SELECT CAST(LIST(PERCODIGO) AS VARCHAR(5000)) AS PERCODLIST
							FROM PER_MAEST
							WHERE ESTCODIGO=1 AND PERINDCOD=$seccodigo ";
				$TableList = sql_query($query, $conn);
				for ($j = 0; $j < $TableList->Rows_Count; $j++) {
					$rowList = $TableList->Rows[$j];
					if (trim($rowList['PERCODLIST']) != '') {
						$percodlist .= trim($rowList['PERCODLIST']) . ',';
					}
				}

				$perfilLog[$seccodigo]['EXISTS'] = 1;

				//Busco si tiene un siguiente nivel / SubSector
				$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES
									FROM PER_SSEC PSB
									LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
									WHERE PSB.PERCODIGO=$percodigo AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 ";
				$TableSSect = sql_query($querySectSub, $conn);
				for ($j = 0; $j < $TableSSect->Rows_Count; $j++) {
					$rowSSect = $TableSSect->Rows[$j];
					$secsubcod = trim($rowSSect['SECSUBCOD']);
					$secsubdes = trim($rowSSect['SECSUBDES']);

					$perfilLog[$seccodigo][$secsubcod]['EXISTS'] = 1;

					//Busco si tiene un siguiente nivel / Categorias
					$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI
									FROM PER_CATE PC
									LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
									WHERE PC.PERCODIGO=$percodigo AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 ";

					$TableCat = sql_query($queryCat, $conn);
					for ($k = 0; $k < $TableCat->Rows_Count; $k++) {
						$rowCat = $TableCat->Rows[$k];
						$catcodigo = trim($rowCat['CATCODIGO']);
						$catdescri = trim($rowCat['CATDESCRI']);

						$perfilLog[$seccodigo][$secsubcod][$catcodigo]['EXISTS'] = 1;

						//Busco si tiene un siguiente nivel / SubCategorias
						$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
											FROM PER_SCAT PSC
											LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
											WHERE PSC.PERCODIGO=$percodigo AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 ";

						$TableCatSub = sql_query($queryCatSub, $conn);
						for ($m = 0; $m < $TableCatSub->Rows_Count; $m++) {
							$rowCatSub = $TableCatSub->Rows[$m];
							$catsubcod = trim($rowCatSub['CATSUBCOD']);
							$catsubdes = trim($rowCatSub['CATSUBDES']);

							$perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod]['EXISTS'] = 1;
						}
					}
				}
			}

			$percodlist .= '0';
			$wherelista = " AND P.PERCODIGO IN ($percodlist) ";
			//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
		}

		//Quito acentos
		$fltbuscar2 = $fltbuscar;
		$fltbuscar2 = str_replace('á', 'a', $fltbuscar2);
		$fltbuscar2 = str_replace('é', 'e', $fltbuscar2);
		$fltbuscar2 = str_replace('í', 'i', $fltbuscar2);
		$fltbuscar2 = str_replace('ó', 'o', $fltbuscar2);
		$fltbuscar2 = str_replace('ú', 'u', $fltbuscar2);

		$where = '';
		if ($fltbuscar != '' && $tipobsq == 1) {
			$where .= " AND (P.PERNOMBRE CONTAINING '$fltbuscar' OR P.PERAPELLI CONTAINING '$fltbuscar' OR P.PERCOMPAN CONTAINING '$fltbuscar'
							OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(P.PERNOMBRE,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') CONTAINING '$fltbuscar2'
							OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(P.PERAPELLI,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') CONTAINING '$fltbuscar2'
							OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(P.PERCOMPAN,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') CONTAINING '$fltbuscar2'
							OR EXISTS(SELECT 1
										FROM PER_SECT PS1
										LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS1.SECCODIGO
										WHERE PS1.PERCODIGO=P.PERCODIGO AND S.SECDESCRI CONTAINING '$fltbuscar')
							OR EXISTS(SELECT 1
										FROM PER_SSEC PS2
										LEFT OUTER JOIN SEC_SUB SS ON SS.SECSUBCOD=PS2.SECSUBCOD
										WHERE PS2.PERCODIGO=P.PERCODIGO AND SS.SECSUBDES CONTAINING '$fltbuscar')
							OR EXISTS(SELECT 1
										FROM PER_CATE PS3
										LEFT OUTER JOIN CAT_MAEST CC ON CC.CATCODIGO=PS3.CATCODIGO
										WHERE PS3.PERCODIGO=P.PERCODIGO AND CC.CATDESCRI CONTAINING '$fltbuscar')
							OR EXISTS(SELECT 1
										FROM PER_SCAT PS4
										LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PS4.CATSUBCOD
										WHERE PS4.PERCODIGO=P.PERCODIGO AND CS.CATSUBDES CONTAINING '$fltbuscar'))  ";
		} elseif ($fltbuscar != '' && $tipobsq == 2) {
			$where .= " AND (P.PERNOMBRE CONTAINING '$fltbuscar' OR P.PERAPELLI CONTAINING '$fltbuscar' OR P.PERCOMPAN CONTAINING '$fltbuscar' OR P.PEREMPDES CONTAINING '$fltbuscar'
							OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(P.PERNOMBRE,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') CONTAINING '$fltbuscar2'
							OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(P.PERAPELLI,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') CONTAINING '$fltbuscar2'
							OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(P.PERCOMPAN,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') CONTAINING '$fltbuscar2' ) ";
		}

		//Si requieren ver los activos
		if ($fltactivos == 1) {
			$where .= " AND P.PERINGLOG IS NOT NULL ";
		}

		if ($area != '' && $area != 0) {
			$where .= " AND P.PERARECOD=$area ";
		}
		if ($industria != '' && $industria != 0) {
			$where .= " AND P.PERINDCOD=$industria ";
			//$where .= " AND (EXISTS(SELECT 1
			//							FROM PER_SSEC PS2
			//							WHERE PS2.PERCODIGO=P.PERCODIGO AND PS2.SECSUBCOD=$industria )) ";
		}

		//Clase de perfil
		if ($fltclase != 0) {
			$where .= " AND P.PERCLASE=5 ";
		}

		//Vereifico si el perfil logueado es un Open Innovation Arena
		$queryPer = "	SELECT PERTIPO 
						FROM PER_MAEST
						WHERE PERCODIGO=$percodigo ";
		$TablePer = sql_query($queryPer, $conn);
		if ($TablePer->Rows_Count > 0) {
			$rowPer = $TablePer->Rows[0];
			if (trim($rowPer['PERTIPO']) == 2) {
				$where .= " AND P.PERCLASE=5 "; //Para los perfiles OIA solo se muestran los Desafiantes (clase 5)
			} else {
				$where .= " AND P.PERTIPO<>2 "; //Para los Perfiles Individuales, no se muestran los OIA
			}
		}


		//Codigo interno superior a 100000 son registrados invitados visitantes

		$query = "	SELECT FIRST $cantPagina SKIP $pagina P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERURLWEB,P.PEREMPDES,P.PERCARGO,
							P.PERAVATAR,P.PERINS,P.PERFAC,P.PERTWI,P.PERCIUDAD,P.PERESTADO,E.EXPSTAND AS MESNUMERO,
							P.PERTIPO,T.PERTIPDESESP,
							P.PERCLASE,C.PERCLADES,
							P.PERARECOD,A.PERAREDESESP,
							P.PERINDCOD,I.PERINDDESESP,
							COALESCE((	SELECT 1
										FROM PER_FAVO F
										WHERE F.PERCODIGO=$percodigo AND F.PERCODFAV=P.PERCODIGO),0) AS ESFAVO,
							CASE WHEN P.PERULTLOG IS NOT NULL THEN 1 ELSE 0 END AS PERACTIVO
					FROM PER_MAEST P
					LEFT OUTER JOIN PER_TIPO T ON T.PERTIPO=P.PERTIPO
					LEFT OUTER JOIN PER_CLASE C ON C.PERCLASE=P.PERCLASE
					LEFT OUTER JOIN PER_AREA A ON A.PERARECOD=P.PERARECOD
					LEFT OUTER JOIN PER_IND I ON I.PERINDCOD=P.PERINDCOD
					LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=P.MESCODIGO
					LEFT OUTER JOIN EXP_MAEST E ON E.PERCODIGO=P.PERCODIGO
					WHERE P.ESTCODIGO=1 AND P.PERCODIGO<>$percodigo 
						$where $wherelista 
					ORDER BY P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN ";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];

			$percod = trim($row['PERCODIGO']);
			$match	= true;


			if ($tipobsq == 2) {
				$match = false;

				//Busco todos los sectores que tiene el perfil
				$query = "	SELECT S.SECCODIGO,S.SECDESCRI
							FROM PER_SECT PS
							LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
							WHERE PS.PERCODIGO=$percod AND S.ESTCODIGO<>3 ";
				$TableSect = sql_query($query, $conn);
				for ($n = 0; $n < $TableSect->Rows_Count; $n++) {
					$rowSect = $TableSect->Rows[$n];
					$seccodigo = trim($rowSect['SECCODIGO']);
					$secdescri = trim($rowSect['SECDESCRI']);

					//Busco si tiene un siguiente nivel / SubSector
					$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES
										FROM PER_SSEC PSB
										LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
										WHERE PSB.PERCODIGO=$percod AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 ";
					$TableSSect = sql_query($querySectSub, $conn);
					for ($j = 0; $j < $TableSSect->Rows_Count; $j++) {
						$rowSSect = $TableSSect->Rows[$j];
						$secsubcod = trim($rowSSect['SECSUBCOD']);
						$secsubdes = trim($rowSSect['SECSUBDES']);

						//Busco si tiene un siguiente nivel / Categorias
						$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI
										FROM PER_CATE PC
										LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
										WHERE PC.PERCODIGO=$percod AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 ";

						$TableCat = sql_query($queryCat, $conn);
						for ($k = 0; $k < $TableCat->Rows_Count; $k++) {
							$rowCat = $TableCat->Rows[$k];
							$catcodigo = trim($rowCat['CATCODIGO']);
							$catdescri = trim($rowCat['CATDESCRI']);

							//Busco si tiene un siguiente nivel / SubCategorias
							$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
												FROM PER_SCAT PSC
												LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
												WHERE PSC.PERCODIGO=$percod AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 ";

							$TableCatSub = sql_query($queryCatSub, $conn);
							for ($m = 0; $m < $TableCatSub->Rows_Count; $m++) {
								$rowCatSub = $TableCatSub->Rows[$m];
								$catsubcod = trim($rowCatSub['CATSUBCOD']);
								$catsubdes = trim($rowCatSub['CATSUBDES']);

								if (isset($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod])) {
									$match = true;
								}
							}
							if ($TableCatSub->Rows_Count == -1) {
								if (isset($perfilLog[$seccodigo][$secsubcod][$catcodigo])) {
									$match = true;
								}
							}
						}
						if ($TableCat->Rows_Count == -1) {
							if (isset($perfilLog[$seccodigo][$secsubcod])) {
								$match = true;
							}
						}
					}

					if ($TableSSect->Rows_Count == -1) {
						if (isset($perfilLog[$seccodigo])) {
							$match = true;
						}
					}
				}
			}

			//Busco si ya tengo una reunion con este perfil
			$reunion = 0;
			$queryReu = " 	SELECT REUREG 
							FROM REU_CABE 
							WHERE (PERCODSOL=$percodigo OR PERCODDST=$percodigo) AND (PERCODSOL=$percod OR PERCODDST=$percod) AND REUESTADO<>3 ";
			$TableReu = sql_query($queryReu, $conn);
			if ($TableReu->Rows_Count > 0) {
				$reunion = 1; //Tiene una Reunion
			}

			//Solo si son favoritos
			if ($esfavorito == 1 && trim($row['ESFAVO']) != 1) {
				$match = false;
			}


			if ($match) {
				$reg = array();
				$reg['PerCodigo'] 				= $percod;
				$reg['Nombre'] 					= trim($row['PERNOMBRE']);
				$reg['Apellido'] 				= trim($row['PERAPELLI']);
				$reg['Compania'] 				= trim($row['PERCOMPAN']);
				$reg['SitioWeb'] 				= trim($row['PERURLWEB']);
				$reg['Cargo'] 					= trim($row['PERCARGO']);
				$reg['Tipo'] 					= trim($row['PERTIPO']);
				$reg['Stand'] 					= trim($row['MESNUMERO']);
				$reg['Clase'] 					= trim($row['PERCLASE']);
				$reg['TipoDescripcion'] 		= trim($row['PERTIPDESESP']);
				$reg['ClaseDescripcion'] 		= trim($row['PERCLADES']);
				$reg['Descripcion'] 			= trim($row['PEREMPDES']);
				$reg['ImgAvatar'] 				= trim($row['PERAVATAR']);
				$reg['Favorito'] 				= trim($row['ESFAVO']);
				$reg['Reunion'] 				= $reunion;
				$reg['AreaCodigo'] 				= trim($row['PERARECOD']);
				$reg['AreaDescripcion'] 		= trim($row['PERAREDESESP']);
				$reg['IndustriaCodigo'] 		= trim($row['PERINDCOD']);
				$reg['IndustriaDescripcion'] 	= trim($row['PERINDDESESP']);
				$reg['Instagram'] 				= trim($row['PERINS']);
				$reg['Facebook'] 				= trim($row['PERFAC']);
				$reg['Twitter'] 				= trim($row['PERTWI']);
				$reg['Ciudad'] 					= trim($row['PERCIUDAD']);
				$reg['Estado'] 					= trim($row['PERESTADO']);
				$reg['PerfilActivo'] 			= trim($row['PERACTIVO']);

				array_push($response["Directorio"], $reg);
			}
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// SPONSORS
$app->post('/sponsors', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Sponsors"]	= array();

	$cantPagina = 20;
	if (isset($data->PAGINA)) {
		$pagina 	= $data->PAGINA * $cantPagina;
	} else {
		$pagina		= 0;
		$cantPagina = 2000;
	}

	try {
		$conn = sql_conectar(); //Apertura de Conexion	
		
		//SECTION Parametro de ordenamiento
		$query2 = "SELECT PARORDEN FROM PAR_MAEST WHERE PARCODIGO = 'SponsorOrden'";

		$Table2 = sql_query($query2, $conn);
		$orden  = $Table2->Rows[0]['PARORDEN'];

		if ($orden == 1) {

			$query = "	SELECT FIRST $cantPagina SKIP $pagina E.EXPREG,E.EXPNOMBRE,E.EXPWEB,E.EXPMAIL,E.EXPSTAND,E.EXPRUBROS,
										E.EXPAVATAR,E.EXPCATEGO,P.PERCODIGO,P.PERCOMPAN,E.EXPPOSX, E.EXPPOSY
								FROM EXP_MAEST E
								LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=E.PERCODIGO
								WHERE E.ESTCODIGO<>3
								ORDER BY E.EXPPOS  ";
		} else {

			$query = "	SELECT FIRST $cantPagina SKIP $pagina E.EXPREG,E.EXPNOMBRE,E.EXPWEB,E.EXPMAIL,E.EXPSTAND,E.EXPRUBROS,
										E.EXPAVATAR,E.EXPCATEGO,P.PERCODIGO,P.PERCOMPAN,E.EXPPOSX, E.EXPPOSY
								FROM EXP_MAEST E
								LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=E.PERCODIGO
								WHERE E.ESTCODIGO<>3
								ORDER BY E.EXPNOMBRE  ";
		}

		

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];

			$reg = array();
			$reg['Reg'] 			= trim($row['EXPREG']);
			$reg['Nombre'] 			= trim($row['EXPNOMBRE']);
			$reg['Web'] 			= trim($row['EXPWEB']);
			$reg['Mail'] 			= trim($row['EXPMAIL']);
			$reg['Stand'] 			= trim($row['EXPSTAND']);
			$reg['Categoria'] 		= trim($row['EXPCATEGO']);
			$reg['Rubros'] 			= trim($row['EXPRUBROS']);
			$reg['ImgAvatar'] 		= trim($row['EXPAVATAR']);
			$reg['PerCodigo'] 		= trim($row['PERCODIGO']);
			$reg['Compania'] 		= trim($row['PERCOMPAN']);
			$reg['PosY'] 			= trim($row['EXPPOSY']);
			$reg['PosX'] 			= trim($row['EXPPOSX']);

			array_push($response["Sponsors"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtejer los Sponsors ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// SPEAKERS
$app->post('/speakers', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);
	//ANCHOR TRAER DATA ORDENADA

	//ANCHOR 
	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Speakers"]	= array();

	$cantPagina = 10;
	$fltbuscar 	= (isset($data->FLTBUSCAR)) ? $data->FLTBUSCAR : '';

	if (isset($data->PAGINA)) {
		$pagina 	= $data->PAGINA * $cantPagina;
	} else {
		$pagina		= 0;
		$cantPagina = 2000;
	}

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		//Quito acentos
		$fltbuscar2 = $fltbuscar;
		$fltbuscar2 = str_replace('á', 'a', $fltbuscar2);
		$fltbuscar2 = str_replace('é', 'e', $fltbuscar2);
		$fltbuscar2 = str_replace('í', 'i', $fltbuscar2);
		$fltbuscar2 = str_replace('ó', 'o', $fltbuscar2);
		$fltbuscar2 = str_replace('ú', 'u', $fltbuscar2);

		$where = '';
		if ($fltbuscar != '') {
			$where .= " AND (SPKNOMBRE CONTAINING '$fltbuscar' OR
							 REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(SPKNOMBRE,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') CONTAINING '$fltbuscar2' )";
		}

		$query = "	SELECT FIRST $cantPagina SKIP $pagina SPKREG, SPKNOMBRE, SPKDESCRI, SPKIMG,SPKEMPRES,SPKCARGO
					FROM SPK_MAEST
					WHERE ESTCODIGO<>3 $where ";

		//SECTION Parametro de ordenamiento
		$queryOrd = "SELECT PARORDEN FROM PAR_MAEST WHERE PARCODIGO = 'SpeakerOrden'";

		$TableOrd = sql_query($queryOrd, $conn);
		$orden  = $TableOrd->Rows[0]['PARORDEN'];		

		if ($orden == 1) {			
			$query .= "	ORDER BY SPKPOS  ";
		} else {
			$query .= " ORDER BY SPKNOMBRE  ";
		}
		
		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];

			$reg = array();
			$reg['Reg'] 			= trim($row['SPKREG']);
			$reg['Nombre'] 			= trim($row['SPKNOMBRE']);
			$reg['Descripcion'] 	= trim($row['SPKDESCRI']);
			$reg['Empresa'] 		= trim($row['SPKEMPRES']);
			$reg['Cargo'] 			= trim($row['SPKCARGO']);
			$reg['ImgAvatar'] 		= trim($row['SPKIMG']);

			array_push($response["Speakers"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtejer los Speakers ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// AREAS
$app->post('/areas', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Areas"]	= array();

	//$percodigo 	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$query = "	SELECT A.PERARECOD,A.PERAREDESESP
					FROM PER_AREA A
					ORDER BY A.PERAREDESESP ";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];

			$reg = array();
			$reg['Codigo'] 			= trim($row['PERARECOD']);
			$reg['Descripcion']		= trim($row['PERAREDESESP']);

			array_push($response["Areas"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener areas ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// INDUSTRIAS
$app->post('/industrias', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Industrias"]	= array();

	$percodigo 	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		
		//Se modifica el query, para llenar el campo cn Subsectores
		$query = "	SELECT I.PERINDCOD,I.PERINDDESESP
					FROM PER_IND I
					ORDER BY I.PERINDDESESP ";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];

			$reg = array();
			$reg['Codigo'] 			= trim($row['PERINDCOD']);
			$reg['Descripcion']		= trim($row['PERINDDESESP']);

			array_push($response["Industrias"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener industrias ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// SECTORES
$app->post('/sectores', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Sectores"]	= array();

	$percodigo 	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$query = "	SELECT SECCODIGO,SECDESCRI
					FROM SEC_MAEST 
					WHERE ESTCODIGO=1
					ORDER BY SECDESCRI ";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$seccodigo = trim($row['SECCODIGO']);

			$reg = array();
			$reg['Codigo'] 			= $seccodigo;
			$reg['Descripcion']		= trim($row['SECDESCRI']);
			$reg['SubSectores']		= array();

			$query = "	SELECT SECSUBCOD,SECSUBDES
						FROM SEC_SUB 
						WHERE ESTCODIGO=1 AND SECCODIGO=$seccodigo
						ORDER BY SECSUBDES ";

			$TableSub = sql_query($query, $conn);
			for ($j = 0; $j < $TableSub->Rows_Count; $j++) {
				$rowSub = $TableSub->Rows[$j];

				$sub = array();
				$sub['Codigo'] 			= trim($rowSub['SECSUBCOD']);
				$sub['Descripcion']		= trim($rowSub['SECSUBDES']);

				array_push($reg["SubSectores"], $sub);
			}

			array_push($response["Sectores"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener sectores ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// ACTIVIDADES
$app->post('/actividades', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Actividades"]	= array();

	$percodigo 	= trim($data->PERCODIGO);
	$fltspkreg 	= trim($data->FLTSPKREG);
	$fltsala 	= trim($data->FLTSALA);
	$fltfecha 	= trim($data->FLTFECHA);

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$fltwhere = '';
		if ($fltspkreg != 0 && $fltspkreg != '') {
			$fltwhere .= " AND SPKREG||',' CONTAINING '$fltspkreg,'";
		}

		//if($fltfecha==1){//Dia 23
		//	$fltwhere .=" AND A.AGEFCH='10/23/2019' ";
		//}elseif($fltfecha==2){ //Dia 24
		//	$fltwhere .=" AND A.AGEFCH='10/24/2019' AND AGELUGAR='$fltsala' ";
		//}

		$query = "	SELECT A.AGEREG,A.AGETITULO, A.AGEDESCRI, A.AGELUGAR, A.AGEFCH, A.AGEHORINI, A.AGEHORFIN, A.ESTCODIGO,
							A.SPKREG, A.AGEPREHAB
					FROM AGE_MAEST A
					WHERE A.ESTCODIGO<>3 $fltwhere
					ORDER BY A.AGELUGAR,A.AGEFCH,A.AGEHORINI  ";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];

			$agefch  = BDConvFch($row['AGEFCH']);
			$spkreg  = trim($row['SPKREG']);
			$dia = substr($agefch, 0, 2);

			switch (intval($dia)) {
				case 12:
					$agefch = "MARTES $dia";
					break;
				case 13:
					$agefch = "MIERCOLES $dia";
					break;
				case 14:
					$agefch = "JUEVES $dia";
					break;
				case 15:
					$agefch = "VIERNES $dia";
					break;
			}

			$hora = substr(trim($row['AGEHORINI']), 0, 5) . ' ' . substr(trim($row['AGEHORFIN']), 0, 5);

			$reg = array();
			$reg['Reg'] 			= trim($row['AGEREG']);
			$reg['Titulo'] 			= trim($row['AGETITULO']);
			$reg['Descripcion']		= trim($row['AGEDESCRI']);
			$reg['Lugar'] 			= trim($row['AGELUGAR']);
			$reg['Fecha'] 			= $agefch;
			$reg['Hora'] 			= $hora;
			$reg['Pregunta'] 		= trim($row['AGEPREHAB']);
			$reg['Speakers']		= array();

			//Busco los datos de los speakers
			$query = "SELECT SPKREG,SPKNOMBRE,SPKDESCRI,SPKIMG
					FROM SPK_MAEST
					WHERE ESTCODIGO=1 AND SPKREG IN ($spkreg)";

			$TableSpk = sql_query($query, $conn);
			for ($j = 0; $j < $TableSpk->Rows_Count; $j++) {
				$rowSpk = $TableSpk->Rows[$j];
				$spkcodigo  = trim($rowSpk['SPKREG']);
				$spknombre  = trim($rowSpk['SPKNOMBRE']);
				$spkdescri  = trim($rowSpk['SPKDESCRI']);
				$spkimg  	= trim($rowSpk['SPKIMG']);

				$spk = array();
				$spk['Reg'] 		= $spkcodigo;
				$spk['Nombre'] 		= $spknombre;
				$spk['Descripcion'] = $spkdescri;
				$spk['ImgAvatar'] 	= $spkimg;

				array_push($reg['Speakers'], $spk);
			}

			array_push($response["Actividades"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// DISPONIBILIDAD
$app->post('/disponibilidad', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 			= false;
	$response["MESSAGE"] 		= '';
	$response["EXCEPTION"] 		= '';
	$response["Disponibilidad"]	= array();

	$percodsol 	= $data->PERCODSOL;
	$percoddst 	= $data->PERCODDST;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		//Busco si ya tengo reunion solicitada sin confirmar
		$query = "	SELECT S.REUFECHA,S.REUHORA
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG
					WHERE R.PERCODSOL=$percodsol AND R.PERCODDST=$percoddst AND R.REUESTADO=1 
					ORDER BY S.REUFECHA,S.REUHORA ";
		$TableReu = sql_query($query, $conn);

		//Busco los dias disponibles del perfil 
		$query = "	SELECT PERDISFCH,PERDISHOR
					FROM PER_DISP
					WHERE PERCODIGO=$percodsol 
					ORDER BY PERDISFCH,PERDISHOR ";
		$TableDispSol = sql_query($query, $conn);

		//Busco si el solicitante tiene: 
		// 1 Reunion que haya solicitado y este confirmada o no
		// 1 Reunion que le hayan solicitado (destino), y este confirmada, si la reunion que le solicitaron no fue confirmada aun, no bloquea disponibilidad
		//WHERE (R.PERCODSOL=$percodsol OR R.PERCODDST=$percodsol) AND R.REUESTADO IN(1,2) 
		$query = "	SELECT S.REUFECHA,S.REUHORA
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
					WHERE (R.PERCODSOL=$percodsol AND R.REUESTADO IN(1,2)) OR (R.PERCODDST=$percodsol AND R.REUESTADO IN(2))
					ORDER BY S.REUFECHA,S.REUHORA ";
		$TableReuSolConf = sql_query($query, $conn);

		//Busco si el solicitado tiene:
		// 1 Reunion que le haya solicitado y este o no confirmada
		// 1 Reunion que este confirmada con otro perfil
		//WHERE (R.PERCODSOL=$percoddst OR R.PERCODDST=$percoddst) AND R.REUESTADO IN(1,2) 
		$query = "	SELECT S.REUFECHA,S.REUHORA
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
					WHERE (R.PERCODSOL=$percoddst AND R.REUESTADO IN(1,2)) OR (R.PERCODDST=$percoddst AND R.REUESTADO IN (2))
					ORDER BY S.REUFECHA,S.REUHORA ";
		$TableReuDstConf = sql_query($query, $conn);

		$query = "	SELECT PERDISFCH,PERDISHOR
					FROM PER_DISP
					WHERE PERCODIGO=$percoddst 
					ORDER BY PERDISFCH,PERDISHOR ";
		$TableDisp = sql_query($query, $conn);
		for ($i = 0; $i < $TableDisp->Rows_Count; $i++) {
			$row = $TableDisp->Rows[$i];
			$fecha	= BDConvFch($row['PERDISFCH']);
			$hora	= substr(trim($row['PERDISHOR']), 0, 5);
			$horaLibre = true;

			//Si el horario esta libre, busco mi disponibilidad horario
			if ($horaLibre) {
				$horaLibre = false;
				for ($j = 0; $j < $TableDispSol->Rows_Count; $j++) {
					$rowDispSol = $TableDispSol->Rows[$j];
					$ffecha = BDConvFch($rowDispSol['PERDISFCH']);
					$hhora = substr(trim($rowDispSol['PERDISHOR']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = true;
					}
				}
			}

			//Verifico si el horario esta libre en mis reuniones
			if ($horaLibre) {
				for ($j = 0; $j < $TableReuSolConf->Rows_Count; $j++) {
					$rowReuSol = $TableReuSolConf->Rows[$j];
					$ffecha = BDConvFch($rowReuSol['REUFECHA']);
					$hhora = substr(trim($rowReuSol['REUHORA']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = false;
					}
				}
			}

			//Si el horario esta libre, busco en las reuniones del destino que no este ocupado
			if ($horaLibre) {
				for ($j = 0; $j < $TableReu->Rows_Count; $j++) {
					$rowReu = $TableReu->Rows[$j];
					$ffecha = BDConvFch($rowReu['REUFECHA']);
					$hhora = substr(trim($rowReu['REUHORA']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = false;
					}
				}
			}

			//Si el horario esta libre, busco en las reuniones del destino que no este ocupado
			if ($horaLibre) {
				for ($j = 0; $j < $TableReuDstConf->Rows_Count; $j++) {
					$rowReuDst = $TableReuDstConf->Rows[$j];
					$ffecha = BDConvFch($rowReuDst['REUFECHA']);
					$hhora = substr(trim($rowReuDst['REUHORA']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = false;
					}
				}
			}


			//Si el horario esta libre
			if ($horaLibre) {
				$reg = array();
				$reg['Fecha'] 	= $fecha;
				$reg['Hora'] 	= $hora;

				array_push($response["Disponibilidad"], $reg);
			}
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// CHAT GUARDA CONVERSACIONES
$app->post('/chatsave', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';

	$percodigo 	= $data->PERCODIGO;
	$percoddst 	= $data->PERCODDST;
	$mensaje 	= $data->MENSAJE;
	$estado 	= $data->ESTADO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		//Solo si es la primera vez que contacta
		$existeChat = false;
		$query = "	SELECT CHAREG
					FROM TBL_CHAT  
					WHERE PERCODIGO=$percodigo AND PERCODDST=$percoddst AND ESTCODIGO<>2 ";

		$TableCtrl = sql_query($query, $conn);
		if ($TableCtrl->Rows_Count > 0) {
			$existeChat = true;
		}


		if ($estado == 1) {
			$query = "	INSERT INTO TBL_CHAT (CHAREG, CHAFCHREG, PERCODIGO, PERCODDST, CHATEXTO, ESTCODIGO, CHALEIDO) 
						VALUES (GEN_ID(G_CHATS,1), CURRENT_TIMESTAMP, $percodigo, $percoddst, '$mensaje', $estado, 0);";
			$err = sql_execute($query, $conn);
		} else if ($estado == 2) { //Cancelo las conersaciones
			$query = "	UPDATE TBL_CHAT SET ESTCODIGO=2 WHERE ((PERCODDST=$percodigo AND PERCODIGO=$percoddst) OR (PERCODDST=$percoddst AND PERCODIGO=$percodigo)) AND ESTCODIGO=1 ";
			$err = sql_execute($query, $conn);
		}

		//Envio una notificacion informando el mensaje
		if (!$existeChat) {
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			//Busco si el destino tiene mobile
			$query = "	SELECT N.ID,TRIM(N.PROVIDER) AS PROVIDER
						FROM NOT_REGI N 
						WHERE N.PERCODIGO=$percoddst ";
			$TableMobil = sql_query($query, $conn);
			if ($TableMobil->Rows_Count > 0) {
				//Busco los datos de la empresa que solicita la reunion
				$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
							FROM PER_MAEST
							WHERE PERCODIGO=$percodigo";
				$TableOrigen = sql_query($query, $conn);
				$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
				$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
				$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);

				$titulo 	= "Contacto por Chat";
				$message 	= "$pernombre $perapelli de la empresa $percompan desea contactarse por chat con usted.";

				for ($n = 0; $n < $TableMobil->Rows_Count; $n++) {
					$id = trim($TableMobil->Rows[$n]['ID']);
					$provider = trim($TableMobil->Rows[$n]['PROVIDER']);

					if ($provider == 'FCM') {
						$target = array();
						array_push($target, $id);
						$data =  array(
							'title' => $titulo,
							'badge_number' => 1,
							'server_message' => '',
							'text' => $message,
							'id' => $reureg
						);

						sendFCMMessage($data, $target);
					} else {
						sendIOSMessage($message, $id);
					}
				}
			}
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*

			sendMailChat($percodigo, $percoddst);
		}

		sql_close($conn);

		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al almacenar chat ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// CHAT CONVERSACIONES
$app->post('/chatbrowser', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Chats"]		= array();

	$percodigo 	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$query  = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN,PERCODIGO,SUM(SINLEER) AS CHATSINLEER
					FROM (
					SELECT  PD.PERNOMBRE,PD.PERAPELLI,PD.PERCOMPAN,PD.PERCODIGO,0 AS SINLEER
					FROM TBL_CHAT C
					LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=C.PERCODDST
					WHERE C.ESTCODIGO=1 AND C.PERCODIGO=$percodigo
					UNION
					SELECT  PD.PERNOMBRE,PD.PERAPELLI,PD.PERCOMPAN,PD.PERCODIGO,
							(SELECT COUNT(*) FROM TBL_CHAT T WHERE T.ESTCODIGO=1 AND T.CHALEIDO=0 AND T.PERCODIGO=PD.PERCODIGO AND T.PERCODDST=$percodigo)  AS SINLEER
					FROM TBL_CHAT C
					LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=C.PERCODIGO
					WHERE C.ESTCODIGO=1 AND C.PERCODDST=$percodigo
					)
					GROUP BY 1,2,3,4 ";
		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$compan 	= trim($row['PERCOMPAN']);
			$codigo 	= trim($row['PERCODIGO']);
			$chatsinleer = trim($row['CHATSINLEER']);

			$exp = array();
			$exp['Codigo'] 			= $codigo;
			$exp['Nombre'] 			= $pernombre . ' ' . $perapelli;
			$exp['ChatsSinLeer'] 	= $chatsinleer;

			array_push($response["Chats"], $exp);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener browser de chat ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// CONVERSACIONES DEL CHAT
$app->post('/chat', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 			= false;
	$response["MESSAGE"] 		= '';
	$response["EXCEPTION"] 		= '';
	$response["Conversacion"]	= array();

	$percodigo 	= $data->PERCODIGO;
	$percoddst 	= $data->PERCODDST;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$query  = "	SELECT PS.PERCODIGO AS SOLCOD,PS.PERNOMBRE AS SOLNOM,C.CHATEXTO
					FROM TBL_CHAT C
					LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=C.PERCODIGO
					WHERE C.ESTCODIGO=1 AND ((C.PERCODIGO=$percodigo AND C.PERCODDST=$percoddst) OR (C.PERCODDST=$percodigo AND C.PERCODIGO=$percoddst)) 
					ORDER BY C.CHAREG ";
		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$solcod 	= trim($row['SOLCOD']);
			$solnom 	= trim($row['SOLNOM']);
			$texto 		= trim($row['CHATEXTO']);

			$exp = array();
			$exp['Codigo'] 		= $solcod;
			$exp['Nombre'] 		= $solnom;
			$exp['Mensaje'] 	= $texto;

			array_push($response["Conversacion"], $exp);
		}

		//Marco como leido las conversaciones del chat
		$query = "UPDATE TBL_CHAT C SET CHALEIDO=1
				WHERE C.ESTCODIGO=1 AND (C.PERCODDST=$percodigo AND C.PERCODIGO=$percoddst) AND C.CHALEIDO=0 ";
		$err = sql_execute($query, $conn);

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener el chat ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
//DATOS DE UBICACION EN EL MAPA
$app->get('/getexpomapa', function ()  use ($app) {
	$response = array();
	$response["ERROR"] 	 			= false;
	$response["MESSAGE"] 			= '';
	$response["EXCEPTION"] 			= '';
	//$response["MapaUrlImagen"]		= '';
	$response["MapaExpositores"]	= array();

	try {
		$conn = sql_conectar(); //Apertura de Conexion				

		//Busco el parametro del nombre del plano
		//$query  = "	SELECT PARVALOR FROM PAR_MAEST WHERE PARCODIGO='NameImagePlano' ";
		//$Table 	= sql_query($query,$conn);
		//$response["MapaUrlImagen"]	= PathPlano.$Table->Rows[0]['PARVALOR'];

		//Busco el listado de expositores
		$query  = "	SELECT E.EXPREG, E.EXPNOMBRE AS EXPNOMBRE, E.EXPPOSX, E.EXPPOSY,E.EXPRUBROS,E.EXPAVATAR,
							P.PERCODIGO,P.PERCOMPAN
					FROM EXP_MAEST E
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=E.PERCODIGO
					WHERE E.ESTCODIGO<>3 AND E.EXPPOSX<>0 AND E.EXPPOSY<>0
					ORDER BY E.EXPNOMBRE ";
		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$expmapreg 	= trim($row['EXPREG']);
			$expmapnom 	= trim($row['EXPNOMBRE']);
			$expmapx 	= trim($row['EXPPOSX']);
			$expmapy 	= trim($row['EXPPOSY']);
			$exprubros 	= trim($row['EXPRUBROS']);
			$expavatar 	= trim($row['EXPAVATAR']);
			$percodigo 	= trim($row['PERCODIGO']);
			$percompan 	= trim($row['PERCOMPAN']);
			$expmaptip 	= 1;

			$exp = array();
			$exp['Reg'] 		= $expmapreg;
			$exp['Nombre'] 		= $expmapnom;
			$exp['PointX'] 		= $expmapx;
			$exp['PointY'] 		= $expmapy;
			$exp['Tipo']		= $expmaptip;
			$exp['Rubro']		= $exprubros;
			$exp['ImgAvatar']	= $expavatar;
			$exp['PerCodigo']	= $percodigo;
			$exp['PerNombre']	= $percompan;

			array_push($response["MapaExpositores"], $exp);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener informacion de los expositores mapa";
		$response["EXCEPTION"] 	=  $e->getMessage();
		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// SOLICITAR
$app->post('/solicitar', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 			= false;
	$response["MESSAGE"] 		= '';
	$response["EXCEPTION"] 		= '';

	$percodsol 	= $data->PERCODSOL;
	$percoddst 	= $data->PERCODDST;
	$diashoras 	= $data->DIASHORAS;
	$desreg 	= (isset($data->DESREG)) ? trim($data->DESREG) : 0;
	$reudessol 	= (isset($data->DESSOLUCION)) ? trim($data->DESSOLUCION) : '';
	$reuestado	= 1; //Reunion sin Confirmar
	$reureg		= 0;

	try {
		$conn = sql_conectar(); //Apertura de Conexion	
		$trans	= sql_begin_trans($conn);

		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_REUNIONES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query, $conn, $trans);
		$RowId		= $TblId->Rows[0];
		$reureg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		//Inserto reunion cabecera
		$query = " 	INSERT INTO REU_CABE(REUREG,REUFCHREG,PERCODSOL,PERCODDST,REUESTADO,REUFECHA,REUHORA,DESREG,REUDESSOL)
					VALUES($reureg,CURRENT_TIMESTAMP,$percodsol,$percoddst,$reuestado,NULL,NULL,$desreg,'$reudessol') ";
		$err = sql_execute($query, $conn, $trans);

		//Inserto Notificacion de Solicitud
		$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG,NOTCODIGO)
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, 'Reunión solicitada', NULL, $percoddst, 1, $percodsol, $reureg,1); ";
		$err = sql_execute($query, $conn, $trans);

		//Envio un mail al destino
		$query = "	SELECT PERCOMPAN,PERAPELLI,PERNOMBRE FROM PER_MAEST WHERE PERCODIGO=$percodsol ";
		$TableSol = sql_query($query, $conn, $trans);

		$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percoddst AND PERCORREO IS NOT NULL ";
		$TableDst = sql_query($query, $conn, $trans);
		if ($TableDst->Rows_Count > 0) {
			$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
			$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante
			$pernombre = trim($TableSol->Rows[0]['PERNOMBRE']);	//Nombre del Solicitante
			$perapelli = trim($TableSol->Rows[0]['PERAPELLI']);	//Apellido del Solicitante

			sendMailSolicitarReunion($correodst, $compansol, $pernombre, $perapelli);
		}

		////Envio un mail al destino
		//$query = "	SELECT PERCOMPAN FROM PER_MAEST WHERE PERCODIGO=$percodsol ";
		//$TableSol = sql_query($query,$conn,$trans);
		//
		//$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percoddst AND PERCORREO IS NOT NULL ";
		//$TableDst = sql_query($query,$conn,$trans);
		//if($TableDst->Rows_Count>0){
		//	$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
		//	$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante
		//	
		//	sendMailSolicitarReunion($correodst, $compansol);
		//}

		//Envio una notifiacion mobile
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Busco si el destino tiene mobile
		$query = "	SELECT N.ID,TRIM(N.PROVIDER) AS PROVIDER
					FROM NOT_REGI N 
					WHERE N.PERCODIGO=$percoddst ";
		$TableMobil = sql_query($query, $conn, $trans);
		if ($TableMobil->Rows_Count > 0) {
			//Busco los datos de la empresa que solicita la reunion
			$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
						FROM PER_MAEST
						WHERE PERCODIGO=$percodsol";
			$TableOrigen = sql_query($query, $conn, $trans);
			$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
			$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
			$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);

			$titulo 	= 'Reunión solicitada';
			$message 	= "$perapelli $pernombre de la empresa $percompan desea agendar una reunión con usted. Ingrese en la sección de  Reuniones Recibidas para coordinar el horario."; //da empresa $percompan deseja marcar uma reunião com você.

			for ($n = 0; $n < $TableMobil->Rows_Count; $n++) {
				$id = trim($TableMobil->Rows[$n]['ID']);
				$provider = trim($TableMobil->Rows[$n]['PROVIDER']);

				if ($provider == 'FCM') {
					$target = array();
					array_push($target, $id);
					$data =  array(
						'title' => $titulo,
						'badge_number' => 1,
						'server_message' => '',
						'text' => $message,
						'id' => $reureg
					);

					sendFCMMessage($data, $target);
				} else {
					sendIOSMessage($message, $id);
				}
			}
		}

		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*

		//Insertando horarios solicitados para la reunion
		$vdiashoras = explode('||', $diashoras);
		for ($i = 0; $i < count($vdiashoras); $i++) {
			if ($vdiashoras[$i] != '') {
				$vaux = explode('-', $vdiashoras[$i]); //0:Dia , 1:Hora
				if ($vaux[0] != '' && $vaux[1] != '') {

					$reufecha 	= ConvFechaBD($vaux[0]); 		//Fecha
					$reuhora 	= VarNullBD($vaux[1], 'S');	//Hora

					$query = "	INSERT INTO REU_SOLI(REUREG,REUFECHA,REUHORA,REUESTADO)
								VALUES($reureg,$reufecha,$reuhora,$reuestado)";
					$err = sql_execute($query, $conn, $trans);
				}
			}
		}

		if ($err == 'SQLACCEPT') {
			sql_commit_trans($trans);
		} else {
			sql_rollback_trans($trans);
			$response["ERROR"] 	 	= true;
			$response["MESSAGE"] 	= "Error al registrar la solicitud ";
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar la solicitud ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// REUNIONES
$app->post('/reuniones', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Reuniones"]	= array();

	$percodigo 	= $data->PERCODIGO;
	$tipobsq 	= $data->TIPOBSQ;
	$pertipo 	= $data->PERTIPO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$fltwhere = "";
		switch ($tipobsq) {
			case 1: //Reuniones Enviadas y Pendientes
				$fltwhere .= " AND (R.PERCODSOL=$percodigo AND R.REUESTADO=1) ";
				break;
			case 2: //Reuniones Recibidas y Pendientes
				$fltwhere .= " AND (R.PERCODDST=$percodigo AND R.REUESTADO=1) ";
				break;
			case 3: //Reuniones Agendadas confirmadas
				$fltwhere .= " AND (R.REUESTADO=2) ";
				break;
			case 4: //Reuniones Canceladas
				$fltwhere .= " AND (R.REUESTADO=3) ";
				break;
		}

		switch ($pertipo) {
			case 1: //Open Innovation Arena
				$fltwhere .= " AND COALESCE(R.DESREG,0)<>0 AND (PD.PERTIPO=2 OR PS.PERTIPO=2) ";
				break;
			case 2: //Individuales
				$fltwhere .= "  AND COALESCE(R.DESREG,0)<>0 AND (PD.PERTIPO=1 OR PS.PERTIPO=1) ";
				break;
			case 3: //Generales
				$fltwhere .= " AND COALESCE(R.DESREG,0)=0 ";
				break;
			case 0: //Todas
				break;
		}


		$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
							PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL,
							PD.PERURLWEB AS PERWEBDST,PS.PERURLWEB AS PERWEBSOL,
							PD.PEREMPDES AS PERDESDST,PS.PEREMPDES AS PERDESSOL,
							R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,
							R.AGEREG,A.AGETITULO,A.AGELUGAR,M.MESNUMERO,
							R.DESREG,R.REUDESSOL
					FROM REU_CABE R
					LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
					LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
					LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=R.AGEREG
					LEFT OUTER JOIN MES_DISP MD ON MD.REUREG=R.REUREG
					LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=MD.MESCODIGO
					WHERE R.AGEREG IS NULL AND (R.PERCODSOL=$percodigo OR R.PERCODDST=$percodigo)
						$fltwhere
					ORDER BY R.REUFECHA,R.REUHORA ";
		//logerror($query);
		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$percoddst 	= trim($row['PERCODDST']);
			$pernomdst	= trim($row['PERNOMDST']);
			$perapedst	= trim($row['PERAPEDST']);
			$percomdst	= trim($row['PERCOMDST']);
			$percordst	= trim($row['PERCORDST']);
			$peravadst	= trim($row['PERAVADST']);
			$perwebdst	= trim($row['PERWEBDST']);
			$perdesdst	= trim($row['PERDESDST']);

			$percodsol 	= trim($row['PERCODSOL']);
			$pernomsol	= trim($row['PERNOMSOL']);
			$perapesol	= trim($row['PERAPESOL']);
			$percomsol	= trim($row['PERCOMSOL']);
			$percorsol	= trim($row['PERCORSOL']);
			$peravasol	= trim($row['PERAVASOL']);
			$perwebsol	= trim($row['PERWEBSOL']);
			$perdessol	= trim($row['PERDESSOL']);
			$desreg		= trim($row['DESREG']);
			$reudessol	= trim($row['REUDESSOL']);

			$mesnumero	= trim($row['MESNUMERO']);

			$agereg		= trim($row['AGEREG']);
			$agetitulo	= trim($row['AGETITULO']);
			$agelugar	= trim($row['AGELUGAR']);

			$reufecha	= BDConvFch($row['REUFECHA']);

			$reuhora = '';
			if (trim($row['REUHORA']) != '') {
				$reuhora	= substr(trim($row['REUHORA']), 0, 5);
			}
			$reuestado	= trim($row['REUESTADO']);
			$reureg		= trim($row['REUREG']);
			$reutipo 	= 0; //1-Solicitada, 2-Recibida

			if ($percoddst == $percodigo) {
				$percod 	= $percodsol;
				$pernombre	= $pernomsol;
				$perapelli	= $perapesol;
				$percompan	= $percomsol;
				$percorreo	= $percorsol;
				$peravatar	= $peravasol;
				$perurlweb 	= $perwebsol;
				$perempdes 	= $perdessol;
				$reutipo 	= 2;
			} else {
				$percod 	= $percoddst;
				$pernombre	= $pernomdst;
				$perapelli	= $perapedst;
				$percompan	= $percomdst;
				$percorreo	= $percordst;
				$peravatar	= $peravadst;
				$perurlweb 	= $perwebdst;
				$perempdes 	= $perdesdst;
				$reutipo 	= 1;
			}


			if ($reuestado == 2) { //Confirmada
			} else {
				//Busco los horarios solicitados para reunion
				$query = "	SELECT FIRST 1 S.REUFECHA,S.REUHORA
							FROM REU_CABE R 
							INNER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
							WHERE R.REUREG=$reureg 
							ORDER BY S.REUFECHA,S.REUHORA ";

				$TableReu = sql_query($query, $conn);
				for ($j = 0; $j < $TableReu->Rows_Count; $j++) {
					$rowReu		= $TableReu->Rows[$j];
					$reufecha 	= BDConvFch($rowReu['REUFECHA']);
					$reuhora 	= substr(trim($rowReu['REUHORA']), 0, 5);
				}
			}

			if ($mesnumero != '') $percompan = $mesnumero . ' - ' . $percompan;

			if (trim($reufecha) != '') {
				$reufecha = 'Día ' . substr($reufecha, 0, 2);
			}

			$reg = array();
			$reg['Reunion'] 			= $reureg;
			$reg['PerCodigo'] 			= $percod;
			$reg['Nombre'] 				= $pernombre;
			$reg['Apellido'] 			= $perapelli;
			$reg['Compania'] 			= $percompan;
			$reg['ImgAvatar'] 			= $peravatar;
			$reg['SitioWeb'] 			= $perurlweb;
			$reg['Descripcion'] 		= $perempdes;
			$reg['Tipo'] 				= $reutipo;
			$reg['Estado'] 				= $reuestado;
			$reg['Fecha'] 				= $reufecha;
			$reg['Hora'] 				= $reuhora;
			$reg['DesafioReg'] 			= $desreg;
			$reg['DesafioSolucion']		= $reudessol;

			array_push($response["Reuniones"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error en reuniones ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// ENVIADAS CANCELAR
$app->post('/cancelarreunion', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 			= false;
	$response["MESSAGE"] 		= '';
	$response["EXCEPTION"] 		= '';

	$percodigo 	= $data->PERCODIGO;
	$reureg 	= $data->REUREG;

	try {
		$conn = sql_conectar(); //Apertura de Conexion	
		$trans	= sql_begin_trans($conn);

		//Busco la reunion, para poder tomar a quien comunicar la notificacion
		$query = " 	SELECT PERCODSOL,PERCODDST
					FROM REU_CABE 
					WHERE REUREG=$reureg ";

		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			$row = $Table->Rows[0];
			$percodsol = trim($row['PERCODSOL']);
			$percoddst = trim($row['PERCODDST']);
		}

		if ($percoddst == $percodigo) {
			$percoddst = $percodsol;
		}

		$query = " 	UPDATE REU_CABE SET REUESTADO=3,REUFCHCAN=CURRENT_TIMESTAMP WHERE REUREG=$reureg ";
		$err = sql_execute($query, $conn, $trans);

		$query = " 	UPDATE REU_SOLI SET REUESTADO=3 WHERE REUREG=$reureg ";
		$err = sql_execute($query, $conn, $trans);

		//Inserto Notificacion de Cancelacion
		$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, 'Reunión cancelada', NULL, $percoddst, 1, $percodigo, $reureg, 3); ";
		$err = sql_execute($query, $conn, $trans);

		//Libero la mesa 
		$query = "	DELETE FROM MES_DISP WHERE REUREG=$reureg ";
		$err = sql_execute($query, $conn, $trans);

		//Envio un mail al destino
		$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI FROM PER_MAEST WHERE PERCODIGO=$percodigo ";
		$TableSol = sql_query($query, $conn, $trans);

		$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percoddst AND PERCORREO IS NOT NULL ";
		$TableDst = sql_query($query, $conn, $trans);
		if ($TableDst->Rows_Count > 0) {
			$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
			$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante
			$pernombre = trim($TableSol->Rows[0]['PERNOMBRE']); //Nombre del Solicitante
			$perapelli = trim($TableSol->Rows[0]['PERAPELLI']); //Apellido del Solicitante

			sendMailCancelarReunion($correodst, $compansol, $pernombre, $perapelli);
		}

		////Envio un mail al destino
		//$query = "	SELECT PERCOMPAN FROM PER_MAEST WHERE PERCODIGO=$percodsol ";
		//$TableSol = sql_query($query,$conn,$trans);
		//
		//$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percoddst AND PERCORREO IS NOT NULL ";
		//$TableDst = sql_query($query,$conn,$trans);
		//if($TableDst->Rows_Count>0){
		//	$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
		//	$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante
		//	
		//	sendMailCancelarReunion($correodst, $compansol);
		//}

		//Envio una notifiacion mobile
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Busco si el destino tiene mobile
		$query = "	SELECT N.ID,TRIM(N.PROVIDER) AS PROVIDER 
					FROM NOT_REGI N 
					WHERE N.PERCODIGO=$percoddst ";
		$TableMobil = sql_query($query, $conn, $trans);
		if ($TableMobil->Rows_Count > 0) {
			//Busco los datos de la empresa que solicita la reunion
			$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
						FROM PER_MAEST
						WHERE PERCODIGO=$percodigo";
			$TableOrigen = sql_query($query, $conn, $trans);
			$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
			$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
			$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);

			$titulo 	= 'Reunión cancelada';
			$message 	= "$perapelli $pernombre de la empresa $percompan no acepto la solicitud de la reunión."; //não aceitou a sua solicitação de reunião

			for ($n = 0; $n < $TableMobil->Rows_Count; $n++) {
				$id = trim($TableMobil->Rows[$n]['ID']);
				$provider = trim($TableMobil->Rows[$n]['PROVIDER']);

				if ($provider == 'FCM') {
					$target = array();
					array_push($target, $id);
					$data =  array(
						'title' => $titulo,
						'badge_number' => 1,
						'server_message' => '',
						'text' => $message,
						'id' => $reureg
					);

					sendFCMMessage($data, $target);
				} else {
					sendIOSMessage($message, $id);
				}
			}
		}
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		logerror($err);

		if ($err == 'SQLACCEPT') {
			sql_commit_trans($trans);
		} else {
			sql_rollback_trans($trans);
			$response["ERROR"] 	 	= true;
			$response["MESSAGE"] 	= "Error al registrar la solicitud ";
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar la solicitud ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// DATOS PARA COORDINAR LA REUNION
$app->post('/coordinar', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 			= false;
	$response["MESSAGE"] 		= '';
	$response["EXCEPTION"] 		= '';
	$response["Coordinar"]		= array();

	$percoddst 	= $data->PERCODIGO;
	$percodsol 	= $data->PERCODSOL;
	$reureg 	= $data->REUREG;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		//Busco si ya tengo reunion solicitada sin confirmar
		$query = "	SELECT S.REUFECHA,S.REUHORA
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG
					WHERE R.PERCODSOL=$percodsol AND R.PERCODDST=$percoddst AND R.REUESTADO=1 AND R.REUREG<>$reureg
					ORDER BY S.REUFECHA,S.REUHORA ";
		$TableReu = sql_query($query, $conn);

		//Busco los dias disponibles del perfil 
		$query = "	SELECT PERDISFCH,PERDISHOR
					FROM PER_DISP
					WHERE PERCODIGO=$percodsol 
					ORDER BY PERDISFCH,PERDISHOR ";
		$TableDispSol = sql_query($query, $conn);

		//Busco si ya tengo reunion para la hora confirmada o sin confirmar
		$query = "	SELECT S.REUFECHA,S.REUHORA
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
					WHERE (R.PERCODSOL=$percodsol OR R.PERCODDST=$percodsol) AND R.REUESTADO IN(1,2) AND R.REUREG<>$reureg
					ORDER BY S.REUFECHA,S.REUHORA ";
		$TableReuSolConf = sql_query($query, $conn);

		//Busco si el solicitado tiene reuniones confirmadas o sin confirmar
		$query = "	SELECT S.REUFECHA,S.REUHORA
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
					WHERE (R.PERCODSOL=$percoddst OR R.PERCODDST=$percoddst) AND R.REUESTADO IN(1,2) AND R.REUREG<>$reureg 
					ORDER BY S.REUFECHA,S.REUHORA ";
		$TableReuDstConf = sql_query($query, $conn);

		$query = "	SELECT PERDISFCH,PERDISHOR
					FROM PER_DISP
					WHERE PERCODIGO=$percoddst 
					ORDER BY PERDISFCH,PERDISHOR ";
		$TableDisp = sql_query($query, $conn);

		//Busco los horarios sugeridos de la reunion
		$query = "	SELECT S.REUFECHA,S.REUHORA
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
					WHERE R.REUREG=$reureg 
					ORDER BY S.REUFECHA,S.REUHORA ";

		$TableReuSuge = sql_query($query, $conn);

		for ($i = 0; $i < $TableDisp->Rows_Count; $i++) {
			$row = $TableDisp->Rows[$i];
			$fecha	= BDConvFch($row['PERDISFCH']);
			$hora	= substr(trim($row['PERDISHOR']), 0, 5);
			$horaLibre = true;
			$sugerida = 0;

			//Si el horario esta libre, busco mi disponibilidad horario
			if ($horaLibre) {
				$horaLibre = false;
				for ($j = 0; $j < $TableDispSol->Rows_Count; $j++) {
					$rowDispSol = $TableDispSol->Rows[$j];
					$ffecha = BDConvFch($rowDispSol['PERDISFCH']);
					$hhora = substr(trim($rowDispSol['PERDISHOR']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = true;
					}
				}
			}

			//Verifico si el horario esta libre en mis reuniones
			if ($horaLibre) {
				for ($j = 0; $j < $TableReuSolConf->Rows_Count; $j++) {
					$rowReuSol = $TableReuSolConf->Rows[$j];
					$ffecha = BDConvFch($rowReuSol['REUFECHA']);
					$hhora = substr(trim($rowReuSol['REUHORA']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = false;
					}
				}
			}

			//Si el horario esta libre, busco en las reuniones del destino que no este ocupado
			if ($horaLibre) {
				for ($j = 0; $j < $TableReu->Rows_Count; $j++) {
					$rowReu = $TableReu->Rows[$j];
					$ffecha = BDConvFch($rowReu['REUFECHA']);
					$hhora = substr(trim($rowReu['REUHORA']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = false;
					}
				}
			}

			//Si el horario esta libre, busco en las reuniones del destino que no este ocupado
			if ($horaLibre) {
				for ($j = 0; $j < $TableReuDstConf->Rows_Count; $j++) {
					$rowReuDst = $TableReuDstConf->Rows[$j];
					$ffecha = BDConvFch($rowReuDst['REUFECHA']);
					$hhora = substr(trim($rowReuDst['REUHORA']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$horaLibre = false;
					}
				}
			}

			//Busco si son los horarios sugeridos de la reunion
			if ($horaLibre) {
				for ($j = 0; $j < $TableReuSuge->Rows_Count; $j++) {
					$rowReuSuge = $TableReuSuge->Rows[$j];
					$ffecha = BDConvFch($rowReuSuge['REUFECHA']);
					$hhora = substr(trim($rowReuSuge['REUHORA']), 0, 5);
					if ($fecha == $ffecha && $hora == $hhora) {
						$sugerida = 1;
					}
				}
			}

			//Si el horario esta libre
			if ($horaLibre) {
				$reg = array();
				$reg['Fecha'] 	= $fecha;
				$reg['Hora'] 	= $hora;
				$reg['Sugerida'] = $sugerida; //0:NO,1:SI

				array_push($response["Coordinar"], $reg);
			}
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// ACEPTAR REUINON
$app->post('/aceptarreunion', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 			= false;
	$response["MESSAGE"] 		= '';
	$response["EXCEPTION"] 		= '';

	$percodsol 	= $data->PERCODSOL;
	$percoddst 	= $data->PERCODDST;
	$reureg 	= $data->REUREG;
	$diashoras 	= $data->DIASHORAS;

	try {
		$conn = sql_conectar(); //Apertura de Conexion	
		$trans	= sql_begin_trans($conn);

		$cambioHorario = false;
		$mesaDisponible = false;
		$vaux = explode('-', $diashoras); //0:Dia , 1:Hora

		$reufecha 	= ConvFechaBD($vaux[0]); 		//Fecha
		$reuhora 	= VarNullBD($vaux[1], 'S');	//Hora

		//Busco si el perfil solicitante o destino, tienen una mesa fija bloqueada asiganda
		$query = "	SELECT FIRST 1 P.MESCODIGO
					FROM PER_MAEST P
					LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=P.MESCODIGO
					WHERE (P.PERCODIGO=$percodsol OR P.PERCODIGO=$percoddst) AND P.MESCODIGO IS NOT NULL
						AND M.MESBLOQUE='S' ";
		$TablePerMesa = sql_query($query, $conn);
		if ($TablePerMesa->Rows_Count > 0) {
			$mescodigo = trim($TablePerMesa->Rows[0]['MESCODIGO']);
			if ($mescodigo != '') {
				$mesaDisponible = true;
				$query = "	INSERT INTO MES_DISP (MESDISREG,MESCODIGO,MESDISFCH,MESDISHOR,REUREG)
							VALUES(GEN_ID(G_MESADISP,1),$mescodigo,$reufecha,$reuhora,$reureg) ";
				$err = sql_execute($query, $conn, $trans);
			}
		}

		if ($mesaDisponible == false) {
			//Busco si hay mesas libres para el horario y fecha
			$query = "	SELECT M.MESCODIGO
						FROM MES_MAEST M
						WHERE M.ESTCODIGO=1 AND M.MESBLOQUE='N' 
								AND  NOT EXISTS(SELECT 1
												 FROM MES_DISP D
												 WHERE D.MESCODIGO=M.MESCODIGO AND D.MESDISFCH=$reufecha AND D.MESDISHOR=$reuhora) ";
			$TableMesa = sql_query($query, $conn);
			if ($TableMesa->Rows_Count > 0) {
				$mescodigo = trim($TableMesa->Rows[0]['MESCODIGO']);
				if ($mescodigo != '') {
					$mesaDisponible = true;
					$query = "	INSERT INTO MES_DISP (MESDISREG,MESCODIGO,MESDISFCH,MESDISHOR,REUREG)
								VALUES(GEN_ID(G_MESADISP,1),$mescodigo,$reufecha,$reuhora,$reureg) ";
					$err = sql_execute($query, $conn, $trans);
				}
			}
		}

		if ($mesaDisponible == true) {
			//Verifico si hubo un cambio de horario-dia
			$query = "	SELECT REUFECHA,REUHORA
						FROM REU_SOLI 
						WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
			$TableChk = sql_query($query, $conn);
			if ($TableChk->Rows_Count > 0) {
				$cambioHorario = false;
				$query = "	UPDATE REU_SOLI SET REUESTADO=2
							WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
				$err = sql_execute($query, $conn, $trans);
			} else {
				$cambioHorario = true;
				$query = "	INSERT INTO REU_SOLI(REUREG,REUFECHA,REUHORA,REUESTADO)
							VALUES($reureg,$reufecha,$reuhora,2)";
				$err = sql_execute($query, $conn, $trans);
			}

			//Elimino los horarios solicitados, para dejar el confirmado
			$query = "DELETE FROM REU_SOLI WHERE REUREG=$reureg AND REUESTADO=1 ";
			$err = sql_execute($query, $conn, $trans);

			$query = " UPDATE REU_CABE SET REUFECHA=$reufecha, REUHORA=$reuhora, REUESTADO=2 WHERE REUREG=$reureg ";
			$err = sql_execute($query, $conn, $trans);


			$msgCambioHorario = 'Reunión confirmada';
			if ($cambioHorario) {
				$msgCambioHorario = 'Reunión confirmada con cambio de horario'; // Reunião confirmada com alteração de horário  
			}

			//Inserto Notificacion de Aceptacion
			$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
						VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$msgCambioHorario', NULL, $percodsol, 1, $percoddst, $reureg, 2); ";
			$err = sql_execute($query, $conn, $trans);

			//Envio un mail al destino
			$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI FROM PER_MAEST WHERE PERCODIGO=$percoddst ";
			$TableSol = sql_query($query, $conn, $trans);

			$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percodsol AND PERCORREO IS NOT NULL ";
			$TableDst = sql_query($query, $conn, $trans);
			if ($TableDst->Rows_Count > 0) {
				$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
				$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante
				$pernombre = trim($TableSol->Rows[0]['PERNOMBRE']); //Nombre del Solicitante
				$perapelli = trim($TableSol->Rows[0]['PERAPELLI']); //Apellido del Solicitante

				sendMailConfirmarReunion($correodst, $compansol, $pernombre, $perapelli);
			}

			////Envio un mail al destino
			//$query = "	SELECT PERCOMPAN FROM PER_MAEST WHERE PERCODIGO=$percodsol ";
			//$TableSol = sql_query($query,$conn,$trans);
			//
			//$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percoddst AND PERCORREO IS NOT NULL ";
			//$TableDst = sql_query($query,$conn,$trans);
			//if($TableDst->Rows_Count>0){
			//	$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
			//	$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante
			//	
			//	sendMailConfirmarReunion($correodst, $compansol);
			//}

			//Envio una notifiacion mobile
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			//Busco si el destino tiene mobile
			$query = "	SELECT N.ID, TRIM(N.PROVIDER) AS PROVIDER
						FROM NOT_REGI N 
						WHERE N.PERCODIGO=$percodsol ";
			$TableMobil = sql_query($query, $conn, $trans);
			if ($TableMobil->Rows_Count > 0) {
				//Busco los datos de la empresa que solicita la reunion
				$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
							FROM PER_MAEST
							WHERE PERCODIGO=$percoddst";
				$TableOrigen = sql_query($query, $conn, $trans);
				$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
				$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
				$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);

				$titulo 	= $msgCambioHorario;
				$message 	= "$perapelli $pernombre de la empresa $percompan confirmó la reunión solicitad. Ingrese en la sección de Reuniones Agendadas para revisar el horario."; //confirmou a solicitação de reunião

				for ($n = 0; $n < $TableMobil->Rows_Count; $n++) {
					$id = trim($TableMobil->Rows[$n]['ID']);
					$provider = trim($TableMobil->Rows[$n]['PROVIDER']);

					if ($provider == 'FCM') {
						$target = array();
						array_push($target, $id);
						$data =  array(
							'title' => $titulo,
							'badge_number' => 1,
							'server_message' => '',
							'text' => $message,
							'id' => $reureg
						);

						sendFCMMessage($data, $target);
					} else {
						sendIOSMessage($message, $id);
					}
				}
			}
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*

			if ($err == 'SQLACCEPT') {
				sql_commit_trans($trans);
				$response["ERROR"] 	 	= false;
				$response["MESSAGE"] 	= "Reunión aceptada! ";
			} else {
				sql_rollback_trans($trans);
				$response["ERROR"] 	 	= true;
				$response["MESSAGE"] 	= "Error al aceptar la reunión ";
			}
		} else { //No hay disponibilidad de mesa, se cancela la reunion
			//Busco la reunion, para poder tomar a quien comunicar la notificacion
			$query = " 	SELECT PERCODSOL,PERCODDST
						FROM REU_CABE 
						WHERE REUREG=$reureg ";

			$Table = sql_query($query, $conn, $trans);
			if ($Table->Rows_Count > 0) {
				$row = $Table->Rows[0];
				$percodsol = trim($row['PERCODSOL']);
				$percoddst = trim($row['PERCODDST']);
			}

			$query = " 	UPDATE REU_CABE SET REUESTADO=3,REUFCHCAN=CURRENT_TIMESTAMP WHERE REUREG=$reureg ";
			$err = sql_execute($query, $conn, $trans);

			$query = " 	UPDATE REU_SOLI SET REUESTADO=3 WHERE REUREG=$reureg ";
			$err = sql_execute($query, $conn, $trans);

			//Inserto Notificacion de Cancelacion
			$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
						VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, 'Reunión cancelada, sin disponibilidad de mesas.', NULL, $percoddst, 1, $percodsol, $reureg, 3); ";
			$err = sql_execute($query, $conn, $trans);

			//Envio un mail al destino
			$query = "	SELECT PERCOMPAN FROM PER_MAEST WHERE PERCODIGO=$percodsol ";
			$TableSol = sql_query($query, $conn, $trans);

			$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percoddst AND PERCORREO IS NOT NULL ";
			$TableDst = sql_query($query, $conn, $trans);
			if ($TableDst->Rows_Count > 0) {
				$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
				$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante

				sendMailCancelarReunionNoMesas($correodst, $compansol);
			}

			//Envio una notifiacion mobile
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			//Busco si el destino tiene mobile
			$query = "	SELECT N.ID,TRIM(N.PROVIDER) AS PROVIDER 
						FROM NOT_REGI N 
						WHERE N.PERCODIGO=$percoddst ";
			$TableMobil = sql_query($query, $conn, $trans);
			if ($TableMobil->Rows_Count > 0) {
				//Busco los datos de la empresa que solicita la reunion
				$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
							FROM PER_MAEST
							WHERE PERCODIGO=$percodsol";
				$TableOrigen = sql_query($query, $conn, $trans);
				$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
				$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
				$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);

				$titulo 	= 'Reunión cancelada, sin disponibilidad de mesas.';
				$message 	= "$perapelli $pernombre de $percompan, al confirmar no hay disponibilidad de mesas, se cancela la reunión.";

				for ($n = 0; $n < $TableMobil->Rows_Count; $n++) {
					$id = trim($TableMobil->Rows[$n]['ID']);
					$provider = trim($TableMobil->Rows[$n]['PROVIDER']);

					if ($provider == 'FCM') {
						$target = array();
						array_push($target, $id);
						$data =  array(
							'title' => $titulo,
							'badge_number' => 1,
							'server_message' => '',
							'text' => $message,
							'id' => $reureg
						);

						sendFCMMessage($data, $target);
					} else {
						sendIOSMessage($message, $id);
					}
				}
			}
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*

			if ($err == 'SQLACCEPT') {
				sql_commit_trans($trans);
				$response["ERROR"] 	 	= false;
				$response["MESSAGE"] 	= "La reunión fue cancelada, sin disponibilidad de mesas. ";
			} else {
				sql_rollback_trans($trans);
				$response["ERROR"] 	 	= true;
				$response["MESSAGE"] 	= "Error al aceptar la reunión ";
			}
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al aceptar la reunión ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// AGENDA
$app->post('/agenda', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Agenda"]	= array();

	$percodigo 	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
							PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL,
							PD.PERURLWEB AS PERWEBDST,PS.PERURLWEB AS PERWEBSOL,
							PD.PEREMPDES AS PERDESDST,PS.PEREMPDES AS PERDESSOL,
							R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,
							R.AGEREG,A.AGETITULO,A.AGELUGAR
					FROM REU_CABE R
					LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
					LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
					LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=R.AGEREG
					WHERE R.AGEREG IS NULL AND (R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo) AND R.REUESTADO=2 
					ORDER BY R.REUFECHA,R.REUHORA,R.REUREG ";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$percoddst 	= trim($row['PERCODDST']);
			$pernomdst	= trim($row['PERNOMDST']);
			$perapedst	= trim($row['PERAPEDST']);
			$percomdst	= trim($row['PERCOMDST']);
			$percordst	= trim($row['PERCORDST']);
			$peravadst	= trim($row['PERAVADST']);
			$perwebdst	= trim($row['PERWEBDST']);
			$perdesdst	= trim($row['PERDESDST']);

			$percodsol 	= trim($row['PERCODSOL']);
			$pernomsol	= trim($row['PERNOMSOL']);
			$perapesol	= trim($row['PERAPESOL']);
			$percomsol	= trim($row['PERCOMSOL']);
			$percorsol	= trim($row['PERCORSOL']);
			$peravasol	= trim($row['PERAVASOL']);
			$perwebsol	= trim($row['PERWEBSOL']);
			$perdessol	= trim($row['PERDESSOL']);

			$agereg		= trim($row['AGEREG']);
			$agetitulo	= trim($row['AGETITULO']);
			$agelugar	= trim($row['AGELUGAR']);

			$reufecha	= BDConvFch($row['REUFECHA']);
			$reuhora	= substr(trim($row['REUHORA']), 0, 5);
			$reuestado	= trim($row['REUESTADO']);
			$reureg		= trim($row['REUREG']);

			$dia = substr($reufecha, 0, 2);

			if ($percoddst == $percodigo) {
				$percod 	= $percodsol;
				$pernombre	= $pernomsol;
				$perapelli	= $perapesol;
				$percompan	= $percomsol;
				$percorreo	= $percorsol;
				$peravatar	= $peravasol;
				$perurlweb 	= $perwebsol;
				$perempdes 	= $perdessol;
			} else {
				$percod 	= $percoddst;
				$pernombre	= $pernomdst;
				$perapelli	= $perapedst;
				$percompan	= $percomdst;
				$percorreo	= $percordst;
				$peravatar	= $peravadst;
				$perurlweb 	= $perwebdst;
				$perempdes 	= $perdesdst;
			}

			switch (intval($dia)) {
				case 12:
					$reufecha = "MARTES $dia";
					break;
				case 13:
					$reufecha = "MIERCOLES $dia";
					break;
				case 14:
					$reufecha = "JUEVES $dia";
					break;
				case 15:
					$reufecha = "VIERNES $dia";
					break;
			}

			$reg = array();
			$reg['Reunion']			= $reureg;
			$reg['Fecha'] 			= $reufecha;
			$reg['Hora'] 			= $reuhora;
			$reg['Empresa'] 		= $percompan;
			$reg['Contacto'] 		= $perapelli . ' ' . $pernombre;

			array_push($response["Agenda"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// PRODUCTOS DE PERFIL
$app->post('/productos', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Detalle"]	= '';

	$percodigo 	= $data->PERCODIGO;
	$percoddst 	= $data->PERCODDST;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$detalle = '';

		//Busco todos los sectores que tiene el perfil
		$query = "	SELECT S.SECCODIGO,S.SECDESCRI
					FROM PER_SECT PS
					LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
					WHERE PS.PERCODIGO=$percoddst AND S.ESTCODIGO<>3 ";
		$TableSect = sql_query($query, $conn);
		for ($i = 0; $i < $TableSect->Rows_Count; $i++) {
			$rowSect = $TableSect->Rows[$i];
			$seccodigo = trim($rowSect['SECCODIGO']);
			$secdescri = trim($rowSect['SECDESCRI']);

			$detalle .= chr(13) . chr(10) . chr(13) . chr(10) . '' . $secdescri;

			//Busco si tiene un siguiente nivel / SubSector
			$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES
								FROM PER_SSEC PSB
								LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
								WHERE PSB.PERCODIGO=$percoddst AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 ";
			$TableSSect = sql_query($querySectSub, $conn);
			for ($j = 0; $j < $TableSSect->Rows_Count; $j++) {
				$rowSSect = $TableSSect->Rows[$j];
				$secsubcod = trim($rowSSect['SECSUBCOD']);
				$secsubdes = trim($rowSSect['SECSUBDES']);

				$detalle .= chr(13) . chr(10) . str_repeat(' ', 6) . $secsubdes;

				//Busco si tiene un siguiente nivel / Categorias
				$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI
								FROM PER_CATE PC
								LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
								WHERE PC.PERCODIGO=$percoddst AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 ";

				$TableCat = sql_query($queryCat, $conn);
				for ($k = 0; $k < $TableCat->Rows_Count; $k++) {
					$rowCat = $TableCat->Rows[$k];
					$catcodigo = trim($rowCat['CATCODIGO']);
					$catdescri = trim($rowCat['CATDESCRI']);

					$detalle .= chr(13) . chr(10) . str_repeat(' ', 12) . $catdescri;

					//Busco si tiene un siguiente nivel / SubCategorias
					$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
										FROM PER_SCAT PSC
										LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
										WHERE PSC.PERCODIGO=$percoddst AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 ";

					$TableCatSub = sql_query($queryCatSub, $conn);
					for ($m = 0; $m < $TableCatSub->Rows_Count; $m++) {
						$rowCatSub = $TableCatSub->Rows[$m];
						$catsubcod = trim($rowCatSub['CATSUBCOD']);
						$catsubdes = trim($rowCatSub['CATSUBDES']);

						$detalle .= chr(13) . chr(10) . str_repeat(' ', 18) . $catsubdes;
						//if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod])){
						//	$detalle .= chr(13).chr(10).str_repeat(' ',18).$catsubdes;	
						//}else{
						//	$detalle .= chr(13).chr(10).str_repeat(' ',18).$catsubdes;
						//}
					}
					if ($TableCatSub->Rows_Count == -1) {

						//if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo])){
						//$tmpl->setVariable('colorcategoria'	, $colormatch);
						//}
					}
				}
				//logerror($queryCat);
				//logerror($seccodigo.'-'.$secsubcod.'-'.$TableCat->Rows_Count);
				if ($TableCat->Rows_Count == -1) {
					//if(isset($perfilLog[$seccodigo][$secsubcod])){
					//$tmpl->setVariable('colorsubsector'	, $colormatch);
					//}
				}
			}

			if ($TableSSect->Rows_Count == -1) {
				//if(isset($perfilLog[$seccodigo])){
				//$tmpl->setVariable('colorsector'	, $colormatch);
				//}
			}
		}

		$response["Detalle"]	= $detalle;

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener productos ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// MARCAR FAVORITOS
$app->post('/favoritos', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 			= false;
	$response["MESSAGE"] 		= '';
	$response["EXCEPTION"] 		= '';

	$percodigo 	= $data->PERCODIGO;
	$percodfav 	= $data->PERCODFAV;

	try {
		$conn = sql_conectar(); //Apertura de Conexion	
		$trans	= sql_begin_trans($conn);

		//Busco si ya esta marcado como favorito
		$query = " SELECT PERCODFAV FROM PER_FAVO WHERE PERCODIGO=$percodigo AND PERCODFAV=$percodfav";

		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			//Elimino la relacion
			$query = " 	DELETE FROM PER_FAVO WHERE PERCODIGO=$percodigo AND PERCODFAV=$percodfav ";
			$err = sql_execute($query, $conn);
		} else {
			//Creo la relacion de favorito
			$query = " 	INSERT INTO PER_FAVO (PERCODIGO, PERCODFAV)
						VALUES ($percodigo, $percodfav)";
			$err = sql_execute($query, $conn);
		}

		if ($err == 'SQLACCEPT') {
			sql_commit_trans($trans);
		} else {
			sql_rollback_trans($trans);
			$response["ERROR"] 	 	= true;
			$response["MESSAGE"] 	= "Error al seleccionar el favorito";
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al seleccionar el favorito";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// PERFIL GUARDAR
$app->post('/perfilguardar', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["User"] 		= array();

	$percodigo 		= $data->PERCODIGO;
	$pernombre 		= (isset($data->PERNOMBRE)) ? trim($data->PERNOMBRE) : '';
	$perapelli 		= (isset($data->PERAPELLI)) ? trim($data->PERAPELLI) : '';
	$percargo 		= (isset($data->PERCARGO)) ? trim($data->PERCARGO) : '';
	$percompan 		= (isset($data->PERCOMPAN)) ? trim($data->PERCOMPAN) : '';
	$perarecod 		= (isset($data->PERARECOD)) ? trim($data->PERARECOD) : '';
	$perindcod 		= (isset($data->PERINDCOD)) ? trim($data->PERINDCOD) : '';
	$pertelefo 		= (isset($data->PERINDCOD)) ? trim($data->PERTELEFO) : '';
	$percpf 		= (isset($data->PERINDCOD)) ? trim($data->PERCPF) : '';

	try {
		$conn = sql_conectar(); //Apertura de Conexion
		if ($pernombre != '' && $percodigo != '' && $percodigo != '0') {
			$query = "	UPDATE PER_MAEST SET 
						PERNOMBRE='$pernombre', PERAPELLI='$perapelli', PERCARGO='$percargo',
						PERCOMPAN='$percompan', PERARECOD=$perarecod, PERINDCOD=$perindcod,
						PERTELEFO='$pertelefo', PERCPF='$percpf'
						WHERE PERCODIGO=$percodigo ";
			$err = sql_execute($query, $conn);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al guardar perfil ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
// PERFIL TARGETS GUARDAR
$app->post('/targetguardar', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["User"] 		= array();

	$percodigo 		= $data->PERCODIGO;
	$sectores 		= $data->SECTORES;

	try {
		$conn = sql_conectar(); //Apertura de Conexion

		$secarr = explode(",", $sectores);

		$query = "DELETE FROM PER_SECT WHERE PERCODIGO=$percodigo";
		$err = sql_execute($query, $conn);

		for ($i = 0; $i < count($secarr) - 1; $i++) {
			$seccodigo = $secarr[$i];
			$query = "INSERT INTO PER_SECT (PERCODIGO,SECCODIGO) VALUES($percodigo,$seccodigo)";
			$err = sql_execute($query, $conn);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al guardar targets de perfil ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// PREGUNTA DE ACTIVIDAD GUARDAR
$app->post('/actividadespregunta', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';

	$percodigo 		= $data->PERCODIGO;
	$agereg 		= (isset($data->AGEREG)) ? trim($data->AGEREG) : '';
	$pregunta 		= (isset($data->PREGUNTA)) ? trim($data->PREGUNTA) : '';

	try {
		$conn = sql_conectar(); //Apertura de Conexion

		if (trim($pregunta) != '') {
			$query = "	INSERT INTO AGE_PREG (AGEREG,AGEPREITM,PERCODIGO,AGEPREGUN)
						VALUES($agereg,GEN_ID(G_AGEPREGITM,1),$percodigo,'$pregunta') ";
			$err = sql_execute($query, $conn);
		}
		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al guardar la pregunta ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
//PARAMETROS
$app->post('/getparametros', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response = array();
	$response["ERROR"] 	 	= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Parametros"]	= array();

	try {
		$conn = sql_conectar(); //Apertura de Conexion				

		$query  = "	SELECT PARREG,PARCODIGO,PARVALOR,PARORDEN,PARTIPO,PERTIPO FROM PAR_MAEST ORDER BY PARORDEN ";
		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$parreg 	= trim($row['PARREG']);
			$parcodigo 	= trim($row['PARCODIGO']);
			$parvalor 	= trim($row['PARVALOR']);
			$parorden 	= trim($row['PARORDEN']);
			$partipo 	= trim($row['PARTIPO']);
			$pertipo 	= trim($row['PERTIPO']);

			$par = array();
			$par['Reg'] 		= $parreg;
			$par['Codigo'] 		= $parcodigo;
			$par['Valor'] 		= $parvalor;
			$par['Orden'] 		= $parorden;
			$par['Tipo'] 		= $partipo;
			$par['TipoPerfil']	= $pertipo;

			array_push($response["Parametros"], $par);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al obtener parametros";
		$response["EXCEPTION"] 	=  $e->getMessage();
		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// FUNCION DE REGISTRACION DE DISPOSITIVO PARA NOTIFICACIONES -----------------------------------
$app->post('/register', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["error"] = false;

	$uid 		= $data->uid;
	$provider 	= $data->provider;
	$id 		= $data->id;
	$percodigo	= $data->PERCODIGO;

	if (trim($percodigo) == '') $percodigo = 0;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		
		//Levanto los IDs
		$query = "	SELECT ID 
					FROM NOT_REGI 
					WHERE TRIM(PROVIDER)=TRIM('$provider') AND ID='$id' ";
		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			$query = "	UPDATE NOT_REGI SET PERCODIGO=$percodigo 
						WHERE TRIM(PROVIDER)=TRIM('$provider') AND ID='$id' ";
			$err	= sql_execute($query, $conn);
		} else {
			$query = "	INSERT INTO NOT_REGI (ID,PROVIDER,UID,PERCODIGO)
						VALUES('$id',TRIM('$provider'),'$uid',$percodigo)";
			$err	= sql_execute($query, $conn);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["error"] 	 	= true;
		$response["MESSAGE"] 	= "Error al registrar dispositivo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// QR LECTOR
$app->post('/qrlector', function ()  use ($app) {
	//10 POR POR PERSONAS Y ORADORES
	//30 POR AGENDA
	//50 POR PERFIL ASOCIADO A UN SPONSOR EXPOSITOR

	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';

	$percodigo 	= $data->PERCODIGO;
	$qrcodigo 	= $data->QRCODIGO;
	$qrtipo 	= $data->QRTIPO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$perqrcod = 0;
		$perqrage = 0;

		if ($qrtipo == 100) { //Escaneo de Perfil
			$perqrcod = $qrcodigo;
			$qrpuntos = 10;

			//Busco si el perfil escaneado corresponde a un expositor
			//ya que el puntaje es superior
			$query = "SELECT EXPREG FROM EXP_MAEST WHERE PERCODIGO=$perqrcod ";
			$Table = sql_query($query, $conn);
			if ($Table->Rows_Count > 0) {
				$qrpuntos = 50;
			}

			//Busco si es un Perfil especial
			if (strpos('/1006/1027/2003/2005/2008/2010/2017/2026/2026/2033/3364/3441/3442/3473/3488/3512/3519/3546/3596/3719/3777/3788/3828/3834/3835/3851/5055/5163/5284/5343/', '/' . $perqrcod . '/') === false) {
			} else {
				$qrpuntos = 50;
			}
		} else if ($qrtipo == 200) { //Escaneo de Agenda
			$perqrage = $qrcodigo;
			$qrpuntos = 30;
		}

		//Alimino el registro por si ya fue leido el QR del perfil/agenda
		$query = "DELETE FROM PER_QR WHERE PERCODIGO=$percodigo AND PERQRPER=$perqrcod AND PERQRAGE=$perqrage";
		$err = sql_execute($query, $conn);

		$query = "	INSERT INTO PER_QR (PERCODIGO, PERQRITM, PERQRPER, PERQRAGE, PERQRFCH, PERQRPUN) 
					VALUES ($percodigo, GEN_ID(G_PERQRITEM,1), $perqrcod, $perqrage, CURRENT_TIMESTAMP, $qrpuntos)";
		$err = sql_execute($query, $conn);


		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al guardar el escaneo ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// QR AGENDADOS
$app->post('/qragenda', function ()  use ($app) {
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response = array();
	$response["ERROR"] 	 	= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["QRAgenda"]	= array();

	$percodigo	= $data->PERCODIGO;
	$fltbuscar 	= (isset($data->FLTBUSCAR)) ? trim($data->FLTBUSCAR) : '';

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$where = '';
		if ($fltbuscar != "") {
			$where = " AND (P.PERNOMBRE CONTAINING '$fltbuscar' OR P.PERAPELLI CONTAINING '$fltbuscar' OR P.PERCOMPAN CONTAINING '$fltbuscar' )";
		}


		$query  = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERTELEFO,P.PERCORREO,P.PERCARGO
					FROM PER_QR Q
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=Q.PERQRPER
					WHERE Q.PERCODIGO=$percodigo AND Q.PERQRAGE=0 $where
					ORDER BY Q.PERQRFCH DESC ";

		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$percodigo	= trim($row['PERCODIGO']);
			$pernombre	= trim($row['PERNOMBRE']);
			$perapelli	= trim($row['PERAPELLI']);
			$percompan	= trim($row['PERCOMPAN']);
			$pertelefo	= trim($row['PERTELEFO']);
			$percorreo	= trim($row['PERCORREO']);
			$percargo	= trim($row['PERCARGO']);

			$per = array();
			$per['Reg'] 		= $percodigo;
			$per['Nombre'] 		= $pernombre;
			$per['Apellido'] 	= $perapelli;
			$per['Compania'] 	= $percompan;
			$per['Telefono'] 	= $pertelefo;
			$per['Correo'] 		= $percorreo;
			$per['Cargo'] 		= $percargo;

			array_push($response["QRAgenda"], $per);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["error"] 	 	= true;
		$response["MESSAGE"] 	= "Error en agendados ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// QR DETALLE DE PUNTOS
$app->post('/qrpuntosdetalle', function ()  use ($app) {
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response = array();
	$response["ERROR"] 	 	= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["QRPuntos"]	= array();

	$percodigo	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$query  = "	SELECT Q.PERQRPER,Q.PERQRAGE,Q.PERQRPUN,A.AGETITULO,E.EXPREG
					FROM PER_QR Q
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=Q.PERQRPER
					LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=Q.PERQRAGE
					LEFT OUTER JOIN EXP_MAEST E ON E.PERCODIGO=Q.PERQRPER AND Q.PERQRPER<>0
					WHERE Q.PERCODIGO=$percodigo 
					ORDER BY Q.PERQRFCH DESC ";

		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$perqrper	= trim($row['PERQRPER']);
			$perqrage	= trim($row['PERQRAGE']);
			$perqrpun	= trim($row['PERQRPUN']);
			$agetitulo	= trim($row['AGETITULO']);
			$expreg		= trim($row['EXPREG']);
			$motivo		= '';

			//Si el puntaje es un perfil
			if ($perqrper != 0) {
				if ($expreg != '') {
					$motivo = 'Patrocinador';
				} else {
					$motivo = 'Contato adicionado';
				}
			} elseif ($perqrage != 0) {
				$motivo = 'Actividad';
			}

			$reg = array();
			$reg['Motivo'] 		= $motivo;
			$reg['Puntos'] 		= $perqrpun;

			array_push($response["QRPuntos"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["error"] 	 	= true;
		$response["MESSAGE"] 	= "Error en obtener puntos ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// QR RANKING DE PUNTOS
$app->post('/qrpuntosranking', function ()  use ($app) {
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response = array();
	$response["ERROR"] 	 	= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["QRPuntosRanking"]	= array();

	$percodigo	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$query  = "	SELECT FIRST 5 P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERQRPUN
					FROM PER_MAEST P
					WHERE P.PERQRPUN<>0
					ORDER BY P.PERQRPUN DESC ";

		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$pernombre	= trim($row['PERNOMBRE']);
			$perapelli	= trim($row['PERAPELLI']);
			$percompan	= trim($row['PERCOMPAN']);
			$perqrpun	= trim($row['PERQRPUN']);

			$reg = array();
			$reg['Nombre'] 		= $pernombre;
			$reg['Apellido']	= $perapelli;
			$reg['Compania']	= $percompan;
			$reg['Puntos']		= $perqrpun;

			array_push($response["QRPuntosRanking"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["error"] 	 	= true;
		$response["MESSAGE"] 	= "Error en obtener ranking de puntos ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// QR PUNTAJE TOTAL
$app->post('/qrpuntostotales', function ()  use ($app) {
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response = array();
	$response["PuntosTotales"]	= 0;
	$response["Posicion"]		= '';

	$percodigo	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		//Busco el puntaje total
		$query  = "	SELECT P.PERQRPUN
					FROM PER_MAEST P
					WHERE P.PERCODIGO=$percodigo ";

		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$perqrpun	= trim($row['PERQRPUN']);
			$response["PuntosTotales"]	= $perqrpun;
		}

		//Cantidad de Perfiles
		$pertotal = 0;

		$query = "	SELECT COUNT(*) AS PERTOTAL
					FROM PER_MAEST P
					WHERE P.PERQRPUN<>0";
		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$pertotal	= trim($row['PERTOTAL']);
		}

		//Busco la posicion
		$posicion = 0;
		$query = "	SELECT PERCODIGO,PERQRPUN
					FROM PER_MAEST P 
					WHERE P.PERQRPUN<>0
					ORDER BY P.PERQRPUN DESC ";
		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$percod	= trim($row['PERCODIGO']);
			if ($percodigo == $percod) {
				$posicion = $i + 1;
				$i = $Table->Rows_Count;
			}
		}

		$response["Posicion"] = 'POSICION ' . $posicion . ' / ' . $pertotal;

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["error"] 	 	= true;
		$response["MESSAGE"] 	= "Error en obtener el total de puntos ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// DESAFIO GUARDAR
$app->post('/desafioguardar', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';

	$percodigo 		= $data->PERCODIGO;
	$desreg 		= (isset($data->DESREG)) ? trim($data->DESREG) : 0;
	$perarecod 		= (isset($data->PERARECOD)) ? trim($data->PERARECOD) : '';
	$destitulo 		= (isset($data->DESTITULO)) ? trim($data->DESTITULO) : '';
	$desdescri 		= (isset($data->DESDESCRI)) ? trim($data->DESDESCRI) : '';
	$sectores 		= (isset($data->SECTORES)) ? trim($data->SECTORES) : '';

	try {
		$conn = sql_conectar(); //Apertura de Conexion
		$trans	= sql_begin_trans($conn);

		//NO existe el desafio, lo creo
		if ($desreg == 0) {
			//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
			//Genero un ID 
			$query 		= 'SELECT GEN_ID(G_DESAFIOS,1) AS ID FROM RDB$DATABASE';
			$TblId		= sql_query($query, $conn, $trans);
			$RowId		= $TblId->Rows[0];
			$desreg 	= trim($RowId['ID']);
			//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

			$query = "	INSERT INTO DES_CABE(DESREG,DESFCHREG,PERCODIGO,DESTITULO,DESDESCRI,PERARECOD,ESTCODIGO)
						VALUES($desreg,CURRENT_TIMESTAMP,$percodigo,'$destitulo','$desdescri',$perarecod,1) ";
			$err = sql_execute($query, $conn, $trans);
		} else {
			$query = "	UPDATE DES_CABE SET
						DESTITULO='$destitulo', DESDESCRI='$desdescri', PERARECOD=$perarecod
						WHERE DESREG=$desreg ";
			$err = sql_execute($query, $conn, $trans);
		}

		$secarr = explode(",", $sectores);

		$query = "DELETE FROM DES_SECT WHERE DESREG=$desreg";
		$err = sql_execute($query, $conn, $trans);

		for ($i = 0; $i < count($secarr) - 1; $i++) {
			$seccodigo = $secarr[$i];
			$query = "INSERT INTO DES_SECT (DESREG,SECCODIGO) VALUES($desreg,$seccodigo)";
			$err = sql_execute($query, $conn, $trans);
		}

		if ($err == 'SQLACCEPT') {
			sql_commit_trans($trans);
		} else {
			sql_rollback_trans($trans);
			$response["ERROR"] 	 	= true;
			$response["MESSAGE"] 	= "Error al registrar el desafio ";
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al guardar el desafio ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// DESAFIO ELIMINAR
$app->post('/desafioeliminar', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';

	$percodigo 		= $data->PERCODIGO;
	$desreg 		= (isset($data->DESREG)) ? trim($data->DESREG) : 0;

	try {
		$conn = sql_conectar(); //Apertura de Conexion
		$trans	= sql_begin_trans($conn);

		$query = "UPDATE DES_CABE SET ESTCODIGO=3 WHERE DESREG=$desreg";
		$err = sql_execute($query, $conn, $trans);

		if ($err == 'SQLACCEPT') {
			sql_commit_trans($trans);
		} else {
			sql_rollback_trans($trans);
			$response["ERROR"] 	 	= true;
			$response["MESSAGE"] 	= "Error al eliminar el desafio ";
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al eliminar el desafio ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// DESAFIOS PROPIOS
$app->post('/desafios', function ()  use ($app) {
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response = array();
	$response["ERROR"] 	 	= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';
	$response["Desafios"]	= array();

	$percodigo	= $data->PERCODIGO;
	$flttipo	= $data->FLTTIPO;
	$fltbuscar	= $data->FLTBUSCAR;
	$fltarecod 	= (isset($data->FLTARECOD)) ? trim($data->FLTARECOD) : 0;
	$fltindcod 	= (isset($data->FLTINDCOD)) ? trim($data->FLTINDCOD) : 0;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		$where = '';
		switch ($flttipo) {
			case 1: //Individuales
				$where = " AND P.PERTIPO=1 AND D.PERCODIGO<>$percodigo ";
				break;
			case 2: //Open Innovation Arena
				$where = " AND P.PERTIPO=2 ";
				break;
			case 3: //Propios
				$where = " AND D.PERCODIGO=$percodigo ";
				break;
		}

		if (trim($fltbuscar) != '') {
			$where .= " AND (D.DESTITULO CONTAINING '$fltbuscar' OR D.DESDESCRI CONTAINING '$fltbuscar') ";
		}

		if ($fltarecod != 0) {
			$where .= " AND P.PERARECOD=$fltarecod ";
		}

		if ($fltindcod != 0) {
			$where .= " AND EXISTS(SELECT 1 
									FROM PER_SECT PS
									WHERE PS.PERCODIGO=P.PERCODIGO AND PS.SECCODIGO=$fltindcod) ";
		}

		$query  = "	SELECT D.DESREG,D.DESTITULO,D.DESDESCRI,D.PERARECOD,A.PERAREDESESP AS PERAREDES,
							D.PERCODIGO,P.PERCOMPAN
					FROM DES_CABE D
					LEFT OUTER JOIN PER_AREA A ON A.PERARECOD=D.PERARECOD
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=D.PERCODIGO
					WHERE D.ESTCODIGO=1 $where
					ORDER BY D.DESREG DESC ";

		$Table 	= sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$desreg	= trim($row['DESREG']);
			$destitulo	= trim($row['DESTITULO']);
			$desdescri	= trim($row['DESDESCRI']);
			$perarecod	= trim($row['PERARECOD']);
			$peraredes	= trim($row['PERAREDES']);
			$percodigo	= trim($row['PERCODIGO']);
			$percompan	= trim($row['PERCOMPAN']);

			$reg = array();
			$reg['Reg'] 			= $desreg;
			$reg['PerCodigo']		= $percodigo;
			$reg['Compania']		= $percompan;
			$reg['Titulo'] 			= $destitulo;
			$reg['Descripcion'] 	= $desdescri;
			$reg['AreaCodigo']		= $perarecod;
			$reg['AreaDescripcion']	= $peraredes;
			$reg['Sectores']		= array();

			//Busco los sectores del desafio
			$query  = "	SELECT S.SECCODIGO,S.SECDESCRI
						FROM DES_SECT D
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=D.SECCODIGO
						WHERE D.DESREG=$desreg ";

			$TableSect 	= sql_query($query, $conn);
			for ($j = 0; $j < $TableSect->Rows_Count; $j++) {
				$rowSect = $TableSect->Rows[$j];
				$seccodigo	= trim($rowSect['SECCODIGO']);
				$secdescri	= trim($rowSect['SECDESCRI']);

				$sec = array();
				$sec['Codigo'] 		= $seccodigo;
				$sec['Descripcion'] = $secdescri;
				array_push($reg["Sectores"], $sec);
			}

			array_push($response["Desafios"], $reg);
		}

		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["error"] 	 	= true;
		$response["MESSAGE"] 	= "Error en obtener puntos ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});
//-----------------------------------------------------------------------------------------------
// ENVIAR CONTACTOS POR MAIL
$app->post('/qrsendcontactos', function ()  use ($app) {
	$response = array();
	$json = $app->request->getBody();
	$data = convertJsonObject($json);

	$response["ERROR"] 		= false;
	$response["MESSAGE"] 	= '';
	$response["EXCEPTION"] 	= '';

	$percodigo 	= $data->PERCODIGO;

	try {
		$conn = sql_conectar(); //Apertura de Conexion		

		//Busco el correo del perfil
		$query = "SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percodigo ";
		$Table = sql_query($query, $conn);
		$row = $Table->Rows[0];
		$percorreo 	= trim($row['PERCORREO']);

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true;

		$mail->CharSet = 'UTF-8';
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;

		$mail->Username 	= SendMailUsuario;
		$mail->Password 	= SendMailPass;

		$mail->FromName 	= SendMailNombre;
		$mail->From			= SendMailUsuario;
		$mail->AddReplyTo(SendMailUsuario, SendMailNombre);

		$mail->IsHTML(true);    // set email format to HTML	
		logerror($percorreo);
		$mail->AddAddress($percorreo, '');
		$mail->Subject = 'Contactos Escaneados';
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		//$mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
		//$mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');

		$mail->Body = '<!DOCTYPE html>
							<html lang="en" class="loading">
								<head>
									<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
								</head>
    
							<body>
							<div style="text-align:center">
								<img src="cid:imglogo" alt="image.png" style="margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								<!--app-assets/img/logo-light.png
								-->
								<br>
							</div> 
							<br>
							<div dir="ltr">
								<div style="text-align:center">
									<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
										<div style="text-align:center">CONTACTOS ESCANEADOS</div>
									</font>
								</div>
							</div>
							<br>';



		//Busco los contactos escaneados del perfil
		$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERTELEFO,P.PERCORREO,P.PERCARGO
					FROM PER_QR Q
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=Q.PERQRPER
					WHERE Q.PERCODIGO=$percodigo AND Q.PERQRAGE=0
					ORDER BY Q.PERQRFCH DESC";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$percompan 	= trim($row['PERCOMPAN']);
			$pertelefo 	= trim($row['PERTELEFO']);
			$percorreo 	= trim($row['PERCORREO']);
			$percargo 	= trim($row['PERCARGO']);

			$mail->Body .= '<div style="text-align:center;">
								<b>' . $perapelli . ' ' . $pernombre . '</b>
								<br>Empresa: ' . $percompan . '
								<br>Telefono: ' . $pertelefo . '
								<br>Correo: ' . $percorreo . '
								<br>Cargo: ' . $percargo . '
							</div><br><br>';
		}

		$mail->Body .= '</body></html>';

		logerror($mail->Body);

		$mail->Send();


		sql_close($conn);
		echoRespnse(200, $response);
	} catch (Exception $e) {
		$response["ERROR"] 	 	= true;
		$response["MESSAGE"] 	= "Error al enviar contactos ";
		$response["EXCEPTION"] 	=  $e->getMessage();

		echoRespnse(400, $response);
	}
});

//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
$app->run();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/**
 * Devuelve la respuesta del API en forma de Json.
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response)
{
	//obtengo una instancia de la aplicacion
	$app = \Slim\Slim::getInstance();
	// Seteo el codigo HTTP
	$app->status($status_code);
	//Seteo la respuesta como Json
	$app->contentType('application/json');
	$responsestring = iconv("UTF-8", "ISO-8859-1", json_encode($response));
	//codifico el array como json.
	echo json_encode($response);
}

function convertJsonObject($data)
{
	$arrgral = explode("&", $data);
	$arr = null;
	foreach ($arrgral as $valgral) {
		$arrint = explode("=", $valgral);
		$valor = $arrint[1];
		//$valor = str_replace('%40', '@', $arrint[1]);
		$valor = urldecode($valor);
		$arr[$arrint[0]] = $valor;
	}

	$json = json_encode($arr, JSON_FORCE_OBJECT);
	$json = json_decode($json);
	return $json;
}


function LogException(Exception $ex)
{
	$logerror = "ERROR DE SINCRONIZACION : " . $ex->getMessage() . " \n Detalle de la excepcion: Codigo ==>" . $ex->getCode() . " \nLinea ==>" . $ex->getLine() . " \nPila de Llamadas ==>" . $ex->getTraceAsString();
	logerror($logerror);
}

class BenvidoException extends Exception
{
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function sendMailSolicitarReunion($correodst, $percompan, $pernombre, $perapelli)
{
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
	//Envio Mail de Reunion Solicitada al Destino
	if ($correodst != '' && strpos($correodst, '@') !== false) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true;

		$mail->CharSet = 'UTF-8';
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;

		$mail->Username 	= SendMailUsuario;
		$mail->Password 	= SendMailPass;

		$mail->FromName 	= SendMailNombre;
		$mail->From			= SendMailUsuario;
		$mail->AddReplyTo(SendMailUsuario, SendMailNombre);

		$mail->IsHTML(true);    // set email format to HTML	

		$mail->AddAddress($correodst, '');
		$mail->Subject = 'Reunión solicitada! | Meeting requested!';
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		$mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
		$mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');

		$mail->Body = '<!DOCTYPE html>
						<html lang="en" class="loading">
							<head>
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
							</head>
    
						<body>
						<div style="text-align:center">
							<img src="cid:imglogo" alt="image.png" style="margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
							<!--app-assets/img/logo-light.png
							-->
							<br>
						</div>
						<div dir="ltr">
							<div style="text-align:center">
								<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
									<div style="text-align:center">' . $pernombre . ' ' . $perapelli . ' de la empresa ' . $percompan . ' desea agendar una reunión con usted. Ingrese en la sección de  Reuniones Recibidas para coordinar el horario. Para aceptar esta invitacion descargue la app Argentina Fintech Forum desde su playstore o applestore.  </div>
								</font>
							</div>
						</div>
						<br>
						<div dir="ltr">
							<div style="text-align:center">
								<a href="' . LinkPlayStore . '" target="_blank">
									<img src="cid:icoplaystore" alt="icoplaystore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								<a>
								&nbsp;&nbsp;
								<a href="' . LinkAppStore . '" target="_blank">
									<img src="cid:icoappstore" alt="icoappstore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								</a>
							</div>
						</div>
						</body>
						</html>';
		$mail->Send();
	}
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
}


function sendMailConfirmarReunion($correodst, $percompan, $pernombre, $perapelli)
{
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
	//Envio Mail de Reunion COnfirmada al Destino
	if ($correodst != '' && strpos($correodst, '@') !== false) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true;

		$mail->CharSet = 'UTF-8';
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;

		$mail->Username 	= SendMailUsuario;
		$mail->Password 	= SendMailPass;

		$mail->FromName 	= SendMailNombre;
		$mail->From			= SendMailUsuario;
		$mail->AddReplyTo(SendMailUsuario, SendMailNombre);

		$mail->IsHTML(true);    // set email format to HTML	

		$mail->AddAddress($correodst, '');
		$mail->Subject = 'Reunión solicitada! | Meeting requested!';
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		$mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
		$mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');


		$mail->Body = '<!DOCTYPE html>
							<html lang="en" class="loading">
								<head>
									<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
								</head>
    
							<body>
							<div style="text-align:center">
								<img src="cid:imglogo" alt="image.png" style="margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								<!--app-assets/img/logo-light.png
								-->
								<br>
							</div>
							<div dir="ltr">
								<div style="text-align:center">
									<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
										<div style="text-align:center">' . $pernombre . ' ' . $perapelli . ' de la empresa ' . $percompan . ' confirmó la reunión solicitad. Ingrese en la sección de Reuniones Agendadas para revisar el horario.</div>
									</font>
								</div>
							</div>
							<br>
							<div dir="ltr">
								<div style="text-align:center">
								</div>
							</body>
							</html>';
		$mail->Send();
	}
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
}


function sendMailCancelarReunion($correodst, $percompan, $pernombre, $perapelli)
{
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
	//Envio Mail de Reunion Cancelada al Destino
	if ($correodst != '' && strpos($correodst, '@') !== false) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true;

		$mail->CharSet = 'UTF-8';
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;

		$mail->Username 	= SendMailUsuario;
		$mail->Password 	= SendMailPass;


		$mail->FromName 	= SendMailNombre;
		$mail->From			= SendMailUsuario;
		$mail->AddReplyTo(SendMailUsuario, SendMailNombre);

		$mail->IsHTML(true);    // set email format to HTML	

		$mail->AddAddress($correodst, '');
		$mail->Subject = 'Reunión Cancelada! | Meeting requested!';
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		$mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
		$mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');


		$mail->Body = '<!DOCTYPE html>
							<html lang="en" class="loading">
								<head>
									<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
								</head>
    
							<body>
							<div style="text-align:center">
								<img src="cid:imglogo" alt="image.png" style="margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								<!--app-assets/img/logo-light.png
								-->
								<br>
							</div>
							<div dir="ltr">
								<div style="text-align:center">
									<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
										<div style="text-align:center">' . $pernombre . ' ' . $perapelli . ' de la empresa ' . $percompan . ' ha <font color="red">cancelado</font> la reunión.</div>
									</font>
								</div>
							</div>
							<br>
							<div dir="ltr">
								<div style="text-align:center">
									<a href="' . LinkPlayStore . '" target="_blank">
										<img src="cid:icoplaystore" alt="icoplaystore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
									<a>
									&nbsp;&nbsp;
									<a href="' . LinkAppStore . '" target="_blank">
										<img src="cid:icoappstore" alt="icoappstore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
									</a>
								</div>
							</div>
							</body>
					</html>';
		$mail->Send();
	}
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
}

function sendMailCancelarReunionNoMesas($correodst, $percompan)
{
	////-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
	//Envio Mail de Reunion Cancelada al Destino por falta de disponibildiad de mesas
	if ($correodst != '' && strpos($correodst, '@') !== false) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true;

		$mail->CharSet = 'UTF-8';
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;

		$mail->Username 	= SendMailUsuario;
		$mail->Password 	= SendMailPass;


		$mail->FromName 	= SendMailNombre;
		$mail->From			= SendMailUsuario;
		$mail->AddReplyTo(SendMailUsuario, SendMailNombre);

		$mail->IsHTML(true);    // set email format to HTML	

		$mail->AddAddress($correodst, '');
		$mail->Subject = 'Reunión Cancelada! | Meeting requested!';
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		$mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
		$mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');


		$mail->Body = '<!DOCTYPE html>
							<html lang="en" class="loading">
								<head>
									<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
								</head>
    
							<body>
							<div style="text-align:center">
								<img src="cid:imglogo" alt="image.png" style="margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								<!--app-assets/img/logo-light.png
								-->
								<br>
							</div>
							<div dir="ltr">
								<div style="text-align:center">
									<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
										<div style="text-align:center">La empresa ' . $percompan . ' al confirmar la reunión, no hay disponibilidad de mesas. Se <font color="red">cancela</font> la reunión.</div>
									</font>
								</div>
							</div>
							<br>
							<div dir="ltr">
								<div style="text-align:center">
									<a href="' . LinkPlayStore . '" target="_blank">
										<img src="cid:icoplaystore" alt="icoplaystore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
									<a>
									&nbsp;&nbsp;
									<a href="' . LinkAppStore . '" target="_blank">
										<img src="cid:icoappstore" alt="icoappstore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
									</a>
								</div>
							</div>
							</body>
						</html>';
		$mail->Send();
	}
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
}


function sendMailChat($percodigo, $percoddst)
{
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
	//Envio Mail de primer contacto de Chat
	$conn = sql_conectar(); //Apertura de Conexion		

	$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI FROM PER_MAEST WHERE PERCODIGO=$percodigo ";
	$TableSol = sql_query($query, $conn);
	$query = "	SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percoddst AND PERCORREO IS NOT NULL ";
	$TableDst = sql_query($query, $conn);
	if ($TableDst->Rows_Count > 0) {
		$correodst = trim($TableDst->Rows[0]['PERCORREO']); //Correo Destino
		$compansol = trim($TableSol->Rows[0]['PERCOMPAN']); //Empresa del Solicitante
		$pernomsol = trim($TableSol->Rows[0]['PERNOMBRE']);
		$perapesol = trim($TableSol->Rows[0]['PERAPELLI']);
	}

	if ($correodst != '' && strpos($correodst, '@') !== false) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true;

		$mail->CharSet = 'UTF-8';
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;

		$mail->Username 	= SendMailUsuario;
		$mail->Password 	= SendMailPass;

		$mail->FromName 	= SendMailNombre;
		$mail->From			= SendMailUsuario;
		$mail->AddReplyTo(SendMailUsuario, SendMailNombre);

		$mail->IsHTML(true);    // set email format to HTML	

		$mail->AddAddress($correodst, '');
		$mail->Subject = 'Conversacion solicitada!';
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		$mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
		$mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');

		$mail->Body = '<!DOCTYPE html>
							<html lang="en" class="loading">
								<head>
									<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
								</head>

							<body>
							<div style="text-align:center">
								<img src="cid:imglogo" alt="image.png" style="margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								<!--app-assets/img/logo-light.png
								-->
								<br>
							</div>
							<div dir="ltr">
								<div style="text-align:center">
									<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
										<div style="text-align:center">' . $pernomsol . ' ' . $perapesol . ' de la empresa ' . $compansol . ' desea contactarse por chat con usted. Por favor descargue la app Argentina Fintech Forum desde su playstore o applestore. </div>
									</font>
								</div>
							</div>
							<br>
							<div dir="ltr">
								<div style="text-align:center">
									<a href="' . LinkPlayStore . '" target="_blank">
										<img src="cid:icoplaystore" alt="icoplaystore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
									<a>
									&nbsp;&nbsp;
									<a href="' . LinkAppStore . '" target="_blank">
										<img src="cid:icoappstore" alt="icoappstore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
									</a>
								</div>
							</div>
							</body>
						</html>';
		$mail->Send();
	}

	sql_close($conn);
	//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
}
?>





