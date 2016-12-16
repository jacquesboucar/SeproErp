<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures 
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2010 OrangeHRM Inc., http://www.orangehrm.com
 *
 * Please refer the file license/LICENSE.TXT for the license which includes terms and conditions on using this software.
 *
 * */

/**
 * Description of ReviewEvaluationForm
 *
 * @author nadeera
 */
class ReviewEvaluationForm extends BasePefromanceSearchForm {

    public $reviewEvaluation;

    /**
     *
     * @return integer 
     */
    public function getReviewId() {

        if ($this->getValue('id')) {
            return $this->getValue('id');
        }

        if ($this->getOption('id') > 0) {
            return $this->getOption('id');
        }
    }

    /**
     *
     * @return type 
     */
    public function getReview() {

        $parameters ['id'] = $this->getReviewId();
        $review = $this->getPerformanceReviewService()->searchReview($parameters, 'piority');
        return $review;
    }

    public function getReviewerId() {
        return $this->getUser()->getEmployeeNumber();
    }

    /**
     *
     * @return type 
     */
    public function getReviewRatings() {

        /* TODO: Control Circle */
        $parameters ['id'] = $this->getReviewId();
        $parameters ['reviewerId'] = $this->getReviewerId();
        $review = $this->getPerformanceReviewService()->searchReview($parameters);

        $ratings = array();

        foreach ($review->getReviewers()->getFirst()->getRating() as $rating) {
            $ratings [$rating->getKpi()->getKpiIndicators() . "_" . $rating->getKpi()->getId() . "_" . $rating->getId()] = $rating;
        }
        ksort($ratings);
        return $ratings;
    }

    public function getSortedRatings($ratings) {

        $ratingsArray = array();

        foreach ($ratings as $rating) {
            $ratingsArray [$rating->getKpi()->getKpiIndicators() . "_" . $rating->getKpi()->getId() . "_" . $rating->getId()] = $rating;
        }

        ksort($ratingsArray);
        return $ratingsArray;
    }

    public function getReviewers() {
        $parameters ['id'] = $this->getReviewId();
        $review = $this->getPerformanceReviewService()->searchReview($parameters);
        return $review->getReviewers();
    }

    public function getReviewer() {
        $parameters ['id'] = $this->getReviewId();
        $parameters ['reviewerId'] = $this->getReviewerId();

        $reviewers = $this->getPerformanceReviewService()->searchReview($parameters)->getReviewers();
        if (sizeof($reviewers) > 0) {
            return $this->getPerformanceReviewService()->searchReview($parameters)->getReviewers()->getFirst();
        } else {
            return new Reviewer();
        }
    }

    /**
     *
     * @param type $reviewEvaluation 
     */
    public function setReviewEvaluation($reviewEvaluation) {
        $this->reviewEvaluation = $reviewEvaluation;
    }

    public function configure() {

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());

        $this->getWidgetSchema()->setNameFormat('reviewEvaluation[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }

    /**
     *
     * @return array
     */
    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();
        $styleSheets[plugin_web_path('orangehrmPerformancePlugin', 'css/reviewEvaluationSuccess.css')] = 'all';
        $styleSheets[plugin_web_path('orangehrmPerformancePlugin', 'css/reviewEvaluateByAdminSuccess.css')] = 'all';
        return $styleSheets;
    }

    public function getJavaScripts() {
        $javaScripts = parent::getJavaScripts();
        return $javaScripts;
    }

    /**
     *
     * @return array
     */
    protected function getFormWidgets() {
        $widgets = array(
            'id' => new sfWidgetFormInputHidden(),
            'action' => new sfWidgetFormInputHidden()
        );
        return $widgets;
    }

    /**
     *
     * @return array
     */
    protected function getFormValidators() {
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array(
            'id' => new sfValidatorString(array('required' => false)),
            'action' => new sfValidatorString(array('required' => false))
        );
        return $validators;
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $requiredMarker = ' <span class="required">*</span>';
        $labels = array(
            'name' => __('Group Name') . $requiredMarker,
            'weight' => __('Weightage (1 - 100) ') . $requiredMarker,
        );
        return $labels;
    }

