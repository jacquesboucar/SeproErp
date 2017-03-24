<?php
use_stylesheets_for_form($form);
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, false);
?>



    <div class="box" id="divFormContainer">
        <div class="head"><h1><?php echo __($form->getReviewer()->getGroup()->getName() . ' Evaluation Form') ?></h1></div>
        <?php include_partial('global/form_errors', array('form' => $form)); ?>

        <div class="inner">
            <?php include_partial('global/flash_messages'); ?>

            <div id="reviewData paginer">
                <div style="" class="reviewInfo" id="review-details">
                    <div class="smallerHeader"><center><h1><b><?php echo __('Review Details') ?></b></h1></center></div>
                    <table class="expandTable">
                        <tr style="height: 6px"><td></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="lableName"><?php echo __("Employee Name"); ?></label></td> <td class="tableColoumnWidthname"><label class="lableValue"><?php echo $form->getReview()->getEmployee()->getFullName() ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="lableName"><?php echo __("Job Title"); ?></label></td> <td class="tableColoumnWidthname"><label class="lableValue"><?php echo $form->getReview()->getJobTitle()->getJobTitleName() ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="lableName"><?php echo __("Department"); ?></label></td> <td class="tableColoumnWidthname"><label class="lableValue"><?php echo $form->getReview()->getDepartment()->getName() ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="lableName"><?php echo __("Review Period"); ?></label></td> <td class="tableColoumnWidthname"><label class="lableValue"><?php echo set_datepicker_date_format($form->getReview()->getWorkPeriodStart()) . " To " . set_datepicker_date_format($form->getReview()->getWorkPeriodEnd()); ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="lableName"><?php echo __("Review Due Date"); ?></label></td> <td class="tableColoumnWidthname"><label class="lableValue"><?php echo set_datepicker_date_format($form->getReview()->getDueDate()) ?></label></td></tr>
                        <tr class="trReviewHeight"><td class="tableColoumnWidth" ><label class="lableName"><?php echo __("Status"); ?></label></td> <td class="tableColoumnWidthname"><label class="lableValue"><?php echo __(ReviewStatusFactory::getInstance()->getStatus($form->getReview()->getStatusId())->getName()) ?></label></td></tr>
                    </table>
                </div>

                <div class="reviewers reviewInfo" style="float: left; min-height: 202px; width: 400px; margin-left: 20px">
                    <div class="smallerHeader"><center><h1><b><?php echo __("Individual Evaluation Status") ?></b></h1></center></div>
                    <div class="evaluation" style="margin: 0px">
                        <table class="expandTable" id="induvidualEvaluate">
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
                                    <td><?php echo __($reviewer->getGroup()->getName()) ?></td>
                                    <td><b><?php echo $reviewer->getEmployee()->getFullName() ?></b></td>
                                    <td><?php echo ReviewerReviewStatusFactory::getInstance()->getStatus($reviewer->getStatus())->getName() ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br/><br/>
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
            <form id="reviewEvaluate" name="reviewEvaluate" method="post" action="">
                <input type="hidden" id="utilisateur_id" value="<?php echo $form->getUser()->getEmployeeNumber(); ?>"/>
                <?php

                $reviewStatus = ReviewStatusFactory::getInstance()->getStatus($form->getReview()->getStatusId())->getName();
                if ($reviewStatus == 'Approved') {
                foreach ($form->getReviewers() as $reviewer) {
                if ($reviewer->getGroup()->getName() == 'Supervisor' && $form->getReviewer()->getGroup()->getName() != 'Supervisor') {
                ?>

                <form id="reviewEvaluate" name="reviewEvaluate" method="post" action="">
                    <div class="smallerHeader" ><h1><?php echo __('Evaluation by ' . $reviewer->getGroup()->getName()) ?></h1></div>
                    <?php echo $form->render() ?>
                    <div class="evaluationexpand">
                        <table  class="expandTable" >
                            <?php
                            $groupe =  $form->getKpiGroupListAsArray();
                            $existe_group = array();
                            foreach ($reviewer->getRating() as $rating) {
                                $review_id=$rating->getReviewId();
                            }
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
                            }

                                ?>
                            <tr>
                                <th colspan="20">
                                    <select id="typeindicateurres" style="width: 100%;font-weight:bold;text-align:center;font-size: 14px;" >
                                        <option>Veuillez choisir un  type d'indicateur</option>
                                        <option value="Pilotage">Pilotage</option>
                                        <option value="Performance">Performance</option>

                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="20">
                                    <select id="ChoisirGrouperes" style="width: 100%;font-weight:bold;text-align:center;font-size: 14px;" >
                                        <option>Veuillez choisir un  kpi groupe</option>
                                        <?php foreach ($existe_group as $key =>$ex_group){ ?>
                                            <option value="<?php echo $ex_group; ?>"><?php echo $ex_group; ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                        </table>
                        <table  class="expandTable" id="selectbygrouperes"></table>

                        <div  id="modalbygrouperes"></div>
                    </div>
                    <?php
                    }
                    }
                    }
                    ?>
                    <br/><br/>
                    <br class="clear"/>
                    <div class="smallerHeader"><h1><?php echo __('Evaluation by ' . $form->getReviewer()->getGroup()->getName()) ?></h1></div>

                    <?php echo $form->render() ?>
                    <div class="evaluationexpand evaldiv">
                        <table  class="expandTable" >
                            <?php
                                $groupe =  $form->getKpiGroupListAsArray();
                                $existe_group = array();
                                foreach ($form->getReviewRatings() as $rating) {
                                    $review_id=$rating->getReviewId();
                                }
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
                                }
                            $notefinale=0;$nbrekpi=0;
                            ?>
                            <tr>
                                <th colspan="20">
                                    <select id="typeindicateur" style="width: 100%;font-weight:bold;text-align:center;font-size: 14px;" >
                                        <option>Veuillez choisir un  type d'indicateur</option>
                                        <option value="Pilotage">Pilotage</option>
                                        <option value="Performance">Performance</option>

                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="20">
                                    <select id="ChoisirGroupe" style="width: 100%;font-weight:bold;text-align:center;font-size: 14px;" >
                                        <option>Veuillez choisir un  kpi groupe</option>
                                        <?php foreach ($existe_group as $key =>$ex_group){ ?>
                                            <option value="<?php echo $ex_group; ?>"><?php echo $ex_group; ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                        </table>


                        <table  class="expandTable" id="selectbygroupe"></table>

                        <div  id="modalbygroupe"></div>

                    </div>
                    <br/><br/>
                    <br class="clear"/>
                    <?php
                    if ($form->isFinalRatingVisible()) {
                        ?>
                        <div style="width: 800px;">
                            <ol>
                                <li>
                                    <label class="lableName"><?php echo __("Final Comment") . (' '); ?></label><label class="longLabel"><?php echo nl2br($form->getReview()->getFinalComment()); ?></label>
                                </li>
                                <li>
                                    <label class="lableName"><?php echo __("Final Rating") . (' '); ?></label> <label class="lableValue"><?php echo $form->getReview()->getfinalRate(); ?></label>
                                </li>
                                <li>
                                    <label class="lableName"><?php echo __("Completed Date") . (' '); ?></label> <label class="lableValue"><?php echo $form->getReview()->getCompletedDate(); ?></label>
                                </li>
                                <br class="clear"/>
                            </ol>
                        </div>
                        <?php
                    }
                    ?>

                    <br/><br/>
                    <br class="clear"/>

                    <p>
                        <?php
                        if ($form->isEditable()) { ?>
                            <input type="button" class="applybutton" id="saveBtn" value="<?php echo __('Save'); ?>" title="<?php echo __('Add'); ?>"/>
                            <input type="button" class="applybutton" id="completeBtn" value="<?php echo __('Complete'); ?>" title="<?php echo __('Complete'); ?>"/>
                        <?php } ?>
                        <input type="button" class="reset" id="backBtn" value="<?php echo __('Back'); ?>" title="<?php echo __('Back'); ?>"/>
                    </p>
                </form>
        </div>
    </div>
    <!-- Confirmation box HTML: Begins -->
    <div class="modal" id="deleteConfModal">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h5><center><?php echo __('SeproRH - Confirmation Requise'); ?></center></h5>
        </div>
        <div class="modal-body">
            <p><?php echo __("L'examen sera rendu en lecture seule une fois la demande terminée.") . __("Cette action ne peut pas être annulée.") . __("Êtes-vous sûr de vouloir continuer?"); ?></p>
        </div>
        <div class="modal-footer">
            <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" value="<?php echo __('Ok'); ?>" />
            <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
        </div>
    </div>

    <script>

        $('.btnValeurres').live('click',function () {
            var arg = $(this).attr('data-id');
            $('#Valeurevaluationsres'+arg).modal("show");
        });
        $('.btnValeuremp').live('click',function () {
            var arg = $(this).attr('data-id');
            $('#Valeurevaluationsemp'+arg).modal("show");
        });

        // AJout des infos evaluation par ajax
        var getChargement = '<?php echo url_for('performance/getChargementTableauEmp');?>';


        $(document).ready(function () {

            $('#ChoisirGroupe').removeAttr("disabled");
            $('#ChoisirGrouperes').removeAttr("disabled");

            //$('#ChoisirGroupe').hide();
            $('#ChoisirGrouperes').hide();

            var kpitype = '';
            var groupeselected = '';

            //$('#ChoisirGroupe').hide();
            $('#ChoisirGroupeemp').hide();

            //Gerer le type d'indicateur

            kpitype = $('#typeindicateur :nth-child(3)').val();
            groupeselected = $('#ChoisirGroupe :nth-child(2)').val();
            var idreview = $('#reviewEvaluation_id').val();
            var popuptype = 'emp';
            $.ajax({
                type: 'GET',
                url: getChargement,
                data: 'groupeselected='+ groupeselected + '&idreview='+ idreview + '&popuptype='+ popuptype + '&typeindicateur='+ kpitype,
                contentType: "application/json",
                success: function (data) {
                    $("#selectbygroupe").html(data.datars);
                    $("#modalbygroupe").html(data.datarsmodal);
                    <?php if (!$form->isEditable()) { ?>
                    $('input,textarea').attr("disabled", "disabled");
                    $('#backBtn').removeAttr("disabled");
                    $('#btnValeur').removeAttr("disabled");
                    <?php } ?>
                    $('.btnValeuremp').removeAttr("disabled");
                },
                error : function (error) {
                    console.dir(error);
                }
            });

            $('#typeindicateur').change(function() {

                ShowGroupByType();
            });

            $('#typeindicateurres').change(function() {

                ShowGroupByTyperes();


            });

            function ShowGroupByType() {

                $('#ChoisirGroupe').show();

                $('#ChoisirGroupe').change(function() {


                    $("#selectbygroupe").html('');
                    $("#modalbygroupe").html('');

                    var kpitype = document.getElementById('typeindicateur').value;
                    var groupeselected = document.getElementById('ChoisirGroupe').value;
                    var idreview = $('#reviewEvaluation_id').val();
                    var popuptype = 'emp';
                    if(groupeselected =='' || idreview ==''){

                    }

                    $.ajax({
                        type: 'GET',
                        url: getChargement,
                        data: 'groupeselected='+ groupeselected + '&idreview='+ idreview + '&popuptype='+ popuptype + '&typeindicateur='+ kpitype,
                        contentType: "application/json",
                        success: function (data) {
                            $("#selectbygroupe").html(data.datars);
                            $("#modalbygroupe").html(data.datarsmodal);
                            <?php if (!$form->isEditable()) { ?>
                            $('input,textarea').attr("disabled", "disabled");
                            $('#backBtn').removeAttr("disabled");
                            $('#btnValeur').removeAttr("disabled");
                            <?php } ?>
                            $('.btnValeuremp').removeAttr("disabled");
                        },
                        error : function (error) {
                            console.dir(error);
                        }
                    });
                });
            }

            function ShowGroupByTyperes() {
                $('#ChoisirGrouperes').show();

                $('#ChoisirGrouperes').change(function() {

                    $("#selectbygrouperes").html('');
                    $("#modalbygrouperes").html('');

                    var kpitype = document.getElementById('typeindicateur').value;
                    var groupeselected = document.getElementById('ChoisirGrouperes').value;
                    var idreview = $('#reviewEvaluation_id').val();
                    var popuptype = 'res';

                    //if(groupeselected =='' || idreview ==''){
                        //$("#selectbygrouperes").html('');
                        //$("#modalbygrouperes").html('');
                        //return;
                    //}
                    $.ajax({
                        type: 'GET',
                        url: getChargement,
                        data: 'groupeselected='+ groupeselected + '&idreview='+idreview + '&popuptype='+popuptype + '&typeindicateur='+ kpitype,
                        contentType: "application/json",
                        success: function (data) {
                            $("#selectbygrouperes").html(data.datars);
                            $("#modalbygrouperes").html(data.datarsmodal);
                            <?php if (!$form->isEditable()) { ?>
                            $('input,textarea').attr("disabled", "disabled");
                            <?php } ?>
                            $('.btnValeurres').removeAttr("disabled");
                        },
                        error : function (error) {
                            console.dir(error);
                        }
                    });
                });

            }


            <?php if (!$form->isEditable()) { ?>
            $('input,textarea').attr("disabled", "disabled");
            $('#backBtn').removeAttr("disabled");
            $('#btnValeur').removeAttr("disabled");
            <?php } ?>


            $('#saveBtn').click(function () {
                $('#reviewEvaluate').validate();
                $('#reviewEvaluation_action').val("save");
                $('#reviewEvaluate').submit();
            });

            $('#completeBtn').click(function () {
                $("#deleteConfModal").modal();
            });

            $('#dialogDeleteBtn').bind('click', function () {
                $('#reviewEvaluate').validate();
                $('#reviewEvaluation_action').val("complete");
               $('#reviewEvaluate').submit();
            });

            $('#backBtn').click(function () {
                $(location).attr('href', '<?php echo url_for($form->getGoBackUrl()) ?>');
            });

            $("#reviewEvaluate").validate({
                rules: {
                },
                messages: {
                }
            });
        });
        var minMsg = "<?php echo __('Rating should be less than or equal to ') ?>";
        var maxMsg = "<?php echo __('Rating should be greater than or equal to ') ?>";
        jQuery.extend(jQuery.validator.messages, {
            max: jQuery.validator.format(minMsg + "{0}."),
            min: jQuery.validator.format(maxMsg + "{0}.")
        });
    </script>

<?php
use_stylesheets_for_form($form);
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);
?>