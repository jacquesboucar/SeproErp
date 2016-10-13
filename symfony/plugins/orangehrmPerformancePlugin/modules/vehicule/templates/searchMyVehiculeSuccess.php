
<?php use_stylesheet(plugin_web_path('orangehrmPerformancePlugin','css/myPerformanceReviewSuccess.css')); ?> 
<br class="clear" />
   <div id="listDiv">   
        <?php  include_component('core', 'ohrmList'); ?>
    </div>
<?php include_partial('global/delete_confirmation'); ?>
  <style>
      
      table.data-table td {
          padding: 5px !important;
      }
  </style>


<script>
    $(document).ready(function() {


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
        document.location.href = "<?php echo public_path('index.php/vehicule/saveVehicule'); ?>";
    }
</script