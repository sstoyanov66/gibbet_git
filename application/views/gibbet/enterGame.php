
<div class="container" style=""> <!-- </div> is in the footer_repairs -->
<!-- Small wrong code Modal -->
  <div class="modal fade" id="wcodeModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Въведените потребителско име и/или парола са грешни!</h3>
        </div>
        <div class="modal-body">
          <p><i>Опитайте отново!</i></p>
        </div>
        <div class="modal-footer-wc">
          <button type="button" class="btn btn-default" data-dismiss="modal">Затвори</button>
        </div>
      </div>
    </div>
  </div><!-- wrong code modal end -->
       
     <!-- Small warning renew Modal -->
  <div class="modal fade" id="signUpModal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header-wd">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">Внимание, проверете дали е активиран Caps Lock!</h4>
        </div>
        <div class="modal-body">
         <label for="name"><span class="glyphicon glyphicon-user"></span><span > Нов играч - името да е на Кирилица с не повече от 20 символа!:</span></label>
 <input  type="text" name="newname" form="signForm" class="form-control" placeholder="Въведете тук потребителско име"  style=" background-color: #cce6ff;"> 
   <br>
  <label for="pswr"><span class="glyphicon glyphicon-eye-open"></span><span> Парола - да е поне с 5 символа!:</span></label>
  <input type="password"  name="newpswr" form="signForm"  class="form-control"  placeholder="Въведете тук парола за достъп"  style=" background-color: #cce6ff;">
  <br>
  <label for="email"><span class="glyphicon glyphicon-envelope"></span> <span>Е-mail за вход при забравена парола:</span></label>
  <input  type="email"  name="email" form="signForm" class="form-control" placeholder="Въведете валиден E-mail адрес"  style=" background-color: #cce6ff;">
        </div>
        <div class="modal-footer-wd">
          <button type="submit" name="newUser" form="signForm"  class="btn btn-success" id="signIt" >Регистрирай!</button>
          <button type="button" class="btn btn-default"  data-dismiss="modal">Отказ</button>
        </div>
      </div>
    </div>
 </div><!--warning renew modal end -->
  
<!-- Small wrong code Modal -->
  <div class="modal fade" id="wnameModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Регистрацията е невъзможна! Има потребител със същото име.</h3>
        </div>
        <div class="modal-body">
          <p><i>Опитайте отново с друго име!</i></p>
        </div>
        <div class="modal-footer-wc">
          <button type="button" class="btn btn-default" data-dismiss="modal">Затвори</button>
        </div>
      </div>
    </div>
  </div><!-- wrong code modal end -->
     
<!-- Small wrong code Modal -->
  <div class="modal fade" id="wmailModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Регистрацията е невъзможна! Има потребител със същия мейл адрес.</h3>
        </div>
        <div class="modal-body">
          <p><i>Опитайте отново с друг е-мейл или ако вие сте този потребител кликнете на линка забравена парола!</i></p>
        </div>
        <div class="modal-footer-wc">
          <button type="button" class="btn btn-default" data-dismiss="modal">Затвори</button>
        </div>
      </div>
    </div>
  </div><!-- wrong code modal end -->     
     
 <!-- Small wrong code Modal -->
  <div class="modal fade" id="missmailModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Няма регистриран потребител с такъв мейл адрес!</h3>
        </div>
        <div class="modal-body">
          <p><i>Проверете и опитайте отново или първо се регистрирайте!</i></p>
        </div>
        <div class="modal-footer-wc">
          <button type="button" class="btn btn-default" data-dismiss="modal">Затвори</button>
        </div>
      </div>
    </div>
  </div><!-- wrong code modal end -->    
 <!-- Small success Modal -->
  <div class="modal fade" id="successModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-ok">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Готово!</h3>        
        </div>
        <div class="modal-body">
          <p>Изпратен беше мейл на адрес: <?php echo $mailaddress; ?>!</p>
          <p><i>Влезте в пощата си в следващите 5 мин за да промените паролата!!</i></p>
          
        </div>
        <div class="modal-footer-wd">
          <button type="button" class="btn btn-default" data-dismiss="modal">ОК</button>
        </div>
      </div>
    </div>
  </div><!-- success modal end -->        
     
     
<!-- my project JS --> <script src="<?php echo base_url();?>assets/js_gibb/gibbet_enter.js"></script>
  
 
  <?php echo $nonamepass; ?> <!-- placed here to serve JS triggers of the modals above  - see  how it works with their session variables in validation functions in the controller -->

<div class="row">


   <div class="col-sm-3">
<h4 style="">ВХОД:</h4>
  <br>     
  

<?php echo form_open('gibbet/index','id="signForm"'); ?>  <!--This has a <form> role. The model (and method) must be envoked in the form -->     


     <div class="form-group">
  
  <label for="name"><span class="glyphicon glyphicon-user"></span><span id="user"> Потребител</span></label>
 <input  type="text" name="name" class="form-control" id="username" placeholder="Въведете тук потребителско име"  style=" background-color: #cce6ff;"> 
   <br>
  <label for="pswr"><span class="glyphicon glyphicon-eye-open"></span><span id="passw" > Парола</span></label>
  <input type="password"  name="pswr"  class="form-control" id="psword" placeholder="Въведете тук парола за достъп"  style=" background-color: #cce6ff;">
  <br>
  <button type="submit"  name="send_request" class="btn btn-block btn-primary" id="enter"><span class="glyphicon glyphicon-log-in"></span><span id="enterButt">&nbsp Към играта</span></button>
  <br>
    <button type="button"  class="btn btn-link"  id="forgtnPass" >забравена парола?</button>     
     <p id="showform"></p> <!--this is a tricky element used to append new element to it-  see gibbet_enter.js The alternative way is:  
     <div id='forgpassForm' style=" display: none;"> Забравена парола?<br><br><label for='email'><span class='glyphicon glyphicon-envelope'></span> <span>Въведете е-мейл с който сте направили регистрация на който ще получите инструкции как да въведете нова парола:</span></label>
	  		<input  type='email'  name='myemail' form='signForm' id='mailaddrr' class='form-control' placeholder='Въведете валиден E-mail адрес'  style=' background-color: #cce6ff;'>
	  		<br><button type='submit'  name='send_mail' class='btn btn-block btn-info' id='sendmail'><span class='glyphicon glyphicon-send'></span><span>&nbsp Изпрати инструкция</span></button></div> see also gibbet_enter.js--> 
    </div>
    </div> 
     <div class="col-sm-3">  
     
     </div>    
      <div class="col-sm-4">
<button type="button"  name="signUp" class="btn btn-block " id="singup"><span class="glyphicon glyphicon-tag"></span><span id="enterButt">&nbsp Регистрация на нов играч</span></button>
<p></p>
<?php echo validation_errors(); ?>
 <!-- <button  type="submit" name="newUser" class="btn btn-link" id="hiddensignup"></button>    to make Submit working normally with Enter key it's better to have just one submmit in a form. For other I use hidden button to serve other outside the form  - JS file and controller-->
</div>            
<?php echo form_close();?>
 
  </div>
  

  