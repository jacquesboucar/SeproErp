<?php
use_javascripts_for_form($applyTrainingForm);
use_stylesheets_for_form($applyTrainingForm);
//use_stylesheet(plugin_web_path('orangehrmLeavePlugin', 'css/assignLeaveSuccess.css'));
?>
<?php //include_partial('overlapping_leave', array('overlapLeave' => $overlapLeave, 'workshiftLengthExceeded' => $workshiftLengthExceeded));?>

<div class="box" id="apply-leave">
    <div class="head">
        <h1><?php echo __('Apply Training') ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <?php //if ($applyLeaveForm->hasErrors()): ?>
                <?php //include_partial('global/form_errors', array('form' => $applyTrainingForm)); ?>
        <?php //endif; ?>        
        <?php i//f (count($leaveTypes) > 1) : ?>           
        <form id="frmTrainingApply" name="frmTrainingApply" method="post" action="">
            <?php include_component('core', 'ohrmPluginPannel', array('location' => 'apply-leave-form-elements'))?>
            <fieldset>                
                <ol>
                    <?php echo $applyTrainingForm->render(); ?>
                    <li class="required new">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>                      
                </ol>            
                
                <p>
                    <input type="button" id="applyBtn" value="<?php echo __("Apply") ?>"/>
                </p>                
            </fieldset>
            
        </form>
            
        </form>
        <?php //endif ?>           
    </div> <!-- inner -->
    
</div> <!-- apply leave -->
<script type="text/javascript">
  $(document).ready(function() {
    //Validation
        $("#frmLeaveApply").validate({
            rules: {
                'applyleave[titre]':{required: true },
                
                'applyleave[description]': {maxlength: 250},               
            }
          });  
    //Click Submit button
        $('#applyBtn').click(function(){
            // if($('#applyleave_txtFromDate').val() == displayDateFormat){
            //     $('#applyleave_txtFromDate').val("");
            // }
            // if($('#applyleave_txtToDate').val() == displayDateFormat){
            //     $('#applyleave_txtToDate').val("");
            // }
            $('#frmTrainingApply').submit();
        });
  });
</script>    


