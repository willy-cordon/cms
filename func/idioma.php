<?php
/**
 * Sistema Cambio de idioma
 * 
 * @if Si no esta seteado "language" por GET, Estrablesco el idioma por defecto.
 * @header Redirigimos a la misma pagina con el lenguaje seteado
 */
 $IdiomView = '';
 
if (!isset($_SESSION["language"])){
  $peridioma = (isset($_SESSION[GLBAPPPORT.'PERIDIOMA']))? trim($_SESSION[GLBAPPPORT.'PERIDIOMA']) : 'esp';
  if($peridioma=='') $peridioma='esp';
  require_once GLBRutaFUNC.'/idioma'.$peridioma.'.php';
  $IdiomView = strtoupper($peridioma);
  
  }elseif(isset($_SESSION["language"])){
	  $change=$_SESSION["language"];
	  require_once GLBRutaFUNC.'/idioma'.$change.'.php';
	  $IdiomView = strtoupper($change);
  }


if (isset($_GET["language"])){
	$_SESSION["language"]=$_GET["language"];
	header ('Location:'.$_SERVER['HTTP_REFERER']);
  }






   