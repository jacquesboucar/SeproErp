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
            <br class="clear"/>
            <form id="reviewEvaluate" name="reviewEvaluate" method="post" action="">
                <?php
                $reviewStatus = ReviewStatusFactory::getInstance()->getStatus($form->getReview()->getStatusId())->getName();
                if ($reviewStatus == 'Approved') {
                foreach ($form->getReviewers() as $reviewer) {
                if ($reviewer->getGroup()->getName() == 'Supervisor' && $form->getReviewer()->getGroup()->getName() != 'Supervisor') {
                ?>

                <form id="reviewEvaluate" name="reviewEvaluate" method="post" action="">
                    <div class="smallerHeader"><h1><?php echo __('Evaluation by ' . $reviewer->getGroup()->getName()) ?></h1></div>
                    <?php echo $form->render() ?>
                    <div class="evaluationexpand">
                        <table class="expandTable">
                            <thead>
                            <tr>
                                <th></th>
                                <th><?php echo __("KPI"); ?></th>
                                <th><?php echo __("Min"); ?></th>
                                <th><?php echo __("Max"); ?></th>
                                <th><?php echo __("Rating"); ?></th>
                                <th><?php echo __("Comment"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $valuesForCalcuation = array();
                            foreach ($reviewer->getRating() as $rating) {
                                ?>
                                <tr>
                                    <td>
                                        <input type="hidden" value="<?php echo $rating->getId(); ?>" id="rating_id_<?php echo $rating->getId(); ?>" name="rating_id[<?php echo $rating->getId(); ?>]" />
                                    </td>
                                    <td class="rightAlign"><center><?php echo $rating->getKpi()->getKpiIndicators() ?></center></td>
                                    <td class="rightAlign"><center><?php echo $rating->getKpi()->getMinRating() ?></center></td>
                                    <td class="rightAlign"><center><?php echo $rating->getKpi()->getMaxRating() ?></center></td>
                                    <td><center><input class="rightAlign" min="<?php echo $rating->getKpi()->getMinRating() ?>" max="<?php echo $rating->getKpi()->getMaxRating() ?>"  type="text" value="<?php echo $rating->getRating(); ?>" id="rating_<?php echo $rating->getId(); ?>"  name="rating[<?php echo $rating->getId(); ?>]" /></center></td>
                                    <td><center><textarea class="comment" type="text" id="comment_<?php echo $rating->getId(); ?>" name="comment[<?php echo $rating->getId(); ?>]" ><?php echo $rating->getComment(); ?></textarea></center></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td colspan="4"><td colspan="1"><label class="lableName"><?php echo __("General Comment : "); ?></label></td>
                                <td colspan="1"><center><textarea class="comment" type="text" id="general_comment_<?php echo $reviewer->getGroup()->getId(); ?>" name="general_comment[<?php echo $reviewer->getGroup()->getId(); ?>]" ><?php echo $reviewer->getComment(); ?></textarea></center></td>
                            </tr>

                            </tbody>
                        </table>
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
                    <div class="evaluationexpand">
                        <table class="expandTable">
                            <thead>
                            <tr>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $valuesForCalcuation = array();
                            $groupe =  $form->getKpiGroupListAsArray();
                            $existe_group = array();
                            //var_dump($form->getReviewRatings());die;
                            foreach ($form->getReviewRatings() as $rating) {
                                ?>
                                <tr>
                                    <input type="hidden" value="<?php echo $rating->getId(); ?>" id="rating_id_<?php echo $rating->getId(); ?>" name="rating_id[<?php echo $rating->getId(); ?>]" />

                                        <td class="rightAlign">
                                            <table border="0" class="tableauevalution">
                                                <thead>

                                                    <?php foreach ($groupe as $key => $value): ?>
                                                        <?php if ($rating->getKpi()->getKpiGroup() == $key): ?>
                                                            <?php $group = $value; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <?php if(!in_array($group, $existe_group)){ ?>

                                                        <tr class="evaluationEmployeeth">
                                                            <th colspan="20">
                                                                <center><b><?php echo $group ?></b></center>
                                                                <?php $existe_group[] = $group ?>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th><b><?php echo __("Indicateur"); ?></b></th>
                                                            <th><b><?php echo __("Objectifs"); ?></b></th>
                                                            <th><b><?php echo __("Mode de Calcul"); ?></b></th>
                                                            <th><b><?php echo __("Delai"); ?></b></th>
                                                            <th><b><?php echo __("Poids"); ?></b></th>
                                                            <th><b><?php echo __("Cible"); ?></b></th>
                                                            <th><b><?php echo __("Mois 1"); ?></b></th>
                                                            <th><b><?php echo __("Mois 2"); ?></b></th>
                                                            <th><b><?php echo __("Mois 3"); ?></b></th>
                                                            <th><b><?php echo __("Mois 4"); ?></b></th>
                                                            <th><b><?php echo __("Mois 5"); ?></b></th>
                                                            <th><b><?php echo __("Mois 6"); ?></b></th>
                                                            <th><b><?php echo __("Mois 7"); ?></b></th>
                                                            <th><b><?php echo __("Mois 8"); ?></b></th>
                                                            <th><b><?php echo __("Mois 9"); ?></b></th>
                                                            <th><b><?php echo __("Mois 10"); ?></b></th>
                                                            <th><b><?php echo __("Mois 11"); ?></b></th>
                                                            <th><b><?php echo __("Mois 12"); ?></b></th>
                                                            <th><b><?php echo __("Comment"); ?></b></th>
                                                        </tr>
                                                    <?php } ?>
                                                </thead>
                                                <tbody>
                                                <tr>

                                                    <td class="rightAlign"><?php echo $rating->getKpi()->getKpiIndicators() ?></td>
                                                    <td class="rightAlign"><center><?php echo $rating->getKpi()->getObjectif() ?></center></td>
                                                    <td class="rightAlign"><center><?php echo $rating->getKpi()->getModeCalcul() ?></center></td>
                                                    <td class="rightAlign"><center><?php echo $rating->getKpi()->getDelai() ?></center></td>
                                                    <td class="rightAlign"><center><?php echo $rating->getKpi()->getMaxRating() ?></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="valeur_cible_<?php echo $rating->getId(); ?>" name="valeur_cible[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getValeurCible(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" min="<?php echo $rating->getKpi()->getMinRating() ?>" max="<?php echo $rating->getKpi()->getMaxRating() ?>"  type="text" value="<?php echo $rating->getRating(); ?>" id="rating_<?php echo $rating->getId(); ?>"  name="rating[<?php echo $rating->getId(); ?>]" /></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois2_<?php echo $rating->getId(); ?>" name="mois2[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois2(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois3_<?php echo $rating->getId(); ?>" name="mois3[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois3(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois4_<?php echo $rating->getId(); ?>" name="mois4[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois4(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois5_<?php echo $rating->getId(); ?>" name="mois5[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois5(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois6_<?php echo $rating->getId(); ?>" name="mois6[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois6(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois7_<?php echo $rating->getId(); ?>" name="mois7[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois7(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois8_<?php echo $rating->getId(); ?>" name="mois8[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois8(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois9_<?php echo $rating->getId(); ?>" name="mois9[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois9(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois10_<?php echo $rating->getId(); ?>" name="mois10[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois10(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois11_<?php echo $rating->getId(); ?>" name="mois11[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois11(); ?>"></center></td>
                                                    <td><center><input class="rightAlign" type="text" id="mois12_<?php echo $rating->getId(); ?>" name="mois12[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getMois12(); ?>"></center></td>
                                                    <td><textarea class="comment" type="text" id="comment_<?php echo $rating->getId(); ?>" name="comment[<?php echo $rating->getId(); ?>]" value="<?php echo $rating->getComment(); ?>"></textarea> </td>

                                                </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                </tr>
                                <?php
                            }
                            ?>
                           <!-- <tr>
                                <td colspan="3"><td colspan="1"><label class="lableName"><?php //echo __("General Comment : "); ?></label></td>
                                <td colspan="7"><center><textarea class="comment" type="text" id="general_comment_<?php //echo $form->getReviewer()->getGroup()->getId(); ?>" name="general_comment[<?php echo $form->getReviewer()->getGroup()->getId(); ?>]" value="<?php echo $form->getReviewer()->getComment(); ?>"></textarea></center></td>
                            </tr>
                            -->
                            </tbody>
                        </table>
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
                        <?php if ($form->isEditable()) { ?>
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
            <h5><?php echo __('SeproRH - Confirmation Required'); ?></h5>
        </div>
        <div class="modal-body">
            <p><?php echo __("The review will be made read-only after completion.") . __("This action cannot be undone.") . __("Are you sure you want to continue?"); ?></p>
        </div>
        <div class="modal-footer">
            <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" value="<?php echo __('Ok'); ?>" />
            <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
        </div>
    </div>

    <script>
        $(document).ready(function () {
            <?php if (!$form->isEditable()) { ?>
            $('input,textarea').attr("disabled", "disabled");
            $('#backBtn').removeAttr("disabled");
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
      /* var minMsg = "<?php //echo __('Rating should be less than or equal to ') ?>";
        var maxMsg = "<?php //echo __('Rating should be greater than or equal to ') ?>";
        jQuery.extend(jQuery.validator.messages, {
            max: jQuery.validator.format(minMsg + "{0}."),
            min: jQuery.validator.format(maxMsg + "{0}.")
        });
        */
    </script>

<?php
use_stylesheets_for_form($form);
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);
?>