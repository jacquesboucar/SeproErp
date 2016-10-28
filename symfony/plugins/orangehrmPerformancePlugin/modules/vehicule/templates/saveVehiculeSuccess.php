<?php //use_stylesheets_for_form($form);  ?>
<style>

</style>
<div id="messagebar" class="messageBalloon_<?php echo $messageType; ?>" >
    <span><?php echo (!empty($messageType)) ? $message : ""; ?></span>
</div>

<div id="location" class="box">
    <div class="head">
        <h1 id="PerformanceHeading"><?php echo __("Dotation de véhicule"); ?></h1>
    </div>

    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>

        <form name="saveVehicule" id="saveVehicule" method="post" action="" enctype="multipart/form-data" class="vehicule">
            <?php echo $form['_csrf_token'];
            echo $form['id'];
            ?>
            <?php if($form['id']->getValue()!=null){ ?>
            <div class="pull-right impressionbtn">
                <a href="<?php echo url_for('vehicule/VehiculePdf') . '?id=' . $form['id']->getValue(); ?>" target="_blank" id="btnimprimer">Télécharger</a>
            </div>
            <?php } ?>
            <fieldset>

                <ol>
                    <li>
                        <?php echo $form['employee']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['employee']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['marque']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['marque']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['energie']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['energie']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['matricule_vehicule']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['matricule_vehicule']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['dotation_carburant']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['dotation_carburant']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['description']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['description']->render() ?>
                    </li>
                    <?php //if($form['id']->getValue() !=null){ ?>
                    <li>
                        <?php echo $form['valider']->renderLabel(null, array('class' => 'labelValue addVehicule_valider')); ?>
                        <?php echo $form['valider']->render() ?>
                    </li>
                    <li>
                        <?php if($form['file']->getValue()== ""){
                        echo $form['file']->renderLabel(null, array('class' => 'labelValue addVehicule_file'));
                        echo $form['file']->render();
                        }else{
                            $linkHtml = "<div id=\"fileLink\"><a target=\"_blank\" class=\"fileLink\" href=\"";
                            $linkHtml .= url_for('vehicule/viewVehiculeAttachment?hdnEditId=' . $form['id']->getValue());
                            $linkHtml .= "\">{$form['file']->getValue()}</a></div>";
                            echo $form['file']->renderLabel(null, array('class' => 'labelValue'));
                            echo $linkHtml;
                        } ?>

                    </li>
                    <?php //} ?>
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

        var status = document.getElementById("addVehicule_valider").value;
        if(status=='Valider' || status=='Rejetter')
        {
            $('#addVehicule_employee_empName').attr("disabled", "disabled");
            $('#addVehicule_marque').attr("disabled", "disabled");
            $('#addVehicule_energie').attr("disabled", "disabled");
            $('#addVehicule_matricule_vehicule').attr("disabled", "disabled");
            $('#addVehicule_dotation_carburant').attr("disabled", "disabled");
            $('#addVehicule_description').attr("disabled", "disabled");
            $('#addVehicule_valider').attr("disabled", "disabled");
            $('#addVehicule_file').attr("disabled", "disabled");
            $('#btnimprimer').hide();
        }else if(!document.getElementById("addVehicule_id").value){
            $('#addVehicule_valider').hide();
            $('#addVehicule_file').hide();
            $('.addVehicule_valider').hide();
            $('.addVehicule_file').hide();
        }

        $('#saveBtn').click(function(){
            $('#saveVehicule').submit();
        });

        $('#btnCancel').click(function(){
            window.location.replace('<?php echo public_path('index.php/vehicule/searchVehicule'); ?>');
        });
    });
</script>