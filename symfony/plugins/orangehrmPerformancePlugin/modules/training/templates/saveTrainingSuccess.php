<?php //use_stylesheets_for_form($form);  ?>
<style>

</style>
<div id="messagebar" class="messageBalloon_<?php echo $messageType; ?>" >
    <span><?php echo (!empty($messageType)) ? $message : ""; ?></span>
</div>

<div id="location" class="box">
    <div class="head">
        <h1 id="PerformanceHeading"><?php echo __("Demande de Formation"); ?></h1>
    </div>

    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>

        <form name="searchKpi" id="searchKpi" method="post" action="" enctype="multipart/form-data">
            <?php echo $form['_csrf_token'];
            echo $form['id']; ?>
            <?php if($form['id']->getValue()!=null){ ?>
            <div class="pull-right impressionbtn">
                <a href="<?php echo url_for('training/TrainingPdf') . '?id=' . $form['id']->getValue(); ?>" target="_blank" id="btnimprimer">Telecharger</a>
            </div>
            <?php } ?>
            <fieldset>

                <ol>
                    <li>
                        <?php echo $form['employee']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['employee']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['cout']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['cout']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['titre']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['titre']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['description']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['description']->render() ?>
                    </li>
                    <li>
                    <li>
                        <?php if($form['fileformation']->getValue()== ""){
                            echo $form['fileformation']->renderLabel(null, array('class' => 'labelValue'));
                            echo $form['fileformation']->render();
                        }else{
                            $linkHtml = "<div id=\"fileLink\"><a target=\"_blank\" class=\"fileLink\" href=\"";
                            $linkHtml .= url_for('training/viewTrainingFormAttachment?hdnEditId=' . $form['id']->getValue());
                            $linkHtml .= "\">{$form['fileformation']->getValue()}</a></div>";
                            echo $form['fileformation']->renderLabel(null, array('class' => 'labelValue'));
                            echo $linkHtml;
                        } ?>

                    </li>
                    <?php if($form['id']->getValue() !=null){ ?>
                    <li>
                        <?php echo $form['valider']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['valider']->render() ?>
                    </li>
                    <li>
                        <?php if($form['file']->getValue()== ""){
                            echo $form['file']->renderLabel(null, array('class' => 'labelValue'));
                            echo $form['file']->render();
                        }else{
                            $linkHtml = "<div id=\"fileLink\"><a target=\"_blank\" class=\"fileLink\" href=\"";
                            $linkHtml .= url_for('training/viewTrainingAttachment?hdnEditId=' . $form['id']->getValue());
                            $linkHtml .= "\">{$form['file']->getValue()}</a></div>";
                            echo $form['file']->renderLabel(null, array('class' => 'labelValue'));
                            echo $linkHtml;
                        } ?>

                    </li>
                    <?php } ?>
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

        var status = document.getElementById("addTraining_valider").value;
        if(status=='Valider' || status=='Rejetter')
        {
            $('#addTraining_employee_empName').attr("disabled", "disabled");
            $('#addTraining_cout').attr("disabled", "disabled");
            $('#addTraining_titre').attr("disabled", "disabled");
            $('#addTraining_description').attr("disabled", "disabled");
            $('#addTraining_fileformation').attr("disabled", "disabled");
            $('#addTraining_valider').attr("disabled", "disabled");
            $('#addTraining_file').attr("disabled", "disabled");
            $('#btnimprimer').hide();
        }
        
        
        $('#saveBtn').click(function(){           
            $('#searchKpi').submit();
        });
        
        $('#btnCancel').click(function(){
            window.location.replace('<?php echo public_path('index.php/training/searchTraining'); ?>');
        });
    });
</script>