
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
         <label for="name"><span class="glyphicon glyphicon-user"></span><span > Нов играч - името да е на Кирилица!:</span></label>
 <input  type="text" name="newname" form="signForm" class="form-control" placeholder="Въведете тук потребителско име"  style=" background-color: #cce6ff;"> 
   <br>
  <label for="pswr"><span class="glyphicon glyphicon-eye-open"></span><span  > Парола - да е поне с 5 символа!:</span></label>
  <input type="password"  name="newpswr" form="signForm"  class="form-control"  placeholder="Въведете тук парола за достъп"  style=" background-color: #cce6ff;">
        </div>
        <div class="modal-footer-wd">
          <button type="button" class="btn btn-success" id="signIt" >Регистрирай!</button>
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
    </div>
    </div> 
     <div class="col-sm-5"></div>
      <div class="col-sm-4">
<button type="submit"  name="signUp" class="btn btn-block " id="singup"><span class="glyphicon glyphicon-tag"></span><span id="enterButt">&nbsp Регистрация на нов играч</span></button>
<p></p>
<?php echo validation_errors(); ?>
 <button  type="submit" name="newUser" class="btn btn-link" id="hiddensignup"></button>    <!-- to make Submit working normally with Enter key it's better to have just one submmit in a form. For other I use hidden button to serve other outside the form  - JS file and controller-->
</div>            
<?php echo form_close();?>
 
  </div>
  

  