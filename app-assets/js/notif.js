//declaramos las variables globales
var notifData = null;

notificationbusc();
function notificationbusc() {
	var indexPage = false;
	var urlnotif = '../notificaciones/notenv.php';
	if(document.URL.indexOf('/index')!==-1){
		 urlnotif = urlnotif.replace('../','');
		 indexPage = true;
	}
	
	
   if ($('#notiopen').hasClass('show')) {
        //
   }else{
   //Generamos el ajax que reciber las notificaciones  
    $.ajax({
        type: "POST",
        url: urlnotif,
        data: null, 
        success: function(data){
          $("#notinum").show();
        },
      }).done(function( rsp ) {
         //console.log(rsp);
         data = $.parseJSON(rsp);
         notifData = data;
         $('#notification').empty(); // no repite las notificaciones

         var cantnotif = 0;
         $.each(data.notificaciones, function (){
			var icon = ' ft-alert-circle ';
			var color = ' danger ';
			
			if(this.notestado==2){
				icon=' ft-save ';
				color=' info ';
			}
			
			//Redireccion segun tipo de notificacion
			var dir = '';
			switch(parseInt(this.notcodigo)){
				case 1: //Solicitud
					dir = '../reuniones/bsq?T=2';
					break;
				case 2: //Confirmado
					dir = '../reuniones/bsq?T=3';
					break;
				case 3: //Cancelado
					dir = '../reuniones/bsq?T=4';
					break;
				case 4: //Cambio de Horario
					dir = '../reuniones/bsq?T=3';
					break;
			}
			
			if(indexPage){
				 dir = dir.replace('../','');
			}
			
			//Insertamos los datos recibidos
            $('#notification').append(
				"<a href='"+dir+"' class='dropdown-item noti-container py-2'>"+
					"<i class='"+icon+color+" float-left d-block font-medium-4 mt-2 mr-2'></i>"+
					"<span class='noti-wrapper'>"+
						"<span class='noti-title line-height-1 d-block text-bold-400 "+color+" titulo'>"+this.nottitulo+"</span>"+
						"<span class='noti-text'>"+ this.perapelli+","+ this.pernombre +" - "+ this.percompan +"</span>"+
					"</span>"+
				 "</a>");
				 
			//Contador de notifiaciones nuevas
			if(this.notestado==1){
				cantnotif++;
			}
         });
         //Calculamos las notificaciones 
         if (cantnotif == 0) {
			$("#notinum").hide();
			//Agregamos un cartel al no tener notificaciones       
			$('#notification').append(''+"<p style='margin-top:30%;margin-left:15%;' class='sinnoti '>Usted no tiene notificaciones disponibles</p>");
			cantnotif='';
		}else{
			$("#notinum").show();
		}
		
        $('#notinum').html(cantnotif);
      });
   }
   //Actualizamos las notificaciones   
    setTimeout(notificationbusc, 5000);
    
  };
  //Generamos el ajax que se encarga de recibir las notificaciones
    function notificationupd() {
		var urlnotif = '../notificaciones/notres.php';
		if(document.URL.indexOf('/index')!==-1){
			 urlnotif = 'notificaciones/notres.php';
		}
		var dataNot = {"notificaciones":[]};
		
		//Tomo solo las notificaciones con estado no leido (1)
		$.each(notifData.notificaciones, function(){
			if(this.notestado==1){
				var itm = {"notreg": this.notreg};
				dataNot.notificaciones.push(itm)
			}
		});
      
        $.ajax({
            type:"POST",
            url: urlnotif,
            data:dataNot,
        }).done(function(rsp){
     });
    };
     //hide notification                       
        $(document).ready(function () {
            $('#dropdownBasic2').click(function(){
                $("#notinum").hide();
            });
        });                                     
    
        
