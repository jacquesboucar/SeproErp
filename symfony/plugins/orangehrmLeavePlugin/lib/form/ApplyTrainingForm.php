<?php

/*
 *
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
 *
 */

/**
 * Form class for apply leave
 */
class ApplyTrainingForm extends sfForm {
    
    public function getConfigService() {
        
        if (!$this->configService instanceof ConfigService) {
            $this->configService = new ConfigService();
        }
        
        return $this->configService;        
    }

    public function setConfigService($configService) {
        $this->configService = $configService;
    }

    //  public function getLeavePeriodService() {
        
    //     if (is_null($this->leavePeriodService)) {
    //         $this->leavePeriodService = new LeavePeriodService();
    //     }
    //     return $this->leavePeriodService;
    // }

    // public function setLeavePeriodService($leavePeriodService) {
    //     $this->leavePeriodService = $leavePeriodService;
    // }
    
    /**
     * Get work schedule service
     * @return WorkScheduleService
     */
    public function getWorkScheduleService() {
        if (!($this->workScheduleService instanceof WorkScheduleService)) {
            $this->workScheduleService = new WorkScheduleService();
        }
        return $this->workScheduleService;
    }

    /**
     *
     * @param WorkScheduleService $service 
     */
    public function setWorkScheduleService(WorkScheduleService $service) {
        $this->workScheduleService = $service;
    }    
    
    protected function getWorkSchedule() {
        if (is_null($this->workSchedule)) {
            $this->workSchedule = $this->getWorkScheduleService()->getWorkSchedule($this->getEmployeeNumber());
        }
        return $this->workSchedule;
    }
    
    
    /**
     * Configure ApplyTrainingForm
     *
     */
    public function configure() {

        //$this->leaveTypeList = $this->getOption('leaveTypes');

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());

        // $workSchedule = $this->getWorkSchedule();
        // $workScheduleStartEndTime = $workSchedule->getWorkShiftStartEndTime();
        
        $this->setDefault('txtEmpID', $this->getEmployeeNumber());
       // $this->setDefault('txtEmpWorkShift', $workSchedule->getWorkShiftLength());
       // $this->setDefault('leaveBalance', '--');
        
        // $specifyTimeDefault = array('time' => 
        //     array('from' => $workScheduleStartEndTime['start_time'], 
        //           'to' => $workScheduleStartEndTime['end_time']));
        
        // $this->setDefault('duration', $specifyTimeDefault);
        // $this->setDefault('firstDuration', $specifyTimeDefault);
        // $this->setDefault('secondDuration', $specifyTimeDefault);

       //$this->getValidatorSchema()->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'postValidation'))));
        $this->getWidgetSchema()->setNameFormat('applytraining[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());

    }

    /**
     * get Leave Request
     * @return TrainingRequest
     */
    public function getTrainingRequest() {

        $posts = $this->getValues();
        $trainingRequest = new TrainingRequest();
       // $leaveRequest->setLeaveTypeId($posts['txtLeaveType']);
        $trainingRequest->setDateApplied($posts['txtFromDate']);
       // $leaveRequest->setLeavePeriodId($this->getLeavePeriod($posts['txtFromDate']));
        $trainingRequest->setEmpNumber($posts['txtEmpID']);
        $trainingRequest->setDescription($posts['description']);
        $trainingRequest->setCoutFormation($posts['cout']);
       // $trainingRequest->setDateApplied($leaveAssignmentData->getFromDate());
        $trainingRequest->setTitle($posts['title']);
        return $trainingRequest;
    }

    /**
     *
     * @return array
     */
    protected function getFormWidgets() {
        // $partialDayChoices = array(
        //     '' => __('None'), 
        //     self::ALL_DAYS => __('All Days'), 
        //     self::START_DAY_ONLY => __('Start Day Only'), 
        //     self::END_DAY_ONLY => __('End Day Only'),
        //     self::START_AND_END_DAY => __('Start and End Day'));
        
        $widgets = array(
            'txtEmpID' => new sfWidgetFormInputHidden(),
            'cout' => new sfWidgetFormInputText(),
            'titre' => new sfWidgetFormInputText(),
            //'txtEmpWorkShift' => new sfWidgetFormInputHidden(),
           // 'txtLeaveType' => new sfWidgetFormChoice(array('choices' => $this->getLeaveTypeList())),
           // 'leaveBalance' => new ohrmWidgetDiv(),            
            // 'txtFromDate' => new ohrmWidgetDatePicker(array(), array('id' => 'applyleave_txtFromDate')),
            // 'txtToDate' => new ohrmWidgetDatePicker(array(), array('id' => 'applyleave_txtToDate')),
            // 'duration' => new ohrmWidgetFormLeaveDuration(),
           // 'partialDays' => new sfWidgetFormChoice(array('choices' => $partialDayChoices)),
           // 'firstDuration' => new ohrmWidgetFormLeaveDuration(array('enable_full_day' => false)),
           // 'secondDuration' => new ohrmWidgetFormLeaveDuration(array('enable_full_day' => false)),
            'description' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30'))
        );

        return $widgets;
    }

     /**
     *
     * @return array
     */
    protected function getFormValidators() {
        //$inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array(
            'txtEmpID' => new sfValidatorString(array('required' => true), array('required' => __(ValidationMessages::REQUIRED))),
            'cout' => new sfValidatorString(array('max_length' => 255)),
            'titre' => new sfValidatorString(array('max_length' => 255)),
            // 'txtEmpWorkShift' => new sfValidatorString(array('required' => false)),
            // 'txtLeaveType' => new sfValidatorChoice(array('choices' => array_keys($this->getLeaveTypeList()))),
            // 'txtFromDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
            //         array('invalid' => 'Date format should be ' . $inputDatePattern)),
            // 'txtToDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
            //         array('invalid' => 'Date format should be ' . $inputDatePattern)),
            // 'duration' => new sfValidatorPass(),
            // 'partialDays' => new sfValidatorPass(),
            // 'firstDuration' => new sfValidatorPass(),
            // 'secondDuration' => new sfValidatorPass(),               
            'description' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 1000))
        );

        return $validators;
    }
    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $requiredMarker = ' <em>*</em>';
        
        $labels = array(
            'titre' => __('Titre') . $requiredMarker,
            'cout' => __('Cout'),           
            'description' => __('Description'),
        );
        
        return $labels;
    }
    
    // protected function getDuration($fromTime, $toTime) {
    //     list($startHour, $startMin) = explode(':', $fromTime);
    //     list($endHour, $endMin) = explode(':', $toTime);

    //     $durationMinutes = (intVal($endHour) - intVal($startHour)) * 60 + (intVal($endMin) - intVal($startMin));
    //     $hours = $durationMinutes / 60;

    //     return number_format($hours, 2);
    // } 
    /**
     * Get Employee number
     * @return int
     */
    private function getEmployeeNumber() {
        return $_SESSION['empID'];
    }     

}
