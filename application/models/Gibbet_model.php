<?php

// !!SEE FIRST QUERY BUILDER CLASS IN CODEIGNITER DOCUMENTATION - SAME QUERIES CAN BE PREPARED IN DIFFERENT WAYS
class Gibbet_model extends CI_Model {
    
    public function __construct(){
        $this->load->database();
        
    }
      
      public function insert_user(){
          $gamername=$this->input->post('newname');  $passwrd=$this->input->post('newpswr');
          
          $userHash = $this->password->password_hash($passwrd, PASSWORD_DEFAULT);
          $record_data = array('name' =>$gamername, 'enterpass'=>$userHash); // save user and his pass hash in DB
          $this->db->insert('users', $record_data);
          return $gamername;
    }
    
    public function get_users($columns)	{ // get user's name and hashed password
        
        $this->db->select($columns);        
        $query = $this->db->get('users');
        return $query->result_array(); 
    }
    
    public function login_user($username){
        
        $update = array("logged_in"=>true );
        $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
    }
    
    public function logout_user($username){
      
        $update = array("logged_in"=>false );
        $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
    }
    public function challng_user($username, $enemy){
       
        $update = array("challenge_by"=>$enemy );
        $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
    }
    
    public function unchallng_user($username){
        
        $update = array("challenge_by"=>"" );
        $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
    }
    
    public function ingame_user($username){
        
        $update = array("in_game"=>true );
        $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
    }
    
    public function outgame_user($username){
        
        $update = array("in_game"=>false,"challenge_by"=>"","word_online"=>"", "discrip_online"=> "", "hidden_online"=>"");
        $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
    }
    
