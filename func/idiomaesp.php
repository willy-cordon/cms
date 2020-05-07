<?php

/**
 * ---Diccionario de idiomas---
 * 
 * Function content $key and $value;
 * Funciones($string) return string 
 */
//-----------------------------------------------------------------------------------------------------
function getIdiomasDB() {
  return ['Reunion solicitada' => 'Reunion solicitada ',
          'Reunion confirmada' => 'Reunion confirmada',
          'Reunion cancelada'  =>'Reunion cancelada',
          'Reunion confirmada con cambio de Horario' => 'Reunion confirmada con cambio de Horario'
      ];
}
function Message(){
    return['Guardado correctamente!'=>'Guardado correctamente!',
           'Perfil eliminado!'=> 'Perfil eliminado!',
           'Error al eliminar el perfil!'=>'Error al eliminar el perfil!',
           'Perfil activado!' =>'Perfil activado!',
           'Error al activar el perfil!'=>'Error al activar el perfil!',
           'Perfil liberado!' => 'Perfil liberado!',
           'Error al liberar el perfil!' => 'Error al liberar el perfil!',
           'Login iniciado!' => 'Login iniciado!',
           'Permiso actualizado!' => 'Permiso actualizado!',
           'Error al actualizar el permiso!' => 'Error al actualizar el permiso!',
           'Sector eliminado!' => 'Sector eliminado!',
           'Error al eliminar el sector!' => 'Error al eliminar el sector!',
           'Subsector eliminado!' => 'Subsector eliminado!',
           'Error al eliminar el subsector!' => 'Error al eliminar el subsector!',
           'Categoria eliminado!' => 'Categoria eliminada!'
        ];
}
//-----------------------------------------------------------------------------------------------------