    /**
     *
     * @return boolean 
     */
    public function saveForm($request) {
        if ($this->isEditable()) {
            $postParameters = $request->getPostParameters();
            //var_dump($postParameters); die();
            foreach ($postParameters['rating_id'] as $key => $ratingId) {

                if ($this->isValidRatingId($ratingId))
                {

                    $rating = $this->getPerformanceReviewService()->getReviewRating($ratingId);
                    //var_dump($this->filterPostValues($postParameters['poids'][$key]));die;
                    $rating->setRating($this->filterPostValues(round(trim($postParameters['rating'][$key], 2))));
                    $rating->setPoids(round($this->filterPostValues(trim($postParameters['poids'][$key]))));
                    if($this->filterPostValues(round(trim($postParameters['valeur_cible'][$key])))!=null)
                    $rating->setValeurCible($this->filterPostValues(round(trim($postParameters['valeur_cible'][$key]))));
                    $rating->setComment($this->filterPostValues(trim($postParameters['comment'][$key])));
                    $rating->setMois2($this->filterPostValues($postParameters['mois2'][$key]));
                    $rating->setMois3($this->filterPostValues(trim($postParameters['mois3'][$key])));
                    $rating->setMois4($this->filterPostValues(trim($postParameters['mois4'][$key])));
                    $rating->setMois5($this->filterPostValues(trim($postParameters['mois5'][$key])));
                    $rating->setMois6($this->filterPostValues(trim($postParameters['mois6'][$key])));
                    $rating->setMois7($this->filterPostValues(trim($postParameters['mois7'][$key])));
                    $rating->setMois8($this->filterPostValues(trim($postParameters['mois8'][$key])));
                    $rating->setMois9($this->filterPostValues(trim($postParameters['mois9'][$key])));
                    $rating->setMois10($this->filterPostValues(trim($postParameters['mois10'][$key])));
                    $rating->setMois11($this->filterPostValues(trim($postParameters['mois11'][$key])));
                    $rating->setMois12($this->filterPostValues(trim($postParameters['mois12'][$key])));
                    //var_dump($rating);die;
                    for($i=1; $i<=12; $i++){
                       // $commentaire = new Commentaire();
                        $commentaire = $this->getPerformanceReviewService()->getCommentaire($rating->getKpi()->getId(),$ratingId,"Mois$i");

                        if ($commentaire['id'] > 0) {
                            $commentaire->setComment($this->filterPostValues($postParameters['comment'.$i][$key]));

                        } else {
                            $commentaire = new Commentaire();
                            $commentaire->setComment($this->filterPostValues($postParameters['comment'.$i][$key]));
                            $commentaire->setMois("Mois$i");
                            $commentaire->setKpiId($rating->getKpi()->getId());
                            $commentaire->setRatingId($ratingId);

                        }

                        $this->getPerformanceReviewService()->saveCommentaire($commentaire);

                    }
                    // Set Final Rating

                    $mois1 = $this->filterPostValues(trim($postParameters['rating'][$key]));
                    $mois2 = $this->filterPostValues($postParameters['mois2'][$key]);
                    $mois3 = $this->filterPostValues(trim($postParameters['mois3'][$key]));
                    $mois4 = $this->filterPostValues(trim($postParameters['mois4'][$key]));
                    $mois5 = $this->filterPostValues(trim($postParameters['mois5'][$key]));
                    $mois6 = $this->filterPostValues(trim($postParameters['mois6'][$key]));
                    $mois7 = $this->filterPostValues(trim($postParameters['mois7'][$key]));
                    $mois8 = $this->filterPostValues(trim($postParameters['mois8'][$key]));
                    $mois9 = $this->filterPostValues(trim($postParameters['mois9'][$key]));
                    $mois10 = $this->filterPostValues(trim($postParameters['mois10'][$key]));
                    $mois11 = $this->filterPostValues(trim($postParameters['mois11'][$key]));
                    $mois12 = $this->filterPostValues(trim($postParameters['mois12'][$key]));
                    $final_rate = $mois1+$mois2+$mois3+$mois4+$mois5+$mois6+$mois7+$mois8+$mois9+$mois10+$mois11+$mois12;
                    $rating->setCumule($final_rate);
                    $rating->setNote($this->filterPostValues($postParameters['noter'][$key]));
                    $nbre_mois=0; $valeur=0;
                    if($mois1!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois1;
                    }
                    if($mois2!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois2;
                    }
                    if($mois3!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois3;
                    }
                    if($mois4!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois4;
                    }
                    if($mois5!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois5;
                    }
                    if($mois6!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois6;
                    }
                    if($mois7!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois7;
                    }
                    if($mois8!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois8;
                    }
                    if($mois9!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois9;
                    }
                    if($mois10!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois10;
                    }
                    if($mois11!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois11;
                    }
                    if($mois12!=null){
                        $nbre_mois++;
                        $valeur=$valeur+$mois12;
                    }
                    $taux=round((double)($valeur/$nbre_mois));
                    $rating->setTauxAtteint($taux);
                    $rating->save();
                }
            }

            if ($this->getValue('action') == 'complete') {
                $reviewer = $rating->getReviewer();
                if ($reviewer->getGroup()->getId() == 2) {
                    $comment = $postParameters['general_comment'][$reviewer->getGroup()->getId()];
                    $status = ReviewerReviewStatusFactory::getInstance()->getStatus('completed');
                    $reviewer->setStatus($status->getStatusId());
                    $reviewer->setCompletedDate(date("Y-m-d H:i:s"));
                    $reviewer->setComment($comment);
                    $reviewer->save();
                } else {
                    $reviewers = $this->getReviewers();
                    foreach ($reviewers as $reviewer) {
                        $comment = $postParameters['general_comment'][$reviewer->getGroup()->getId()];
                        $status = ReviewerReviewStatusFactory::getInstance()->getStatus('completed');
                        $reviewer->setStatus($status->getStatusId());
                        $reviewer->setCompletedDate(date("Y-m-d H:i:s"));
                        $reviewer->setComment($comment);
                        $reviewer->save();
                    }
                }
            }

            if ($this->getValue('action') == 'save') {
                $reviewer = $rating->getReviewer();
                $comment = $postParameters['general_comment'][$reviewer->getGroup()->getId()];
                $status = ReviewerReviewStatusFactory::getInstance()->getStatus('inProgress');
                $reviewer->setStatus($status->getStatusId());
                $reviewer->setComment($comment);
                $reviewer->save();
            }

            $review = $this->getPerformanceReviewService()->searchReview($this->getReviewId());
            $status = $this->getReviewStatusFactory()->getStatus($reviewer->getReview()->getStatusId());
            $review = $reviewer->getReview();
            $review->setStatusId($status->getNextStatus());
            $review->save();
            return $review;
        }
    }

