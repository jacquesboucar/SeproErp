<?php //use_stylesheets_for_form($form);  ?>
<style>

</style>
<div id="messagebar" class="messageBalloon_<?php echo $messageType; ?>" >
    <span><?php echo (!empty($messageType)) ? $message : ""; ?></span>
</div>

<div id="location" class="box">
    <div class="head">
        <h1 id="PerformanceHeading"><?php echo __("Key Performance Indicator"); ?></h1>
    </div>

    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>

        <form name="searchKpi" id="searchKpi" method="post" action="" >

            <fieldset>

                <ol>
                    <li>
                        <?php echo $form['kpi_group']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['kpi_group']->render() ?>
                    </li>
                    <p id="selectManyTable">
                    <table border="0" width="45%" class="">
                        <tbody>
                        <tr>
                            <td width="35%" style="font-weight:bold; height: 20px">
                                <?php echo __("available JobTitle"); ?>
                            </td>
                            <td width="30%"></td>
                            <td width="35%" ><span style="font-weight: bold"><?php echo __("added JobTitle"); ?></span><em style="color: #AA4935"> *</em></td>
                        </tr>
                        <td>
                            <?php echo $form['availableJob']->render(array("class" => "selectMany", "size" => 10, "style" => "width: 100%")); ?>
                        </td>
                        <td align="center" style="vertical-align: middle">

                            <input type="button" style="width: 70%;" value="<?php echo __("Add"). " >"; ?>" class="" id="btnAssignEmployee" name="btnAssignEmployee">
                            <br><br>
                            <input type="button" style="width: 70%;" value="<?php echo "< ".__("Remove"); ?>" class="delete" id="btnRemoveEmployee" name="btnRemoveEmployee">

                        </td>
                        <td>
                            <?php echo $form['assignedJob']->render(array("class" => "selectMany", "size" => 10, "style" => "width: 100%")); ?>
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    </p>
                    <li>
                       <?php echo $form['keyPerformanceIndicators']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['keyPerformanceIndicators']->render() ?>
                    </li>
                    <li class="minratingclasse">
                        <?php echo $form['minRating']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['minRating']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['maxRating']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['maxRating']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['delai']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['delai']->render() ?>
                    </li>
                    <li>
                       <?php echo $form['objectif']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['objectif']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['mode_calcul']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['mode_calcul']->render() ?>
                        <?php echo $form['id']->render() ?>
                        <?php echo $form['_csrf_token']->render() ?>
                    </li>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>

                <p>
                    <input type="button" class="addbutton" name="saveBtn" id="saveBtn" value="<?php echo __("Save"); ?>"/>
                    <input id="btnCancel" class="reset" type="button" value="<?php echo __("Cancel"); ?>" name="btnCancel">
                </p>

            </fieldset>

        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#btnAssignEmployee').click(function() {
            return !$('#defineKpi360_availableJob option:selected').remove().appendTo('#defineKpi360_assignedJob');
        });

        $('#btnRemoveEmployee').click(function() {
            return !$('#defineKpi360_assignedJob option:selected').remove().appendTo('#defineKpi360_availableJob');
        });
        $('#saveBtn').click(function(){
            $('#searchKpi').submit();
        });
        
        $("#searchKpi").validate({
            rules: {
                'defineKpi360[jobTitleCode]':{required: true },
                'defineKpi360[keyPerformanceIndicators]':{required: true, maxlength:100 },
                'defineKpi360[minRating]':{ required: true, min: 0, max:100, number:true,  positiveNumber: true, maxMinValidation: true },
                'defineKpi360[maxRating]':{ required: true, min: 0, max:100, number:true,  positiveNumber: true, maxMinValidation: true } 
            },
            messages: {
                'defineKpi360[jobTitleCode]':{
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                },
                'defineKpi360[keyPerformanceIndicators]':{
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100)); ?>'
                },
                'defineKpi360[minRating]':{
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    number:'<?php echo __(ValidationMessages::VALID_NUMBER); ?>',
                    min:'<?php echo __(ValidationMessages::GREATER_THAN, array('%number%' => 0)); ?>',
                    max:'<?php echo __(ValidationMessages::LESS_THAN, array('%number%' => 100)); ?>',
                    maxMinValidation:'<?php echo __(PerformanceValidationMessages::MAX_SHOULD_BE_GREATER_THAN_MIN); ?>'
                    
                },
                'defineKpi360[maxRating]':{
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    number:'<?php echo __(ValidationMessages::VALID_NUMBER); ?>',
                    min:'<?php echo __(ValidationMessages::GREATER_THAN, array('%number%' => 0)); ?>',
                    max:'<?php echo __(ValidationMessages::LESS_THAN, array('%number%' => 100)); ?>',
                    maxMinValidation:'<?php echo __(PerformanceValidationMessages::MAX_SHOULD_BE_GREATER_THAN_MIN); ?>'
                    
                }
            }
        });
        
        $.validator.addMethod('positiveNumber',
        function (value) { 
            if(value>=0 && parseInt(value) >= 0){
                return /^[0-9][0-9]*$/.test(value);    
            } else {
                return false;
            } 
        }, '<?php echo __(PerformanceValidationMessages::ONLY_INTEGER_ALLOWED); ?>');
        
        $.validator.addMethod('jobOrDepartmentValidation',
        function (value) { 
                
            if($('#defineKpi360_jobTitleCode').val() >0 || $('#defineKpi360_department').val() > 0){
                return true
            } else {
                return false;
            }
        });

        $.validator.addMethod('maxMinValidation',
        function (value) { 
            if($('#defineKpi360_maxRating').val() !='' && value>0){
                if( parseInt($('#defineKpi360_maxRating').val()) >  parseInt($('#defineKpi360_minRating').val()) ){                       
                    return true;
                } else {                       
                    return false;
                }
            } else {                  
                return true;
            } 
        });

        
        $('#saveBtn').click(function(){           
            $('#kpiGroup').submit();
        });
        
        $('#btnCancel').click(function(){
            window.location.replace('<?php echo public_path('index.php/performance/searchKpi'); ?>');
        });
    });
</script>