<?php
	if(!isset($_SESSION))  session_start();
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('presentacionMobile.html');
	
	//--------------------------------------------------------------------------------------------------------------
	$valcode = substr(md5('OnLifeAccesoAppMobile'),0,10).substr(md5('MobileApp'),5,10).substr(md5('oNlIFEApplication'),8,20).md5('OnLifeAccesoApplication');
	//Ej:MXx8T25MaWZlQXBwQ01TQnVzaW5lc3NSZXZvbHV0aW9uTW9iaWxlUmVnaXN0cmFjaW9u
	$datainfo = (isset($_GET['P']))? trim($_GET['P']) : '';
	$tmpl->setVariable('datainfo'	, $datainfo);
	
	if($datainfo!=''){
		$datainfo = str_replace($valcode,'',$datainfo);
		$data = base64_decode($datainfo);
		
		$vdata = explode('||',$data);
		$percodlog = $vdata[0];//CODIGO DE PERFIL

		$conn= sql_conectar();//Apertura de Conexion

		$query = "	SELECT EP.ENCREG, EC.ENCREG, EC.ENCDESCRI,EP.ENCPREITM, EP.ENCPREGUN, EP.ENCPRETIP, EP.ENCPREVAL,PM.ENCRES
						FROM ENC_CABE EC
						LEFT OUTER JOIN ENC_PREG EP ON EP.ENCREG=EC.ENCREG
						LEFT OUTER JOIN PER_MAEST PM ON PM.PERCODIGO=$percodlog
						WHERE EC.ESTCODIGO=1 AND EC.ENCPUBLIC='S' AND EP.ESTCODIGO<>3 
						ORDER BY EP.ENCPREORD ";
		
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$encreg 	= trim($row['ENCREG']);
			// logerror($encreg);
			$encpreitm 	= trim($row['ENCPREITM']);
			$encdescri 	= trim($row['ENCDESCRI']);
			$encpretip 	= trim($row['ENCPRETIP']);
			// logerror($encpretip);
			$encpregun 	= trim($row['ENCPREGUN']);

			$encpreval 	= trim($row['ENCPREVAL']);
			$encpreval 	= trim($row['ENCPREVAL']);
			$encres 	= trim($row['ENCRES']);
			
			if ($encres==2) {
				$tmpl->setVariable('Msg'	, 'La encuesta ya fue contestada');
				$tmpl->setVariable('Msgbutton'	, 'disabled');
				$tmpl->setVariable('Msgbutton'	, 'disabled');
				header("Location: presentaciones.php?P=".$datainfo);
				exit;
			}else {
				//$tmpl->setVariable('styletitle'	, 'display:none');
			}
			$tmpl->setCurrentBlock('preguntas');

			if ($encpretip==1) {
				$tmpl->setVariable('pregtip1'	, 'display:visible');
				$tmpl->setVariable('pregtip2'	, 'display:none');
				$tmpl->setVariable('pregtip3'	, 'display:none');
			}
			if($encpretip==2){
				$tmpl->setVariable('pregtip1'	, 'display:none');
				$tmpl->setVariable('pregtip2'	, 'display:visible');
				$tmpl->setVariable('pregtip3'	, 'display:none');
				$vopciones= explode(",",$encpreval);

				foreach ($vopciones as $key => $value) {
					$tmpl->setCurrentBlock('preval');
					$tmpl->setVariable('encpreval'	, $value);
					$tmpl->parse('preval');
				}
			}
			if ($encpretip==3) {
				$tmpl->setVariable('pregtip1'	, 'display:none');
				$tmpl->setVariable('pregtip2'	, 'display:none');
				$tmpl->setVariable('pregtip3'	, 'display:visible');

				$tmpl->setCurrentBlock('jsclasificar');
				$tmpl->setVariable('encpreitmcla', $encpreitm);
				$tmpl->setVariable('encpreval'	, $encpreval);
				$tmpl->parse('jsclasificar');

			}


			$tmpl->setVariable('encreg'	, $encreg);
			$tmpl->setVariable('encdescri'	, $encdescri);

			$tmpl->setVariable('encpreitm'	, $encpreitm);
			$tmpl->setVariable('encpregun'	, $encpregun);

			$tmpl->parse('preguntas');
		}
		
		//Si aun no esta habilitada la encuesta
		if($Table->Rows_Count==-1){
			$tmpl->setVariable('Msg'	, 'No fim do evento você poderá acessar o conteúdo logo depois de completar uma breve pesquisa.
											<br><br>Esperamos que esteja aproveitando ao máximo a experiência de um evento com a marca CMS. Obrigado por fazer parte!');
			$tmpl->setVariable('Msgbutton'	, 'style="display:none;"');
		}else{
			$tmpl->setVariable('Msg'	, '2020 ESTA AÍ E NÓS QUEREMOS TE OUVIR!
											<br><br>
											Em 2019 completamos 15 anos e você esteve presente em nossa festa. É sempre um privilégio ter você conosco! Preparamos tudo para que você pudesse viver o máximo da experiência de um grande evento, repleto de conteúdo, dinâmicas e oportunidades.
											Mas agora precisamos te ouvir. Afinal, sim, nós já estamos nos primeiros momentos da organização do Business Revolution 2020. O céu é o limite!
											Portanto pedimos a sua gentileza em preencher esse relatório com o máximo de informações possíveis. Vamos lá? Muito obrigado!');
			
		}		
	}

	sql_close($conn);

	$tmpl->show();
	
?>	
