   
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

	  /*This is needed in the controller for the purpose of "close- browser -before -end" watch dog*/

	   $('#guessButt').on('click',function(){
	 if(!$(this).data('clickedBefore')) {
		 $(this).data('clickedBefore', true) 
	  }  
	})
	
     /*control Enter key function if the word is guessed or not loaded  -
     * 1. When player is not selected: Prevent any other submit button be pressed and losing game by the "watch dog" on page reload 
     * 2. When player is selected:  Prevent any other submit button be pressed and push the putton for challenge 
     * 3. In case the word field is not empty use Enter key for instant guess only*/
    $(document).keypress(
	    function(event){
	      if ( $('#word').html()=="" && $('#slctPlayer').val()=="" && event.which == '13') {
	        event.preventDefault();
	         
	      }else if($('#word').html()=="" && $('#slctPlayer').val()!="" && event.which == '13'){
	    	  event.preventDefault();
	    	  $('#challng').click();
	      }else if($('#word').html()!="" && $('#guess').val()!="" && event.which == '13'){
	    	  event.preventDefault();
	    	  $('#guessButt').click();
	      }
	  });
    
					         
	  /*to click the hidden buttons inside the input form  form outside:*/
    $('#signIt').on('click', function () {        	       	
        $('#hiddensignup').click();
 			    
      	});
    

    /*to enable/disable buttons - only if there's option selected  */
    
    $('#slctPlayer').change(function () {
		 	
        if ($(this).val() == '') {
            $('#challng').prop('disabled', true);
     
        } else {
        	
            $('#challng').prop('disabled', false);
           
        }
    });
    
    /*
    $('#slctPlayer').blur(function () {   
      
    	if($('#challng').data('clicked')){
   
    	 }else{ 
    		 
    		 $('#challng').prop('disabled', true);
        	 $(this).val("");
    	}
    });
   */
    
    /*to disable Letter buttons if word dissapear*/
    if ($('#word').html()==""){
        
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
   
   
   
   /*push the button to clear the winner progress to privent same modal to show in the loser browser*/
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
      
   
    /*initialize Letter buttons with Cyrillic values
   document.getElementById("a").value = "а"; document.getElementById("b").value = "б"; document.getElementById("v").value = "в"; document.getElementById("g").value = "г";
   document.getElementById("d").value = "д"; document.getElementById("e").value = "е"; document.getElementById("zh").value = "ж"; document.getElementById("z").value = "з";
document.getElementById("i").value = "и"; document.getElementById("ii").value = "й"; document.getElementById("k").value = "к"; document.getElementById("l").value = "л";
document.getElementById("m").value = "м"; document.getElementById("n").value = "н"; document.getElementById("o").value = "о"; document.getElementById("p").value = "п";   
	 document.getElementById("r").value = "р"; document.getElementById("s").value = "с"; document.getElementById("t").value = "т"; document.getElementById("u").value = "у";
	   document.getElementById("f").value = "ф"; document.getElementById("h").value = "х"; document.getElementById("c").value = "ц"; document.getElementById("ch").value = "ч";
	document.getElementById("sh").value = "ш"; document.getElementById("sht").value = "щ"; document.getElementById("q").value = "ъ"; document.getElementById("qs").value = "ь";
	 document.getElementById("w").value = "ю"; document.getElementById("ia").value = "я";  */ 
   
   document.getElementById("а").value = "а"; document.getElementById("б").value = "б"; document.getElementById("в").value = "в"; document.getElementById("г").value = "г";
   document.getElementById("д").value = "д"; document.getElementById("е").value = "е"; document.getElementById("ж").value = "ж"; document.getElementById("з").value = "з";
document.getElementById("и").value = "и"; document.getElementById("й").value = "й"; document.getElementById("к").value = "к"; document.getElementById("л").value = "л";
document.getElementById("м").value = "м"; document.getElementById("н").value = "н"; document.getElementById("о").value = "о"; document.getElementById("п").value = "п";   
	 document.getElementById("р").value = "р"; document.getElementById("с").value = "с"; document.getElementById("т").value = "т"; document.getElementById("у").value = "у";
	   document.getElementById("ф").value = "ф"; document.getElementById("х").value = "х"; document.getElementById("ц").value = "ц"; document.getElementById("ч").value = "ч";
	document.getElementById("ш").value = "ш"; document.getElementById("щ").value = "щ"; document.getElementById("ъ").value = "ъ"; document.getElementById("ь").value = "ь";
	 document.getElementById("ю").value = "ю"; document.getElementById("я").value = "я"; 
   

	 
});// here ends $(document).ready(function(){