function DDIdioma($tmpl){


    $tmpl->setVariable('Idioma_Subsectores',' Subsectores');
    $tmpl->setVariable('Idioma_Descripcion',' Descripción');
    $tmpl->setVariable('Idioma_Empresa',' Empresa');
    $tmpl->setVariable('Idioma_Seccion','Sección');
    $tmpl->setVariable('Idioma_MaestroSec','Maestro de Sectores');
    $tmpl->setVariable('Idioma_MaestroSubSec','Maestro de Subsectores');
    $tmpl->setVariable('Idioma_MaestroIdioma','Maestro de Idioma');
    $tmpl->setVariable('Idioma_Traducciones','Traducción');
	$tmpl->setVariable('Idioma_GuardarSwal','GUARDAR');
	$tmpl->setVariable('Idioma_Confirmar','¿Confirma guardar los cambios?');
	$tmpl->setVariable('Idioma_BotonConf','Confirmar');
	$tmpl->setVariable('Idioma_BotonCan','Cancelar');
	$tmpl->setVariable('Idioma_SelecIdiom','Seleccione Idioma');
	$tmpl->setVariable('Idioma_Fltnom','Falta el nombre');
	$tmpl->setVariable('Idioma_FltApe','Falta el apellido');
	$tmpl->setVariable('Idioma_FltComp','Falta la compañia');
    $tmpl->setVariable('Idioma_FltSelecComp','Falta seleccionar la disponibilidad');
    $tmpl->setVariable('Idioma_Configuracion','Configuracion' );
    $tmpl->setVariable('Idioma_Perfiles','Perfiles' );
    $tmpl->setVariable('Idioma_Productos','Productos' );
    $tmpl->setVariable('Idioma_Sectores','Sectores' );
    $tmpl->setVariable('Idioma_Subsectores','Subsectores' );
    $tmpl->setVariable('Idioma_Categorias','Categorias' );
    $tmpl->setVariable('Idioma_SubC','Subcategorias' );
    $tmpl->setVariable('Idioma_Exportar','Exportar' );
    $tmpl->setVariable('Idioma_Perfiles','Perfiles' );
    $tmpl->setVariable('Idioma_Noticias','Noticias' );
    $tmpl->setVariable('Idioma_Agenda','Agenda' );
    $tmpl->setVariable('Idioma_Mensajeria','Mensajeria' );
    $tmpl->setVariable('Idioma_ExpositoresApp','Expositores Aplicacion' );
    $tmpl->setVariable('Idioma_Directorio', 'Directorio' );
    $tmpl->setVariable('Idioma_Buscar','Buscar' );
    $tmpl->setVariable('Idioma_Recomendados','Recomendados' );
    $tmpl->setVariable('Idioma_Reuniones','Reuniones' );
    $tmpl->setVariable('Idioma_ReunionesEnviadas','Enviadas' );
    $tmpl->setVariable('Idioma_ReunionesRecibidas','Recibidas' );
    $tmpl->setVariable('Idioma_ReunionesConfirmadas','Confirmadas' );
    $tmpl->setVariable('Idioma_ReunionesCanceladas','Canceladas' );
    $tmpl->setVariable('Idioma_Calendario', 'Calendario' );
    $tmpl->setVariable('Idioma_Actividades', 'Actividades' );
    $tmpl->setVariable('Idioma_AgendaActvidades','Agenda de Actividades' );
    $tmpl->setVariable('Idioma_CalendarioAc','Calendario de Actividades' );
    $tmpl->setVariable('Idioma_Mesas','Sala' );
    $tmpl->setVariable('Idioma_VerPerf','Ver Perfil' );
	$tmpl->setVariable('Idioma_Reunion','Reunión' );
    $tmpl->setVariable('Idioma_Coordinar','Aceptar' );
    $tmpl->setVariable('Idioma_Cancelar','Cancelar' );
    $tmpl->setVariable('Idioma_miPerfil','Mi perfil' );
    $tmpl->setVariable('Idioma_Salir','Salir' );
    $tmpl->setVariable('Idioma_ProdctYservi','Productos & SS.' );
    $tmpl->setVariable('Idioma_fav','Favoritos' );
    $tmpl->setVariable('Idioma_Disponibilidad','Disponibilidad' );
    $tmpl->setVariable('Idioma_Filtros','Filtros' );
    $tmpl->setVariable('Idioma_PLbuscar','por nombre de perfil, por empresa' );
    $tmpl->setVariable('Idioma_PLbtodos','Todos' );
    $tmpl->setVariable('Idioma_PLbverSolofav','Ver solo favoritos' );
	$tmpl->setVariable('Idioma_PLbactivos','Activos' );
    $tmpl->setVariable('Idioma_PLbeliminados','Eliminados' );
	$tmpl->setVariable('Idioma_PLbnoliberados','No LIberados' );
	$tmpl->setVariable('Idioma_PLbcorreonoconf','Correo no confirmado' );
	$tmpl->setVariable('Idioma_CBtipo','Tipo' );
    $tmpl->setVariable('Idioma_MSTPerfiles','Master profile');
    $tmpl->setVariable('Idioma_MSTDatosPersonales','Datos personales');
    $tmpl->setVariable('Idioma_Nombre','Nombre');
    $tmpl->setVariable('Idioma_Apellido','Apellido');
    $tmpl->setVariable('Idioma_Compania','Compañia');
    $tmpl->setVariable('Idioma_Cargo','Cargo');
    $tmpl->setVariable('Idioma_Idioma','Idioma');
    $tmpl->setVariable('Idioma_Seleccione','Seleccione');
    $tmpl->setVariable('Idioma_DescripcionEmpresa','Descripcion de la compañia');
    $tmpl->setVariable('Idioma_Correo','Correo');
    $tmpl->setVariable('Idioma_Telefono','Telefono');
    $tmpl->setVariable('Idioma_SitioWeb','Sitio web');
    $tmpl->setVariable('Idioma_Contacto','Contacto');
    $tmpl->setVariable('Idioma_Domicilio','Domicilio');
    $tmpl->setVariable('Idioma_Direccion','Direccion');
    $tmpl->setVariable('Idioma_Ciudad','Ciudad');
    $tmpl->setVariable('Idioma_Estado','Estado');
    $tmpl->setVariable('Idioma_CodPostal','Codigo postal');
    $tmpl->setVariable('Idioma_Pais','Pais');
    $tmpl->setVariable('Idioma_DatosDeAc','Datos de acceso web');
    $tmpl->setVariable('Idioma_Usuario','Usuario ');
    $tmpl->setVariable('Idioma_Contrasena','Contraseña ');
    $tmpl->setVariable('Idioma_Guardar','Guardar');
    $tmpl->setVariable('Idioma_Clasificar','Clasificar');
    $tmpl->setVariable('Idioma_Rubros','Rubro');
	$tmpl->setVariable('Idioma_Cerrar','Cerrar');
    $tmpl->setVariable('Idioma_OrdenarPor','Ordenar Por');
    $tmpl->setVariable('Idioma_Eliminar','ELIMINAR');
    $tmpl->setVariable('Idioma_ConfEliminar','¿Confirma eliminar el perfil?');
    $tmpl->setVariable('Idioma_ConfEliminarSec','¿Confirma eliminar el sector?');
    $tmpl->setVariable('Idioma_ConfEliminarSubSec','¿Confirma eliminar el Subsector?');
    $tmpl->setVariable('Idioma_ConfEliminarSubSec','¿Confirma eliminar el Subsector?');
    $tmpl->setVariable('Idioma_ConfEliminarCat','¿Confirma eliminar la Categoría?');
    $tmpl->setVariable('Idioma_Activar','ACTIVAR');
    $tmpl->setVariable('Idioma_ActivarPerf','¿Confirma reactivar el perfil?');
    $tmpl->setVariable('Idioma_LiberarAccs','LIBERAR ACCESO');
    $tmpl->setVariable('Idioma_ConfLibPerf','¿Confirma liberar el perfil?');
    $tmpl->setVariable('Idioma_Permisos','PERMISOS');
    $tmpl->setVariable('Idioma_ConfPerFil','¿Confirma cambiar el permiso del perfil?');
    $tmpl->setVariable('Idioma_ConfPerFiles','¿Confirma cambiar el permiso a todos los perfiles?');

    $tmpl->setVariable('Idioma_IngfPerFil','INGRESO PERFIL');
    $tmpl->setVariable('Idioma_ConfIngPerf','¿Confirma ingresar como el Perfil seleccionado?');
	$tmpl->setVariable('Idioma_ZonaHoraria','Zona Horaria');
	$tmpl->setVariable('Idioma_Tradiccion','Traducción');
	$tmpl->setVariable('Idioma_Encuesta','Encuestas');
	$tmpl->setVariable('Idioma_MaestroEnc','Maestro de Encuestas');
	$tmpl->setVariable('Idioma_MaestroEncPreg','Maestro de Preguntas');
	$tmpl->setVariable('Idioma_ReuUrl','Link de Reuniones');
    $tmpl->setVariable('Idioma_Speakers','Maestro de Oradores');
    $tmpl->setVariable('Idioma_Speakers1','Oradores');
    $tmpl->setVariable('Idioma_Nombre','Nombre');
    $tmpl->setVariable('Idioma_Descripcion','Descripción');
    $tmpl->setVariable('Idioma_Orden','Orden');
    $tmpl->setVariable('Idioma_Imagen','Imagen');
	$tmpl->setVariable('Idioma_Parametros','Parametros');
	$tmpl->setVariable('Idioma_MaestroParametros','Maestro de Parametros');


//-----------------------------------------------------------------------------------------------------

}
function DDStrIdioma($string){
 
      $traducciones = getIdiomasDB();
      
      return @$traducciones[$string];
  }
  function TrMessage($string){
    $tr = Message();
        
    return $tr[$string];
}
