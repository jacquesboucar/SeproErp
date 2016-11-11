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
                                $valuesForCalcuation = array();
                                foreach ($rating as $value)
                                {

                                    $reviewer_id = $value->getReviewerId();
                                    $reviewer = $form->getPerformanceReviewService()->getReviewerById($reviewer_id);
                                    $reviewer_group = $reviewer->getReviewerGroupId();

                                    if ($reviewer_group == 1 && $value->getKpi()->getKpiGroup() == $key)
                                    {
                                        ?>
                                        <input type="hidden" value="<?php echo $value->getId(); ?>" id="rating_id_<?php echo $value->getId(); ?>" name="rating_id[<?php echo $value->getId(); ?>]" />
                                        <tr>

                                            <td style="width:400px;"><?php echo $value->getKpi()->getKpiIndicators() ?></td>
                                            <td style="width:200px;"><?php echo $value->getKpi()->getDelai() ?></td>
                                            <td style="width:100px;"><?php echo $value->getKpi()->getMaxRating() ?></td>
                                            <td><?php echo $value->getValeurCible(); ?>" id="rating_<?php echo $value->getId(); ?>"  name="rating[<?php echo $value->getId(); ?>]" /></td>
                                            <td><?php echo $value->getCumule() ?></td>
                                            <td><?php echo round((double)(($value->getTauxAtteint()/$value->getValeurCible())*100)) ?>%</td>
                                            <td><input type="text" style="width:100px;" id="noter_<?php echo $value->getId(); ?>" name="noter[<?php echo $value->getId(); ?>]" value="<?php echo $value->getNote(); ?>"></td>
                                            <td><textarea class="comment" type="text" id="comment_<?php echo $value->getId(); ?>" name="comment[<?php echo $value->getId(); ?>]" ><?php echo $value->getComment(); ?></textarea></td>
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
                                                                <div class="row"><label><b>Valeur atteinte en Janvier</b></label></div>
                                                                <div class="row"><input style="width:200px;" type="text" value="<?php echo $value->getRating(); ?>" id="rating_<?php echo $value->getId(); ?>"  name="rating[<?php echo $value->getId(); ?>]" />
                                                                    Taux:&nbsp<?php echo round((double)($value->getRating()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment1_<?php echo $value->getId(); ?>" name="comment1[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois2"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Fevrier</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois2_<?php echo $value->getId(); ?>" name="mois2[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois2(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois2()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment2_<?php echo $value->getId(); ?>" name="comment2[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois3"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Mars</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois3_<?php echo $value->getId(); ?>" name="mois3[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois3(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois3()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment3_<?php echo $value->getId(); ?>" name="comment3[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois4"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Avril</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois4_<?php echo $value->getId(); ?>" name="mois4[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois4(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois4()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment4_<?php echo $value->getId(); ?>" name="comment4[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois5"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Mai</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois5_<?php echo $value->getId(); ?>" name="mois5[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois5(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois5()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment5_<?php echo $value->getId(); ?>" name="comment5[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois6"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Juin</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois6_<?php echo $value->getId(); ?>" name="mois6[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois6(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois6()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment_<?php echo $value->getId(); ?>" name="comment[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois7"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Juillet</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois7_<?php echo $value->getId(); ?>" name="mois7[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois7(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois7()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment7_<?php echo $value->getId(); ?>" name="comment7[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois8"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Aout</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois8_<?php echo $value->getId(); ?>" name="mois8[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois8(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois8()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment8_<?php echo $value->getId(); ?>" name="comment8[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois9"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Septembre</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois9_<?php echo $value->getId(); ?>" name="mois9[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois9(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois9()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment9_<?php echo $value->getId(); ?>" name="comment9[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois10"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Octobre</b></label></div>
                                                                <div class="row"><input  style="width:200px;" type="text" id="mois10_<?php echo $value->getId(); ?>" name="mois10[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois10(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois10()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment10_<?php echo $value->getId(); ?>" name="comment10[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois11"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Novembre</b></label></div>
                                                                <div class="row"><input style="width:200px;" type="text" id="mois11_<?php echo $value->getId(); ?>" name="mois11[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois11(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois11()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment11_<?php echo $value->getId(); ?>" name="comment11[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois12"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Decembre</b></label></div>
                                                                <div class="row"><input  type="text" style="width:200px;" id="mois12_<?php echo $value->getId(); ?>" name="mois12[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois12(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois12()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment12_<?php echo $value->getId(); ?>" name="comment12[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
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
                        <table  class="expandTable">
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
                                $valuesForCalcuation = array();
                                foreach ($rating as $value)
                                {

                                    $reviewer_id = $value->getReviewerId();
                                    $reviewer = $form->getPerformanceReviewService()->getReviewerById($reviewer_id);
                                    $reviewer_group = $reviewer->getReviewerGroupId();

                                    if ($reviewer_group == 1 && $value->getKpi()->getKpiGroup() == $key)
                                    {
                                        ?>

                                        <input type="hidden" value="<?php echo $value->getId(); ?>" id="rating_id_<?php echo $value->getId(); ?>" name="rating_id[<?php echo $value->getId(); ?>]" />
                                        <tr>

                                            <td style="width:400px;"><?php echo $value->getKpi()->getKpiIndicators() ?></td>
                                            <td style="width:200px;"><?php echo $value->getKpi()->getDelai() ?></td>
                                            <td style="width:100px;"><?php echo $value->getKpi()->getMaxRating() ?></td>
                                            <td><?php echo $value->getValeurCible() ?></td>
                                            <td><?php echo $value->getCumule() ?></td>
                                            <td><?php echo round((double)(($value->getTauxAtteint()/$value->getValeurCible())*100)) ?>%</td>
                                            <td><?php echo $value->getNote(); ?></td>
                                            <td><textarea class="comment" type="text" id="comment_<?php echo $value->getId(); ?>" name="comment[<?php echo $value->getId(); ?>]" ><?php echo $value->getComment(); ?></textarea></td>
                                            <td><input type="button" id="btnValeur2" name="btnValeur" value="Valeur/Mois" onclick="FormValeurParMois2(<?php echo $value->getId(); ?>)"></td>
                                            <!-- Confirmation box HTML: Begins -->
                                            <div class="modal valeurevaluation" id="Valeurevaluations2<?php echo $value->getId(); ?>">
                                                <div class="modal-header">
                                                    <a class="close" data-dismiss="modal">×</a>
                                                    <h5><?php echo __('Valeurs Atteintes/Mois'); ?></h5>
                                                </div>
                                                <div class="modal-body">
                                                    <fieldset>
                                                        <ol>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois1"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Janvier</b></label></div>
                                                                <div class="row"><input style="width:200px;" type="text" value="<?php echo $value->getRating(); ?>" id="rating_<?php echo $value->getId(); ?>"  name="rating[<?php echo $value->getId(); ?>]" />
                                                                    Taux:&nbsp<?php echo round((double)($value->getRating()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment1_<?php echo $value->getId(); ?>" name="comment1[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois2"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Fevrier</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois2_<?php echo $value->getId(); ?>" name="mois2[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois2(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois2()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment2_<?php echo $value->getId(); ?>" name="comment2[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois3"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Mars</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois3_<?php echo $value->getId(); ?>" name="mois3[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois3(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois3()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment3_<?php echo $value->getId(); ?>" name="comment3[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois4"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Avril</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois4_<?php echo $value->getId(); ?>" name="mois4[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois4(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois4()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment4_<?php echo $value->getId(); ?>" name="comment4[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois5"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Mai</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois5_<?php echo $value->getId(); ?>" name="mois5[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois5(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois5()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment5_<?php echo $value->getId(); ?>" name="comment5[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois6"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Juin</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois6_<?php echo $value->getId(); ?>" name="mois6[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois6(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois6()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment_<?php echo $value->getId(); ?>" name="comment[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois7"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Juillet</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois7_<?php echo $value->getId(); ?>" name="mois7[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois7(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois7()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment7_<?php echo $value->getId(); ?>" name="comment7[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois8"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Aout</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois8_<?php echo $value->getId(); ?>" name="mois8[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois8(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois8()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment8_<?php echo $value->getId(); ?>" name="comment8[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois9"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Septembre</b></label></div>
                                                                <div class="row"><input type="text" style="width:200px;" id="mois9_<?php echo $value->getId(); ?>" name="mois9[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois9(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois9()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment9_<?php echo $value->getId(); ?>" name="comment9[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois10"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Octobre</b></label></div>
                                                                <div class="row"><input  style="width:200px;" type="text" id="mois10_<?php echo $value->getId(); ?>" name="mois10[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois10(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois10()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment10_<?php echo $value->getId(); ?>" name="comment10[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois11"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Novembre</b></label></div>
                                                                <div class="row"><input style="width:200px;" type="text" id="mois11_<?php echo $value->getId(); ?>" name="mois11[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois11(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois11()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment11_<?php echo $value->getId(); ?>" name="comment11[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
                                                            </li>
                                                            <li>
                                                                <?php $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(),$value->getId(),"Mois12"); ?>
                                                                <div class="row"><label><b>Valeur atteinte en Decembre</b></label></div>
                                                                <div class="row"><input  type="text" style="width:200px;" id="mois12_<?php echo $value->getId(); ?>" name="mois12[<?php echo $value->getId(); ?>]" value="<?php echo $value->getMois12(); ?>">
                                                                    Taux:&nbsp<?php echo round((double)($value->getMois12()/$value->getValeurCible())*100); ?>%</div>
                                                                <div class="row"><label>Commentaire</label></div>
                                                                <div class="row"><textarea class="comment" type="text" id="comment12_<?php echo $value->getId(); ?>" name="comment12[<?php echo $value->getId(); ?>]" ><?php echo $commentaire['comment']; ?></textarea></div>
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
            <h5><center><?php echo __('SeproRH - Confirmation Required'); ?></center></h5>
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
        function FormValeurParMois(arg) {
            $('#Valeurevaluations'+arg).modal("show");
        }
        function FormValeurParMois2(arg) {
            $('#Valeurevaluations2'+arg).modal("show");
        }
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