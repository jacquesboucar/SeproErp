<?php use_stylesheets_for_form($form); ?>

<div class="box searchForm toggableForm" id="divFormContainer">
    <div class="head">
        <h1><?php echo __('Search Key Performance Indicators'); ?></h1>
    </div>

    <div class="inner">

        <form id="searchKpi" name="searchKpi" method="post" action="">
            <fieldset>
                <ol>
                    <?php echo $form->render(); ?>
                </ol>

                <p>
                    <input type="button" class="addbutton" name="searchBtn" id="searchBtn" value="<?php echo __("Search"); ?>"/>
                </p>
                <div class="pull-right impressionbtn">
                    <input type="button" class="btnTypeKpi" data-toggle="modal" data-target="#KpiTelecharger" value="Telecharger">
                </div>
            </fieldset>
        </form>
    </div>
    <a href="#" class="toggle tiptip" title="<?php echo __(CommonMessages::TOGGABLE_DEFAULT_MESSAGE); ?>">&gt;</a>
</div>


<?php include_component('core', 'ohrmList'); ?>

<?php include_partial('global/delete_confirmation'); ?>

<div class="modal KpiTele" id="KpiTelecharger">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h5>Choisir le Type d'indicateur</h5>
    </div>
    <div class="modal-body">
        <fieldset>
            <ol>
                <a href="<?php echo url_for('performance/searchKpiPdf') . '?typeindicateur=Performance' ?>" style="width: 100px" target="_blank">
                    <button type="button" class="btn btn-success btn-lg btn-block">Performance</button>
                </a>
            </ol>
            <ol>
                <a href="<?php echo url_for('performance/searchKpiPdf') . '?typeindicateur=Pilotage'  ?>" target="_blank">
                    <button type="button" class="btn btn-success btn-lg btn-block">Pilotage</button>
                </a>
            </ol>

        </fieldset>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn reset" data-dismiss="modal" value="Cancel" />
    </div>
</div>
<script>
    $(document).ready(function() {


        $('#searchBtn').click(function(){
            $('#searchKpi').submit();
        });   


        $('#btnDelete').attr('disabled', 'disabled');
        
        $("#ohrmList_chkSelectAll").change(function() {
            if($(":checkbox").length == 1) {
                $('#btnDelete').attr('disabled','disabled');
            }
            else {
                if($("#ohrmList_chkSelectAll").is(':checked')) {
                    $('#btnDelete').removeAttr('disabled');
                } else {
                    $('#btnDelete').attr('disabled','disabled');
                }
            }
        });
        
        $(':checkbox[name*="chkSelectRow[]"]').click(function() {
            if($(':checkbox[name*="chkSelectRow[]"]').is(':checked')) {
                $('#btnDelete').removeAttr('disabled');
            } else {
                $('#btnDelete').attr('disabled','disabled');
            }
        });
        
        $('#dialogDeleteBtn').click(function() {
            $('#frmList_ohrmListComponent').submit();
        });
    });

    function addKpi(){
        document.location.href = "<?php echo public_path('index.php/performance/saveKpi'); ?>";
    }
</script>