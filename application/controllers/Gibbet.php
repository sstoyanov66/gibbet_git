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
            $alluserss=$this->gibbet_model->get_users();
                        
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
                $this->gametype($username);
                
            }else{
            	//Jscript injection in php to view
                $data["nonamepass"] = "<script type='text/javascript'>  $('#wcodeModal').modal('show');   $('#wcodeModal').on('hidden.bs.modal', function () { }); </script>";
                                                  
                $this->load->view('templates/header_gibbet');
                $this->load->view('gibbet/enterGame',$data);   
                $this->load->view('templates/footer_gibbet');
            }
            
            }else{
                redirect('gibbet/index'); //<-- no conn to database or whatever===> Also can catch this as an error
          
            }
  
      }   
      
      
      //registration modal:
        	}else if (isset ($_POST['signUp']) ) {
        	    $data['nonamepass']="<script type='text/javascript'>  
                $('#signUpModal').modal({
             	show: true,
                 backdrop: 'static',// to prevent close modal on background click
                 keyboard: false  // to prevent closing with Esc button (if you want this too)
                 })    
                $('#signUpModal').on('hidden.bs.modal', function () { }); </script>";
        		
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/enterGame',$data);   
        		$this->load->view('templates/footer_gibbet');
        		
        	}else if (isset ($_POST['newUser']) ) {
        	    /*watch if something is entered in the fields but set_rules() check also if these are Cyrillic letters w/o numeric and their min number is 5*/
        	    $this->form_validation->set_rules('newname', 'име', 'required|regex_match[/^[а-яА-Я\p{Cyrillic}\s\-\.\!\?]+$/u]|min_length[3]');
        	    $this->form_validation->set_rules('newpswr', 'парола', 'required|min_length[5]');
        	    
        	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); //<-- See also depoNariad.css and config.php. In Form_validation class default erro_prefix is <p>
        	    
        	    $user_field = $this->input->post('newname');
        	    
        	    if ($this->form_validation->run() == FALSE)  {
        	              	        
        	        $data['nonamepass']="<script type='text/javascript'> $('#signUpModal').modal({show: false}) </script>";
                 	        
        	        $this->load->view('templates/header_gibbet');
        	        $this->load->view('gibbet/enterGame',$data);   
        	        $this->load->view('templates/footer_gibbet');
        	        
        	    } else  {
        	        $alluserss=$this->gibbet_model->get_users(); // check 1st if the user exists:
        	               	              	        
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
        		
        	if (isset ($_POST['exitUser']) ) {
        	       	    
        	    $this->session->unset_userdata('username');//clear out user session 
        	    redirect('', 'location');
        	    
        	    
        	}else if (isset ($_POST['wordRequest']) ) {
        		
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
        		    
        		$wordArray = $this->gibbet_model-> get_hidden_word();       // get the word with dashes and discription from the model     		
        		
        		$_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["hiddenDiscrip"]=$wordArray[1];
        		$_SESSION["usedLetters"] =array('@','$'); //need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
        		
        		$data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600); 
        		$data['discrip']=$this->session->hiddenDiscrip;   $this->session->mark_as_temp('hiddenDiscrip', 3600); 
        		        		       		        		        		       		
        		//$data['word']=$word;
        		 $data['image']='assets/img_gibb/01.png';
        		 
        		$this->load->view('templates/header_gibbet');
        		$this->load->view('gibbet/gameType',$data);
        		$this->load->view('templates/footer_gibbet');
        	}
        	
        	} else if (isset ($_POST['newWordRequest']) ) { // exit current game - lose points and load a new word
        	    
        	    $statistic = array(1,0,1,0,0);
        	    $this->gibbet_model->set_statistic($username,$statistic);
        	    
        	    $array_items = array('hiddenWord', 'hiddenDiscrip', 'gibbetWord','imageNumb','msg_sorry');        	    
        	    $this->session->unset_userdata( $array_items);//clear out session
        	        	        	    
        	    $helloUser='<div class="alert alert-success text-center"> Играч '. $username.' ,желаем Ви късмет! </div>';
        	    $this->session->set_flashdata('msg',$helloUser);
        	    
        	    
        	    $wordArray = $this->gibbet_model-> get_hidden_word();       // get the word with dashes and discription from the model
        	    
        	    $_SESSION["imageNumb"] = 1;
        	    $_SESSION["hiddenWord"] =$wordArray[0]; $_SESSION["hiddenDiscrip"]=$wordArray[1];
        	    $_SESSION["usedLetters"] =array('@','$'); // need this session with some random elements in Letter block and guess_letter($usedSymbols) method;
        	    
        	    $data['word']=$this->session->hiddenWord;     $this->session->mark_as_temp('hiddenWord', 3600);
        	    $data['discrip']=$this->session->hiddenDiscrip;   $this->session->mark_as_temp('hiddenDiscrip', 3600);
        	    
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
        	    
        	    $usedSymbols= $this->session->usedLetters;//See up session array containing all used letters - initialized with the word request then 1st letter click by guess_letter($usedSymbols) return
        	    
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
        	        
        	        $array_items = array('hiddenWord', 'hiddenDiscrip', 'gibbetWord');
        	        $this->session->unset_userdata( $array_items);//clear out sessions
        	        
        	        $sorryUser='<div class="alert alert-success text-center"> Съболезнования за загубата! </div>';
        	        $this->session->set_flashdata('msg_sorry',$sorryUser); 
        	    }
        	    
        		 }else{ //The word is GUESSED !!
        		 	
        		 	$statistic = array(1,1,0,1,0);
        		 	$this->gibbet_model->set_statistic($username,$statistic);
        		 	$data["jscript"] = "<script type='text/javascript'>  $('#successModal').modal('show');   $('#successModal').on('hidden.bs.modal', function () { }); </script>";
        		 	
        		 	$array_items = array('hiddenWord', 'hiddenDiscrip', 'gibbetWord','usedLetters');
        		 	$this->session->unset_userdata( $array_items);//clear out sessions
        		 	
        		 	
        		 }
        	    
        	    
        	    
        		$data['word']=$this->session->hiddenWord;
        		$data['discrip']=$this->session->hiddenDiscrip;
        		        		          	   
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
        	   
        	        $array_items = array('hiddenWord', 'hiddenDiscrip', 'gibbetWord','imageNumb');
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
        	    	
        	    	
        	    	$array_items = array('hiddenWord', 'hiddenDiscrip', 'gibbetWord','imageNumb');
        	    	$this->session->unset_userdata( $array_items);//clear out sessions
        	    	
        	    	$statArray = $this->gibbet_model->get_statistic($username);
        	    	$data['total']=$statArray[0]; $data['won']=$statArray[1];$data['lost']=$statArray[2];$data['guesses']=$statArray[3];$data['instant']=$statArray[4];
        	    	
        	    	$sorryUser='<div class="alert alert-success text-center"> Съболезнования за загубата! </div>';
        	    	$this->session->set_flashdata('msg_sorry',$sorryUser); 
        	    	
        	        $data['image']='assets/img_gibb/08.png';
        	        
        	        $this->load->view('templates/header_gibbet');
        	        $this->load->view('gibbet/gameType',$data);
        	        $this->load->view('templates/footer_gibbet');
        	    }
        	            	           	    
        	} else if (isset ($_POST['loseExit']) ) { // exit without ending current game - lose points
        	    	        		
        		$statistic = array(1,0,1,0,0);
        		$this->gibbet_model->set_statistic($username,$statistic);
        		
        		$array_items = array('hiddenWord', 'hiddenDiscrip', 'gibbetWord','username','imageNumb');
        		$this->session->unset_userdata( $array_items);//clear out all sessions        		
        		
        	    	redirect('', 'location');
        	    	
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
     
        
       
        
}//class end