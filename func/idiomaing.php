<?php

/**
 * ---Diccionario de idiomas---
 * 
 * Function content $key and $value;
 * Funciones($string) return string 
 */


//-----------------------------------------------------------------------------------------------------
function getIdiomasDB() {
    return ['Reunion solicitada' => 'Meeting requested ',
            'Reunion confirmada' => 'Meeting confirmed',
            'Reunion cancelada'  =>'Meeting canceled',
            'Reunion confirmada con cambio de Horario ' => 'Meeting confirmed with change of Schedule'];
}
function Message(){
    return['Guardado correctamente!' => 'Saved correctly',
           'Perfil eliminado!' => 'Profile removed!',
           'Error al eliminar el perfil!' => 'Error deleting the profile!',
           'Perfil activado!' =>'Profile activated!',
           'Error al activar el perfil!'=>'Error activating the profile!',
           'Perfil liberado!' => 'Profile released!',
           'Error al liberar el perfil!' => 'Error releasing the profile!',
           'Login iniciado!' => 'Login started!',
           'Permiso actualizado!' => 'Permit updated!',
           'Error al actualizar el permiso!' => 'Error updating the permission!',
           'Sector eliminado!' => 'Sector eliminated!',
           'Error al eliminar el sector!' => 'Error removing the sector!',
           'Subsector eliminado!' => 'Subsector removed!',
           'Error al eliminar el subsector!' => 'Error removing the subsector!',
           'Categoria eliminado!' => 'Category eliminated!'
        ];
}

//-----------------------------------------------------------------------------------------------------

/**
 * @param $tmpl
 */