    public function get_challenge($username){
        
        $this->db->select('challenge_by');
        $query = $this->db->get_where('users',"name = "."'".$username."'");       
        $challenge=$query->row_array();
        
        if($challenge){
        $opponent =  $challenge["challenge_by"];
        return $opponent;
        }else{
              return "";
        }
    
    }
    
    
    public function get_hidden_word($online,$username)	{ //!!!THERE SHOULDN'T BE ID's MISSING IN USERS TABLE  because of delition or manual data insert !!!!
        
 /*Prepare n-words expression with string operations only*/
        
        $wordArray = $this-> get_word($online,$username); // get a random word from a table selected by user by option list
        $word=$wordArray [0]; $discrip=$wordArray [1];
        
    	$wordsArray=explode(' ', $word);   //array of all words in the string --in case there are more than 1 word
    	$arrlength = count($wordsArray);
    	
    	$currWord=''; $words=array();
    	for($d = 0; $d < $arrlength; $d++) {
    		 		
    	    $wrdlngth=mb_strlen($wordsArray[$d],'UTF-8'); //the length of each word then..
    		//get the 1st and last letter in the word - !!!BECAUSE OF CYRILLIC every string function must begin with mb_ . Otherwise EACH SYMBOL OCCUPIES 2 KEYS WITHIN THE ARRAY THAT'S WHY THE POSITION
    		//OF 1ST AND LAST SYMPOLS ARE SO WIERD:
    	    $letter1= mb_substr($wordsArray[$d],0,1,'UTF-8'); 
    	    $letterLast= mb_substr($wordsArray[$d],$wrdlngth-1,$wrdlngth,'UTF-8');
    		
    	    $middle= mb_substr($wordsArray[$d],1,$wrdlngth-2,'UTF-8');
    		$dashes = str_repeat(" _",$wrdlngth-2); //prepare the string with dashes to substitute for the middle part of the word
    		
    		$currWord=$letter1.$dashes.' '.$letterLast; // compose the string with dashes to display and add it to the array with words
    		array_push($words,$currWord);
    		
    	}
    	//$intvls = str_repeat(" ",2);
    	$hiddenWord='';
    	for($w = 0; $w < count($words); $w++) {
   		 		
    	    $hiddenWord.= $words[$w].'   ';
    	}
    	   	
    	if($online){
    	    $update = array("hidden_online"=>$hiddenWord);
    	    $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
    	}
    	
    	return [$hiddenWord,$discrip];
    }
    
    
    private function get_word($online, $username)	{ // get a random word from a table selected by user by option list
      
       $this->db->select('id');
       
        if(!$online){ // logic for gametype() controller method
                
        $type=$this->input->post('slcType'); 
        
      
        if($type=='animals'){
            $query = $this->db->get('animals');
        }else if($type=='cities'){
            $query = $this->db->get('cities');
        }else if($type=='plants'){
            $query = $this->db->get('plants');
        }
        
        }else{ // logic for gameonline() controller method
                      
            $tableID = rand(1,3); //get random table for selection in online mode
            
            if( $tableID==1){
                $type='animals';
                $query = $this->db->get('animals');
            }else if( $tableID==2){
                $type='cities';
                $query = $this->db->get('cities');
            }else if( $tableID==3){
                $type='plants';
                $query = $this->db->get('plants');
            }                       
        }
        
        
        $allIDs =$query->result_array(); //<--
        
        $columnlength = count($allIDs);
        
        $wordID = rand(1,$columnlength); //<--!!!!! get random id from min to max (!!!THERE SHOULDN'T BE ID's MISSING IN USERS TABLE  because of delition or manual data insert !!!!)
        
        $this->db->select('word, discrip');
        if($type=='animals'){
            $queryWord = $this->db->get_where('animals',"id = ".$wordID);
        }else if($type=='cities'){
            $queryWord = $this->db->get_where('cities',"id = ".$wordID);
        }else if($type=='plants'){
            $queryWord = $this->db->get_where('plants',"id = ".$wordID);
        }
        
        $chosen=$queryWord->result_array();
        
        $word = $discrip="";
        if($chosen){
            foreach ($chosen as $element):
            $word = $element['word'];
            $discrip=$element['discrip'];
            endforeach;
        }
        /* because of string operation needed to display word like A_ _ _ _ A the real word and discription have to be saved as global variables*/                
        $_SESSION["gibbetWord"]=$word;
     //   $_SESSION["gibbetDiscrip"]=$discrip;
    
        if($online){
            $update = array("word_online"=>$word, "discrip_online"=> $discrip);
            $this->db->set($update)->where('name', $username)->update('users'); // <--update user statistics columns
        }               
        
        return [$word,$discrip];
    }
    
    
    public function get_opponent_word($enemy){
        $this->db->select('hidden_online, discrip_online, word_online');
        $query = $this->db->get_where('users','name = '."'".$enemy."'");
        $word=$query->row_array(); 
        
        $wordReturn=array();
        $hidden =$discrip=$real ='';
        if($word){
            
            $hidden =  $word['hidden_online']; $discrip= $word['discrip_online']; $real= $word['word_online'];
           
            array_push($wordReturn, $hidden );array_push($wordReturn,$discrip);array_push($wordReturn,$real);
   
        }
        
        return $wordReturn;
                
    }
    
    
    public function guess_Letter($usedSymbols){
        
        $letter_field = $this->input->post('Letter');
        $word=$this->session->gibbetWord; // get session of the random chosen word
        
        if (in_array($letter_field,$usedSymbols) ){   // check if entered has been used already    	
        $letterExists=true ;
        }else{$letterExists=false ; }
        	    
        $wordsArray=explode(' ', $word);   //array of all words in the string --in case there are more than 1 word
        $arrlength = count($wordsArray);
        
        $yes_no= $ok =false; // the letter / word is guessed/not
        $currHiddenWord= array(); //
        $words=array(); //
        $usedLetters=$usedSymbols;
        $wholeWord='';
        
        if(!$letterExists){//letter 1st time entered
        for($k = 0; $k < $arrlength; $k++) {
        	        	       	
        	$arrayLetters=preg_split('/(?!^)(?=.)/u', $wordsArray[$k]);//Array of word letters.!! preg_split() MUST be used to process UTF-8 encoded Cyrillic strings
        	$wrdlngth=count($arrayLetters);
        	
        	$usedlength=count($usedSymbols);
        	       	
        	$currHiddenWord[0]= $arrayLetters[0];
        	$currHiddenWord[$wrdlngth-1]= $arrayLetters[$wrdlngth-1];
        	
        	for($i = 1; $i < $wrdlngth-1; $i++){
        		$currHiddenWord[$i]=' _';
        	}
        	
        	for($i = 0; $i < $wrdlngth-1; $i++){
        		for($d = 0; $d < $usedlength; $d++){
        			
        			if ($arrayLetters[$i] ==$usedSymbols[$d]) { //<--keep already guessed letters
        				$currHiddenWord[$i]=$usedSymbols[$d];
        			}
        			
        			if ($arrayLetters[$i]==$letter_field ){ //<--new guessed letter
        				$currHiddenWord[$i]=$letter_field;
        				
        				array_push($usedLetters,$letter_field);
        				$yes_no=true;
        			}
        			
        		}}
        	
        	$neword='';
        	for($i = 0; $i <$wrdlngth; $i++){
        		$neword.= $currHiddenWord[$i];
        	}
        	                                         
        	array_push($words,$neword);            
        }
             
        for($w = 0; $w < count($words); $w++) {
            
            $wholeWord.= $words[$w].' '; // String to view
        }
        
        }else{//letter already entered
        	$wholeWord=$this->session->hiddenWord;
        }
        
        if(count(array_diff( $wordsArray,$words))==0){
        	$ok=true;
        }
        
        return [$wholeWord,$yes_no, $usedLetters,$ok]; // the session hiddenword is going to be overriden in the controller
    }
    
    
    public function guess_word(){
        
        $guess_field = $this->input->post('wholeWord');
                          
        $wordsArray=explode(' ',  $guess_field);
        $arrlength = count($wordsArray);
        
        //Every new space or sequence of spaces after the one betwwen words in the string  is treated as an empty element within the string array. 
        // To protect users from adding spaces between words it's needed to remove more than 1 spaces between words:
        $noSpaces='';
        for($i=0; $i<$arrlength; $i++){
            if($wordsArray[$i]!=""){
                $noSpaces.= $wordsArray[$i]." ";
            }
        }
        //To equalise all letters and save problems with Upper/Lower case I change 1st letter to lowercase where strtolower() is needed. 
        //However it doesn't work with Cyrillic chars that's why I use the utf-8 compatible mb_strtolower():
        $guess_field = mb_strtolower($noSpaces,'UTF-8');
        $word= mb_strtolower($this->session->gibbetWord,'UTF-8');
       // the next string function compares case insensetively the string session of the chosen word and string imput for the instant guess. Result is integer. Need to add -1 to it because of the "" added up in the loop
        $yes_no= strcasecmp($guess_field, $word)-1; 
      
        //if result is 0 they are equal
        return $yes_no;
        
    }
    
    
    public function get_statistic($username){
        
        $this->db->select('games_total, games_won, games_lost, guesses, instant_words');
        $query = $this->db->get_where('users','name = '."'".$username."'");
        $stat=$query->row_array(); 
        
        $statistics=array();
        $total=$won=$lost=$guesses=$instant=0;
        if($stat){
           
            $total = $stat['games_total']; $lost=$stat['games_lost']; $guesses=$stat['guesses'];
            $won=$stat['games_won']; $instant=$stat['instant_words'];
           
            array_push($statistics,$total);array_push($statistics,$won);array_push($statistics,$lost);
            array_push($statistics,$guesses);array_push($statistics,$instant);
                    
          /*  
            foreach ($statisic as $element):
            $total = $element['games_total']; $lost=$element['games_lost']; $guesses=$element['guesses'];
            $won=$element['games_won']; $instant=$element['instant_words'];
            
            endforeach;*/
            
        }
        
        return $statistics;
    }
    
    
    public function set_statistic($username,$addArray){ 
        
        $currStat=$this->get_statistic($username);               
            $total= $currStat[0]+$addArray[0];$won=$currStat[1]+$addArray[1];$lost=$currStat[2]+$addArray[2];
            $guesses=$currStat[3]+$addArray[3];$instant=$currStat[4]+$addArray[4];
        
        
        $rec_data = array('games_total' => $total, 'games_won' =>$won, 'games_lost'=>$lost,'guesses'=> $guesses,'instant_words'=>$instant);
        $this->db->set($rec_data)->where('name', $username)->update('users'); // <--update user statistics columns
        
    
    }
    
