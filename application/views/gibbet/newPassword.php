
<div class="container" style=""> <!-- </div> is in the footer_repairs -->
<!-- Small wrong code Modal -->
<div class="modal fade" id="wcodeModal" role="dialog">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header-wc">
<!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
<h3 class="modal-title">Няма съответствие на въведените пароли!</h3>
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


<!-- Small wrong code Modal -->
<div class="modal fade" id="wconnModal" role="dialog">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header-wc">
<!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
<h3 class="modal-title">Промяната в момента е невъзможна! Проблем с комуникацията със сървъра.</h3>
</div>
<div class="modal-body">
<p><i>Опитайте отново по-късно!</i></p>
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
<p>Паролата Ви е сменена. Може да влезте с нея в играта.</p>
          
          
        </div>
        <div class="modal-footer-wd">
          <button type="button" class="btn btn-default" data-dismiss="modal">ОК</button>
        </div>
      </div>
    </div>
  </div><!-- success modal end -->        
     
     
<!-- my project JS  <script src="<?php echo base_url();?>assets/js_gibb/gibbet_enter.js"></script>-->
  
 
  <?php echo $nonamepass; ?> <!-- placed here to serve JS triggers of the modals above  - see  how it works with their session variables in validation functions in the controller -->

<div class="row">

  <div class="col-sm-4">  
     </div>
     
   <div class="col-sm-4">
<h4 style="">СМЯНА НА ПАРОЛА</h4>(Внимавайте за активен CapsLock!)
  <br> <br>     
  

<?php echo form_open('gibbet/resetpass','id="passForm"'); ?>  <!--This has a <form> role. The model (and method) must be envoked in the form -->     


     <div class="form-group">
  
  <label for="pswr"><span class="glyphicon glyphicon-eye-open"></span><span id=""> Нова парола</span></label>
 <input  type="password"  name="pswr" class="form-control" id="" placeholder="Въведете тук парола за достъп"  style=" background-color: #cce6ff;"> 
   <br>
  <label for="repswr"><span class="glyphicon glyphicon-eye-open"></span><span id="" > Потвърди паролата</span></label>
  <input type="password"  name="repswr"  class="form-control" id="" placeholder=""  style=" background-color: #cce6ff;">
  <br>
  <button type="submit"  name="send_pass" class="btn btn-block btn-primary" id=""><span class="glyphicon glyphicon-log-in"></span><span id="enterButt">&nbsp Въведи</span></button>
  <br> 
  
  <button type="submit" name="goto_enter"  class="btn btn-link"  id="hiddentoEnter" ></button><!-- hidden button to enterGame page -->
  
  <?php echo validation_errors(); ?>       
     <p id="showform"></p> <!--this is a tricky element used to append new element to it-  see gibbet_enter.js -->  
    </div>
    </div> 
       
      <div class="col-sm-4">
</div>            
<?php echo form_close();?>
 
  </div>