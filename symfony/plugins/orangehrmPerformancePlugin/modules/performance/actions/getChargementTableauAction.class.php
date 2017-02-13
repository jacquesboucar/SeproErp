<?php
header("Content-type: application/json");
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

/**
 * Get leave balance for given employee for given leave type
 *
 */
class getChargementTableauAction extends basePeformanceAction {


    public function getReviewEvaluationForm($options = array()) {

        if ($this->reviewEvaluationForm == null) {
            $form = new ReviewEvaluationAdminForm(array(), $options);
            $form->setUser($this->getUser());
            return $form;
        } else {
            return $this->reviewEvaluationForm;
        }
    }

    /**
     * @param sfRequest $request
     * @return string
     */
    public function execute($request) {
        sfConfig::set('sf_web_debug', false);
        sfConfig::set('sf_debug', false);

       // $result = json_decode($request->getParameter('tab'), true);
        $form = $this->getReviewEvaluationForm();
        $popup = $request->getParameter('popuptype');
        //code chargement du tableau

        $groupe =  $form->getKpiGroupListAsArray();
        $existe_group = array();
        $param = array('reviewId' => $request->getParameter('idreview'));
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
        $rs ='';
        $rsmodal ='';

        foreach ($existe_group as $key =>$ex_group)
        {
            //var_dump(strcmp($result[0]['groupe'],$ex_group));
           if($request->getParameter('groupeselected') === $ex_group )
           {
               $rs .='<tr><th style="width:400px;"><b> INDICATEURS </b></th>
                  <th style="width:200px;"><b> PERIODICITE </b></th>
                  <th style="width:100px;"><b> POIDS </b></th>
                  <th style="width:200px;"><b> CIBLE </b></th>
                  <th style="width:250px;"><b> CUMULE </b></th>
                  <th style="width:100px;"><b> TAUX ATTEINT </b></th>
                  <th style="width:100px;"><b> NOTE FINALE </b></th>
                  <th style="width:150px;"><b> COMMENTAIRE </b></th>
                  <th style="width:40px;"><b> EVALUER </b></th>
                  </tr>';
                foreach ($rating as $value)
                {

                    $reviewer_id = $value->getReviewerId();

                    $reviewer = $form->getPerformanceReviewService()->getReviewerById($reviewer_id);
                    $reviewer_group = $reviewer->getReviewerGroupId();

                    if ($reviewer_group == 1 && $value->getKpi()->getKpiGroup() == $key) {
                        if($value->getKpi()->getKpiType() == $request->getParameter('typeindicateur'))
                        {
                        $nbrekpi++;
                        $notefinale = $notefinale + $value->getNote();
                        $idratings[] = $value->getId();
                        $rs .= '
                                <input type="hidden" value="' . $value->getId() . '" id="rating_id_"' . $value->getId() . '" name="rating_id[' . $value->getId() . ']" />
                                <tr>
                                   <td style="width:400px;">' . $value->getKpi()->getKpiIndicators() . '</td>
                                   <td style="width:200px;">' . $value->getKpi()->getDelai() . '</td>
                                   <td><input type="text" class="emp" style="width:200px;" id="poids_' . $value->getId() . '" name="poids[' . $value->getId() . ']" value="' . $value->getPoids() . '"></td>
                                   <td><input type="text" class="emp" style="width:200px;" id="valeur_cible_' . $value->getId() . '" name="valeur_cible[' . $value->getId() . ']" value="' . $value->getValeurCible() . '"></td>
                                   <td>' . $value->getCumule() . '</td>
                                   <td>' . round((double)(($value->getTauxAtteint() / $value->getValeurCible()) * 100), 2) . '%</td>
                                   <td><input type="text" class="emp" style="width:100px;" id="noter_' . $value->getId() . '" name="noter[' . $value->getId() . ']" value="' . $value->getNote() . '"></td>
                                   <td><textarea class="comment emp" type="text" id="comment_' . $value->getId() . '" name="comment[' . $value->getId() . ']" >' . $value->getComment() . '</textarea></td>
                                   <td><input type="button" class="btnValeur' . $popup . '" data-id="' . $value->getId() . '" name="btnValeur" value="Valeur/Mois" data-toggle="modal" data-target="#Valeurevaluations' . $popup . $value->getId() . '"></td>
                                </tr> 
                                
                             ';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois1");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '  <div class="modal valeurevaluation" id="Valeurevaluations' . $popup . $value->getId() . '">  
                                    <div class="modal-header">
                                         <a class="close" data-dismiss="modal">×</a>
                                         <h5>Valeurs Atteintes/Mois</h5>
                                    </div>
                                    <div class="modal-body">
                                        <fieldset>
                                             <ol>
                                                <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Janvier</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getRating() . '" id="rating_' . $value->getId() . '"  name="rating[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getRating() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment1_' . $value->getId() . '" name="comment1[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois2");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                 <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Fevrier</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois2() . '" id="mois2_' . $value->getId() . '"  name="mois2[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois2() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment2_' . $value->getId() . '" name="comment2[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois3");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Mars</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois3() . '" id="mois3_' . $value->getId() . '"  name="mois3[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois3() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment3_' . $value->getId() . '" name="comment3[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois4");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Avril</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois4() . '" id="mois4_' . $value->getId() . '"  name="mois4[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois4() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment4_' . $value->getId() . '" name="comment4[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois5");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Mai</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois5() . '" id="mois5_' . $value->getId() . '"  name="mois5[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois5() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment5_' . $value->getId() . '" name="comment5[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois6");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Juin</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois6() . '" id="mois6_' . $value->getId() . '"  name="mois6[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois6() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment6_' . $value->getId() . '" name="comment6[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois7");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Juillet</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois7() . '" id="mois7_' . $value->getId() . '"  name="mois7[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois7() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment7_' . $value->getId() . '" name="comment7[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois8");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Aout</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois8() . '" id="mois8_' . $value->getId() . '"  name="mois8[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois8() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment8_' . $value->getId() . '" name="comment8[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois9");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Septembre</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois9() . '" id="mois9_' . $value->getId() . '"  name="mois9[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois9() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment9_' . $value->getId() . '" name="comment9[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois10");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Octobre</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois10() . '" id="mois10_' . $value->getId() . '"  name="mois10[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois10() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment10_' . $value->getId() . '" name="comment10[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois11");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Novembre</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois11() . '" id="mois11_' . $value->getId() . '"  name="mois11[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois11() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment11_' . $value->getId() . '" name="comment11[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';
                        $commentaire = $form->getPerformanceReviewService()->getCommentaire($value->getKpi()->getId(), $value->getId(), "Mois12");
                        $comment = $commentaire['comment'];
                        $rsmodal .= '                  <li>                              
                                                    <div class="row"><label><b>Valeur atteinte en Decembre</b></label></div>
                                                    <div class="row"><input style="width:200px;" class="emp" type="text" value="' . $value->getMois12() . '" id="mois12_' . $value->getId() . '"  name="mois12[' . $value->getId() . ']" />
                                                    Taux:&nbsp' . round((double)($value->getMois12() / $value->getValeurCible()) * 100, 2) . '%</div>
                                                    <div class="row"><label>Commentaire</label></div>
                                                    <div class="row"><textarea class="comment emp" type="text" id="comment12_' . $value->getId() . '" name="comment12[' . $value->getId() . ']" >' . $comment . '</textarea></div>
                                                <li>';

                        $rsmodal .= '
                                             </ol>
                                        </fieldset>
                                    </div>
                                    <div class="modal-footer">
                                         <input type="button" class="btn" data-dismiss="modal" id="Evaluationbtn" value="Ok"/>
                                         <input type="button" class="btn reset" data-dismiss="modal" value="Cancel" />
                                    </div>
                                </div>
                        ';
                    }
                    }
                }

            }

        }
        //var_dump($rsmodal, $rs);die;
        //code chargement du tableau
       // var_dump($request->getParameter('typeindicateur'));die;
        $result = array('datars' => $rs, 'datarsmodal' => $rsmodal, 'notefinal' => round((double)$notefinale/$nbrekpi));
        //var_dump($result);die;
        echo json_encode($result);

        return sfView::NONE;
    }


}

