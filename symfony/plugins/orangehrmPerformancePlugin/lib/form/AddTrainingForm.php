<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of DefineKpiForm
 *
 * @author nadeera
 */

class AddTrainingForm extends BasePefromanceSearchForm {
    
    public $trainingService;

    /**
     *
     * @return \TrainingService 
     */
    public function getTrainingService() {

        //if ($this->$trainingService == null) {
            return new TrainingService();
        // } else {
        //     return $this->$trainingService;
        // }
    }

    public function configure() {

        $this->setWidgets(array(
            //'txtEmpID' => new sfWidgetFormInputHidden(),
            'cout' => new sfWidgetFormInputText(),
            'titre' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
            'file' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => ''))

        ));
        $this->setValidators(array(
           // 'txtEmpID' => new sfValidatorString(array('required' => true), array('required' => __(ValidationMessages::REQUIRED))),
            'cout' => new sfValidatorString(array('max_length' => 255)),
            'titre' => new sfValidatorString(array('max_length' => 255)),             
            'description' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 2000)),
            'file' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false))
        ));
        $this->getWidgetSchema()->setNameFormat('addTraining[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }



   

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'titre' => __('Titre'),
            'cout' => __('Cout'),           
            'description' => __('Description'),
            'file' => __('Televersement')
        );
        return $labels;
    }

    public function saveForm() {
        // Get logged user employee Id
        $user = sfContext::getInstance()->getUser();
        $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        $values = $this->getValues();
        $file = $this->getValue('file');
        if(!$file){
            $filetype=$file->getType();
            $filename=$file->getOriginalName();
            $filesize=$file->getSize();
            $fileTmpName=file_get_contents($file->getTempName());
        }else{
            $filetype=null;
            $filename=null;
            $filesize=null;
            $fileTmpName=null;
        }

        $training = new Training();
        $training->setTitle($values['titre']);
        $training->setCoutFormation($values['cout']);
        $training->setDescription($values['description']);
        $training->setDateApplied(date('Y-m-d H:i:s'));
        $training->setFilecontent($fileTmpName);
        $training->setFiletype($filetype);
        $training->setFilesize($filesize);
        $training->setFilename($filename);
        $training->setEmpNumber($loggedInEmpNumber);
        $this->getTrainingService()->saveTraining($training);
          
    }

    /**
     *
     * @param integer $kpiId 
     */
    public function loadFormData($kpiId) {

        if ($kpiId > 0) {
            $kpi = $this->getKpiService()->searchKpi(array('id' => $kpiId));
            $this->setDefault('id', $kpi->getId());
            $this->setDefault('jobTitleCode', $kpi->getJobTitleCode());
            $this->setDefault('keyPerformanceIndicators', $kpi->getKpiIndicators());
            $this->setDefault('minRating', 1);
            $this->setDefault('maxRating', $kpi->getMaxRating());
            $this->setDefault('delai', $kpi->getDelai());
            $this->setDefault('objectif',$kpi->getObjectif());
            $this->setDefault('mode_calcul',$kpi->getModeCalcul());
            $this->setDefault('makeDefault', $kpi->getDefaultKpi());
            
        } else {
            
            $parameters ['isDefault'] = 1;
            $kpi = $this->getKpiService()->searchKpi($parameters);
            
            if(sizeof($kpi)>0){
                $kpi = $kpi->getFirst();
                $this->setDefault('minRating', 1);
                $this->setDefault('maxRating', $kpi->getMaxRating());
            }           
        }
    }
    /**
     *
     * @return type 
     */
    public function searchTraining($page) {
        
        $serachParams ['title'] =  $this->getValue('titre');
        $serachParams ['page'] =  $page;
        $serachParams ['limit'] =  sfConfig::get('app_items_per_page');        

        return $this->getTrainingService()->searchTraining($serachParams);
    }
    
    public function getTrainingCount(){
        $serachParams ['title'] =  $this->getValue('titre');
        $serachParams['limit'] = null;
        
        return $this->getTrainingService()->getTrainingCount($serachParams);
    }
}