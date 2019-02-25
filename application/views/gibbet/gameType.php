
<div class="container" style=""> <!-- </div> is in the footer_ -->
<!-- Small warnning Modal -->
  <div class="modal fade" id="warnModal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Сигурни ли сте?</h3>
        </div>
        <div class="modal-body">
          <p><i>Ще изгубите текущата игра и това ще се отрази във Вашата статистика!</i></p>
        </div>
        <div class="modal-footer-wc">
         <button type="button"  class="btn btn-warning"  id="exitDB" >ИЗХОД</button>
         <button type="button" class="btn btn-warning"  id="newGame" disabled>НОВА ИГРА</button>
          <button type="button" class="btn btn-default" data-dismiss="modal"  style="color:green" >Обратно в играта</button>
        </div>
      </div>
    </div>
  </div><!-- warrning  modal end -->

<div class="modal fade" id="warnOnlineModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Сигурни ли сте?</h3>
        </div>
        <div class="modal-body">
          <p><i>Ще изгубите текущата игра и това ще се отрази във Вашата статистика!</i></p>
        </div>
        <div class="modal-footer-wc">
         <button type="button"  class="btn btn-warning"  id="onlineDB" >Към online игра</button>       
          <button type="button" class="btn btn-default" data-dismiss="modal"  style="color:green" >Обратно в играта</button>
        </div>
      </div>
    </div>
  </div><!-- warrning  modal end -->

<!-- Small warnning Modal -->
  <div class="modal fade" id="gibbetModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Опааа!</h3>
        </div>
        <div class="modal-body">
        <h4 id='realwrd'style="color:red"></h4>  
           <img alt="gibbet" class="img-responsive" src="<?php echo base_url();?><?php echo $image; ?>">
        </div>
        <div class="modal-footer-wc">           
          <button type="button" class="btn btn-default" data-dismiss="modal">Затвори</button>
        </div>
      </div>
    </div>
  </div><!-- warrning  modal end -->

<!-- Small warnning Modal -->
  <div class="modal fade" id="loserModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wc">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title">Опааа!</h3>
        </div>
        <div class="modal-body">
          <p><i>Реализирахте си бързо немъчително обесване!</i></p>
          <h4 style="color:red">Думата беше <?php echo $realword; ?></h4>  
        </div>
        <div class="modal-footer-wc">     
          <button type="button" class="btn btn-default" data-dismiss="modal">Затвори</button>
        </div>
      </div>
    </div>
  </div><!-- warrning  modal end -->

   <!-- Small warning renew Modal -->
  <div class="modal fade" id="lastChanceModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-wd">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">Внимание!</h4>
        </div>
        <div class="modal-body">
         <p><i>Последен шанс преди да увиснете на въжето!</i></p>
        </div>
        <div class="modal-footer-wd">          
          <button type="button" class="btn btn-default"  data-dismiss="modal">ОК</button>
        </div>
      </div>
    </div>
 </div><!--warning renew modal end -->


<!-- Small success Modal -->
  <div class="modal fade" id="successModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header-ok">
          <!--  <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h3 class="modal-title"><?php echo $realword; ?></h3>        
        </div>
        <div class="modal-body">
          <p>БРАВО!</p>
          <p><i>Познахте думата!</i></p>
          
        </div>
        <div class="modal-footer-wd">
          <button type="button" class="btn btn-default" data-dismiss="modal">ОК</button>
        </div>
      </div>
    </div>
  </div><!-- success modal end -->    
    
 <!-- my project JS -->  <script src="<?php echo base_url();?>assets/js_gibb/gibbet.js"></script>
 
    
 <?php echo $jscript; ?><?php echo $javascript; ?><?php echo $ajaxphp; ?><?php echo $jsclose; ?>
 
   <!-- placed here to serve JS triggers of the modals above  - see  how it works with their session variables in validation functions in the controller -->
   <div class="btn-group">
 <button  type="button"  class="btn btn-md " id="tryExit" ><span class="glyphicon glyphicon-log-out"></span><strong>&nbspИЗХОД</strong></button>
   <button type="button"  class="btn btn-link"  id="tryOnline" >->или игра срещу друг играч</button>
</div>

<div class="row">
<?php echo form_open('gibbet/gametype', 'id="playForm"'); ?>  <!--This has a <form> role. The controller (and method) must be envoked in the form -->     
   <div class="col-sm-3">

  <br>    
  <div id="leftflash"></div>
  <?php echo $this->session->flashdata('msg'); ?>	<!--  successful entering terminal page-->  
