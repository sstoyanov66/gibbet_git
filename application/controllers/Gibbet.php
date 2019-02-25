<?php
class Gibbet extends CI_Controller {
			
        public function __construct() {
            
                parent::__construct();              
            /*set in autoload.php  
       $this->load->helper('url_helper'); //where I call for base_url() or other related to url strings
      $this->load->library('session'); // needed for set_flashdata() here and in enter.php, for access termin/admin
      */  
        }
      
        public function index()  {
        	/*set in autoload.php
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	$this->load->library('email');
       	!!!  THE MODEL $this->load->model('gibbet_model'); ALSO IS LOADED IN config/autoload.php */
           
        	$this->load->library('password');// this is my library created in applicationss/libraries on bases of  Anthony Ferrara encrypting file but made as a class with namespace
        	
        	$data["nonamepass"] = $data["mailaddress"]=""; // needed to pass view variables (used for injecting JS in view pages) to other function like captcha_ and name_validation() 
             	
        	if (isset ($_POST['send_request']) ) {
        	        	        
        	//$this->form_validation->set_rules('name', 'Потребител', 'callback_name_validation');
       // $this->form_validation->set_rules('pswr', 'Парола', 'callback_pass_validation');
        $this->form_validation->set_rules('name', 'Потребител', 'required');
        $this->form_validation->set_rules('pswr', 'Парола', 'required|min_length[5]');
        
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); //<-- See also .css and config.php. In Form_validation class default erro_prefix is <p>
        
     
       $user_field = $this->input->post('name');	$pass_field = $this->input->post('pswr'); 
       
        if ($this->form_validation->run() == FALSE)  {     
                      	
        $this->load->view('templates/header_gibbet');
        $this->load->view('gibbet/enterGame',$data);   
        $this->load->view('templates/footer_gibbet');
        
        } else  {
        	
            //check user name and pass - good example of how to get n-colums values with 1 request to the model
            $alluserss=$this->gibbet_model->get_users('name, enterpass');
                        
            if($alluserss){  
                
            $User=$Pass=$name=$passwHash = $Users="";
            
            foreach ($alluserss as $user):
            
            $name = $user["name"];	$passwHash = $user["enterpass"];
            $Users = array($name=>$passwHash );
            
            
            if (($user_field==$name)&&($this->password->password_verify($pass_field, $passwHash)) )	{
                
                $User = $user_field;   $Pass = $pass_field;
                
            }
            endforeach;
            
            if($User !=="" &&  $Pass !=="" ){
        
                $_SESSION["username"] =$User;
                $username =$this->session->username;
                $this->gibbet_model->logout_user($username); // this is in case the user closed the browser without exiting an online game or the user session expired
                $this->gibbet_model->outgame_user($username);// this is in case the user closed the browser without exiting an online game or the user session expired
                $this->gametype($username);
                
                
            }else{
            	//Jscript injection in php to view
                $data["nonamepass"] = "<script type='text/javascript'>  $('#wcodeModal').modal('show');   $('#wcodeModal').on('hidden.bs.modal', function () { }); </script>";
                                                  
                $this->load->view('templates/header_gibbet');
                $this->load->view('gibbet/enterGame',$data);   
                $this->load->view('templates/footer_gibbet');
            }
            
            }else{
                redirect('gibbet/index'); //<-- Also can catch this as an error
          
            }
  
      }   
            
      /*registration modal:
        	}else if (isset ($_POST['signUp']) ) {
        	    $data['nonamepass']="<script type='text/javascript'>  
                $('#signUpModal').modal({
             	show: true,
                 backdrop: 'static',// to prevent close modal on background click
                 keyboard: false  // to prevent closing with Esc button (if you want this too)
                 });   
                $('#signUpModal').on('hidden.bs.modal', function () { }); </script>";
        		
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/enterGame',$data);   
        		$this->load->view('templates/footer_gibbet');*/
        		
        	}else if (isset ($_POST['newUser']) ) {
        	    /*watch if something is entered in the fields but set_rules() check also if these are Cyrillic letters w/o numeric and their min number is 5*/
        	    $this->form_validation->set_rules('newname', 'име', 'required|regex_match[/^[а-яА-Я\p{Cyrillic}\s\-\.\!\?]+$/u]|min_length[3]|max_length[20]');
        	    $this->form_validation->set_rules('newpswr', 'парола', 'required|min_length[5]');
        	    $this->form_validation->set_rules('email', 'e-mail', 'required');
        	    
        	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); //<-- See also gibbet.css and config.php. In Form_validation class default erro_prefix is <p>
        	    
        	    $user_field = $this->input->post('newname');
        	    $mail_field = $this->input->post('email');
        	    
        	    if ($this->form_validation->run() == FALSE)  {
        	              	        
        	        $data['nonamepass']="<script type='text/javascript'> $('#signUpModal').modal({show: false}) </script>";
                 	        
        	        $this->load->view('templates/header_gibbet');
        	        $this->load->view('gibbet/enterGame',$data);   
        	        $this->load->view('templates/footer_gibbet');
        	        
        	    } else  {
        	        $alluserss=$this->gibbet_model->get_users('name'); // check 1st if the user exists:
        	        $allmails=$this->gibbet_model->get_users('mail'); // check 1st if the user exists:
        	        
        	        if($alluserss){
        	            
        	            $name=""; $watchdogName=false;
        	            
        	            foreach ($alluserss as $user):
        	            
        	            $name = $user["name"];
        	                 	            
        	            
        	            if ($user_field==$name) {
        	                $watchdogName=true;
        	                // redirect('', 'location');
        	               
        	                 break;
        	            }
        	            endforeach;
        	        }
        	        
        	        if($allmails){
        	            
        	            $mails=""; $watchdogMail=false;
        	            
        	            foreach ( $allmails as $mail):
        	            
        	            $mails = $mail["mail"];
        	            
        	            
        	            if ($mail_field==$mails) {
        	                $watchdogMail=true;
        	                // redirect('', 'location');
        	                
        	                break;
        	            }
        	            endforeach;
        	        }
        	        
        	        
        	        if($watchdogName){ //if the user exists:
        	        	
        	        	$data["nonamepass"] = "<script type='text/javascript'>  $('#wnameModal').modal('show');   $('#wnameModal').on('hidden.bs.modal', function () { }); </script>";
        	        	
        	        	$this->load->view('templates/header_gibbet');
        	        	$this->load->view('gibbet/enterGame',$data);   
        	        	$this->load->view('templates/footer_gibbet');
        	        	
        	        }else if($watchdogMail){//if the mail exists:
        	        	
        	            $data["nonamepass"] = "<script type='text/javascript'>  $('#wmailModal').modal('show');   $('#wmailModal').on('hidden.bs.modal', function () { }); </script>";        	            
        	            
        	            $this->load->view('templates/header_gibbet');
        	            $this->load->view('gibbet/enterGame',$data);
        	            $this->load->view('templates/footer_gibbet');
        	            
        	        }else{
        	        
        	        $terminUser = $this->gibbet_model-> insert_user() ;	
        	        
        	        $_SESSION["username"] =$terminUser;
        	        $username =$this->session->username;
        	        $this->gametype($username);     	                	        
        	        }
        	    }
        		
        	}else if (isset ($_POST['send_mail']) ) { //this attr is not in the view file element. It is generated by java script in gibbet_enter.js
        	    
        	    $this->form_validation->set_rules('myemail', 'e-mail', 'required');       	    
        	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); //<-- See also gibbet.css and config.php. In Form_validation class default erro_prefix is <p>
        	    
        	    if ($this->form_validation->run() == FALSE)  {        	               	        
       	        
        	    } else  {
        	        
        	        $recipient = $this->input->post('myemail');
        	        $checkmails = $this->gibbet_model->get_users('mail, name'); // check 1st if the mail exists and get the name of the player if user forgot also it:
        	        
        	        
        	        if($checkmails){
        	            
        	            $mails=$names=""; $watchdog=false;
        	            
        	            foreach ( $checkmails as $mail):
        	            
        	            $mails = $mail["mail"]; $names= $mail["name"];
        	            
        	            
        	            if ($recipient==$mails) {
        	                $watchdog=true;
        	                // redirect('', 'location');
        	                
        	                break;
        	            }
        	            endforeach;
        	        }
        	        
        	        if(!$watchdog){ //if user exists:
        	            
        	            $data["nonamepass"] = "<script type='text/javascript'>  $('#missmailModal').modal({show: true, backdrop:'static',keyboard: false}); </script>";
        	                    	    
        	        }else{
        	       
        	        $_SESSION["usermail"] =$recipient; // need this session in ... to change users password
        	        $this->session->mark_as_temp('usermail', 300); // unset session in 5 min
        	        
        	        $this->email->from('info@sstoyanov.eu', 'игра "Бесеничка"');
        	        $this->email->to($recipient);
        	      //  $this->email->cc('another@another-example.com');
        	      //  $this->email->bcc('them@their-example.com');
        	        
        	        $this->email->subject('Смяна на парола в gibbet.sstoyanov.eu');
        	        $this->email->message("Смени паролата си в играта 'Бесеничка' \n\n Здравейте играч ".$names.",\n Забравена парола? За да я смениш, използвай линка: http://localhost:8080/gibbet/index.php/gibbet/resetpass  ");
      	        
        	        $this->email->send();
        	        
        	        //!!! VERY IMPORTANT -when mail is send via localhost-XAMPP the mail settings in its C:\xampp\php\php.ini and C:\xampp\sendmail\sendmail.ini MUST BE CHANGED - open and see how!!
        	        
        	        $data["mailaddress"]=$recipient;
        	        $data["nonamepass"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
        	    }
        	    
        	      } 	    
        	      
        	    $this->load->view('templates/header_gibbet');
        	    $this->load->view('gibbet/enterGame',$data);
        	    $this->load->view('templates/footer_gibbet');
        	   
        	    
        	}else{ //on start up
        		
        		
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/enterGame',$data);   
        		$this->load->view('templates/footer_gibbet');
        		
        	}
      
}       //index() ends here