    public function get_progress($oppname){
        
        $this->db->select('curr_letters, curr_word, curr_guess');
        $query = $this->db->get_where('users','name = '."'".$oppname."'");
        $stat=$query->row_array();
        
        $statistics=array();
        $total=$won=$instant=0;
        if($stat){            
        	$total = $stat['curr_letters']; $won=$stat['curr_word']; $instant=$stat['curr_guess'];                      
            array_push($statistics,$total); array_push($statistics,$won); array_push($statistics,$instant);           
        }
        
        return $statistics;
    }
    
    
    public function set_progress($username,$addArray){
    	$total=$won=$instant=0;
    	
        $currStat=$this->get_progress($username);
        $total= $currStat[0]+$addArray[0];	$won=$currStat[1]+$addArray[1];	$instant=$currStat[2]+$addArray[2];
       
        $rec_data = array('curr_letters' => $total, 'curr_word' =>$won, 'curr_guess'=>$instant);
        $this->db->set($rec_data)->where('name', $username)->update('users'); // <--update user statistics columns
        
        
    }
    
    public function clear_progress($username){
        
        $rec_data = array('curr_letters' => "", 'curr_word' =>"", 'curr_guess'=>"");
        $this->db->set($rec_data)->where('name', $username)->update('users'); // <--update user statistics columns
        
    }
    
}