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
 * Displaying ApplyLeave UI and saving data
 */
class applyTrainingAction extends baseAction {
   
   protected $employeeService;
   protected $leaveApplicationService;
   protected $trainingRequestService;

    

    /**
     *
     * @return EmployeeService
     */
    public function getEmployeeService() {
        if (!($this->employeeService instanceof EmployeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    /**
     *
     * @param EmployeeService $service 
     */
    public function setEmployeeService(EmployeeService $service) {
        $this->employeeService = $service;
    }

    /**
     *
     * @return LeaveApplicationService
     */
    public function getTrainingApplicationService() {
        if (!($this->trainingApplicationService instanceof TrainingApplicationService)) {
            $this->trainingApplicationService = new TrainingApplicationService();
        }
        return $this->trainingApplicationService;
    }

    /**
     *
     * @param LeaveApplicationService $service 
     */
    public function setTrainingApplicationService(TrainingApplicationService $service) {
        $this->trainingApplicationService = $service;
    }

    /**
     *
     * @return LeaveRequestService
     */
    public function getTrainingRequestService() {
        if (!($this->trainingRequestService instanceof TrainingRequestService)) {
            $this->trainingRequestService = new TrainingRequestService();
        }
        print_r($this->trainingRequestService); die();
        return $this->trainingRequestService;
    }

    

    /**
     *
     * @param TrainingRequestService $service 
     */
    public function setTrainingRequestService(TrainingRequestService $service) {
        $this->trainingRequestService = $service;
    }
    
    public function execute($request) {
        
        // $this->getLeaveRequestService()->markApprovedLeaveAsTaken();

        // $this->leaveTypes = $this->getElegibleLeaveTypes();
        
        // if (count($this->leaveTypes) <= 1) {
        //     $this->getUser()->setFlash('warning.nofade', __('No Leave Types with Leave Balance'));
        // }
        
        $this->applyTrainingForm = new ApplyTrainingForm();
        //$this->overlapLeaves = 0;
       // $this->workshiftLengthExceeded = true;

        //this section is to save leave request
        if ($request->isMethod('post')) {
            $this->applyTrainingForm->bind($request->getParameter($this->applyTrainingForm->getName()));
            if ($this->applyTrainingForm->isValid()) {
                try {
                   // print_r($this->applyTrainingForm->getValues()); die();
                    $trainingParameters = $this->getTrainingParameterObject($this->applyTrainingForm->getValues());
                    
                    $success = $this->getTrainingApplicationService()->applyTraining($trainingParameters);
                    if ($success) {
                        $this->getUser()->setFlash('success', __('Successfully Submitted'));
                    } else {
                        //$this->overlapLeave = $this->getLeaveApplicationService()->getOverlapLeave();
                        $this->getUser()->setFlash('warning', __('Failed to Submit'));
                    }
                } catch (LeaveAllocationServiceException $e) {
                    $this->getUser()->setFlash('warning', __($e->getMessage()));
                    $this->overlapLeave = $this->getLeaveApplicationService()->getOverlapLeave();
                    $this->workshiftLengthExceeded = true;
                }
            }
        }
    }
    
    protected function getTrainingParameterObject(array $formValues) {

        // $time = $formValues['time'];
        // $formValues['txtFromTime'] = $time['from'];
        // $formValues['txtToTime'] = $time['to'];
        
        return new TrainingParameterObject($formValues);
    } 

    // /**
    //  * Retrieve Eligible Leave Type
    //  */
    // protected function getElegibleLeaveTypes() {
    //     $leaveTypeChoices = array();
    //     $empId = $_SESSION['empNumber']; // TODO: Use a session manager
    //     $employeeService = $this->getEmployeeService();
    //     $employee = $employeeService->getEmployee($empId);

    //     $leaveRequestService = $this->getLeaveRequestService();
    //     $leaveTypeList = $leaveRequestService->getEmployeeAllowedToApplyLeaveTypes($employee);

    //     $leaveTypeChoices[''] = '--' . __('Select') . '--';
    //     foreach ($leaveTypeList as $leaveType) {
    //         $leaveTypeChoices[$leaveType->getId()] = $leaveType->getName();
    //     }
        
    //     return $leaveTypeChoices;
    // }

    // /**
    //  * Creating user forms
    //  */
    // protected function getApplyLeaveForm($leaveTypes) {
    //     $form = new ApplyLeaveForm(array(), array('leaveTypes' => $leaveTypes), true);
    //     return $form;
    // }

}