<?php echo validation_errors(); ?>

     <div class="form-group">
  
  <label for="name"><span class="glyphicon glyphicon-th-list"></span> Избери категория:</label>
  <select class="form-control input-md " name="slcType"  id="slctType" size='4' >  
        
        <option value="animals">Животни</option> 
        <option value="cities">Градове</option>  
        <option value="plants">Растения</option>  
       
      </select>                      
  <button type="button" class="btn btn-block btn-primary" id="startGame" disabled ><span class="glyphicon glyphicon-ok" ></span>&nbsp ПОТВЪРДИ</button>  
    <br>
    </div>
    </div> 
  <!-- to make Submit working normally with Enter key it's better to have just one submmit in a form. For other I use hidden buttons to serve other outside the form  - JS file and controller-->           
<button  type="submit" name="wordRequest" class="btn btn-link" id="hiddenstart1"></button>  
<button type="submit" name="newWordRequest"  class="btn btn-link"  id="hiddenstart2" ></button>  
<button  type="submit" name="exitUser" class="btn btn-link" id="hiddenexit1"></button>    
<button type="submit"  name="loseExit"  class="btn btn-link"  id="hiddenexit2" ></button>
 <button type="submit" name="online"  class="btn btn-link"  id="hiddenonline1" ></button>
 <button type="submit" name="loseOnline"  class="btn btn-link"  id="hiddenonline2" ></button>
 
 <div class="col-sm-6">

 <h2>дума: <label><span style="color:#1a1aff" id="word"><?php echo $word; ?></span></label></h2><br>
 <h4>описание: <label><span style="color:#000" id="discrpt"> <?php echo $discrip; ?></span></label></h4><br>
 
  <div class="btn-group btn-group-justified"> <!-- the way to justify buttons in a group is each of them to be wrapped in <div class="btn-group"> -->
 
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="а"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="б"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="в"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="г"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="д"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="е"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="ж"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="з"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="и"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="й"></div>
</div>
 <div class="btn-group btn-group-justified">

<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="к"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="л"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="м"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="н"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="о"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="п"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="р"></div> <div class="btn-group"> <input type="submit"  class="btn btn-sm btn-primary" disabled id="с"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="т"></div> <div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="у"></div>

</div>
 <div class="btn-group btn-group-justified">
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="ф"></div> <div class="btn-group"><input type="submit"   class="btn btn-sm btn-primary"  disabled id="х"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="ц"></div> <div class="btn-group"><input type="submit"   class="btn btn-sm btn-primary"  disabled id="ч"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary"  disabled id="ш"></div> <div class="btn-group"><input type="submit"   class="btn btn-sm btn-primary" disabled id="щ"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="ъ"></div> <div class="btn-group"><input type="submit"   class="btn btn-sm btn-primary"  disabled id="ь"></div>
<div class="btn-group"><input type="submit"  class="btn btn-sm btn-primary" disabled id="ю"></div> <div class="btn-group"><input type="submit"   class="btn btn-sm btn-primary"  disabled id="я"></div>
 </div>
 
 <br><br><br>
 <div class="form-group">
 <h5><label>Знаете думата? Добре, въведете я в полето долу и потвърдете,но сгрешите ли - увисвате на бесилото &#9785!</label></h5>  
 <input  type="text" name="wholeWord" class="form-control" id="guess" onkeyup = "enableButton()" placeholder="Думата е?"  style=" background-color: #cce6ff;">
  <button type="submit"  name="instantGuess" form="playForm" class="btn btn-block btn-danger"  id="guessButt" disabled><span class="glyphicon glyphicon-ok" ></span>ПОТВЪРДИ ДУМАТА</button> 
 </div>
   
 </div> 
  <?php echo form_close();?>
  
 <div class="col-sm-3">
 <div id="rightflash"></div>
  <?php echo $this->session->flashdata('msg_sorry'); ?>	<!--  sorry user-->  
 <img alt="gibbet" class="img-responsive" src="<?php echo base_url();?><?php echo $image; ?>"> <!--  -->
 </div>

  </div>
  
  
  <div class="row">
   <div class="col-sm-7">
  <h4>СТАТИСТИКА за играч <?php echo $name; ?>:</h4>
    <div class="table-responsive">          
  <table class="table table-bordered">
    <thead>
      <tr class="info">
        <th>Изиграни игри</th>
        <th>Спечелени</th>
        <th>Изгубени</th>
         <th>Предположения за букви</th>
        <th>Думи отгатнати веднага</th>
        
      </tr>
    </thead>
    <tbody>
      <tr class="success">
        <td id="tot"><?php echo $total; ?></td>
        <td id="won"><?php echo $won; ?></td>
        <td id="lost"><?php echo $lost; ?></td>
        <td id="gues"><?php echo $guesses; ?></td>
        <td id="instn"><?php echo $instant; ?></td>
      </tr>
      
    </tbody>
  </table>
  </div>
  </div>
  </div>

  