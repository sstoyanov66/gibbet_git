					
$(document).ready(function(){
    					         
	  /*to click the hidden buttons inside the input form  form outside:
    $('#signIt').on('click', function () {        	       	
        $('#hiddensignup').click();
 			    
      	});*/
   
	$('#singup').on('click',function(){
	 $('#signUpModal').modal({
      	show: true,
          backdrop: 'static',// to prevent close modal on background click
          keyboard: false  // to prevent closing with Esc button (if you want this too)
          });   
	}); 
	
	/*!!! FROM HERE TO NEXT !!! is the logic to chose between several submit buttons to work with ENTER key in a form */
	//initial load state
	
	 $('#signIt').prop('disabled', true);
     $('#enter').prop('disabled', false); 
     	
	 $("#signUpModal").on('show.bs.modal', function(){
		   
		 $('#signIt').prop('disabled', false);
         $('#enter').prop('disabled', true);
		  });
	
	 $("#signUpModal").on('hide.bs.modal', function(){
		   
		 $('#signIt').prop('disabled', true);
         $('#enter').prop('disabled', false);
		  });
	 	

	 if($('#forgpassForm').is(':visible') ){
	     $('#enter').prop('disabled', true);
	 }
	
	$(document).on("keydown", function(event) {
		
		var keyEnter = event.which || event.keyCode;
		  if (keyEnter === 13 && $('#signUpModal').is(':visible')) {
			  $('#signIt').click();
		  } else if(keyEnter === 13 && !$('#forgpassForm').is(':visible')&& !$('#signUpModal').is(':visible')){
			  $('#enter').click();
		  }else if(keyEnter === 13 && $('#forgpassForm').is(':visible')&& !$('#signUpModal').is(':visible')){
		      $('#sendmail').click();
		  }
		}); //!!!
	
	
	  $('#forgtnPass').on('click', function () {
		  var data=$(this);
		  data.toggle(); // hide the link button on press
		  
		//  $('#forgpassForm').show(); <-- that's the simplest easier way to show the element. The element <div id='forgpassForm'> below is not dynamicaly created, it is in enterGame.php with attr  style=" display: none;"
		 // However the more elegant way is to create it here with AJAX:
		  $.ajax({
			  url:'',  // call  fuction in the controller
		       //  type:'POST',
		      //   dataType: 'text',
		                                        //data: dataTosend,  -> alternative way to present data in ajax
		         data: data,         // !! THIS IS TO ASSIGN A NAME ATTRIBUTE TO THE HTML ELEMENT THAT PHP POST FROM i.e. LETTER BUTTONS. THIS WAY NAME=...IS NOT NECCESSERY IN HTML
		         //cache: false,                       //<- option , works also w/o it
		        // async: true,                       <- option , works also w/o it - so browser doesn't wait till ajax request completed
		         error: function() {
		              alert('Something is wrong with AJAX call');
		           },
		         success: function() {    //do something on success...
		                  
	  $('#showform').after("<div id='forgpassForm'> Забравена парола?<br><br><label for='email'><span class='glyphicon glyphicon-envelope'></span> <span>Въведете е-мейл с който сте направили регистрация на който ще получите инструкции как да въведете нова парола:</span></label>" +
	  		"<input  type='email'  name='myemail' form='signForm' id='mailaddrr' class='form-control' placeholder='Въведете валиден E-mail адрес'  style=' background-color: #cce6ff;'></div>"+
	  		"<br><button type='submit'  name='send_mail' class='btn btn-block btn-info' id='sendmail'><span class='glyphicon glyphicon-send'></span><span>&nbsp Изпрати инструкция</span></button>");	
		  	  
	 }
		         		           
		           
		  }); /**/
		
 
		  
	  });
	
	
});