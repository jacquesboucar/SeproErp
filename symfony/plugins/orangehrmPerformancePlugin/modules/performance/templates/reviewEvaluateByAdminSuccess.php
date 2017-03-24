<script>
    function FormValeurParMois(arg) {
        alert('test');
        $('#Valeurevaluations'+arg).modal("show");
    }
</script>
<?php use_stylesheets_for_form($form); ?>

<?php
use_stylesheets_for_form($form);
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, false);
?>

<?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>

    <div class="box" id="divFormContainer">
        <div class="head"><h1><?php echo __('Administrator Evaluation Form'); ?></h1></div>
        <?php include_partial('global/form_errors', array('form' => $form)); ?>

        <div class="inner">
            <?php include_partial('global/flash_messages'); ?>
            <div id="reviewData" >
                <div style="" class="reviewInfo" id="review-details">
                    <div class="smallerHeader"><center><h1><b><?php echo __('Review Details') ?></b></h1></center></div>
                    <table >
                        <tr style="height: 6px"><td></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="labelName"><?php echo __("Employee Name"); ?></label></td><td class="tableColoumnWidthname" ></td> <td><label class="labelValue"><?php echo $form->getReview()->getEmployee()->getFullName() ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="labelName"><?php echo __("Job Title"); ?></label></td> <td class="tableColoumnWidthname" > </td> <td><label class="labelValue"><?php echo $form->getReview()->getJobTitle()->getJobTitleName() ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="labelName"><?php echo __("Review Period"); ?></label></td> <td></td> <td id="reviewColoumnId" ><label class="labelValue"><?php echo set_datepicker_date_format($form->getReview()->getWorkPeriodStart()) . " To " . set_datepicker_date_format($form->getReview()->getWorkPeriodEnd()); ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="labelName"><?php echo __("Review Due Date"); ?></label></td><td></td>  <td><label class="labelValue"><?php echo set_datepicker_date_format($form->getReview()->getDueDate()) ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="labelName"><?php echo __("Status"); ?></label></td> <td></td> <td><label class="labelValue"><?php echo __(ReviewStatusFactory::getInstance()->getStatus($form->getReview()->getStatusId())->getName()) ?></label></td></tr>
                    </table>


                </div>

                <div class="reviewersreviewInfo" >
                    <div class="smallerHeader"><center><h1><?php echo __("Individual Evaluation Status") ?></h1></center></div>

                    <div class="evaluation">
                        <table id="induvidualEvaluate" >
                            <thead>
                            <tr>
                                <th><?php echo __("Reviewer Type"); ?></th>
                                <th><?php echo __("Reviewer Name"); ?></th>
                                <th><?php echo __("Review Status"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($form->getReviewers() as $reviewer) { ?>
                                <tr>
                                    <td class="leftAlign"><?php echo __($reviewer->getGroup()->getName()) ?></td>
                                    <td class="leftAlign"><b><?php echo $reviewer->getEmployee()->getFullName() ?></b></td>
                                    <td class="leftAlign"><?php echo ReviewerReviewStatusFactory::getInstance()->getStatus($reviewer->getStatus())->getName() ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <br/><br/>
            <div>
                <form id="reviewEvaluate" name="reviewEvaluate" method="post" action="">
                    <input type="hidden" id="utilisateur_id" value="<?php echo $form->getUser()->getEmployeeNumber(); ?>"/>
                    <?php

                    echo $form['_csrf_token'];
                    echo $form['id']->render();
                    echo $form['action']->render();
                    echo $form['evaluationsAction']->render();
                    ?>
                    <?php
                    $ratings = $form->getSortedRatings($reviewer->getRating());
                    foreach ($ratings as $rating) {
                        $review_id = $rating->getReviewId();
                    }

                    ?>
                    <div class="pull-right impressionbtn">
                        <a href="<?php echo url_for('performance/reviewEvaluateByAdminPdf') . '?id=' . $review_id ?>" target="_blank">Telecharger</a>
                    </div>
                    <br class="clear"/>
                    <?php
                    if (sizeof($form->getReviewers()) > 0) {
                        $reviewerGroupId = $form->getReviewers()->getFirst()->getReviewerGroupId();
                    }


                    $columNumber = 0;$emp='';
                    foreach ($form->getReviewers() as $reviewer) {

                    if (($reviewer->getGroup()->getId() == 2 && $reviewer->getStatus() == 3) || $reviewer->getGroup()->getId() != 2) {
                    if ($reviewer->getReviewerGroupId() != 2) {
                    $reviewerGroupId = $reviewer->getReviewerGroupId();
                    $columNumber = 1;$emp='';
                    ?>
                    </tr>
                    </table>
                    <br class="clear"/>
                    <div class="smallerHeader"><h1><?php echo __('Evaluation by ' . $reviewer->getGroup()->getName()); ?></h1></div>
                    <table class="expandTable">
                        <br class="clear"/>
                        <tr>
                            <?php } else { $emp='emp';
                            ?>

                            <div class="smallerHeader"><h1><?php echo __('Evaluation by Employee'); ?></h1></div>
                            <table class="expandTable" id="emp">
                                <br class="clear"/>
                                <tr>
                                    <?php
                                    $columNumber++;
                                    }
                                    ?>
                                </tr>
                                <td>
                                    <div>
                                        <div class="evaluationexpand">

                                            <table  class="evaluateBy">
                                                <tr>
                                                    <?php if ($columNumber == 1) { ?>
                                                        <th colspan="20" class="evaluationEmployee">
                                                            &nbsp&nbsp&nbsp&nbsp<?php echo __("Evaluation by") . ' ' . $reviewer->getEmployee()->getFullName(); ?></th>
                                                    <?php } else { ?>
                                                        &nbsp&nbsp&nbsp&nbsp<th colspan="20" class="evaluationEmployee"><?php echo __("Evaluation by") . ' ' . $reviewer->getEmployee()->getFullName(); ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                                    $groupe =  $form->getKpiGroupListAsArray();
                                                    $existe_group = array();
                                                    $param = array('reviewId' => $review_id);
                                                    $rating = $form->getPerformanceReviewService()->searchReviewRating($param);
                                                foreach ($rating as $value) {
                                                    foreach ($groupe as $key => $group) {
                                                        if ($value->getKpi()->getKpiGroup() == $key) {
                                                            $kpigroup = $group;
                                                            if(!in_array($kpigroup, $existe_group)) {
                                                                $existe_group[$key] = $kpigroup;
                                                            }
                                                        }
                                                    }
                                                } $notefinale=0;
                                                $nbrekpi=0;
                                                ?>
                                                <tr>
                                                    <th colspan="20">
                                                        <select id="typeindicateur<?php echo $emp; ?>" style="width: 100%;font-weight:bold;text-align:center;font-size: 14px;" >
                                                            <option>Veuillez choisir un  type d'indicateur</option>
                                                            <option value="Pilotage">Pilotage</option>
                                                            <option value="Performance">Performance</option>

                                                        </select>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="20" style="font-weight:bold;text-align:center;font-size: 12px;">
                                                        <select id="ChoisirGroupe<?php echo $emp; ?>" style="width: 100%;font-weight:bold;text-align:center;font-size: 14px;" >
                                                                <option>Veuillez choisir un  kpi groupe</option>
                                                            <?php foreach ($existe_group as $key =>$ex_group){ ?>
                                                                <option value="<?php echo $ex_group; ?>"><?php echo $ex_group; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </th>
                                                </tr>


                                            </table>

                                            <table  class="expandTable" id="selectbygroupe<?php echo $emp; ?>"></table>

                                            <div  id="modalbygroupe<?php echo $emp; ?>"></div>
                                        </div>
                                    </div>
                                </td>
                                <?php
                                }
                                }
                                ?>
                                </tr>
                            </table>
                            <br class="clear"/>
                            <div class="smallerHeader"><h1><?php echo __('Finalization'); ?></h1></div>
                            <br class="clear"/>
                            <ol>
                                <li class="largeTextBox">
                                    <?php echo $form['hrAdminComments']->renderLabel(null, array('class' => 'labelValue')); ?>
                                    <?php echo $form['hrAdminComments']->render() ?>
                                </li>

                                <li>
                                    <?php echo $form['finalRating']->renderLabel(null, array('class' => 'labelValue')); ?>
                                    <?php echo $form['finalRating']->render() ?>
                                </li>
                                <li>
                                    <?php echo $form['completedDate']->renderLabel(null, array('class' => 'labelValue')); ?>
                                    <?php echo $form['completedDate']->render() ?>
                                </li>

                                <?php if ($form->isEvaluationsEditable()) { ?>
                                    <li class="required">
                                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                                    </li>
                                <?php } ?>
                            </ol>
                            <p>
                                <?php if ($form->isEvaluationsEditable()) { ?>
                                    <input type="button" class="applybutton" id="saveBtn" value="<?php echo __('Save'); ?>" title="<?php echo __('Add'); ?>"/>
                                <?php } ?>
                                <?php if ($form->isEvaluationsCompleateEnabled()) { ?>
                                    <input type="button" class="applybutton" id="completeBtn" data-toggle="modal" data-target="#deleteConfModal" value="<?php echo __('Complete'); ?>" title="<?php echo __('Complete'); ?>"/>
                                <?php } ?>
                                <input type="button" class="reset" id="backBtn" value="<?php echo __('Back'); ?>" title="<?php echo __('Back'); ?>"/>
                            </p>
                </form>
                <!-- Confirmation box HTML: Begins -->
                <div class="modal" id="deleteConfModal">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">×</a>
                        <h5><?php echo __('SeproRH - Confirmation Requise'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <p><?php echo __("L'examen sera rendu en lecture seule une fois la demande terminée.") . __("Cette action ne peut pas être annulée.") . __("Êtes-vous sûr de vouloir continuer?"); ?></p>
                        <br/>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" value="<?php echo __('Ok'); ?>" />
                        <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
                    </div>
                </div>
                <!-- Confirmation box HTML: Ends -->
            </div>

        </div>
    </div>

    <script>

        var backUrl = '<?php echo url_for($backUrl); ?>';
        var getChargement = '<?php echo url_for('performance/getChargementTableau');?>';

        $('.btnValeur').live('click',function () {
            var arg = $(this).attr('data-id');
            $('#Valeurevaluations'+arg).modal("show");
        });

        $('.btnValeuremp').live('click',function () {

            var arg = $(this).attr('data-id');
            $('#Valeurevaluationsemp'+arg).modal("show");
        });

        $(document).ready(function () {


            var kpitype = '';
            var groupeselected = '';

            //$('#ChoisirGroupe').hide();
            $('#ChoisirGroupeemp').hide();

            //Gerer le type d'indicateur

            kpitype = $('#typeindicateur :nth-child(3)').val();
            groupeselected = $('#ChoisirGroupe :nth-child(2)').val();
            var idreview = $('#reviewEvaluation_id').val();
            var popuptype = '';
            $.ajax({
                type: 'GET',
                url: getChargement,
                data: 'groupeselected='+ groupeselected + '&idreview='+idreview + '&popuptype='+popuptype + '&typeindicateur='+kpitype,
                contentType: "application/json",
                success: function (data) {
                    $("#selectbygroupe").html(data.datars);
                    $("#modalbygroupe").html(data.datarsmodal);
                    $('#reviewEvaluation_finalRating').val(data.notefinal);
                    <?php if (!$form->isEvaluationsEditable()) { ?>
                    $('input,textarea').attr("disabled", "disabled");
                    $('#backBtn').removeAttr("disabled");
                    $(".calendar").datepicker('disable');
                    <?php } ?>
                    $('.btnValeur').removeAttr("disabled");
                },
                error : function (error) {
                    console.dir(error);
                }
            });


            $('#typeindicateur').change(function() {

                ShowGroupbyType();
            });

            $('#typeindicateuremp').change(function() {

                ShowGroupbyTypeEmp();
            });

            function ShowGroupbyType() {

                $('#ChoisirGroupe').show();

               // alert(kpitype);
                $('#ChoisirGroupe').change(function() {

                    $("#selectbygroupe").html('');
                    $("#modalbygroupe").html('');

                   // alert(kpitype);
                    kpitype = document.getElementById('typeindicateur').value;
                    var groupeselected = document.getElementById('ChoisirGroupe').value;

                    var idreview = $('#reviewEvaluation_id').val();
                    var popuptype = '';


                    $.ajax({
                        type: 'GET',
                        url: getChargement,
                        data: 'groupeselected='+ groupeselected + '&idreview='+idreview + '&popuptype='+popuptype + '&typeindicateur='+kpitype,
                        contentType: "application/json",
                        success: function (data) {
                            $("#selectbygroupe").html(data.datars);
                            $("#modalbygroupe").html(data.datarsmodal);
                            $('#reviewEvaluation_finalRating').val(data.notefinal);
                            <?php if (!$form->isEvaluationsEditable()) { ?>
                            $('input,textarea').attr("disabled", "disabled");
                            $('#backBtn').removeAttr("disabled");
                            $(".calendar").datepicker('disable');
                            <?php } ?>
                            $('.btnValeur').removeAttr("disabled");
                        },
                        error : function (error) {
                            console.dir(error);
                        }
                    });
                });

            }

            function ShowGroupbyTypeEmp() {

                $('#ChoisirGroupeemp').show();

                $('#ChoisirGroupeemp').change(function() {

                    $("#selectbygroupeemp").html('');
                    $("#modalbygroupeemp").html('');

                    var kpitype = document.getElementById('typeindicateuremp').value;
                    var groupeselected = document.getElementById('ChoisirGroupeemp').value;
                    var idreview = $('#reviewEvaluation_id').val();
                    var popuptype = 'emp';
                    /*
                    if(groupeselected =='' || idreview ==''){
                        $("#selectbygroupeemp").html('');
                        $("#modalbygroupeemp").html('');
                        return;
                    }
                    */
                    $.ajax({
                        type: 'GET',
                        url: getChargement,
                        data: 'groupeselected='+ groupeselected + '&idreview='+idreview + '&popuptype='+popuptype + '&typeindicateur='+kpitype,
                        contentType: "application/json",
                        success: function (data) {
                            $("#selectbygroupeemp").html(data.datars);
                            $("#modalbygroupeemp").html(data.datarsmodal);
                            $('#reviewEvaluation_finalRating').val(data.notefinal);
                            $('#emp #selectbygroupeemp .emp').attr("disabled", "disabled");
                            $('#emp #modalbygroupeemp .emp').attr("disabled", "disabled");
                        },
                        error : function (error) {
                            console.dir(error);
                        }
                    });
                });

            }

            //Sans Le change
            function ShowGroupbyTypeNotChange() {

                $('#ChoisirGroupe').show();

                //alert(kpitype);
                    $("#selectbygroupe").html('');
                    $("#modalbygroupe").html('');

                    var idreview = $('#reviewEvaluation_id').val();
                    var popuptype = '';


                    $.ajax({
                        type: 'GET',
                        url: getChargement,
                        data: 'groupeselected='+ groupeselected + '&idreview='+idreview + '&popuptype='+popuptype + '&typeindicateur='+kpitype,
                        contentType: "application/json",
                        success: function (data) {
                            $("#selectbygroupe").html(data.datars);
                            $("#modalbygroupe").html(data.datarsmodal);
                            $('#reviewEvaluation_finalRating').val(data.notefinal);
                            <?php if (!$form->isEvaluationsEditable()) { ?>
                            $('input,textarea').attr("disabled", "disabled");
                            $('#backBtn').removeAttr("disabled");
                            $(".calendar").datepicker('disable');
                            <?php } ?>
                            $('.btnValeur').removeAttr("disabled");
                        },
                        error : function (error) {
                            console.dir(error);
                        }
                    });

            }

            $('#ChoisirGroupe').removeAttr("disabled");

            $('#emp .emp').attr("disabled", "disabled");
            $('.evaluationEmployeeths').show();
            $('#btnValeur').removeAttr("disabled");
            //alert(test);
            <?php if (!$form->isEvaluationsEditable()) { ?>
            $('input,textarea').attr("disabled", "disabled");
            $('#backBtn').removeAttr("disabled");
            $(".calendar").datepicker('disable');
            <?php } ?>
            $('#saveBtn').click(function () {
                var settings = $('#reviewEvaluate').validate().settings;
                delete settings.rules["reviewEvaluation[hrAdminComments]"];
                delete settings.messages["reviewEvaluation[hrAdminComments]"];
                delete settings.rules["reviewEvaluation[finalRating]"];
                delete settings.messages["reviewEvaluation[finalRating]"];
                delete settings.rules["reviewEvaluation[completedDate]"];
                delete settings.messages["reviewEvaluation[completedDate]"];
                $('#reviewEvaluation_action').attr('value', 'save');
                $('#reviewEvaluate').submit();
            });

            $('#completeBtn').click(function () {
                if ($('#reviewEvaluate').valid()) {
                    $("#deleteConfModal").modal();
                }

            });

            $('#dialogDeleteBtn').bind('click', function () {
                $('#reviewEvaluation_action').attr('value', 'complete');
                $('#reviewEvaluation_evaluationsAction').attr('value', 'complete');
                $('#reviewEvaluate').submit();
            });

            $('#backBtn').click(function () {
                console.log(backUrl);
                window.location.replace(backUrl);
            });

            var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
            var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => get_datepicker_date_format($sf_user->getDateFormat()))) ?>';

            $.datepicker.setDefaults({showOn: 'click'});

            daymarker.bindElement(".ohrm_datepicker", {
                dateFormat: datepickerDateFormat,
                onClose: function () {
                    $(this).valid();
                }
            });

            $('.ohrm_datepicker').click(function () {
                daymarker.show(this);
            });

            $('.calendarBtn').click(function () {
                var elementId = ($(this).prev().attr('id'));
                daymarker.show("#" + elementId);
            });
            $('#completeBtn').click(function(){
                $('#deleteConfModal').modal({
                    show: true,
                    closeOnEscape: true
                });
            });


            $("#reviewEvaluate").validate({
                rules: {
                    'reviewEvaluation[hrAdminComments]': {required: true, maxlength: 10000000},
                    'reviewEvaluation[finalRating]': {required: true, min: 0, max: 10000, number: true},
                    'reviewEvaluation[completedDate]': {
                        required: true,
                        valid_date: function () {
                            return {
                                format: datepickerDateFormat
                            }
                        }

                    }
                },
                messages: {
                    'reviewEvaluation[hrAdminComments]': {
                        required: '<?php echo __(ValidationMessages::REQUIRED); ?>',
                        maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 10000000)); ?>'
                    },
                    'reviewEvaluation[finalRating]': {
                        required: '<?php echo __(ValidationMessages::REQUIRED); ?>',
                        number: '<?php echo __(ValidationMessages::VALID_NUMBER); ?>'

                    },
                    'reviewEvaluation[completedDate]': {
                        required: '<?php echo __(ValidationMessages::REQUIRED); ?>',
                        valid_date: lang_invalidDate
                    }
                }
            });

            $.validator.addMethod('positiveNumber',
                function (value) {
                    if (!parseFloat(value) > 0) {
                        return /^[0-9][0-9]*$/.test(value);
                    } else {
                        return true;
                    }
                }, '<?php echo __(PerformanceValidationMessages::ONLY_INTEGER_ALLOWED); ?>');

        });
         var minMsg = "<?php //echo __('Rating should be less than or equal to ') ?>";
         var maxMsg = "<?php //echo __('Rating should be greater than or equal to ') ?>";
         jQuery.extend(jQuery.validator.messages, {
         max: jQuery.validator.format(minMsg + "{0}."),
         min: jQuery.validator.format(maxMsg + "{0}.")
         });

    </script>

<?php
use_stylesheets_for_form($form);
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);
?>