public  function resetpass($usermail = NULL) {
    $usermail =$this->session->usermail;
    
    if ($usermail == NULL) {
        echo " Изтекла сесия за промяна на паролата. МОЛЯ, ИЗПОЛЗВАЙТЕ ЗА ВХОД <a href='".base_url()."'> Бесеничка </a>";
    }else { 
        $data["nonamepass"] ="";
        
        if (isset ($_POST['send_pass']) ) {
            
            $this->form_validation->set_rules('pswr', 'нова парола', 'required|min_length[5]');
            $this->form_validation->set_rules('repswr', 'потвърди парола', 'required|min_length[5]');               
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
            
            if ($this->form_validation->run() == FALSE)  {
             
            } else  {
            
                $passwrd=$this->input->post('pswr');
                $repasswrd=$this->input->post('repswr');
                
                if( $passwrd!==$repasswrd){
                    $data["nonamepass"] = "<script type='text/javascript'>  $('#wcodeModal').modal('show'); </script>";
                    
                }else{
                    $this->load->library('password');// this is my library created in applicationss/libraries 
                    $changepass = $this->gibbet_model->change_pass($usermail,$passwrd); // check 1st if the mail exists:
                    
                    if($changepass){
                    $data["nonamepass"] = "<script type='text/javascript'>  $('#successModal').modal('show'); 
 $('#successModal').on('hidden.bs.modal', function () { $('#hiddentoEnter').click();}); 
</script>";
                                                           
                    }else{             
                        $data["nonamepass"] = "<script type='text/javascript'>  $('#wconnModal').modal('show'); </script>";
                   
                    }                                   
                }        
            }
            
            $this->load->view('templates/header_gibbet');
            $this->load->view('gibbet/newPassword',$data);
            $this->load->view('templates/footer_gibbet');
            
        } else if (isset ($_POST['goto_enter']) ) { // button is pressed by java script above on hidden successModal -> click #hiddentoEnter
            session_destroy();//clear out all sessions
            redirect('', 'location');
             
                   
        }else{//initial function load
       
      //  $data["nonamepass"] = 'Линка през мейл '.$usermail.' е активиран';
        
        $this->load->view('templates/header_gibbet');
        $this->load->view('gibbet/newPassword',$data);
        $this->load->view('templates/footer_gibbet');
    }
    }
    
}


        public  function gametype($username = NULL) {
        	$username =$this->session->username;
        	// 'item' will be erased after 300 seconds
        	$this->session->mark_as_temp('username', 3600); // unset session in 1 h
        	        	
        	if ($username == NULL) {
        		echo " МОЛЯ, ИЗПОЛЗВАЙТЕ ЗА ВХОД <a href='".base_url()."'> Бесеничка </a>";
        	}else {   
        	    
        	    $data['name']=$username;
        	    $data['image']=$data['word']=$data['discrip']=$data['realword']= "";
        	    $data['total']= $data['won']=$data['lost']=$data['guesses']=$data['instant']=0;
        	    
        	    $data["jscript"] =  $data["javascript"]= $data["ajaxphp"]=  $data["jsclose"] =  '';
        	           	            	           	         		
        		$statArray = $this->gibbet_model->get_statistic($username);
        		$data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    		       		
        		//when word is chosen enable letter buttons disabled in html and js file
        		$data["javascript"] ="<script type='text/javascript'> 
                $(document).ready(function(){
 
        		if ($('#word').html()!=''){
        		    $('.btn-sm').prop('disabled', false);
        		    }
                    });
        		   </script>";
       	
       /* use AJAX "close- browser -before -end" watch dog function to punish the gamer if he closes the browser without word guess.!!-> Note, the listener is going to activate the function also on every page reload i.e. that will happened
       when instant word guess is made. To avoid this is needed to control if the button for instant guess was pressed with a 2nd check here. For the purpose data['clickedPreviously'] is initialised in gibbet.js  */
        		    $data["jsclose"] =   " <script type='text/javascript'>
               window.addEventListener('beforeunload', function (e) {
if ($('#word').html()!=''&& !$('#guessButt').data('clickedPreviously')){
                $.ajax({
                url:'browser_close',  // call the fuction below in this controller
                type:'POST',
                 // async:false,            so browser waits till xhr completed
                    success:function() {    //do something on success...
                    }
                    });
}
                    });

                </script>";
       
        		      		
         if (isset ($_POST['wordRequest']) ) {
            
             $array_items = array('hiddenWord', 'Discript', 'gibbetWord','usedLetters','msg_sorry');
        	    $this->session->unset_userdata( $array_items);//clear out sessions from previous game
        	    
        		$this->form_validation->set_rules('slcType', 'за селекция на категория',  'required');
        		$this->form_validation->set_error_delimiters('<div class="error">', '</div>'); //<-- See also .css and config.php. In Form_validation class default erro_prefix is <p>
        		
        		if($this->form_validation->run()===false){
        			       		          		    
        			$data['image']='assets/img_gibb/01.png';
        			
        			$this->load->view('templates/header_gibbet');
        			$this->load->view('gibbet/gameType',$data);
        			$this->load->view('templates/footer_gibbet');
        			
        		}else { 
        		            
        $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        $this->session->set_flashdata('msg',$helloUser);
        		
        $_SESSION["imageNumb"] = 1;
        		    
                $wordArray = $this->gibbet_model-> get_hidden_word(false,$username);       // get the word with dashes and discription from the model     		
        		
        		$_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["Discript"]=$wordArray[1];
        	//	$_SESSION["usedLetters"] =array('@','$'); //need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
        		
        		$data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600); 
        		$data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600); 
        		 $data['image']='assets/img_gibb/01.png';
        		 
   /*A GOOD EXAMPLE HOW TO EXCHANGE AJAX-PHP DATA BETWEEN SERVER AND BROWSER TO AVOIDE RELOADING THE PAGE ON EVERY LETTER CLICK INSTEAD OF else if (isset ($_POST['Letter']) - SEE BELOW .  */
        		 $data["ajaxphp"] = $this->ajax_letter();  
        		 
        		 
        		 $data['realword']=$this->session->gibbetWord;
        		 
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/gameType',$data);
        		$this->load->view('templates/footer_gibbet');
        		        		
        	}
        	
        	} else if (isset ($_POST['newWordRequest']) ) { // exit current game - lose points and load a new word
        	    
        	 /* Basically with a newWordRequest the player must be punished with:
        	  *   $statistic = array(1,0,1,0,0);
        	    $this->gibbet_model->set_statistic($username,$statistic);
        	    
        	  However that operation is done by  $data["jsclose"] above like an watch dog   
        	    */
        	    
        	    $array_items = array('hiddenWord', 'Discript', 'usedLetters','gibbetWord','imageNumb','msg_sorry');        	    
        	    $this->session->unset_userdata( $array_items);//clear out session
        	        	        	    
        	    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        	    $this->session->set_flashdata('msg',$helloUser);
        	    
        	    
        	    $wordArray = $this->gibbet_model-> get_hidden_word(false,$username);       // get the word with dashes and discription from the model
        	    
        	    $_SESSION["imageNumb"] = 1;
        	    $_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["Discript"]=$wordArray[1];
        	   // $_SESSION["usedLetters"] =array('@','$'); // need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
        	    
        	    $data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600);
        	    $data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600);
        	    
        	    $statArray = $this->gibbet_model->get_statistic($username);
        	    $data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	            	  
        	    $data['image']='assets/img_gibb/01.png';
        	         	    
        	    /*A GOOD EXAMPLE HOW TO EXCHANGE AJAX-PHP DATA BETWEEN SERVER AND BROWSER TO AVOIDE RELOADING THE PAGE ON EVERY LETTER CLICK INSTEAD OF else if (isset ($_POST['Letter']) - SEE BELOW .  */
        	    $data["ajaxphp"] = $this->ajax_letter();  
        	    
        	    $data['realword']=$this->session->gibbetWord;
        	    
        	    $this->load->view('templates/header_gibbet');
        	    $this->load->view('gibbet/gameType',$data);
        	    $this->load->view('templates/footer_gibbet');
        	    
        	    
        /* 	}else if (isset ($_POST['Letter']) ) {// click the letter buttons -> to activate this a name attr 'Letter'  must be assigned on every letter button in the html view file and remove EXCHANGE AJAX-PHP DATA BETWEEN SERVER AND BROWSER

       	    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        	    $this->session->set_flashdata('msg',$helloUser);
        	    
        	    $usedSymbols= $this->session->usedLetters;//session array containing all used letters - initialized with the 1st letter click by guess_letter($usedSymbols) return
        	    
        	    $lettersArray = $this->gibbet_model->guess_letter($usedSymbols);      // get the word with dashes and discription from the model
        		 $_SESSION["hiddenWord"] =$lettersArray[0];
        		 $_SESSION["usedLetters"] =$lettersArray[2];
        		
        		 //to prevent same letter click it's needed to disable used letters on the screen:        		
        		 $usedlett= '';
        		 foreach ($this->session->usedLetters as $letter):
        		 
        		 $usedlett= trim(json_encode($letter));
        		         		 
        		 $data["javascript"] .="<script type='text/javascript'>  
            $(document).ready(function(){
            var cyrLett = new String($usedlett);
             $('#'+cyrLett).prop('disabled', true);
                        });                
                  </script>";        		 
        		 endforeach;
        		        		 
        		 $counter= $this->session->imageNumb; 
        		        		 
        		 if(!$lettersArray[3]){ // the word is still Not guessed
        	    if( $lettersArray[1]){ // guessed letter
        	        $statistic = array(0,0,0,1,0);
        	        $this->gibbet_model->set_statistic($username,$statistic);        	        
        	    
        	    }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb !=5){
        	        
        	        $statistic = array(0,0,0,1,0);
        	        $this->gibbet_model->set_statistic($username,$statistic);
        	        
        	        $counter++;
        	        $_SESSION["imageNumb"]=  $counter; 
        	        
        	        $data["test"]= "<script type='text/javascript'>  $('#gibbetModal').modal('show');   $('#gibbetModal').on('hidden.bs.modal', function () { }); </script>";
        	        
        	    }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb ==5){ // warnning message
        	        
        	        $statistic = array(0,0,0,1,0);
        	        $this->gibbet_model->set_statistic($username,$statistic);
        	        
        	        $counter++;
        	        $_SESSION["imageNumb"]=  $counter; 
        	        
        	        $data["jscript"] = "<script type='text/javascript'>  $('#lastChanceModal').modal('show');   $('#lastChanceModal').on('hidden.bs.modal', function () { }); </script>";
       	        
        	    }else if( !$lettersArray[1] && $this->session->imageNumb ==6){ // GAME's OVER LOSER !
        	        
        	        $statistic = array(1,0,1,1,0);
        	        $this->gibbet_model->set_statistic($username,$statistic);
        	        
        	        $counter++;
        	        $_SESSION["imageNumb"]=  $counter; 
        	        
        	        $showword=$this->session->gibbetWord;       	        
        	        $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! Думата беше <strong  style="color:black" >'.$showword.'</strong></div>';
        	        $this->session->set_flashdata('msg_sorry',$sorryUser); 
        	        
        	        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','usedLetters');
        	        $this->session->unset_userdata( $array_items);//clear out sessions
        	     
        	        $data["test"]= "<script type='text/javascript'>  $('#gibbetModal').modal('show');   $('#gibbetModal').on('hidden.bs.modal', function () { }); </script>";
        	    }
        	    
        		 }else{ //The word is GUESSED !!
        		 	
        		 	$statistic = array(1,1,0,1,0);
        		 	$this->gibbet_model->set_statistic($username,$statistic);
        		 	$data["jscript"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
        		 	
        		 	$array_items = array('hiddenWord', 'Discript', 'gibbetWord','usedLetters');
        		 	$this->session->unset_userdata( $array_items);//clear out sessions
        		 	$counter=1;
        		 	
        		 }
        	    
        	            	    
        		$data['word']=$this->session->hiddenWord;
        		$data['discrip']=$this->session->Discript;
        		        		          	   
        	    $data['image']='assets/img_gibb/0'.$counter.'.png';
        		
        		
        		$statArray = $this->gibbet_model->get_statistic($username);
        		$data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
    
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/gameType',$data);
        		$this->load->view('templates/footer_gibbet');
        		*/        		 
        	
        	}else if (isset ($_POST['instantGuess']) ) {// click the guess buttons
        	    
        	    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        	    $this->session->set_flashdata('msg',$helloUser);       	    
        	    
        	    $result = $this->gibbet_model->guess_word();
      	        $showword=$this->session->gibbetWord;
        
        	    If($result==0){ //instant guess done
   	        
        	        $statistic = array(1,1,0,0,1);
        	        $this->gibbet_model->set_statistic($username,$statistic);
        	        $data["jscript"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
        	   
        	        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
        	        $this->session->unset_userdata( $array_items);//clear out sessions
        	       
        	        $statArray = $this->gibbet_model->get_statistic($username);
        	        $data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    
        	        $data['image']='assets/img_gibb/01.png';
        	            	        	     
        	    }else{// instant guess failed
        	                    	    	
        	    	$statistic = array(1,0,1,0,0);
        	    	$this->gibbet_model->set_statistic($username,$statistic);
        	    	$data["jscript"] = "<script type='text/javascript'>  $('#loserModal').modal('show');   $('#loserModal').on('hidden.bs.modal', function () { }); </script>";
        	    	
        	    	
        	    	$sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! Думата беше <strong  style="color:black" >'.$showword.'</strong></div>';
        	    	$this->session->set_flashdata('msg_sorry',$sorryUser); 
        	    	
        	    	$array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
        	    	$this->session->unset_userdata( $array_items);//clear out sessions
        	    	
        	    	$statArray = $this->gibbet_model->get_statistic($username);
        	    	$data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    	  
        	    	$data['image']='assets/img_gibb/07.png';         	         	    	        	    	
        	    	        	             	        
        	      }
        	      
        	      $helloUser='<div class="alert alert-success text-center">'. $username.', изберете отново категория и отгатнете коя е думата която ще зареди компютъра. Използвайте клавиатурата в дясно, за да отгатнете липсващите букви. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
        	      $this->session->set_flashdata('msg',$helloUser);
        	      
        	      $data['realword']=$showword;
        	      
        	        $this->load->view('templates/header_gibbet');
        	        $this->load->view('gibbet/gameType',$data);
        	        $this->load->view('templates/footer_gibbet');
        	        
        	          	        	 
        	}else  if (isset ($_POST['exitUser']) ) {     
        	    
        	     $this->gibbet_model->logout_user($username);// log out from online game if the user has been there before and closed browser 
        	        session_destroy();//clear out all sessions  
        	        /*$array_items = array('hiddenWord', 'Discript', 'gibbetWord','username','imageNumb');
        	         $this->session->unset_userdata( $array_items); detailed alternative to clear out all sessions       */ 	
        	        redirect('', 'location');
      	    
        	} else if (isset ($_POST['loseExit']) ) { // same as above because:
        	    	        		        	    
        	    /* Basically with a newWordRequest the player must be punished with:
        	     *   $statistic = array(1,0,1,0,0);
        	     $this->gibbet_model->set_statistic($username,$statistic);
        	     
        	     However that operation is done by  $data["jsclose"] above like an watch dog
        	     */
        	           		
        		$this->gibbet_model->logout_user($username);
        		      session_destroy();//clear out all sessions         			     		       		
        	    	redirect('', 'location');
        	    	
        	} else if (isset ($_POST['online']) ) { // go to the method for online play
        	    $this->gameonline($username); 
        	    
        	} else if (isset ($_POST['loseOnline']) ) { // go to the method for online play without ending current game - lose points
        	    
        	    /* Basically with a newWordRequest the player must be punished with:
        	     *   $statistic = array(1,0,1,0,0);
        	     $this->gibbet_model->set_statistic($username,$statistic);
        	     
        	     However that operation is done by  $data["jsclose"] above like an watch dog
        	     */
                	    
        	    $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
        	    $this->session->unset_userdata( $array_items);//clear out all sessions
        	    
        	    $this->gameonline($username); 
        	         	    
        	    	
        	}else{// this is for initial load through index() call - see above 
       	         	          
        $helloUser='<div class="alert alert-success text-center"> Здравейте '. $username.'! Влязохте успешно в играта "Бесеничка"! Изберете категория и отгатнете коя е думата която ще зареди компютъра. Използвайте клавиатурата в дясно, за да отгатнете липсващите букви. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
        $this->session->set_flashdata('msg',$helloUser);
        $this->session->set_flashdata('msg_sorry',"");
        	  $statArray = $this->gibbet_model->get_statistic($username);
        	  $data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    	
        	    	
        		$data["jscript"] = '';
        		
        		$data['image']='assets/img_gibb/01.png';
        		
        	
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/gameType',$data);
        		$this->load->view('templates/footer_gibbet');
        		        		        		
        	}
        	        	       	
        	}
        }
     
        public  function gameonline($username = NULL) {
            
            $username =$this->session->username;          
     /* !! unset user session in 172800 sec(48 h). In case the user forget to close window for long time its session is needed for window.addEventListener() to activate AJAX-browser_close_online()
      * and get him out of the game room if the user eventually closes the window within 48 hours. Note: That will not help in case if the user clear the cache during his stay in the room or after this session expiration*/
            $this->session->mark_as_temp('username', 86400); 
                                 
            if ($username == NULL) {
                echo " МОЛЯ, ИЗПОЛЗВАЙТЕ ЗА ВХОД <a href='".base_url()."'> Бесеничка </a>";
                                
            }else {
                       
                $data['name']=$username;
                $data['enemy']=$data['challenger'] =$data['image']=$data['word']=$data['discrip']=  $data['realword']="";
                $data['total']= $data['won']=$data['instant']=0;
                
                $data["jscript"] =$data["javascript"] =  $data["javascpt"] =$data["javascrt"] =  $data["jsdislett"] =  $data["ajaxphp"] ='';
                
                $this->gibbet_model->login_user($username); 	 
                /* use AJAX "close- browser" watch dog function to: 1. Punish the gamer if he closes the browser without word guess.!!-> Note, the listener is going to activate the function also on every page reload i.e. that will happened
                 when instant word guess is made. To avoid this is needed to control if the button for instant guess was pressed with a 2nd check here. For the purpose data['clickedBefore'] is initialised in gibbet.js  
                2. Get the user off-line to get him out of the play room and make him invisible for other online players*/              
                $data["jsclose"] =   " <script type='text/javascript'>
               window.addEventListener('beforeunload', function (e) {
if ($('#word').html()!=''&& !$('#guessButt').data('clickedBefore')){
                $.ajax({
                url:'browser_close',  // call the fuction below in this controller to punish user 
                type:'POST',
                 //  async:false,           so browser waits till xhr completed
                    success:function() {    //do something on success...
                    }
                    });
}
                    });
                		
 window.addEventListener('beforeunload', function (e) {
if (!$('#guessButt').data('clickedBefore')){
                $.ajax({
                url:'browser_close_online',  // call the fuction below in this controller to get user off-line
                type:'POST',
                 //  async:false,           so browser waits till xhr completed
                    success:function() {    //do something on success...
                   }
                    });
}
                    });

                </script>";
                
                //enable letter buttons disabled in html and js file
                $data["jsdislett"] ="<script type='text/javascript'>
                $(document).ready(function(){
                    
        		if ($('#word').html()!=''){
        		    $('.btn-sm').prop('disabled', false);
        		    }
                    });
        		   </script>";
                
                if (isset ($_POST['challange']) ) { // the user send challange to other player
                    
                    $this->gibbet_model->clear_progress($username); // clear table values if this is not player's 1st challenge
                     $this->session->unset_userdata('msg_sorry');//in case there was a lost game before                                                      
                    
                    $this->form_validation->set_rules('slcPlayer', 'за селекция на играч',  'required');
                    $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); //<-- See also .css and config.php. In Form_validation class default erro_prefix is <p>
                    
                    if($this->form_validation->run()===false){
                        
                        $data['image']='assets/img_gibb/01.png';
                        
                        $this->load->view('templates/header_gibbet');
                        $this->load->view('gibbet/gameOnline',$data);
                        $this->load->view('templates/footer_gibbet');
                        
                    }else {                        
                     
                        $enemy_field=$this->input->post('slcPlayer'); // need to pass this variable to the server
                        $_SESSION["enemyname"]=  $enemy_field;
                        $this->session->mark_as_temp('enemyname', 3600); // unset session in 1 h
                        $enemy =$this->session->enemyname;
                        $data["enemy"] = $enemy;
                        $this->gibbet_model->challng_user($enemy,$username ); // ! THIS ACTUALLY WILL RECORD CHALLENGE FOR THE ENEMY IN THE DATABASE - see the model

                        $data["jscript"] = "<script type='text/javascript'>  $('#challangeSentModal').modal({show: true, backdrop:'static',keyboard: false});   $('#challangeSentModal').on('hidden.bs.modal', function () { });</script>";
                       
             /* THIS IS SSE -HTML5 logic to close the challangeSentModal and click hiddenwordreturn button when conditions in  challenge_accepted method below are met     */      
                        $data["javascript"] = $this->view_accepted();
                      
                        $data['image']='assets/img_gibb/01.png';
                        
                        $this->load->view('templates/header_gibbet');
                        $this->load->view('gibbet/gameOnline',$data);
                        $this->load->view('templates/footer_gibbet');
                    }
                
                } else if (isset ($_POST['accept']) ) { // the user has been challenged , accept the challange and his browser take a random word of any type from DB
                    
                    $array_items = array('hiddenWord', 'Discript', 'gibbetWord','usedLetters','msg_sorry','loseModal');
                    $this->session->unset_userdata( $array_items);//clear out sessions from previous game
                                       
                    
                    $this->gibbet_model->clear_progress($username); // clear table values if this is not player's 1st challenge accepted
                 $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                $this->session->set_flashdata('msg',$helloUser);
                    
                $wordArray = $this->gibbet_model-> get_hidden_word(true,$username);       // get the word with dashes and discription from the model
                
                $_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["Discript"]=$wordArray[1];
                //$_SESSION["usedLetters"] =array('@','$'); //need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
                
                $_SESSION["imageNumb"] = 1;
                
                $data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600);
                $data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600);
                
                $this->gibbet_model->ingame_user($username);
               
                //sse-html5 script to view the opponent progress:
                $data["javascrt"] = $this->view_opponent_progress();
                
          		$data['image']='assets/img_gibb/01.png';
                
          		/*A GOOD EXAMPLE HOW TO EXCHANGE AJAX-PHP DATA BETWEEN SERVER AND BROWSER TO AVOIDE RELOADING THE PAGE ON EVERY LETTER CLICK INSTEAD OF else if (isset ($_POST['Letter']) - SEE BELOW .  */
          		$data["ajaxphp"] = $this->ajax_letter_online();  
          		
          		$data['realword']=$this->session->gibbetWord;
          		
                $this->load->view('templates/header_gibbet');
                $this->load->view('gibbet/gameOnline',$data);
                $this->load->view('templates/footer_gibbet');
                    
                    
                } else if (isset ($_POST['wordReturn']) ) {// the user has challenged other player who accepted the challenge and the user get the word, hidden word and discription by him using the hiddenwordreturn button activated by SSE-HTML5 in   if (isset ($_POST['challange']) ) { // the user send challange to other player
                    
                    $array_items = array('hiddenWord', 'Discript', 'gibbetWord','usedLetters','msg_sorry','loseModal');
                    $this->session->unset_userdata( $array_items);//clear out sessions from previous game
                                       
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                    $this->session->set_flashdata('msg',$helloUser);
                    
                    $enemy =$this->session->enemyname;
                    $wordArray = $this->gibbet_model-> get_opponent_word($enemy);       // get the word with dashes and discription from the model 
                    
                    $_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["Discript"]=$wordArray[1];   
                    $_SESSION["gibbetWord"]=$wordArray[2];// <-!! the main difference between online and type methods is where all the sessions of real word are created. In gametype() and also in ($_POST['accept']) here they are created in the model by get_word()
                                                        // However here in $_POST['wordReturn']) the values of that sessions are extracted from DB where the calling player saved them on his request
                    //$_SESSION["usedLetters"] =array('@','$'); //need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
                    
                    $_SESSION["imageNumb"] = 1;
                    
                    $data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600);
                    $data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600);
                  
                    
                    $this->gibbet_model->ingame_user($username);
                    
                    //sse-html5 script to view the opponent progress:
                    $data["javascrt"] = $this->view_opponent_progress();
                                       
                    $data['image']='assets/img_gibb/01.png';
                    
                    /*A GOOD EXAMPLE HOW TO EXCHANGE AJAX-PHP DATA BETWEEN SERVER AND BROWSER TO AVOIDE RELOADING THE PAGE ON EVERY LETTER CLICK INSTEAD OF else if (isset ($_POST['Letter']) - SEE BELOW .  */
                    $data["ajaxphp"] = $this->ajax_letter_online();  
                    
                    $data['realword']=$this->session->gibbetWord;
                    
                    $this->load->view('templates/header_gibbet');
                    $this->load->view('gibbet/gameOnline',$data);
                    $this->load->view('templates/footer_gibbet');
                
                 /*
                }else if (isset ($_POST['Letter']) ) {// click the letter buttons-> to activate this a name attr 'Letter'  must be assigned on every letter button in the html view file and remove EXCHANGE AJAX-PHP DATA BETWEEN SERVER AND BROWSER
                    
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                    $this->session->set_flashdata('msg',$helloUser);
                    
                    $usedSymbols= $this->session->usedLetters;//session array containing all used letters - initialized with the 1st letter click by guess_letter($usedSymbols) return
                    
                    $lettersArray = $this->gibbet_model->guess_letter($usedSymbols);      // get the word with dashes and discription from the model
                    $_SESSION["hiddenWord"] =$lettersArray[0];
                    $_SESSION["usedLetters"] =$lettersArray[2];
                    
                    //need to disable used letters on the screen:
                    $usedlett= '';
                    foreach ($this->session->usedLetters as $letter):
                    
                    $usedlett= trim(json_encode($letter));
                    
                    $data["jsdislett"] .="<script type='text/javascript'>
            $(document).ready(function(){
            var cyrLett = new String($usedlett);
             $('#'+cyrLett).prop('disabled', true);
                        });
                  </script>";
                    endforeach;
                    
                    $counter= $this->session->imageNumb;
                    
                    if(!$lettersArray[3]){ // the word is still Not guessed
                    	                    	
                    	//sse-html5 script to be abble to accept sorry modal and view the opponent progress on every letter button click:
                    	$data["javascrt"] = $this->view_opponent_progress();
                    	
                        if( $lettersArray[1]){ // guessed letter
                            $statistic = array(1,0,0);
                            $this->gibbet_model->set_progress($username,$statistic);
                            
                            $totstatis = array(0,0,0,1,0);
                            $this->gibbet_model->set_statistic($username,$totstatis);
                            
                        }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb !=5){
                            
                            $statistic = array(0,0,0);
                            $this->gibbet_model->set_progress($username,$statistic);
                            
                            $counter++;
                            $_SESSION["imageNumb"]=  $counter;
                            
                            $totstatis = array(0,0,0,1,0);
                            $this->gibbet_model->set_statistic($username,$totstatis);
                            
                            $data["test"]= "<script type='text/javascript'>  $('#gibbetModal').modal('show');   $('#gibbetModal').on('hidden.bs.modal', function () { }); </script>";
                            
                        }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb ==5){ // warnning message
                            
                            $statistic = array(0,0,0);
                            $this->gibbet_model->set_progress($username,$statistic);
                            
                            $counter++;
                            $_SESSION["imageNumb"]=  $counter;
                            
                            $totstatis = array(0,0,0,1,0);
                            $this->gibbet_model->set_statistic($username,$totstatis);
                            
                            $data["jscript"] = "<script type='text/javascript'>  $('#lastChanceModal').modal('show');   $('#lastChanceModal').on('hidden.bs.modal', function () { }); </script>";
                            
                        }else if( !$lettersArray[1] && $this->session->imageNumb ==6){ // GAME's OVER LOSER !
                            
                            $statistic = array(0,0,0);
                            $this->gibbet_model->set_progress($username,$statistic);
                            
                            $counter++;
                            $_SESSION["imageNumb"]=  $counter;
                            
                            $showword=$this->session->gibbetWord;
                            $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! Думата беше <strong  style="color:black" >'.$showword.'</strong></div>';
                            $this->session->set_flashdata('msg_sorry',$sorryUser); 
                                                       
                            $array_items = array('hiddenWord', 'Discript', 'gibbetWord');
                            $this->session->unset_userdata( $array_items);//clear out sessions
                                                                           
                            $this->gibbet_model->outgame_user($username);
                            
                            $data["javascript"] =  $this->fillin_select_users();// fill in the option list with available for online game users
                            //sse-html5 script to ensure that the page can get challenge sent by other player for next game:                        
                            $data["javascpt"] =  $this->view_challenge();
                            
                            $totstatis = array(1,0,1,1,0);
                            $this->gibbet_model->set_statistic($username,$totstatis);
                            
                            $data["test"]= "<script type='text/javascript'>  $('#gibbetModal').modal('show');   $('#gibbetModal').on('hidden.bs.modal', function () { }); </script>";
                                                                 
                        }
                                               
                    }else{ //The word is GUESSED !!
                        
                        $statistic = array(1,1,0);
                        $this->gibbet_model->set_progress($username,$statistic);
                        $data["jscript"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
                     
                        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','usedLetters');
                        $this->session->unset_userdata( $array_items);//clear out sessions
                        $counter=1;
   // !!! THERE SOULD NOT VIEW OPPONENT PROGRESS $this->view_opponent_progress(); BECAUSE IF THE OPPONENT GUESSE MEANWHILE ALSO THE WORD THE SORRY MODAL APPEAR ON THIS BROWSER NO MATTER THAT THIS PLAYER WON !!
                        $this->gibbet_model->outgame_user($username);
                        
           				$data["javascript"] =  $this->fillin_select_users();// fill in the option list with available for online game users
                        //sse-html5 script to ensure that the page can get challenge sent by other player for next game:
						$data["javascpt"] =  $this->view_challenge();
																													
						$data["javascrt"] =''; // !! must be declared to clear the value of view_opponent_progress(); !!
						
						$totstatis = array(1,1,0,1,0);
						$this->gibbet_model->set_statistic($username,$totstatis);
               }
                                       
                 	$data['word']=$this->session->hiddenWord;
                    $data['discrip']=$this->session->Discript;
                    
                    $data['image']='assets/img_gibb/0'.$counter.'.png';
                                                      
                    $this->load->view('templates/header_gibbet');
                    $this->load->view('gibbet/gameOnline',$data);
                    $this->load->view('templates/footer_gibbet');
                    
                    */
                    
                }else if (isset ($_POST['instantGuess']) ) {// click the guess buttons
                    
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                    $this->session->set_flashdata('msg',$helloUser);
                    $showword=$this->session->gibbetWord;
                    $result = $this->gibbet_model->guess_word();
                                                           
                    If($result!=0){ //instant guess failed
                        //sse-html5 script to be abble to accept sorry modal and view the opponent progress on every letter button click or instant guess:
                        $data["javascrt"] = $this->view_opponent_progress();
                        
                        $statistic = array(0,0,0);
                        $this->gibbet_model->set_progress($username,$statistic);
                        $data["jscript"] = "<script type='text/javascript'>  $('#loserModal').modal('show');   $('#loserModal').on('hidden.bs.modal', function () { }); </script>";
                                              
                        $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! Думата беше <strong  style="color:black" >'.$showword.'</strong></div>';
                        $this->session->set_flashdata('msg_sorry',$sorryUser);
                        
                        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
                        $this->session->unset_userdata( $array_items);//clear out sessions
                                          
                        $data['image']='assets/img_gibb/07.png';
                        
                        $totstatis = array(1,0,1,0,0);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                                           
                    }else{// instant guess done
                         $statistic = array(0,1,1);
                        $this->gibbet_model->set_progress($username,$statistic);
                        $data["jscript"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
                        
                        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
                        $this->session->unset_userdata( $array_items);//clear out sessions
                        
                        $_SESSION["loseModal"]=  true; // see view_opponent_progress where this flag prevents appearance of #loseOnlineModal if the other player(loser) guesses the word afterwards
                        
                        $data['image']='assets/img_gibb/01.png';
                        
                        $totstatis = array(1,1,0,0,1);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                                                                                          
                    }
                    $this->gibbet_model->outgame_user($username);
                    
                    $data["javascript"] =  $this->fillin_select_users();// fill in the option list with available for online game users
                      //sse-html5 script to view challange modal and name of the opponent who send it inside
					$data["javascpt"] = $this->view_challenge();
                    
					$data['realword']=$showword;
					
                    $this->load->view('templates/header_gibbet');
                    $this->load->view('gibbet/gameOnline',$data);
                    $this->load->view('templates/footer_gibbet');
                    
                    
                } else if (isset ($_POST['reject']) ) {
                    $enemy=$this->session->enemyname;
                    if($enemy){                  //<--if the game call is made by current user (see above)
                        $this->gibbet_model->unchallng_user($enemy); // - see the model
                        $this->session->unset_userdata('enemyname');
                        
                    }else{//<--if the game call is made by user outside (see below)
                        $this->gibbet_model->unchallng_user($username); 
                    }
                    
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.', предизвикайте играч от списъка и отгатнете преди него думата която ще зареди компютъра. Използвайте клавиатурата в дясно, за да отгатнете липсващите букви. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
                    $this->session->set_flashdata('msg',$helloUser);      
                    
                    $data["jscript"] =  $this->fillin_select_users();// fill in the option list with available for online game users
                   
                    
                   //sse-html5 script to view challange modal and name of the opponent who send it inside
                    $data["javascript"] = $this->view_challenge();
           
                    $data['image']='assets/img_gibb/01.png';                  
                    
                    $this->load->view('templates/header_gibbet');
                    $this->load->view('gibbet/gameOnline',$data);
                    $this->load->view('templates/footer_gibbet');
                                   	
               } else if (isset ($_POST['exitUser']) ) {
                   $this->gibbet_model->clear_progress($username); // clear table values on exit 
                   $this->gibbet_model->logout_user($username); 
                   $this->gibbet_model->outgame_user($username);
                   session_destroy();//clear out all sessions 
                    redirect('', 'location');
                
               } else if (isset ($_POST['loseExit']) ) { // exit without ending current game - lose points
               	
              // 	$totstatis = array(1,0,1,0,0);
            //   	$this->gibbet_model->set_statistic($username,$totstatis);
               	
               	$this->gibbet_model->logout_user($username);
               	$this->gibbet_model->outgame_user($username);
               	session_destroy();//clear out all sessions
               	/*$array_items = array('hiddenWord', 'Discript', 'gibbetWord','username','imageNumb');
               	 $this->session->unset_userdata( $array_items);//clear out all sessions       */
               	redirect('', 'location');
                    
                } else if (isset ($_POST['newSelfGame']) ) { // go to the method for online play
                    $this->gibbet_model->clear_progress($username); // clear table values on exit gamer room
                    $this->gibbet_model->logout_user($username); 
                    $this->gibbet_model->outgame_user($username);
                    $this->gametype($username); 
                
                } else if (isset ($_POST['loseToSelfGame']) ) { // go to the method for online play
                    
                //	$totstatis = array(1,0,1,0,0);
                //	$this->gibbet_model->set_statistic($username,$totstatis);
                	
                	$this->gibbet_model->clear_progress($username); // clear table values on exit gamer room
                	$this->gibbet_model->logout_user($username);
                	$this->gibbet_model->outgame_user($username);
                	$this->gametype($username); 
                	
                }else{// this is for initial load through gametype() call - see above 
                	$this->gibbet_model->clear_progress($username); // clear table values on entering gamer room
                	$this->gibbet_model->outgame_user($username);  //in case user closed windows before without exit/log out this will clear important data in DB
                	
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,предизвикайте играч от списъка и отгатнете преди него думата която ще зареди компютъра. Използвайте клавиатурата в дясно, за да отгатнете липсващите букви. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
                    $this->session->set_flashdata('msg',$helloUser);                    
                    $this->session->set_flashdata('msg_sorry',"");
                   
                    $data["jscript"] =  $this->fillin_select_users();// fill in the option list with available for online game users
                         
                    //sse-html5 script to view challange modal and name of the opponent who send it inside
                    $data["javascript"] = $this->view_challenge();
					                                       
                    $data['image']='assets/img_gibb/01.png';
                                     
                    $this->load->view('templates/header_gibbet');
                    $this->load->view('gibbet/gameOnline',$data);
                    $this->load->view('templates/footer_gibbet');
                                        
                }
            }
            }
         
            
            public function browser_close(){
                
                $name =$this->session->username;
                
                $statistic = array(1,0,1,0,0);
                $this->gibbet_model->set_statistic($name,$statistic);
              // session_destroy();//clear out all sessions 
              
            }
            
            
            public function browser_close_online(){     

                $name =$this->session->username;
                
             //     $this->gibbet_model->outgame_user($name);
                /* !!! outgame_user() is playing dangerous with AJAX because on every click within the form CodeIgniter makes page rendering(unload-reload) and if this is activated we outgame user because the windows unload event listener() TREATS RENDERING AS UNLOAD and activate this function.
                there's not problem with   logout_user(); because login_user is started on every page rendering - see above gameonline() !!!*/   
                $this->gibbet_model->logout_user($name);
              
            }
            
      
            private function ajax_letter(){
                
              $js=  " <script type='text/javascript'>
  $(document).ready(function(){
                    
$('.btn-sm').click(function(e) {
 var getButt = $(this).val();
//var dataTosend='Letter='+getButt;
                    
	e.preventDefault();  // !!! <--this is to prevent submitting form and get out from isset (s_POST['wordRequest'])
                    
         $.ajax({
         url:'letter_click',  // call the fuction below in this controller
         type:'POST',
         dataType: 'JSON',
                                        //data: dataTosend,  -> alternative way to present data in ajax
         data: {'Letter' : getButt },         // !! THIS IS TO ASSIGN A NAME ATTRIBUTE TO THE HTML ELEMENT THAT PHP POST FROM i.e. LETTER BUTTONS. THIS WAY NAME=...IS NOT NECCESSERY IN HTML view file
         cache: false,                       //<- option , works also w/o it
         async:false,                       //<- option , works also w/o it - so browser waits till xhr completed
         error: function() {
              alert('Something is wrong with php-AJAX call');
           },
         success: function(ajax_data) {    //do something on success...
                    
 if(ajax_data.gib=='gibbMod'){
 $('#gibbetModal').modal('show');
}else if(ajax_data.gib=='lastChanceMod'){
 $('#lastChanceModal').modal('show');
}else if(ajax_data.gib=='succMod'){
 $('#successModal').modal('show');
}
                    
 //to prevent same letter click it's needed to disable used letters on the screen:
  
var cyrLett = ajax_data.disabl;
 $('#'+cyrLett).prop('disabled', true);

/*  For online game when only guessed letters are disabled:               
var cyrLett = '';
var letters = ajax_data.disabl; //array
letters.forEach(disableLett);
                    
function disableLett(value) {
                    
   cyrLett = new String(value);
                    
if(cyrLett!='^'){
 $('#'+cyrLett).prop('disabled', true);
}
}
        */

            
                // visualise results w/o page reload
 $('#word').text(ajax_data.wrd);
$('#discrpt').text(ajax_data.dscrp);
$('#realwrd').text(ajax_data.realword);
$('img').attr('src',ajax_data.imag);
 $('#tot').text(ajax_data.tot);
$('#won').text(ajax_data.win);
 $('#lost').text(ajax_data.lose);
$('#gues').text(ajax_data.gues);
$('#instnt').text(ajax_data.instn);
             
if(ajax_data.flashlft!=''){
$('.btn-sm').prop('disabled', true); // disable letters on lose or win
}
       
$('#leftflash').after(ajax_data.flashlft); // elegant way to substitute for CodeIgniter flash data by adding new div element to HTML existing
$('#rightflash').after(ajax_data.flashrgt);

         }// success ends here
                    
         });
        		//return false; -> alternative to e.preventDefault();
});
});
                    
         </script>";
                                               
               return $js; 
                
            }
            
            
            public function letter_click (){
                       
                $letter_field = $this->input->post('Letter');
                $javascr= $javascript= $helloUser=$sorryUser='';
                
                $username =$this->session->username;
               
              //  $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' , желаем Ви късмет! </div>';
             //   $this->session->set_flashdata('msg',$helloUser);
              
                $usedSymbols= $this->session->usedLetters;//session array containing all used letters - initialized with the 1st letter click by guess_letter($usedSymbols) return
               
                $lettersArray = $this->gibbet_model->guess_letter($usedSymbols);      // get the word with dashes and discription from the model
                $_SESSION["hiddenWord"] =$lettersArray[0];
                $_SESSION["usedLetters"] =$lettersArray[2];
               
                /*to prevent same letter click it's needed to disable used letters on the screen: -> THIS IS DONE ABOVE IN THE JAVA SCRIPT
                $usedlett='';
                foreach ($this->session->usedLetters as $letter):
                
                $usedlett= trim(json_encode($letter));
              
                $javascript .="<script type='text/javascript'>
            $(document).ready(function(){
            var cyrLett = new String($usedlett);
             $('#'+cyrLett).prop('disabled', true);
                        });
                  </script>";
                endforeach;
                                  */
                $counter= $this->session->imageNumb;
                $showword='';
                if(!$lettersArray[3]){ // the word is still Not guessed
                    if( $lettersArray[1]){ // guessed letter
                        $statistic = array(0,0,0,1,0);
                        $this->gibbet_model->set_statistic($username,$statistic);
                        
                    }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb !=5){
                        
                        $statistic = array(0,0,0,1,0);
                        $this->gibbet_model->set_statistic($username,$statistic);
                        
                        $counter++;
                        $_SESSION["imageNumb"]=  $counter;
                        
                        $javascr= 'gibbMod';
                       
                    }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb ==5){ // warnning message
                        
                        $statistic = array(0,0,0,1,0);
                        $this->gibbet_model->set_statistic($username,$statistic);
                        
                        $counter++;
                        $_SESSION["imageNumb"]=  $counter;
                        
                        $javascr = "lastChanceMod";
                        
                    }else if( !$lettersArray[1] && $this->session->imageNumb ==6){ // GAME's OVER LOSER !
                        
                        $statistic = array(1,0,1,1,0);
                        $this->gibbet_model->set_statistic($username,$statistic);
                        
                        $counter++;
                        $_SESSION["imageNumb"]=  $counter;
                        
                        $showword=$this->session->gibbetWord;
                        $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! Думата беше <strong  style="color:black" >'.$showword.'</strong></div>';
                        //$this->session->set_flashdata('msg_sorry',$sorryUser); <-session can be set here but can not be read above in AJAX without page reload !!
                        $helloUser='<div class="alert alert-success text-center">'. $username.', изберете отново категория и отгатнете коя е думата която ще зареди компютъра. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';                        
                        //$this->session->set_flashdata('msg',$helloUser); <-session can be set here but can not be read above in AJAX without page reload !!
                        
                        $array_items = array('hiddenWord', 'Discript', 'gibbetWord');
                        $this->session->unset_userdata( $array_items);//clear out sessions
                        
                        $javascr= "gibbMod";
                        
                    }
                    
                }else{ //The word is GUESSED !!
                    
                    $statistic = array(1,1,0,1,0);
                    $this->gibbet_model->set_statistic($username,$statistic);                   
                    
                    $array_items = array('hiddenWord', 'Discript', 'gibbetWord');
                    $this->session->unset_userdata( $array_items);//clear out sessions
                    $counter=1;
                    $helloUser='<div class="alert alert-success text-center">'. $username.', изберете отново категория и отгатнете коя е думата която ще зареди компютъра. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';                   
                    // $this->session->set_flashdata('msg',$helloUser); <-session can be set here but can not be read above in AJAX without page reload !!
                     $javascr = "succMod";
                     
                }
                
                                              
                $word=$this->session->hiddenWord;
                $discrip=$this->session->Discript;
                
                $image=base_url().'assets/img_gibb/0'.$counter.'.png';
               /*  */               
                $statArray = $this->gibbet_model->get_statistic($username);
                $total=$statArray[0]; $won=$statArray[1];$lost=$statArray[2];$guesses=$statArray[3];$instant=$statArray[4];
                                                               
                $ajax_data['disabl']=  $letter_field;//$this->session->usedLetters;
                $ajax_data['gib']=$javascr;
                                    
                $ajax_data['imag']=$image; $ajax_data['wrd']=$word;  $ajax_data['dscrp']=$discrip;  
                
                //next is interesting conditional javascript injection in the gibbet modal without constant php variable in the view - see AJAX above and the view file:
                if($counter==7){
                    $ajax_data['realword']="Думата беше ".$showword;
                }else{
                    $ajax_data['realword']='';
                }
                
                $ajax_data['tot']=$total; $ajax_data['win']=$won; $ajax_data['lose']=$lost;
                $ajax_data['gues']=$guesses;  $ajax_data['instn']=$instant;
                $ajax_data['flashlft']=$helloUser; $ajax_data['flashrgt']=$sorryUser;
               
                echo json_encode($ajax_data); // convert in a json string the requested from server data array and send it to ajax script above
                
            }
            
            private function ajax_letter_online(){
                
                $js=  " <script type='text/javascript'>
 $(document).ready(function(){          
      
$('.btn-sm').click(function(e) {
 var getButt = $(this).val();
//var dataTosend='Letter='+getButt;
                  
	e.preventDefault();  // !!! <--this is to prevent submitting form and get out from isset (s_POST['wordRequest'])
                    
         $.ajax({
         url:'letter_click_online',  // call the fuction below in this controller
         type:'POST',
         dataType: 'JSON',
                                        //data: dataTosend,  -> alternative way to present data in ajax
         data: {'Letter' : getButt },         // !! THIS IS TO ASSIGN A NAME ATTRIBUTE TO THE HTML ELEMENT THAT PHP POST FROM i.e. LETTER BUTTONS. THIS WAY NAME=...IS NOT NECCESSERY IN HTML
         cache: false,                       //<- option , works also w/o it
         async: true,                       //<- option , works also w/o it - so browser doesn't wait till ajax request completed
         error: function() {
              alert('Something is wrong with php-AJAX call');
           },
         success: function(ajax_data) {    //do something on success...
                  
 if(ajax_data.gib=='gibbMod'){
 $('#gibbetModal').modal('show');
}else if(ajax_data.gib=='lastChanceMod'){
 $('#lastChanceModal').modal('show');
}else if(ajax_data.gib=='succMod'){
 $('#successModal').modal('show');
}
     
              
 //to prevent same letter click it's needed to disable used letters on the screen:
                    
var cyrLett = '';
var letters = ajax_data.disabl;
letters.forEach(disableLett);
                    
function disableLett(value) {
                    
   cyrLett = new String(value);
                    
if(cyrLett!='^'){
 $('#'+cyrLett).prop('disabled', true);
}
}
                    
                // visualise results w/o page reload
 $('#word').text(ajax_data.wrd);
$('#discrpt').text(ajax_data.dscrp);
$('#realwrd').text(ajax_data.realword);
$('img').attr('src',ajax_data.imag);

    
if(ajax_data.fillin_view){ //<--HERE DOWN IS COPIED THE CODE OF fillin_select_users() and view_challenge() FUNCTIONS CAUSE THERE'S NO WAY FOR THEM TO BE PASSED THROUGH json_encode(ajax_data) 

 $('.btn-sm').prop('disabled', true); // disable letters on lose or win

if(typeof(EventSource) !== 'undefined') {
                  
 var source = new EventSource('field_select_users');//the function below                                                                                      
source.onmessage = function(event) {

var playerList = document.getElementById('slctPlayer');
					
var nameSelect = JSON.parse(event.data); var onlineLength = nameSelect.length; // js associative array with players online and their in_game statuses
 
var selected = '';
for (var names in nameSelect) {// loop through associative js array where names is the key i.e. the name but nameSelect[names] is the value i.e. the status - see below field_select_users()

if(playerList.value==names){
selected = names; // <-get selected name if there's such one
}
}

while (playerList.options.length) {  // remove previous added options
playerList.remove(0); }	
 

for (var names in nameSelect) { // loop through associative js array where names is the key i.e. the name but nameSelect[names] is the value i.e. the status - see below field_select_users()

var optionPlayer='';


        if(nameSelect[names]==true){ // if the player is in other online game already color its name in magenta and disable his option
        
optionPlayer = new Option(names + ' - играе');

optionPlayer.style.color = 'magenta';
optionPlayer.disabled = true;
                }else{

optionPlayer = new Option(names);

            }
 playerList.options.add(optionPlayer); 
          
if(names==selected){  
        playerList.value=names; // <-select again this option i.e. page rendering in every 3 sec should not change already selected player
 	}
}
 
                if(playerList.options.length==0){
         var noPlayer = new Option('Няма в момента други играчи!');
            playerList.options.add(noPlayer);
	playerList.disabled = true;
                        }else{
			 var i;
		        for (i = 0; i < onlineLength; i++) {
			playerList.options[i].value = nameSelect[i];   //!! This is to set option value as text ot number - important for php POST
			}
playerList.disabled = false;
            }

 };
     
         } else {
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 ! Моля, използвайте Chrome, FireFox, Opera или Safary');
                    } 

   //<--HERE DOWN IS COPIED THE CODE OF view_challenge()

 if(typeof(EventSource) !== 'undefined') {
                                                                        // example with 2 functions below-->var source = new EventSource('demo_sse1');
                    var source = new EventSource('challenge_view');//the function below
                    source.onmessage = function(event) {
            		
                  $('#challangeAcceptModal').modal(event.data);
  
                    };
            		
					var source2 = new EventSource('challenge_message');//the function below
                    source2.onmessage = function(event) {
   					document.getElementById('acceptMessage').innerHTML = event.data + '<br>';
                    };
               } else {
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5! Моля, използвайте Chrome, FireFox, Opera или Safary');
                    }
}

           
$('#leftflash').after(ajax_data.flashlft); // elegant way to substitute for CodeIgniter flash data by adding new div element to HTML existing
$('#rightflash').after(ajax_data.flashrgt);

         } // success ends here
    });                
         
      		
//document.getElementById('testis').innerHTML = 'ФЛАГЪТ Е: '+loseModal;
  

 //return false; -> alternative to e.preventDefault();
}); 
});
                    
         </script>";
                
                return $js;
                
            }
            
            public function letter_click_online(){
                
                $javascr = $helloUser=$sorryUser= '';
                $username =$this->session->username;
                $javascript =false;
               // $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
              //  $this->session->set_flashdata('msg',$helloUser);
                
                $usedSymbols= $this->session->usedLetters;//session array containing all used letters - initialized with the 1st letter click by guess_letter($usedSymbols) return
                
                $lettersArray = $this->gibbet_model->guess_letter($usedSymbols);      // get the word with dashes and discription from the model
                $_SESSION["hiddenWord"] =$lettersArray[0];
                $_SESSION["usedLetters"] =$lettersArray[2];
                      
                /*to prevent same letter click it's needed to disable used letters on the screen: -> THIS IS DONE ABOVE IN THE JAVA SCRIPT
                 $usedlett='';
                 foreach ($this->session->usedLetters as $letter):
                 
                 $usedlett= trim(json_encode($letter));
                 
                 $data["jsdislett"] .="<script type='text/javascript'>
                 $(document).ready(function(){
                 var cyrLett = new String($usedlett);
                 $('#'+cyrLett).prop('disabled', true);
                 });
                 </script>";
                 endforeach;
                 */
                              
                $counter= $this->session->imageNumb;
                
                if(!$lettersArray[3]){ // the word is still Not guessed
                    
                    //sse-html5 script to be abble to accept sorry modal and view the opponent progress on every letter button click:
                   
                    
                    if( $lettersArray[1]){ // guessed letter
                        $statistic = array(1,0,0);
                        $this->gibbet_model->set_progress($username,$statistic);
                        
                        $totstatis = array(0,0,0,1,0);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                        
                    }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb !=5){
                        
                        $statistic = array(0,0,0);
                        $this->gibbet_model->set_progress($username,$statistic);
                        
                        $counter++;
                        $_SESSION["imageNumb"]=  $counter;
                        
                        $totstatis = array(0,0,0,1,0);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                        
                        $javascr= 'gibbMod';
                        
                        
                        
                    }else if( !$lettersArray[1] && $this->session->imageNumb !=6 && $this->session->imageNumb ==5){ // warnning message
                        
                        $statistic = array(0,0,0);
                        $this->gibbet_model->set_progress($username,$statistic);
                        
                        $counter++;
                        $_SESSION["imageNumb"]=  $counter;
                        
                        $totstatis = array(0,0,0,1,0);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                        
                        $javascr = "lastChanceMod";
                        
                    }else if( !$lettersArray[1] && $this->session->imageNumb ==6){ // GAME's OVER LOSER !
                        
                        $statistic = array(0,0,0);
                        $this->gibbet_model->set_progress($username,$statistic);
                        
                        $counter++;
                        $_SESSION["imageNumb"]=  $counter;
                        
                        $showword=$this->session->gibbetWord;
                        $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! Думата беше <strong  style="color:black" >'.$showword.'</strong></div>';
                       // $this->session->set_flashdata('msg_sorry',$sorryUser);
                        $helloUser='<div class="alert alert-success text-center">'. $username.', изберете отново играч и отгатнете преди него думата която ще зареди компютъра. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
                        //$this->session->set_flashdata('msg',$helloUser); <-session can be set here but can not be read above in AJAX without page reload !!
                        
                        $array_items = array('hiddenWord', 'Discript', 'gibbetWord');
                        $this->session->unset_userdata( $array_items);//clear out sessions
                        
                        $this->gibbet_model->outgame_user($username);
                        
                        $javascript = true;// fill in the option list with available for online game users
                        //sse-html5 script to ensure that the page can get challenge sent by other player for next game:                     
                        
                        $totstatis = array(1,0,1,1,0);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                        
                        $javascr= "gibbMod";
                        
                    }
                    
                }else{ //The word is GUESSED !!
                    
                    $statistic = array(1,1,0);
                    $this->gibbet_model->set_progress($username,$statistic);
                                       
                    $helloUser='<div class="alert alert-success text-center">'. $username.', изберете отново играч и отгатнете преди него думата която ще зареди компютъра. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
                    // $this->session->set_flashdata('msg',$helloUser); <-session can be set here but can not be read above in AJAX without page reload !!
                    
                    $array_items = array('hiddenWord', 'Discript', 'gibbetWord');
                    $this->session->unset_userdata( $array_items);//clear out sessions
                    $counter=1;
                    // !!! THERE SOULD NOT VIEW OPPONENT PROGRESS $this->view_opponent_progress(); BECAUSE IF THE OPPONENT GUESSE MEANWHILE ALSO THE WORD THE SORRY MODAL APPEAR ON THIS BROWSER NO MATTER THAT THIS PLAYER WON !!
                    $this->gibbet_model->outgame_user($username);
                    
                    $javascript = true;// fill in the option list with available for online game users
                    //sse-html5 script to ensure that the page can get challenge sent by other player for next game:
                   
                    $totstatis = array(1,1,0,1,0);
                    $this->gibbet_model->set_statistic($username,$totstatis);
                    
                    $javascr = "succMod";
                    
                    $_SESSION["loseModal"]=  true; // see view_opponent_progress where this flag prevents appearance of #loseOnlineModal if the other player(loser) guesses the word afterwards
                }
                
                $word=$this->session->hiddenWord;
                $discrip=$this->session->Discript;
                
                $image=base_url().'assets/img_gibb/0'.$counter.'.png';
                /*  */
                
                
                $ajax_data['disabl']= $this->session->usedLetters;
                $ajax_data['gib']=$javascr;
                
                $ajax_data['imag']=$image; $ajax_data['wrd']=$word;  $ajax_data['dscrp']=$discrip;
                
                //next is interesting conditional javascript injection in the gibbet modal without constant php variable in the view - see AJAX above and the view file:
                if($counter==7){
                    $ajax_data['realword']="Думата беше ".$showword;
                }else{
                    $ajax_data['realword']='';
                }
                                                
                $ajax_data['fillin_view']= $javascript;  
                
                $ajax_data['flashlft']=$helloUser; $ajax_data['flashrgt']=$sorryUser;
                
                echo json_encode($ajax_data); // convert in a json string the requested from server data array and send it to ajax script above
                
            }
            
            
            
              /* NEXT DOWN ARE COUPLES OF ""SCRIPT - PHP"" FUNCTIONS TO CONTROL DYNAMICALY (WITHOUT PAGE RELOAD) WITH SSE-HTML5 THROUGH DATABASE MOST OF THE VARIABLES LIKE FILLING PLAYERS SELECT LIST, 
                 TRIGGER MODALS, GET OPPONENT PROGRESS. ITS POSSIBLE ALL OTHER VARIABLES TO BE RENDERED THIS WAY !!!*/
            private  function   fillin_select_users(){
                               
                $jscriptbody = "<script type='text/javascript'>\n
                
 if(typeof(EventSource) !== 'undefined') {
                  
 var source = new EventSource('field_select_users');//the function below                                                                                      
source.onmessage = function(event) {

var playerList = document.getElementById('slctPlayer');
					
var nameSelect = JSON.parse(event.data); var onlineLength = nameSelect.length; // js associative array with players online and their in_game statuses
 
var selected = '';
for (var names in nameSelect) {// loop through associative js array where names is the key i.e. the name but nameSelect[names] is the value i.e. the status - see below field_select_users()

if(playerList.value==names){
selected = names; // <-get selected name if there's such one
}
}

while (playerList.options.length) {  // remove previous added options
playerList.remove(0); }	
 

for (var names in nameSelect) { // loop through associative js array where names is the key i.e. the name but nameSelect[names] is the value i.e. the status - see below field_select_users()

var optionPlayer='';

/**/ 
        if(nameSelect[names]==true){ // if the player is in other online game already color its name in magenta and disable his option
        
optionPlayer = new Option(names + ' - играе');

optionPlayer.style.color = 'magenta';
optionPlayer.disabled = true;
                }else{

optionPlayer = new Option(names);

            }
 playerList.options.add(optionPlayer); 
          
if(names==selected){  
        playerList.value=names; // <-select again this option i.e. page rendering in every 3 sec should not change already selected player
 	}
}
 
                if(playerList.options.length==0){
         var noPlayer = new Option('Няма в момента други играчи!');
            playerList.options.add(noPlayer);
	playerList.disabled = true;
                        }else{
			 var i;
		        for (i = 0; i < onlineLength; i++) {
			playerList.options[i].value = nameSelect[i];   //!! This is to set option value as text ot number - important for php POST
			}
playerList.disabled = false;
            }

 };
     
         } else {
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 ! Моля, използвайте Chrome, FireFox, Opera или Safary');
                    }         		
 					</script>" ;
                
                return  $jscriptbody ;
            }
            
                                  
            
            public  function   field_select_users(){
                               
                $name =$this->session->username;
                
                //$usersOnline=$this->gibbet_model->get_users('name, id, logged_in, in_game');
           
               $this->db->select('name, id, logged_in, in_game');
               $usersOnline= $this->db->get_where('users','logged_in = 1')->result_array();
 
                 if(count($usersOnline)>0){ //to exclude current user to appear as option in the list when alone in the game room

                 	$selecttUsers=$users_status=array();
                 	
                 	$getLog =$ableOption=false;
                    $printoutUsers='';
                   
                    foreach($usersOnline as $user):
                    
                    $getLog = $user["logged_in"];
                    $printoutUsers =  $user["name"];
                    
                    
                    if($getLog && $printoutUsers!=$name){ // if user is available for online game and this is not same user as the logged one
                                                                   
                        $ableOption = $user["in_game"];
                                                                                                 
                        $users_status[$printoutUsers]=$ableOption; //associative array with elements containing user and its status in_game
                        array_push($selecttUsers, $printoutUsers );
                    }
                   endforeach;
                    
                   $jsusers= json_encode($users_status);
                   
                 
                    echo "data: " .$jsusers. "\n\n";
                                
                 } else{
                 		echo "data:  \n\n"; 
                 }  /* */
     /*   */        
                 header("Content-Type: text/event-stream");
                 header('Cache-Control: no-cache');
                 header("Connection: keep-alive");
                                 
                 ob_end_flush();
                 flush();
            }
            
          
            /*WORKING STATIC VARIANT FOR FILLING PLAYERS SELECT LIST
             private  function   fillin_select_users($name){
             
             $usersOnline=$this->gibbet_model->get_users('name, id, logged_in, in_game');
             
             $jscriptbody ='';
             
             if($usersOnline){
             
             $jscriptbody = "<script type='text/javascript'>\n
             
             $(document).ready(function(){ // without this the select list is not possible to be filled in before view
             
             var numberID = []; // id's array
             var playerList = document.getElementById('slctPlayer');
             while (playerList.options.length) {  // remove previous added options
             playerList.remove(0);
             }
             
             ";
             $getLog =$getGameStatuse=false;
             $ableOption = $getNumber=$numbUser =$printoutUsers=$toprintUsers='';
             
             foreach($usersOnline as $user):
             
             $getLog = $user["logged_in"];
             $printoutUsers =  $user["name"];
             
             
             if($getLog && $printoutUsers!=$name){ // if user is available for online game and this is not same user as the logged one
             
             $getNumber = $user["id"];
             $numbUser = json_encode($getNumber);
             
             $toprintUsers = json_encode($printoutUsers);
             $getGameStatuse = $user["in_game"];
             
             $ableOption = json_encode($getGameStatuse);
             
             $jscriptbody  .=	"\n
             
             var userId = new String($numbUser);      //  for id_Numb -> getNumber
             var valueOption = new String($toprintUsers);
             var onscreenPlayers = new String ($toprintUsers);
             var optionPlayer = new Option(onscreenPlayers);
             var disableOpt = new String($ableOption);
             
             
             if(disableOpt==1){ // if the player is in online game already color it in magenta and disable his option
             
             onscreenPlayers = new String ($toprintUsers + ' - играе');
             optionPlayer = new Option(onscreenPlayers);
             
             optionPlayer.style.color = 'magenta';
             optionPlayer.disabled = true;
             }
             
             playerList.options.add(optionPlayer);
             numberID[numberID.length]= valueOption;
             
             ";
             }
             endforeach;
             
             $jscriptbody  .=	"\n
             
             if(playerList.options.length==0){
             var noPlayer = new Option('Няма в момента други играчи!');
             playerList.options.add(noPlayer);
             document.getElementById('slctPlayer').disabled = true;
             }else{
             var i;
             for (i = 0; i < numberID.length; i++) {
             playerList.options[i].value = numberID[i];   //!! This is to set option value as text ot number - important for php POST
             }
             document.getElementById('slctPlayer').disabled = false;
             }
             
             
             });				//<--end of $(document).ready(function(
             </script>" ;
             
             
             }
             return $jscriptbody;
             }
             
             */
            
            
            public function view_challenge(){
            	 /* this is example of javascript ServerSendEvent for HTML5 where the function via challange_view method below listens for challenges sent
            	  * and return view with the modal triggered and name of the opponent in it - 2 functions:*/
            	
            	$jscrpt="<script type='text/javascript'>\n
            		
        				$(document).ready(function(){ // without this the select list is not possible to be filled in before view> // not a must in this case!
            		
                    if(typeof(EventSource) !== 'undefined') {
                                                                        // example with 2 functions below-->var source = new EventSource('demo_sse1');
                    var source = new EventSource('challenge_view');//the function below
                    source.onmessage = function(event) {
            		
                  $('#challangeAcceptModal').modal(event.data);
                                                                    // document.getElementById('testis').innerHTML = event.data + '<br>';
                        /*privent modal to close on background click 
                    $('#challangeAcceptModal').modal({ 			
                    backdrop: 'static',// to prevent close modal on background click
                    keyboard: false  // to prevent closing with Esc button (if you want this too)        			   
                    });
	*/
                    };
            		
					var source2 = new EventSource('challenge_message');//the function below
                    source2.onmessage = function(event) {
   					document.getElementById('acceptMessage').innerHTML = event.data + '<br>';
                    };
               } else {
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5! Моля, използвайте Chrome, FireFox, Opera или Safary');
                    }
                    });				//<--end of $(document).ready(function(
 					</script>" ;
            	return  $jscrpt;
            }
            
            
            public function challenge_view($username = NULL){
            	
            	$username =$this->session->username;
            	$challngPlayer= $this->gibbet_model->get_challenge($username);
            	
            	header("Content-Type: text/event-stream");
            	header('Cache-Control: no-cache');
            	header("Connection: keep-alive");
            	
            	if ($challngPlayer!=''){
            		
            		//  "data: show:true, backdrop:'static', keyboard:false\n\n"; /*<-- this format "data: variable\n\n";*  is a must for SSE HTML5 */            	
            		echo "data: show\n\n";
            	}else{
            	    echo "data: hide\n\n";
            	}
            	
            	// echo    "data: $challngPlayer\n\n";
            	ob_end_flush();
            	flush();
            }
            
            
            public function challenge_message($username = NULL){   
            	$username =$this->session->username;
            	$challngPlayer= $this->gibbet_model->get_challenge($username);
            	
            	header("Content-Type: text/event-stream");
            	header('Cache-Control: no-cache');
            	header("Connection: keep-alive");
            	
            	echo "data: <i>Предизвикан сте за битка на живот и смърт от $challngPlayer !</i>\n\n"; 
            	ob_end_flush();
            	flush();
            }
            
            
            public function view_accepted(){
            	/* */
            	
            	$jscrpt="<script type='text/javascript'>\n
            			
                    if(typeof(EventSource) !== 'undefined') {
            			
                    var source = new EventSource('challenge_accepted'); //the function below
                    source.onmessage = function(event) {
            			
                 $('#challangeSentModal').modal(event.data);
                  $('#hiddenwordreturn').click(); 	// push the hidden button to set (_POST['wordReturn']) and load the same random word generated in the browser that accepted the challenge
                    };
                            } else {
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 !Моля, използвайте Chrome, FireFox, Opera или Safary');
            			
                    }</script>" ;
            	          	
            	return  $jscrpt;
            }
            
         
            
            public function challenge_accepted($enemy = NULL){
                $enemy =$this->session->enemyname;
                $challngPlayer= $this->gibbet_model->get_opponent_word($enemy);
                
                header("Content-Type: text/event-stream");
                header('Cache-Control: no-cache');
                header("Connection: keep-alive");
                
                if($enemy && $challngPlayer[0]!="" && $challngPlayer[1]!="" && $challngPlayer[2]!=""){
                	                	
                    echo "data: hide\n\n";
                }else{
                    return FALSE;
                }   
                
                ob_end_flush();
                flush();
            }
            
           
            public function view_opponent_progress(){
            	
            $jscrpt="<script type='text/javascript'>\n
            		
                    if(typeof(EventSource) !== 'undefined') {
            		
                     var source1 = new EventSource('get_opponent_progress?key=0');//the function below with parameter
					var source2 = new EventSource('get_opponent_progress?key=1');
					var source3 = new EventSource('get_opponent_progress?key=2');
            		
					source1.onmessage = function(event) {
                    document.getElementById('totlett').innerHTML = event.data + '<br>';
                  };
               
                source2.onmessage = function(event) {
                    document.getElementById('won').innerHTML = event.data + '<br>';
        
					if(event.data=='1'){ 	 // trigger sorry modal
					     $('#loseOnlineModal').modal({
             			show: true,
                 			backdrop: 'static',// to prevent close modal on background click
                				 keyboard: false  // to prevent closing with Esc button (if you want this too)
                		 });
						}
                               
                  };    

                   source3.onmessage = function(event) {
                    document.getElementById('instword').innerHTML = event.data + '<br>';
                  };
                            } else {
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 ! Моля, използвайте Chrome, FireFox, Opera или Safary');
            		
                    }</script>" ;
            return  $jscrpt;
            }
            
    
            public function get_opponent_progress($enemy = NULL,$username = NULL){
            /*   */
            	
            	$key = $_REQUEST["key"];
            	
            	$enemy =$this->session->enemyname;
            	$username =$this->session->username;
            	$stopModal = $this->session->loseModal; // a flag risen on user win to stop loseOnlineModal if the other player(loser) guesses the word afterwards
            	
            	
             if($enemy!=""){ //means the current browser sent the challenge
             $statArray = $this->gibbet_model->get_progress($enemy);
             
             }else{//means the current browser has been challenged
             $challngPlayer= $this->gibbet_model->get_challenge($username);
             $statArray = $this->gibbet_model->get_progress($challngPlayer);
             }
             
            header("Content-Type: text/event-stream");
             header('Cache-Control: no-cache');
             header("Connection: keep-alive");
             
            //  echo "retry: 2000\n";	!! very important js server function for online apps - push the server requests in ms. Default value is 3 sec –3000
            
             
             if($key==1&&$stopModal){
                 echo  "data: 0\n\n"; // in case the opponent lose his result must be 0 to avoid loseOnlineModal appear on winner's screen  if the loser guesses the word afterwards
             }else{
                 echo "data: $statArray[$key]\n\n";
             }
            
             ob_end_flush();
             flush();
                       
            }
            
            //!! THE SCRIPT FOR SSE EventSource() FOR THE FUNCTION BELOW IS IN gibbet_online.js
            public function clear_opponent_progress($enemy = NULL,$username = NULL){
            
         	// the opponen was faster - you lose so clear his progress on modal close to prevent modal show again
            	$enemy =$this->session->enemyname;
            	$username =$this->session->username;
            	if($enemy!=""){ //means the current browser sent the challenge
            		
            		$this->gibbet_model->clear_progress($enemy); // clear table values
            		
            	}else{//means the current browser has been challenged
            		$challngPlayer= $this->gibbet_model->get_challenge($username);
            		$this->gibbet_model->clear_progress($challngPlayer); // clear table values
            		}
            	
            	header("Content-Type: text/event-stream");
            	header('Cache-Control: no-cache');
            	header("Connection: keep-alive");
            	
            	echo "data: \n\n";
						
            	
            	ob_end_flush();
            	flush();
            	
            }
            
            
            
            /*
             public function demo_sse(){
             
             header("Content-Type: text/event-stream");
             header('Cache-Control: no-cache');
             header("Connection: keep-alive");
             $time = date('r');
             echo "data: The server time is: {$time}\n\n".PHP_EOL;
             echo PHP_EOL;
             
             ob_end_flush();
             flush();
             
             }
             
             public function demo_sse1(){
             
             $this->output->set_header('Cache-Control: no-cache');
             $time = date('r');
             $output="data: The server time is: {$time}\n\n";
             $this->output->set_content_type('text/event-stream')->_display($output);
             
             flush();
             }*/
            
            
}//class end