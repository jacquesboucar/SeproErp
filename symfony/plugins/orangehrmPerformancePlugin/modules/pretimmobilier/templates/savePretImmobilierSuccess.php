<?php use_stylesheets_for_form($form);  ?>
<style>

</style>
<div id="messagebar" class="messageBalloon_<?php echo $messageType; ?>" >
    <span><?php echo (!empty($messageType)) ? $message : ""; ?></span>
</div>

<div id="location" class="box">
    <div class="head">
        <h1 id="PerformanceHeading"><?php echo __("Prêt Immobilier"); ?></h1>
    </div>

    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>

        <form name="savePretImmobilier" id="savePretImmobilier" method="post" action="" enctype="multipart/form-data">
            <?php echo $form['_csrf_token'];
            echo $form['id'];
            ?>
            <?php if($form['id']->getValue()!=null){ ?>
            <div class="pull-right impressionbtn">
                <a href="<?php echo url_for('pretimmobilier/PretImmobilierPdf') . '?id=' . $form['id']->getValue(); ?>" target="_blank">Telecharger</a>
            </div>
            <?php } ?>
            <fieldset>

                <ol>

                    <li>
                        <?php echo $form['montant_pret']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['montant_pret']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['objet']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['objet']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['nombre_mois']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['nombre_mois']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['date_accord']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['date_accord']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['date_prelevement']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['date_prelevement']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['quotite_saisissable']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['quotite_saisissable']->render() ?>
                    </li>
                    <li>
                        <?php echo $form['description']->renderLabel(null, array('class' => 'labelValue')); ?>
                        <?php echo $form['description']->render() ?>
                    </li>
                    <?php if($form['id']->getValue() !=null){ ?>
                    <li>
                        <?php echo $form['valider']->renderLabel(null, array('class' => 'labelValue valider')); ?>
                        <?php echo $form['valider']->render() ?>
                    </li>
                    <li>
                        <?php if($form['file']->getValue()== ""){
                            echo $form['file']->renderLabel(null, array('class' => 'labelValue fileteleversement'));
                            echo $form['file']->render();
                        }else{
                            $linkHtml = "<div id=\"fileLink\"><a target=\"_blank\" class=\"fileLink\" href=\"";
                            $linkHtml .= url_for('pretimmobilier/viewPretImmobilierAttachment?hdnEditId=' . $form['id']->getValue());
                            $linkHtml .= "\">{$form['file']->getValue()}</a></div>";
                            echo $form['file']->renderLabel(null, array('class' => 'labelValue'));
                            echo $linkHtml;
                        } ?>

                    </li>
                    <?php }?>
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

        $('#saveBtn').click(function(){
            $('#savePretImmobilier').submit();
        });

        $('#btnCancel').click(function(){
            window.location.replace('<?php echo public_path('index.php/pretimmobilier/savePretImmobilier'); ?>');
        });
    });
</script>