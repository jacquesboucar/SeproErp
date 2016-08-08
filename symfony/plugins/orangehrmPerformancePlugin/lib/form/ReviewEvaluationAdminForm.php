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
 * Description of ReviewEvaluationAdminForm
 *
 * @author nadeera
 */
class ReviewEvaluationAdminForm extends ReviewEvaluationForm {

 
    /**
     *
     * @return array
     */
    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();
        $styleSheets[plugin_web_path('orangehrmPerformancePlugin','css/reviewEvaluationSuccess.css')] = 'all';
        $styleSheets[plugin_web_path('orangehrmPerformancePlugin','css/reviewEvaluateByAdminSuccess.css')] = 'all';
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
            'action' => new sfWidgetFormInputHidden(),
            'evaluationsAction' => new sfWidgetFormInputHidden(),
            'Mois1' => new sfWidgetFormInput(),
            'Mois1' => new sfWidgetFormInput(),
            'Mois2' => new sfWidgetFormInput(),
            'Mois3' => new sfWidgetFormInput(),
            'Mois4' => new sfWidgetFormInput(),
            'Mois5' => new sfWidgetFormInput(),
            'Mois6' => new sfWidgetFormInput(),
            'Mois7' => new sfWidgetFormInput(),
            'Mois8' => new sfWidgetFormInput(),
            'Mois9' => new sfWidgetFormInput(),
            'Mois10' => new sfWidgetFormInput(),
            'Mois11' => new sfWidgetFormInput(),
            'hrAdminComments' => new sfWidgetFormTextarea(array(), array('rows' => '5')),
            'finalRating' => new sfWidgetFormInput(array(), array('class' => 'formInputText')),
            'completedDate' => new ohrmWidgetDatePicker(array(), array('id' => 'saveReview360Form_workPeriodStartDate'), array('class' => 'formDateInput'))
        );
        return $widgets;
    }

    /**
     *
     * @return array
     */
    protected function getFormValidators() {
       
        $validators = array(
            'id' => new sfValidatorString(array('required' => false)),
            'action' => new sfValidatorString(array('required' => false)),
            'evaluationsAction' => new sfValidatorString(array('required' => false)),
            'Mois1' => new sfValidatorString(array('required' => false)),
            'Mois2' => new sfValidatorString(array('required' => false)),
            'Mois3' => new sfValidatorString(array('required' => false)),
            'Mois4' => new sfValidatorString(array('required' => false)),
            'Mois5' => new sfValidatorString(array('required' => false)),
            'Mois6' => new sfValidatorString(array('required' => false)),
            'Mois7' => new sfValidatorString(array('required' => false)),
            'Mois8' => new sfValidatorString(array('required' => false)),
            'Mois9' => new sfValidatorString(array('required' => false)),
            'Mois10' => new sfValidatorString(array('required' => false)),
            'Mois11' => new sfValidatorString(array('required' => false)),
            'Mois12' => new sfValidatorString(array('required' => false)),
            'hrAdminComments' => new sfValidatorString(array('required' => false)),
            'finalRating' => new sfValidatorString(array('required' => false)),
            'completedDate' => new ohrmDateValidator(array('required' => false))
        );
        return $validators;
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'Mois1' => __('Mois 1'),
            'Mois2' => __('Mois 2'),
            'Mois3' => __('Mois 3'),
            'Mois4' => __('Mois 4'),
            'Mois5' => __('Mois 5'),
            'Mois6' => __('Mois 6'),
            'Mois7' => __('Mois 7'),
            'Mois8' => __('Mois 8'),
            'Mois9' => __('Mois 9') ,
            'Mois10' => __('Mois 10'),
            'Mois11' => __('Mois 11'),
            'Mois12' => __('Mois 12'),
            'hrAdminComments' => __('Final Comment') . $requiredMarker,
            'finalRating' => __('Final Rating') . $requiredMarker,
            'completedDate' => __('Completed Date') . $requiredMarker
            
        );
        return $labels;
    }

    /**
     *
     * @return boolean 
     */
    public function saveForm($request) {    
        
       $review = parent::saveForm($request);
       if(strlen($this->getValue('hrAdminComments'))>0){
            $review->setFinalComment($this->getValue('hrAdminComments'));
       }
       
       if(strlen($this->getValue('finalRating'))>0){
            $review->setFinalRate(round($this->getValue('finalRating'),2));
       }

       if(strlen($this->getValue('completedDate'))>0){
            $review->setCompletedDate( date( "Y-m-d", strtotime($this->getValue('completedDate'))) );
       }
     
       
       if($this->getValue('evaluationsAction') == "complete"){         
           $review->setStatusId($this->getReviewStatusFactory()->getStatus('approved')->getStatusId());
       }       
       $review->save();      
    }

   

    /**
     *
     * @param integer $id 
     */
    public function loadFormData($id) {
        
        $this->setDefault('id', $this->getReviewId());
        $this->setDefault('hrAdminComments', $this->getReview()->getFinalComment());
        $this->setDefault('finalRating', $this->getReview()->getFinalRate());
        $this->setDefault('completedDate', set_datepicker_date_format( $this->getReview()->getCompletedDate()) );
       
    }

    /**
     *
     * @return boolean 
     */
    public function isEvaluationsEditable() {
        /* TODO: Control Circle */
        $parameters ['id'] = $this->getReviewId();      

        $review = $this->getPerformanceReviewService()->searchReview($parameters);
        if ( ReviewStatusFactory::getInstance()->getStatus($review->getStatusId())->isEvaluationsEditable()) {
            return true;
        } else {
            return false;
        }
    }  

    /**
     *
     * @return type 
     */
    public function isEditable() {
       return $this->isEvaluationsCompleateEnabled();
    }
    
    /**
     *
     * @return boolean 
     */
    public function isEvaluationsCompleateEnabled() {
        /* TODO: Control Circle */
        $parameters ['id'] = $this->getReviewId();      

        $review = $this->getPerformanceReviewService()->searchReview($parameters);
        if ( ReviewStatusFactory::getInstance()->getStatus($review->getStatusId())->isEvaluationsCompleateEnabled()) {
            return true;
        } else {
            return false;
        }
    }

}