function DDIdioma($tmpl){
    $tmpl->setVariable('Idioma_Tradiccion','Translate');
    $tmpl->setVariable('Idioma_Traducciones','Translations');
    $tmpl->setVariable('Idioma_Descripcion',' Description');
    $tmpl->setVariable('Idioma_Empresa',' Company');
    $tmpl->setVariable('Idioma_MaestroIdioma','Idiom Master');
    $tmpl->setVariable('Idioma_Subsectores','Subsectors');
    $tmpl->setVariable('Idioma_Seccion','Section');
    $tmpl->setVariable('Idioma_MaestroSec','Sector Master');
    $tmpl->setVariable('Idioma_MaestroSubSec','Master of Subsectors');
	$tmpl->setVariable('Idioma_GuardarSwal','SAVE');
	$tmpl->setVariable('Idioma_Confirmar','Do you confirm to save the changes?');
	$tmpl->setVariable('Idioma_BotonConf','Confirm');
	$tmpl->setVariable('Idioma_BotonCan','Cancel');
	$tmpl->setVariable('Idioma_SelecIdiom','Select a language');
	$tmpl->setVariable('Idioma_Fltnom','The name is missing');
	$tmpl->setVariable('Idioma_FltApe','The last name is missing');
	$tmpl->setVariable('Idioma_FltComp','The company is missing');
	$tmpl->setVariable('Idioma_FltSelecComp','Missing select availability');
    $tmpl->setVariable('Idioma_Configuracion','Configuration' );
    $tmpl->setVariable('Idioma_Perfiles','Profiles' );
    $tmpl->setVariable('Idioma_Productos','Products' );   
    $tmpl->setVariable('Idioma_Sectores','Sectors' );
    $tmpl->setVariable('Idioma_Subsectores','Subsectors' );
    $tmpl->setVariable('Idioma_Categorias','Categories' );
    $tmpl->setVariable('Idioma_SubC','Subcategories' );
    $tmpl->setVariable('Idioma_Exportar','Export' );
    $tmpl->setVariable('Idioma_Perfiles','Profiles' );;
    $tmpl->setVariable('Idioma_Noticias','News' );
    $tmpl->setVariable('Idioma_Agenda','Schedule' );
    $tmpl->setVariable('Idioma_Mensajeria','Messenger service' );
    $tmpl->setVariable('Idioma_ExpositoresApp','Exhibitors Application' );
    $tmpl->setVariable('Idioma_Directorio', 'Directory' );
    $tmpl->setVariable('Idioma_Buscar','Search' );
    $tmpl->setVariable('Idioma_Recomendados','Recommended' );
    $tmpl->setVariable('Idioma_Reuniones','Meetings' );        
    $tmpl->setVariable('Idioma_ReunionesEnviadas','Sent' );
    $tmpl->setVariable('Idioma_ReunionesRecibidas','Received' );
    $tmpl->setVariable('Idioma_ReunionesConfirmadas','Confirmed' );
    $tmpl->setVariable('Idioma_ReunionesCanceladas','Canceled' );
    $tmpl->setVariable('Idioma_Calendario', 'Calendar' );
    $tmpl->setVariable('Idioma_Actividades', 'Activities' );
    $tmpl->setVariable('Idioma_AgendaActvidades',' Activities Schedule' );
    $tmpl->setVariable('Idioma_CalendarioAc','Meeting´s Schedule' );
    $tmpl->setVariable('Idioma_Mesas','Room' );
    $tmpl->setVariable('Idioma_VerPerf','View profile' );
	$tmpl->setVariable('Idioma_Reunion','Meet' );
    $tmpl->setVariable('Idioma_Coordinar','Accept' );
    $tmpl->setVariable('Idioma_Cancelar','Cancel' );
    $tmpl->setVariable('Idioma_miPerfil','My profile' );
    $tmpl->setVariable('Idioma_Salir','Log out' );
    $tmpl->setVariable('Idioma_ProdctYservi','Products & SS.' );
    $tmpl->setVariable('Idioma_fav','Favorites' );
    $tmpl->setVariable('Idioma_Disponibilidad','Availability' );
    $tmpl->setVariable('Idioma_Filtros','Filters' );
    $tmpl->setVariable('Idioma_PLbuscar','by profile name, by company' );
    $tmpl->setVariable('Idioma_PLbtodos','All' );
    $tmpl->setVariable('Idioma_PLbverSolofav','Only favorites' );
	$tmpl->setVariable('Idioma_PLbactivos','Active' );
    $tmpl->setVariable('Idioma_PLbeliminados','Delete' );
	$tmpl->setVariable('Idioma_PLbnoliberados','Not released' );
	$tmpl->setVariable('Idioma_PLbcorreonoconf','Unconfirmed mail' );
    $tmpl->setVariable('Idioma_CBtipo','Type' );
    $tmpl->setVariable('Idioma_MSTPerfiles','Master profile');
    $tmpl->setVariable('Idioma_MSTDatosPersonales','Personal information');
    $tmpl->setVariable('Idioma_Nombre','Name');
    $tmpl->setVariable('Idioma_Apellido','Surname');
    $tmpl->setVariable('Idioma_Compania','Company');
    $tmpl->setVariable('Idioma_Cargo','Position');
    $tmpl->setVariable('Idioma_Idioma','Language');
    $tmpl->setVariable('Idioma_Seleccione','Select');
    $tmpl->setVariable('Idioma_DescripcionEmpresa','Company description');
    $tmpl->setVariable('Idioma_Correo','Mail');
    $tmpl->setVariable('Idioma_Telefono','Telephone');
    $tmpl->setVariable('Idioma_SitioWeb','Website');
    $tmpl->setVariable('Idioma_Contacto','Contact');
    $tmpl->setVariable('Idioma_Domicilio','Home');
    $tmpl->setVariable('Idioma_Direccion','Address');
    $tmpl->setVariable('Idioma_Ciudad','City');
    $tmpl->setVariable('Idioma_Estado','State');
    $tmpl->setVariable('Idioma_CodPostal','Zip code');
    $tmpl->setVariable('Idioma_Pais','Country');
    $tmpl->setVariable('Idioma_DatosDeAc','Access data ');
    $tmpl->setVariable('Idioma_Usuario','User ');
    $tmpl->setVariable('Idioma_Contrasena','Password ');
    $tmpl->setVariable('Idioma_Guardar','Save');
    $tmpl->setVariable('Idioma_Clasificar','Classify');
    $tmpl->setVariable('Idioma_Rubros','Sector');
	$tmpl->setVariable('Idioma_Cerrar','Close');
    $tmpl->setVariable('Idioma_OrdenarPor','Sort By');
    $tmpl->setVariable('Idioma_Eliminar','REMOVE');
    $tmpl->setVariable('Idioma_ConfEliminar','Do you confirm deleting the profile?');
    $tmpl->setVariable('Idioma_ConfEliminarSec','Do you confirm deleting the Sector?');
    $tmpl->setVariable('Idioma_ConfEliminarSubSec','¿Do you confirm deleting the Subsector?');
    $tmpl->setVariable('Idioma_Activar','ACTIVATE');
    $tmpl->setVariable('Idioma_ActivarPerf','Do you confirm reactivate the profile?');
    $tmpl->setVariable('Idioma_LiberarAccs','RELEASE ACCESS');
    $tmpl->setVariable('Idioma_ConfLibPerf','Are you sure to release the profile?');
    $tmpl->setVariable('Idioma_Permisos','PERMITS');
    $tmpl->setVariable('Idioma_ConfPerFil','Do you confirm changing the profile permission?');
    $tmpl->setVariable('Idioma_IngfPerFil','PROFILE INCOME');
    $tmpl->setVariable('Idioma_ConfIngPerf','Do you confirm entering as the selected Profile?');
    $tmpl->setVariable('Idioma_ConfEliminarCat','Are you sure to delete the Category?');
	$tmpl->setVariable('Idioma_ZonaHoraria','Time Zone');
	$tmpl->setVariable('Idioma_Encuesta','Survey');
	$tmpl->setVariable('Idioma_MaestroEnc','Survey Master');
	$tmpl->setVariable('Idioma_MaestroEncPreg','Questions Master');
    $tmpl->setVariable('Idioma_ReuUrl','Link Meet');
    $tmpl->setVariable('Idioma_Speakers','Master Speakers');
    $tmpl->setVariable('Idioma_Speakers1','Speakers');
    $tmpl->setVariable('Idioma_Nombre','Name');
    $tmpl->setVariable('Idioma_Descripcion','Description');
    $tmpl->setVariable('Idioma_Orden','Order');
    $tmpl->setVariable('Idioma_Imagen','Image');
    $tmpl->setVariable('Idioma_Parametros','Parameters');
	$tmpl->setVariable('Idioma_MaestroParametros','Master Parameters');


}
//-----------------------------------------------------------------------------------------------------
function DDStrIdioma($string){
    
        $traducciones = getIdiomasDB();
        
         return $traducciones[$string];
    }
function TrMessage($string){
    $tr = Message();
        
    return $tr[$string];
}