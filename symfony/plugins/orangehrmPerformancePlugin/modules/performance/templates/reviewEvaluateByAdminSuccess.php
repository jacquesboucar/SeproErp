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


                    $columNumber = 0;
                    foreach ($form->getReviewers() as $reviewer) {

                    if (($reviewer->getGroup()->getId() == 2 && $reviewer->getStatus() == 3) || $reviewer->getGroup()->getId() != 2) {
                    if ($reviewer->getReviewerGroupId() != 2) {
                    $reviewerGroupId = $reviewer->getReviewerGroupId();
                    $columNumber = 1;
                    ?>
                    </tr>
                    </table>
                    <br class="clear"/>
                    <div class="smallerHeader"><h1><?php echo __('Evaluation by ' . $reviewer->getGroup()->getName()); ?></h1></div>
                    <table class="expandTable">
                        <br class="clear"/>
                        <tr>
                            <?php } else {
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

                                            <table  class="evaluateBy" >
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
                                                } $notefinale=0;$nbrekpi=0;
                                                foreach ($existe_group as $key =>$ex_group)
                                                {
                                                 ?>
                                                <tr>
                                                    <th colspan="20" style="font-weight:bold;text-align:center;font-size: 12px;"><b><?php echo $ex_group; ?> </b></th>
                                                </tr>
                                                    <tr>
                                                        <th style="width:400px;"><b> INDICATEURS </b></th>
                                                        <th style="width:200px;"><b> PERIODICITE </b></th>
                                                        <th style="width:100px;"><b> POIDS </b></th>
                                                        <th style="width:200px;"><b> CIBLE </b></th>
                                                        <th style="width:250px;"><b> CUMULE </b></th>
                                                        <th style="width:100px;"><b> TAUX ATTEINT </b></th>
                                                        <th style="width:100px;"><b> NOTE FINALE </b></th>
                                                        <th style="width:150px;"><b> COMMENTAIRE </b></th>
                                                        <th style="width:40px;"><b> EVALUER </b></th>
                                                    </tr>
                                                    <?php
                                                        foreach ($rating as $value)
                                                        {

                                                            $reviewer_id = $value->getReviewerId();
                                                            $reviewer = $form->getPerformanceReviewService()->getReviewerById($reviewer_id);
                                                            $reviewer_group = $reviewer->getReviewerGroupId();

                                                            if ($reviewer_group == 1 && $value->getKpi()->getKpiGroup() == $key)
								                            { $nbrekpi++;$notefinale=$notefinale+$value->getNote();
                                                                ?>
                                                                <input type="hidden" value="<?php echo $value->getId(); ?>" id="rating_id_<?php echo $value->getId(); ?>" name="rating_id[<?php echo $value->getId(); ?>]" />
                                                                <tr>

                                                                    <td style="width:400px;"><?php echo $value->getKpi()->getKpiIndicators() ?></td>
                                                                    <td style="width:200px;"><?php echo $value->getKpi()->getDelai() ?></td>
                                                                    <td style="width:100px;"><?php echo $value->getKpi()->getMaxRating() ?></td>
                                                                    <td><input type="text" class="emp" style="width:200px;" id="valeur_cible_<?php echo $value->getId(); ?>" name="valeur_cible[<?php echo $value->getId(); ?>]" value="<?php echo $value->getValeurCible(); ?>"></td>
                                                                    <td><?php echo $value->getCumule() ?></td>
                                                                    <td><?php echo round((double)(($value->getTauxAtteint()/$value->getValeurCible())*100)) ?>%</td>
                                                                    <td><input type="text" class="emp" style="width:100px;" id="noter_<?php echo $value->getId(); ?>" name="noter[<?php echo $value->getId(); ?>]" value="<?php echo $value->getNote(); ?>"></td>
                                                                    <td><textarea class="comment emp" type="text" id="comment_<?php echo $value->getId(); ?>" name="comment[<?php echo $value->getId(); ?>]" ><?php echo $value->getComment(); ?></textarea></td>
                                                                   <td><input type="button" id="btnValeur" name="btnValeur" value="Valeur/Mois" onclick="FormValeurParMois(<?php echo $value->getId(); ?>)"></td>
                                                                    <!-- Confirmation box HTML: Begins -->
                                                                    <div class="modal valeurevaluation" id="Valeurevaluations<?php echo $value->getId(); ?>">
                                                                        <div class="modal-header">
                                                                            <a class="close" data-dismiss="modal">×</a>
                                                                            <h5><?php echo __('Valeurs Atteintes/Mois'); ?></h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <fieldset>
                                                                                <ol>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois1"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Janvier</b></label></div>
                                                                                        <div class="row"><input style="width:200px;" class="emp" type="text" value="<?php echo $value->getRating(); ?>" id="rating_<?php echo $value->getId(); ?>"  name="rating[<?php echo $value->getId(); ?>]" />
                                                                                            Taux:&nbsp<?php echo round((double)($value->getRating()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment1_<?php echo $value->getId(); ?>" name="comment1[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois2"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Fevrier</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois2_<?php echo $value->getId(); ?>" name="mois2[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois2(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois2()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment2_<?php echo $value->getId(); ?>" name="comment2[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                     </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois3"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Mars</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois3_<?php echo $value->getId(); ?>" name="mois3[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois3(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois3()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment3_<?php echo $value->getId(); ?>" name="comment3[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois4"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Avril</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois4_<?php echo $value->getId(); ?>" name="mois4[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois4(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois4()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment4_<?php echo $value->getId(); ?>" name="comment4[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois5"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Mai</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois5_<?php echo $value->getId(); ?>" name="mois5[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois5(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois5()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment5_<?php echo $value->getId(); ?>" name="comment5[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                     </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois6"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Juin</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois6_<?php echo $value->getId(); ?>" name="mois6[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois6(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois6()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment_<?php echo $value->getId(); ?>" name="comment[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois7"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Juillet</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois7_<?php echo $value->getId(); ?>" name="mois7[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois7(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois7()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment7_<?php echo $value->getId(); ?>" name="comment7[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois8"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Aout</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois8_<?php echo $value->getId(); ?>" name="mois8[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois8(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois8()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment8_<?php echo $value->getId(); ?>" name="comment8[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois9"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Septembre</b></label></div>
                                                                                        <div class="row"><input type="text" class="emp" style="width:200px;" id="mois9_<?php echo $value->getId(); ?>" name="mois9[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois9(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois9()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment9_<?php echo $value->getId(); ?>" name="comment9[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois10"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Octobre</b></label></div>
                                                                                        <div class="row"><input  style="width:200px;" class="emp" type="text" id="mois10_<?php echo $value->getId(); ?>" name="mois10[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois10(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois10()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment10_<?php echo $value->getId(); ?>" name="comment10[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois11"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Novembre</b></label></div>
                                                                                        <div class="row"><input style="width:200px;" class="emp" type="text" id="mois11_<?php echo $value->getId(); ?>" name="mois11[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois11(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois11()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment11_<?php echo $value->getId(); ?>" name="comment11[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                    <li>
                                                                                        <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois12"); ?>
                                                                                        <div class="row"><label><b>Taux atteint en Decembre</b></label></div>
                                                                                        <div class="row"><input  type="text" class="emp" style="width:200px;" id="mois12_<?php echo $value->getId(); ?>" name="mois12[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois12(); ?>">
                                                                                            Taux:&nbsp<?php echo round((double)($value->getMois12()/$value->getValeurCible())*100); ?>%</div>
                                                                                        <div class="row"><label>Commentaire</label></div>
                                                                                        <div class="row"><textarea class="comment emp" type="text" id="comment12_<?php echo $value->getId(); ?>" name="comment12[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                                                    </li>
                                                                                </ol>
                                                                            </fieldset>

                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="button" class="btn" data-dismiss="modal" id="dialogEvalutionBtn" value="<?php echo __('Ok'); ?>" />
                                                                            <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <!-- Confirmation box HTML: Ends -->
                                                                </tr>
                                                                <?php
                                                            }

                                                        }

                                                        }
                                                    ?>

                                            </table>

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
                                    <?php echo $form['finalRating']->render(array('value'=> $notefinale/$nbrekpi, 'readonly' => 'readonly')) ?>
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
                        <h5><?php echo __('SeproRH - Confirmation Required'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <p><?php echo __("The review will be made read-only after completion.") . __("This action cannot be undone.") . __("Are you sure you want to continue?"); ?></p>
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
        function FormValeurParMois(arg) {
            $('#Valeurevaluations'+arg).modal("show");
        }
        $(document).ready(function () {
            $('#emp .emp').attr("disabled", "disabled");
            $('.evaluationEmployeeths').show();
            document.getElementById('btnValeur').disabled = false;

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
                    'reviewEvaluation[hrAdminComments]': {required: true, maxlength: 255},
                    'reviewEvaluation[finalRating]': {required: true, min: 0, max: 10000, number: true, positiveNumber: true},
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
                        maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 255)); ?>'
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