    public function isValidRatingId($ratingId) {

        $parameters ['id'] = $ratingId;
        $parameters ['reviewId'] = $this->getReviewId();

        if (sizeof($this->getPerformanceReviewService()->searchReviewRating($parameters) > 0)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return boolean
     */
    public function isFinalRatingVisible() {
        /* TODO: Control Circle */
        if ($this->getReview()->getEmployeeNumber() == $this->getReviewerId()) {
            $parameters ['id'] = $this->getReviewId();
            $review = $this->getPerformanceReviewService()->searchReview($parameters);

            if (ReviewStatusFactory::getInstance()->getStatus($review->getStatusId())->isFinalRatingVisible()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *
     * @param integer $id 
     */
    public function loadFormData($id) {
        $this->setDefault('id', $this->getReviewId());
        $this->setDefault('action', 'save');
    }

    public function isEditable() {
        /* TODO: Control Circle */
        $parameters ['id'] = $this->getReviewId();
        $parameters ['reviewerId'] = $this->getReviewerId();

        $review = $this->getPerformanceReviewService()->searchReview($parameters);

        if (ReviewerReviewStatusFactory::getInstance()->getStatus($review->getReviewers()->getFirst()->getStatus())->isEvaluationFormEditable() && $this->getReviewStatusFactory()->getStatus($review->getStatusId())->isEvaluationsEditable()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return string 
     */
    public function getGoBackUrl() {
        if ($this->getReview()->getEmployeeNumber() == $this->getReviewerId()) {
            return "performance/myPerformanceReview";
        } else {
            return "performance/searchEvaluatePerformancReview";
        }
    }
    /**
     *
     * @return array
     */
    public function getKpiGroupListAsArray() {
        foreach ($this->getKpiGroupService()->getKpiGroupList() as $group) {
            $kpiGroup[$group->getId()] = $group->getKpiGroupName();
        }
        return $kpiGroup;
    }

}
