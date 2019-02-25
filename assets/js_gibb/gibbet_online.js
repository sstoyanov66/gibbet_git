   
    /*This is to Enable/disable the button for entire word*/
	function enableButton(){
		   
		 var wholeWord = document.getElementById("guess").value; 
		var wholeWords = wholeWord.trim();
		  
		  	 			  			  
		  if(wholeWords!="" && $('#word').html()!=""){			    			    	
		    	document.getElementById("guessButt").disabled = false;
		      							    	
		    }else{
		    	document.getElementById("guessButt").disabled = true;
		    }	
	  
		  }//end  enableButton
							
		
$(document).ready(function(){

	
    /*This is to Enable/disable the button for entire word

       $('#guess').focus(function () {
   		 	
           if ($('#guess').val()!='' && $('#word').html()!="") {
               $('#guessButt').prop('disabled', false);         
           } else {
               $('#guessButt').prop('disabled', true);                       
           }
       });
          */
    
    /*disable Enter key if the word is not guessed to prevent new word load w/o warning message and losing game*/
    $(document).keypress(
	    function(event){
	      if ( $('#word').html()!="" && event.which == '13') {
	        event.preventDefault();
	      }
	  });
    
					         
	  /*to click the hidden buttons inside the input form  form outside:*/
    $('#signIt').on('click', function () {        	       	
        $('#hiddensignup').click();
 			    
      	});
    

    /*to enable/disable start game and new game buttons - only if there's option selected*/
    
    $('#slctPlayer').focus(function () {
		 	
        if ($(this).val() == '') {
            $('#challng').prop('disabled', true);
         //   $('#startGame').prop('disabled', true);
            $('#newGame').prop('disabled', true);
        } else {
            $('#challng').prop('disabled', false);
          //  $('#startGame').prop('disabled', false);
            $('#newGame').prop('disabled', false);
                        
        }
    });
    
    
    /*to enable/disable Letter buttons*/
   if ($('#word').html()!=""){
           $('.btn-sm').prop('disabled', false);
                       
      }else{  
    	  $('.btn-sm').prop('disabled', true);
     }
    
    /*	 if (this.keyCode== 13 && $('#word').html()!="") { 
	        $('#warnModal').modal('show'); 
	       
	   }*/

   
   /*try to Exit or exit:*/  
     
   $('#tryExit').on('click', function () { 
         
	   if ( $('#word').html()!=""){
	   $('#warnModal').modal('show'); 
	   }else{
	      
		   $('#hiddenexit1').click();
	   }	       
     	});
   
   /*to click the hidden buttons inside the input form form outside:*/
   $('#exitDB').on('click', function () {   
       
       $('#hiddenexit2').click();
			    
     	});
      
   
   /*try Self game or  go there */
   $('#onself').on('click', function () { 
         
	   if ( $('#word').html()!=""){
	   $('#warnSelfModal').modal('show'); 
	   }else{
	      
		   $('#hiddenonself').click();
	   }	       
     	});
   
   /*to click the hidden buttons inside the input form form outside:*/
   $('#gotoSelf').on('click', function () {   
       
       $('#hiddenonself2').click();
			    
     	});
   
   /*try to start a new game - check if there's a current one:*/
   $('#startGame').on('click', function () { 
        
	      if ($('#word').html()!=""){
		   $('#warnModal').modal('show'); 
		   }else{
			   $('#hiddenstart1').click();
		   }
	      	 	
   });
   
   /*privent modal to close on background click */
  $("#challangeAcceptModal").on('shown.bs.modal', function (){    	 		 
		 this.modal({ 
			 show: true,
           backdrop: 'static',// to prevent close modal on background click
           keyboard: false  // to prevent closing with Esc button (if you want this too)
       })   			   
  });
		  
   
   /*to click the hidden buttons inside the input form form outside:*/
   $('#newGame').on('click', function () {        	       	
       $('#hiddenstart2').click();
			    
     	});   
   
   $('#reject_challng').on('click', function () {        	       	
       $('#hiddenreject').click();
			    
     	});
   $('#reject_offer').on('click', function () {        	       	
       $('#hiddenreject').click();
			    
     	});
   
   $('#accept_challng').on('click', function () {        	       	
       $('#hiddenaccept').click();
			    
     	});
   
   /*push the button to clear the winner progress to privent same modal to show*/
   $("#loseOnlineModal").on('hidden.bs.modal', function (){    	 		 
	   if(typeof(EventSource) !== 'undefined') {
			
           var source = new EventSource('clear_opponent_progress'); //the function in the controller
           source.onmessage = function(event) {
   			
     //something to do related with data sent by php function?!
           };
                   } else {
           window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 !');
   			
           }
     			   
});
   
   
   
    /*initialize Letter buttons with Cyrillic values*/
   document.getElementById("a").value = "а"; document.getElementById("b").value = "б"; document.getElementById("v").value = "в"; document.getElementById("g").value = "г";
   document.getElementById("d").value = "д"; document.getElementById("e").value = "е"; document.getElementById("zh").value = "ж"; document.getElementById("z").value = "з"
document.getElementById("i").value = "и"; document.getElementById("ii").value = "й"; document.getElementById("k").value = "к"; document.getElementById("l").value = "л"
document.getElementById("m").value = "м"; document.getElementById("n").value = "н"; document.getElementById("o").value = "о"; document.getElementById("p").value = "п"   
	 document.getElementById("r").value = "р"; document.getElementById("s").value = "с"; document.getElementById("t").value = "т"; document.getElementById("u").value = "у";
	   document.getElementById("f").value = "ф"; document.getElementById("h").value = "х"; document.getElementById("c").value = "ц"; document.getElementById("ch").value = "ч"
	document.getElementById("sh").value = "ш"; document.getElementById("sht").value = "щ"; document.getElementById("q").value = "ъ"; document.getElementById("qs").value = "ь"
	 document.getElementById("w").value = "ю"; document.getElementById("ia").value = "я"   
	
	
});// here ends $(document).ready(function(){