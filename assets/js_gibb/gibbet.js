/**
 * 
 */
		
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
			
	  /*to click the hidden buttons inside the input form  form outside:*/
    $('#signIt').on('click', function (e) {        	       	
        $('#hiddensignup').click();
 			    
      	});
    
    /*to enable/disable start game button*/
  
    $('#slctType').focus(function () {
        if ($(this).val() == '') {
            $('#startGame').prop('disabled', true);
        } else {
            $('#startGame').prop('disabled', false);
        }
    });
    
    /*to enable/disable Letter buttons*/
   if ($('#word').html()!=""){
           $('.btn-sm').prop('disabled', false);
      }else{  
    	  $('.btn-sm').prop('disabled', true);
     }
    
    	
   /*try to Exit or exit:*/
   $('#tryExit').on('click', function (e) { 
	   if ($('#word').html()!=""){
	   $('#warnModal').modal('show'); 
	   }else{
		   $('#hiddenexit').click();
	   }
     	});
   
   /*try to start a new game - check if there's a current one:*/
   $('#startGame').on('click', function (e) { 
	   if ($('#word').html()!=""){
		   $('#warnModal').modal('show'); 
		   }else{
			   $('#hiddenstart').click();
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