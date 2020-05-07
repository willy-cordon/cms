/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Convex - Bootstrap 4 HTML Admin Dashboard Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Wizard tabs with icons setup
(function(window, document, $) {
    'use strict';
    $(document).ready( function(){
        $(".icons-tab-steps").steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: 'Submit'
			},
			
            onFinished: function (event, currentIndex) {
                $(".icons-tab-steps").hide();
				$("#lbcamposesp").hide();
				$("#lbcamposing").hide();
				
				var pernombre 	= $('#pernombre').val();
				var perapelli 	= $('#perapelli').val();
				var percompan 	= $('#percompan').val();
				var percargo 	= $('#percargo').val();
				var pertipo 	= $('#pertipo').val();
				var perclase 	= $('#perclase').val();
				var perrubcod   = $('#perrubcod').val();
				var percorreo 	= $('#percorreo').val();
				var pertelefo 	= $('#pertelefo').val();
				var perurlweb 	= $('#perurlweb').val();
				var perdirecc 	= $('#perdirecc').val();
				var perciudad 	= $('#perciudad').val();
				var perestado 	= $('#perestado').val();
				var percodpos 	= $('#percodpos').val();
				var paicodigo 	= $('#paicodigo').val();
				var perparnom1  = $('#perparnom1').val();
				var perparape1  = $('#perparape1').val();
				var perparcarg1 = $('#perparcarg1').val();
				var perparnom2  = $('#perparnom2').val();
				var perparape2  = $('#perparape2').val();
				var perparcarg2 = $('#perparcarg2').val();
				var perparnom3  = $('#perparnom3').val();
				var perparape3  = $('#perparape3').val();
				var perparcarg3 = $('#perparcarg3').val();

				var perusuacc 	= $('#perusuacc').val();
				var perpasacc 	= $('#perpasacc').val();
				
				var percoment 	= $('#percoment').val();

				var errcode=0;

			
				//Verificamos que los campos no esten en blanco
				if($("#pernombre").val().length == 0){
					errcode=2;
					
				}
				if($("#perapelli").val().length == 0){
					errcode=2;
					
				}
				if($("#percompan").val().length == 0){
					errcode=2;
					
				}
				if($("#percargo").val().length == 0){
					errcode=2;
					
				}
				if($("#pertipo").val().length == 0){
					errcode=2;
					
				}
				if($("#perclase").val().length == 0){
					errcode=2;
					
				}
				if($("#perrubcod").val().length == 0){
					errcode=2;
					
				}
				if($("#percorreo").val().length == 0){
					errcode=2;
					
				}
				if($("#pertelefo").val().length == 0){
					errcode=2;
					
				}
				if($("#perurlweb").val().length == 0){
					errcode=2;
					
				}
				if($("#perciudad").val().length == 0){
					errcode=2;
					
				}
				if($("#paicodigo").val().length == 0){
					errcode=2;
					
				}
				if($("#percoment").val().length == 0){
					errcode=2;
					
				}


				if (errcode == 0) {
					
					var data = {"pernombre":pernombre,
							"pernombre":pernombre,
							"perapelli":perapelli,
							"percompan":percompan,
							"percargo":percargo,
							"pertipo":pertipo,
							"perclase":perclase,
							"perrubcod":perrubcod,
							"percorreo":percorreo,
							"pertelefo":pertelefo,
							"perurlweb":perurlweb,
							"perdirecc":perdirecc,
							"perciudad":perciudad,
							"perestado":perestado,
							"percodpos":percodpos,
							"paicodigo":paicodigo,

							"perparnom1":perparnom1,
							"perparape1":perparape1,
							"perparcarg1":perparcarg1,
							"perparnom2":perparnom2,
							"perparape2":perparape2,
							"perparcarg2":perparcarg2,
							"perparnom3":perparnom3,
							"perparape3":perparape3,
							"perparcarg3":perparcarg3,

							"perusuacc":perusuacc,
							"perpasacc":perpasacc,
							"percoment":percoment};
							
				
				$.ajax({
				  type: "POST",
				  url: 'registersend.php',
				  data: data
				}).done(function( rsp ) {
					console.log(rsp);
					//toastr.success('Se ha enviado un mail a su casilla de correo.', 'CONFIRMAR');
					data = $.parseJSON(rsp);
					if(data.errcod == 0){			
						//toastr.success(data.errmsg, 'ELIMINAR');
						alert('Se ha enviado un mail a su casilla de correo.');
						window.location='registermail';
					}else{
						//toastr.error(data.errmsg, 'ELIMINAR');
						//errmsg="usuario ya registrado"
						alert(data.errmsg);
						$(".icons-tab-steps").show();
						$("#lbcamposesp").show();
						$("#lbcamposing").show();
					}
					
					
				});
				
			}else{
				toastr.error("Porfavor verifique los campos requeridos, son obligatorios para poder registrarse", 'ERROR!!');
				$(".icons-tab-steps").show();
				$("#lbcamposesp").show();
				$("#lbcamposing").show();
			}	
			
          }
        });

        // To select event date
        $('.pickadate').pickadate();
     });
})(window, document, jQuery);