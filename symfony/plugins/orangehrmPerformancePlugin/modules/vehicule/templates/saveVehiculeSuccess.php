<?php //use_stylesheets_for_form($form);  ?>
<style>

</style>
<div id="messagebar" class="messageBalloon_<?php echo $messageType; ?>" >
    <span><?php echo (!empty($messageType)) ? $message : ""; ?></span>
</div>

<div id="location" class="box">
    <div class="head">
        <h1 id="PerformanceHeading"><?php echo __("Dotation de vehicule"); ?></h1>
    </div>

    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>

        <form name="saveVehicule" id="saveVehicule" method="post" action="" >

            <fieldset>

                <ol>
                    <?php echo $form->render(); ?>

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
            $('#saveVehicule').submit();
        });

        $('#btnCancel').click(function(){
            window.location.replace('<?php echo public_path('index.php/vehicule/saveVehicule'); ?>');
        });
    });
</script>