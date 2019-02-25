


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
 if(!$(this).data('clickedPreviously')) {
	 $(this).data('clickedPreviously', true) 
  }  
})
              
    /*control Enter key function if the word is not guessed -
     * 1. When the input for whole word guess is empty: Prevent new word load and losing game by the "watch dog" on page reload and activate warning message 
     * 2. When the input for whole word guess is not empty: Prevent new word load and push the putton for instant guess 
     * 3. In case the word field is empty use Enter key to load new word*/
    $(document).keypress(function(event){ //alternative of $(document).on("keydown", function(event) {
	    
    	var keyEnter = event.which || event.keyCode;
    	
	      if ( $('#word').html()!="" && $('#guess').val()=="" && keyEnter === 13) {
	        event.preventDefault();
	        $('#warnModal').modal('show'); 
	      }else if($('#word').html()!="" && $('#guess').val()!="" && keyEnter === 13){
	    	  event.preventDefault();
	    	  $('#guessButt').click();
	      }else if($('#word').html()=="" && keyEnter === 13){
	    	  event.preventDefault();
	    	  $('#hiddenstart1').click();
	      }
	      	      
	  });
    					         

    /*to enable/disable start game and new game buttons - only if there's option selected*/
    
    $('#slctType').focus(function () {
		 	
        if ($(this).val() == '') {
            $('#startGame').prop('disabled', true);
            $('#newGame').prop('disabled', true);
        } else {
            $('#startGame').prop('disabled', false);
            $('#newGame').prop('disabled', false);
                        
        }
    });
    
    
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
   
   /*try online game or go online:*/  
   
   $('#tryOnline').on('click', function () { 
         
	   if ( $('#word').html()!=""){
	   $('#warnOnlineModal').modal('show'); 
	   }else{
	      
		    $('#hiddenonline1').click();
	   }	       
     	});
   
 $('#onlineDB').on('click', function () {   
       
       $('#hiddenonline2').click();
			    
     	});
        
      
   
   /*try to start a new game - check if there's a current one:*/
   $('#startGame').on('click', function (е) { 
        
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

         
   
   document.getElementById("а").value = "а"; document.getElementById("б").value = "б"; document.getElementById("в").value = "в"; document.getElementById("г").value = "г";
   document.getElementById("д").value = "д"; document.getElementById("е").value = "е"; document.getElementById("ж").value = "ж"; document.getElementById("з").value = "з";
document.getElementById("и").value = "и"; document.getElementById("й").value = "й"; document.getElementById("к").value = "к"; document.getElementById("л").value = "л";
document.getElementById("м").value = "м"; document.getElementById("н").value = "н"; document.getElementById("о").value = "о"; document.getElementById("п").value = "п";   
	 document.getElementById("р").value = "р"; document.getElementById("с").value = "с"; document.getElementById("т").value = "т"; document.getElementById("у").value = "у";
	   document.getElementById("ф").value = "ф"; document.getElementById("х").value = "х"; document.getElementById("ц").value = "ц"; document.getElementById("ч").value = "ч";
	document.getElementById("ш").value = "ш"; document.getElementById("щ").value = "щ"; document.getElementById("ъ").value = "ъ"; document.getElementById("ь").value = "ь";
	 document.getElementById("ю").value = "ю"; document.getElementById("я").value = "я";   
	
    /*initialize Letter buttons with Cyrillic values
		 document.getElementById("a").value = "а"; document.getElementById("b").value = "б"; document.getElementById("v").value = "в"; document.getElementById("g").value = "г";
		   document.getElementById("d").value = "д"; document.getElementById("e").value = "е"; document.getElementById("zh").value = "ж"; document.getElementById("z").value = "з";
		document.getElementById("i").value = "и"; document.getElementById("ii").value = "й"; document.getElementById("k").value = "к"; document.getElementById("l").value = "л";
		document.getElementById("m").value = "м"; document.getElementById("n").value = "н"; document.getElementById("o").value = "о"; document.getElementById("p").value = "п";   
			 document.getElementById("r").value = "р"; document.getElementById("s").value = "с"; document.getElementById("t").value = "т"; document.getElementById("u").value = "у";
			   document.getElementById("f").value = "ф"; document.getElementById("h").value = "х"; document.getElementById("c").value = "ц"; document.getElementById("ch").value = "ч";
			document.getElementById("sh").value = "ш"; document.getElementById("sht").value = "щ"; document.getElementById("q").value = "ъ"; document.getElementById("qs").value = "ь";
			 document.getElementById("w").value = "ю"; document.getElementById("ia").value = "я";   		 
		 
		 */
	
});// here ends $(document).ready(function(){