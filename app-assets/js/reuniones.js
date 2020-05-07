setTimeout('findReunionesStatus()',5000);

function findReunionesStatus(){
	var urlnotif = '../reuniones/reunionesfind.php';
	if(document.URL.indexOf('/index')!==-1){
		 urlnotif = 'reuniones/reunionesfind.php';
	}
	
	$.ajax({
	  type: "POST",
	  url: urlnotif,
	  data: null
	}).done(function( rsp ) {
		data = $.parseJSON(rsp);
		
		$('#cantenv').html(data.cantEnviados);
		$('#cantrec').html(data.cantRecibidos);
		setTimeout('findReunionesStatus()',5000);
	});
}