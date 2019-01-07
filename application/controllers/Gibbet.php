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
       	!!!  THE MODEL $this->load->model('gibbet_model'); ALSO IS LOADED IN config/autoload.php */
           
        	$this->load->library('password');// this is my library created in applicationss/libraries on bases of  Anthony Ferrara encrypting file but made as a class with namespace
             	
        	if (isset ($_POST['send_request']) ) {
        	        	
        $data["nonamepass"] =""; // needed to pass view variables (used for injecting JS in view pages) to other function like captcha_ and name_validation() 

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
        	
            //check user name and pass
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
            
      //registration modal:
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
        		$this->load->view('templates/footer_gibbet');
        		
        	}else if (isset ($_POST['newUser']) ) {
        	    /*watch if something is entered in the fields but set_rules() check also if these are Cyrillic letters w/o numeric and their min number is 5*/
        	    $this->form_validation->set_rules('newname', 'име', 'required|regex_match[/^[а-яА-Я\p{Cyrillic}\s\-\.\!\?]+$/u]|min_length[3]|max_length[20]');
        	    $this->form_validation->set_rules('newpswr', 'парола', 'required|min_length[5]');
        	    
        	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); //<-- See also depoNariad.css and config.php. In Form_validation class default erro_prefix is <p>
        	    
        	    $user_field = $this->input->post('newname');
        	    
        	    if ($this->form_validation->run() == FALSE)  {
        	              	        
        	        $data['nonamepass']="<script type='text/javascript'> $('#signUpModal').modal({show: false}) </script>";
                 	        
        	        $this->load->view('templates/header_gibbet');
        	        $this->load->view('gibbet/enterGame',$data);   
        	        $this->load->view('templates/footer_gibbet');
        	        
        	    } else  {
        	        $alluserss=$this->gibbet_model->get_users('name'); // check 1st if the user exists:
        	               	              	        
        	        if($alluserss){
        	            
        	            $name=""; $watchdog=false;
        	            
        	            foreach ($alluserss as $user):
        	            
        	            $name = $user["name"];
        	                 	            
        	            
        	            if ($user_field==$name) {
        	            	$watchdog=true;
        	                // redirect('', 'location');
        	               
        	                 break;
        	            }
        	            endforeach;
        	        }
        	        
        	        if($watchdog){ //if user exists:
        	        	
        	        	$data["nonamepass"] = "<script type='text/javascript'>  $('#wnameModal').modal('show');   $('#wnameModal').on('hidden.bs.modal', function () { }); </script>";
        	        	
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
        		
        	}else{ //on start up
        		
        		$data['nonamepass']='';
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/enterGame',$data);   
        		$this->load->view('templates/footer_gibbet');
        		
        	}
      
}       //index() ends here

                              
        
        public  function gametype($username = NULL) {
        	$username =$this->session->username;
        	// 'item' will be erased after 300 seconds
        	$this->session->mark_as_temp('username', 3600); // unset session in 1 h
        	
        	$data['name']=$data['image']=$data['word']=$data['discrip']= "";
        	$data['total']= $data['won']=$data['lost']=$data['guesses']=$data['instant']=0;
        	        	
        	$data["jscript"] = '';
        	
        	if ($username == NULL) {
        		echo " МОЛЯ, ИЗПОЛЗВАЙТЕ ЗА ВХОД <a href='".base_url()."'> Бесеничка </a>";
        	}else {   
        	           	         		
        		$statArray = $this->gibbet_model->get_statistic($username);
        		$data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        		$data['name']=$username;
        		      		
         if (isset ($_POST['wordRequest']) ) {
        		
        	    $this->session->unset_userdata('msg_sorry');//in case there was a lost game before
        	    
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
        		$_SESSION["usedLetters"] =array('@','$'); //need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
        		
        		$data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600); 
        		$data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600); 
        		        		       		        		        		       		
        		//$data['word']=$word;
        		 $data['image']='assets/img_gibb/01.png';
        		 
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/gameType',$data);
        		$this->load->view('templates/footer_gibbet');
        	}
        	
        	} else if (isset ($_POST['newWordRequest']) ) { // exit current game - lose points and load a new word
        	    
        	    $statistic = array(1,0,1,0,0);
        	    $this->gibbet_model->set_statistic($username,$statistic);
        	    
        	    $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb','msg_sorry');        	    
        	    $this->session->unset_userdata( $array_items);//clear out session
        	        	        	    
        	    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        	    $this->session->set_flashdata('msg',$helloUser);
        	    
        	    
        	    $wordArray = $this->gibbet_model-> get_hidden_word(false,$username);       // get the word with dashes and discription from the model
        	    
        	    $_SESSION["imageNumb"] = 1;
        	    $_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["Discript"]=$wordArray[1];
        	    $_SESSION["usedLetters"] =array('@','$'); // need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
        	    
        	    $data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600);
        	    $data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600);
        	    
        	    $statArray = $this->gibbet_model->get_statistic($username);
        	    $data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    
        	    //$data['word']=$word;
        	    $data['image']='assets/img_gibb/01.png';
        	    
        	    $this->load->view('templates/header_gibbet');
        	    $this->load->view('gibbet/gameType',$data);
        	    $this->load->view('templates/footer_gibbet');
        	    
        	    
        	}else if (isset ($_POST['Letter']) ) {// click the letter buttons
        		        	
        	    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        	    $this->session->set_flashdata('msg',$helloUser);
        	    
        	    $usedSymbols= $this->session->usedLetters;//session array containing all used letters - initialized with the 1st letter click by guess_letter($usedSymbols) return
        	    
        	    $lettersArray = $this->gibbet_model->guess_letter($usedSymbols);      // get the word with dashes and discription from the model
        		 $_SESSION["hiddenWord"] =$lettersArray[0];
        		 $_SESSION["usedLetters"] =$lettersArray[2];
        		
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
        	        
        	        $array_items = array('hiddenWord', 'Discript', 'gibbetWord');
        	        $this->session->unset_userdata( $array_items);//clear out sessions
        	        
        	        $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! </div>';
        	        $this->session->set_flashdata('msg_sorry',$sorryUser); 
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
        	
        	}else if (isset ($_POST['instantGuess']) ) {// click the guess buttons
        	    
        	    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        	    $this->session->set_flashdata('msg',$helloUser);       	    
        	    
        	    $result = $this->gibbet_model->guess_word();
        	    
        	    If($result==0){ //instant guess done
   	        
        	        $statistic = array(1,1,0,1,1);
        	        $this->gibbet_model->set_statistic($username,$statistic);
        	        $data["jscript"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
        	   
        	        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
        	        $this->session->unset_userdata( $array_items);//clear out sessions
        	       
        	        $statArray = $this->gibbet_model->get_statistic($username);
        	        $data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    
        	        $data['image']='assets/img_gibb/01.png';
        	    
        	     $this->load->view('templates/header_gibbet');
        	     $this->load->view('gibbet/gameType',$data);
        	     $this->load->view('templates/footer_gibbet');
        	     
        	    }else{// instant guess failed
        	                    	    	
        	    	$statistic = array(1,0,1,1,0);
        	    	$this->gibbet_model->set_statistic($username,$statistic);
        	    	$data["jscript"] = "<script type='text/javascript'>  $('#loserModal').modal('show');   $('#loserModal').on('hidden.bs.modal', function () { }); </script>";
        	    	
        	    	
        	    	$array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
        	    	$this->session->unset_userdata( $array_items);//clear out sessions
        	    	
        	    	$statArray = $this->gibbet_model->get_statistic($username);
        	    	$data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    	
        	    	$sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! </div>';
        	    	$this->session->set_flashdata('msg_sorry',$sorryUser); 
        	    	
        	        $data['image']='assets/img_gibb/08.png';
        	        
        	        $this->load->view('templates/header_gibbet');
        	        $this->load->view('gibbet/gameType',$data);
        	        $this->load->view('templates/footer_gibbet');
        	    }
        	 
        	}else  if (isset ($_POST['exitUser']) ) {     
        	    
        	     $this->gibbet_model->logout_user($username);// log out from online game if the user has been there before 
        	     session_destroy();//clear out all sessions      	       
        	        redirect('', 'location');
      	    
        	} else if (isset ($_POST['loseExit']) ) { // exit without ending current game - lose points
        	    	        		
        		$statistic = array(1,0,1,0,0);
        		$this->gibbet_model->set_statistic($username,$statistic);  
        		
        		$this->gibbet_model->logout_user($username);
        		
        		session_destroy();//clear out all sessions 
        		/*$array_items = array('hiddenWord', 'Discript', 'gibbetWord','username','imageNumb');        		
        		$this->session->unset_userdata( $array_items);//clear out all sessions       */ 		     		       		
        	    	redirect('', 'location');
        	    	
        	} else if (isset ($_POST['online']) ) { // go to the method for online play
        	    $this->gameonline($username); 
        	    
        	} else if (isset ($_POST['loseOnline']) ) { // go to the method for online play without ending current game - lose points
        	    
        	    $statistic = array(1,0,1,0,0);
        	    $this->gibbet_model->set_statistic($username,$statistic);
                	    
        	    $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
        	    $this->session->unset_userdata( $array_items);//clear out all sessions
        	    
        	    $this->gameonline($username); 
        	         	    
        	    	
        	}else{// this is for initial load through index() call - see above 
       	         	          
        $helloUser='<div class="alert alert-success text-center"> Здравейте '. $username.'! Влязохте успешно в играта "Бесеничка"! Изберете категория и отгатнете коя е думата която ще зареди компютъра. Използвайте клавиатурата в дясно, за да отгатнете липсващите букви. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
        $this->session->set_flashdata('msg',$helloUser);
        	         	    
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
            // 'item' will be erased after 300 seconds
            $this->session->mark_as_temp('username', 3600); // unset session in 1 h
            
            $data['name']=$data['enemy']=$data['challenger'] =$data['image']=$data['word']=$data['discrip']= "";
            $data['total']= $data['won']=$data['instant']=0;
            
            $data["jscript"] =$data["javascript"] =  $data["javascpt"] =$data["javascrt"] = '';
            
            if ($username == NULL) {
                echo " МОЛЯ, ИЗПОЛЗВАЙТЕ ЗА ВХОД <a href='".base_url()."'> Бесеничка </a>";
            }else {
                                                 
                $this->gibbet_model->login_user($username); 	 
                $data['name']=$username;
            
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
                    
                    $this->gibbet_model->clear_progress($username); // clear table values if this is not player's 1st challenge accepted
                 $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                $this->session->set_flashdata('msg',$helloUser);
                    
                $wordArray = $this->gibbet_model-> get_hidden_word(true,$username);       // get the word with dashes and discription from the model
                
                $_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["Discript"]=$wordArray[1];
                $_SESSION["usedLetters"] =array('@','$'); //need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
                
                $_SESSION["imageNumb"] = 1;
                
                $data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600);
                $data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600);
                
                $this->gibbet_model->ingame_user($username);
               
                //sse-html5 script to view the opponent progress:
                $data["javascrt"] = $this->view_opponent_progress();
                
          		$data['image']='assets/img_gibb/01.png';
                
                $this->load->view('templates/header_gibbet');
                $this->load->view('gibbet/gameOnline',$data);
                $this->load->view('templates/footer_gibbet');
                    
                    
                } else if (isset ($_POST['wordReturn']) ) {// the user has challenged other player who accepted the challenge and the user get the word, hidden word and discription by him using the hiddenwordreturn button activated by SSE-HTML5 in   if (isset ($_POST['challange']) ) { // the user send challange to other player
                    
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                    $this->session->set_flashdata('msg',$helloUser);
                    
                    $enemy =$this->session->enemyname;
                    $wordArray = $this->gibbet_model-> get_opponent_word($enemy);       // get the word with dashes and discription from the model 
                    
                    $_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["Discript"]=$wordArray[1];   
                    $_SESSION["gibbetWord"]=$wordArray[2];// <-!! the main difference between online and type methods is where all the sessions of real word are created. In gametype() and also in ($_POST['accept']) here they are created in the model by get_word()
                                                        // However here in $_POST['wordReturn']) the values of that sessions are extracted from DB where the calling player saved them on his request
                    $_SESSION["usedLetters"] =array('@','$'); //need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
                    
                    $_SESSION["imageNumb"] = 1;
                    
                    $data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600);
                    $data['discrip']=$this->session->Discript;   $this->session->mark_as_temp('Discript', 3600);
                  
                    
                    $this->gibbet_model->ingame_user($username);
                    
                    //sse-html5 script to view the opponent progress:
                    $data["javascrt"] = $this->view_opponent_progress();
                                       
                    $data['image']='assets/img_gibb/01.png';
                    
                    $this->load->view('templates/header_gibbet');
                    $this->load->view('gibbet/gameOnline',$data);
                    $this->load->view('templates/footer_gibbet');
                
                 
                }else if (isset ($_POST['Letter']) ) {// click the letter buttons
                    
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                    $this->session->set_flashdata('msg',$helloUser);
                    
                    $usedSymbols= $this->session->usedLetters;//session array containing all used letters - initialized with the 1st letter click by guess_letter($usedSymbols) return
                    
                    $lettersArray = $this->gibbet_model->guess_letter($usedSymbols);      // get the word with dashes and discription from the model
                    $_SESSION["hiddenWord"] =$lettersArray[0];
                    $_SESSION["usedLetters"] =$lettersArray[2];
                    
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
                            
                            $array_items = array('hiddenWord', 'Discript', 'gibbetWord');
                            $this->session->unset_userdata( $array_items);//clear out sessions
                            
                            $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! </div>';
                            $this->session->set_flashdata('msg_sorry',$sorryUser);
                           
                                                     
                            $this->gibbet_model->outgame_user($username);
                            
                            $data["javascript"] =  $this->fillin_select_users($username);// fill in the option list with available for online game users
                            //sse-html5 script to ensure that the page can get challenge sent by other player for next game:                        
                            $data["javascpt"] =  $this->view_challenge();
                            
                            $totstatis = array(1,0,1,1,0);
                            $this->gibbet_model->set_statistic($username,$totstatis);
                                                                 
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
                        
           				$data["javascript"] =  $this->fillin_select_users($username);// fill in the option list with available for online game users
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
                    
                    
                    
                }else if (isset ($_POST['instantGuess']) ) {// click the guess buttons
                    
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
                    $this->session->set_flashdata('msg',$helloUser);
                    
                    $result = $this->gibbet_model->guess_word();
                                                           
                    If($result!=0){ //instant guess failed
                        //sse-html5 script to be abble to accept sorry modal and view the opponent progress on every letter button click or instant guess:
                        $data["javascrt"] = $this->view_opponent_progress();
                        
                        $statistic = array(0,0,0);
                        $this->gibbet_model->set_progress($username,$statistic);
                        $data["jscript"] = "<script type='text/javascript'>  $('#loserModal').modal('show');   $('#loserModal').on('hidden.bs.modal', function () { }); </script>";
                        
                        
                        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
                        $this->session->unset_userdata( $array_items);//clear out sessions
                        
                        $sorryUser='<div class="alert alert-danger text-center"> Съболезнования за загубата! </div>';
                        $this->session->set_flashdata('msg_sorry',$sorryUser);
                        
                        $data['image']='assets/img_gibb/08.png';
                        
                        $totstatis = array(1,0,1,0,0);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                                           
                    }else{// instant guess done
                         $statistic = array(0,1,1);
                        $this->gibbet_model->set_progress($username,$statistic);
                        $data["jscript"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
                        
                        $array_items = array('hiddenWord', 'Discript', 'gibbetWord','imageNumb');
                        $this->session->unset_userdata( $array_items);//clear out sessions
                        
                        $data['image']='assets/img_gibb/01.png';
                        
                        $totstatis = array(1,1,0,0,1);
                        $this->gibbet_model->set_statistic($username,$totstatis);
                                                                                          
                    }
                    $this->gibbet_model->outgame_user($username);
                    
                    $data["javascript"] =  $this->fillin_select_users($username);// fill in the option list with available for online game users
                      //sse-html5 script to view challange modal and name of the opponent who send it inside
					$data["javascpt"] = $this->view_challenge();
                                                          
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
                    
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,предизвикайте играч от списъка и отгатнете преди него думата която ще зареди компютъра. Използвайте клавиатурата в дясно, за да отгатнете липсващите букви. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
                    $this->session->set_flashdata('msg',$helloUser);      
                    
                    $data["jscript"] =  $this->fillin_select_users($username);// fill in the option list with available for online game users
                   
                    
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
               	
               	$totstatis = array(1,0,1,0,0);
               	$this->gibbet_model->set_statistic($username,$totstatis);
               	
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
                    
                	$totstatis = array(1,0,1,0,0);
                	$this->gibbet_model->set_statistic($username,$totstatis);
                	
                	$this->gibbet_model->clear_progress($username); // clear table values on exit gamer room
                	$this->gibbet_model->logout_user($username);
                	$this->gibbet_model->outgame_user($username);
                	$this->gametype($username); 
                	
                }else{// this is for initial load through gametype() call - see above 
                	$this->gibbet_model->clear_progress($username); // clear table values on entering gamer room
                     
                    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,предизвикайте играч от списъка и отгатнете преди него думата която ще зареди компютъра. Използвайте клавиатурата в дясно, за да отгатнете липсващите букви. Имате право на 5 грешки преди да увиснете на бесилото &#9785:</div>';
                    $this->session->set_flashdata('msg',$helloUser);                    
                   
                   
                    $data["jscript"] =  $this->fillin_select_users($username);// fill in the option list with available for online game users
                    
                    //sse-html5 script to view challange modal and name of the opponent who send it inside
                    $data["javascript"] = $this->view_challenge();
					                                       
                    $data['image']='assets/img_gibb/01.png';
                                     
                    $this->load->view('templates/header_gibbet');
                    $this->load->view('gibbet/gameOnline',$data);
                    $this->load->view('templates/footer_gibbet');
                                        
                }
            }
            }
         
            
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
                    };
            		
					var source2 = new EventSource('challenge_message');//the function below
                    source2.onmessage = function(event) {
   					document.getElementById('acceptMessage').innerHTML = event.data + '<br>';
                    };
               } else {
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 !');
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
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 !');
            			
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
                    window.alert('Съжаляваме,Браузърът Ви нe поддържа SSE - HTML5 !');
            		
                    }</script>" ;
            return  $jscrpt;
            }
            
            public function get_opponent_progress($enemy = NULL,$username = NULL){
            /*   */
            	
            	$key = $_REQUEST["key"];
            	
            	$enemy =$this->session->enemyname;
            	$username =$this->session->username;
            	
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
             echo "data: $statArray[$key]\n\n";
             
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