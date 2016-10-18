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

        $type = array('En cours'=>'En cours','Valider'=>'Valider', 'Rejetter'=>'Rejetter');
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'employee' => new ohrmWidgetEmployeeNameAutoFill(),
            'cout' => new sfWidgetFormInputText(),
            'titre' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
            'valider' => new sfWidgetFormSelect(array('choices' => $type), array('class' => 'formSelect')),
            'fileformation' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => '')),
            'file' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => ''))

        ));
        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'employee' => new ohrmValidatorEmployeeNameAutoFill(),
            'cout' => new sfValidatorString(array('max_length' => 255)),
            'titre' => new sfValidatorString(array('max_length' => 255)),             
            'description' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 2000)),
            'valider' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($type))),
            'fileformation' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false)),
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
            'employee' => __('Employee'),
            'cout' => __('Coût'),
            'description' => __('Description'),
            'valider' => __('Valider'),
            'fileformation' => __('Fiche Formation'),
            'file' => __('Téléversement')
        );
        return $labels;
    }

    public function saveForm() {
        // Get logged user employee Id
        $user = sfContext::getInstance()->getUser();
        $values = $this->getValues();
        $file = $this->getValue('file');
        $fileform = $this->getValue('fileformation');
        $training = new Training();
        if ($values['id']>0){
            $training = $this->getTrainingService()->getTrainingById($values['id']);
            $loggedInEmpNumber=$training->getEmpNumber();
        }elseif ($values['employee']['empId']==''){
            $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        }else{
            $loggedInEmpNumber = $values['employee']['empId'];
        }
        if(!empty($fileform)){
            $filetypeform=$fileform->getType();
            $filenameform=$fileform->getOriginalName();
            $filesizeform=$fileform->getSize();
            $fileTmpNameform=file_get_contents($fileform->getTempName());
        }else{
            $filetypeform=null;
            $filenameform=null;
            $filesizeform=null;
            $fileTmpNameform=null;
        }
        if(!empty($file)){
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
        $training->setTitle($values['titre']);
        $training->setCoutFormation($values['cout']);
        $training->setDescription($values['description']);
        $training->setDateApplied(date('Y-m-d H:i:s'));
        $training->setValider($values['valider']);

        $training->setFormFilecontent($fileTmpNameform);
        $training->setFormFiletype($filetypeform);
        $training->setFormFilesize($filesizeform);
        $training->setFormFilename($filenameform);

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
    public function loadFormData($trainingId) {

        if ($trainingId > 0) {
            $training = $this->getTrainingService()->getTrainingById(array('id' => $trainingId));
            //var_dump($training->getId());die;
            $this->setDefault('id', $training->getId());
            $this->setDefault('cout', $training->getCoutFormation());
            $this->setDefault('employee', array('empName' => $training->getEmployee()->getFullName(), 'empId' => $training->getEmployee()->getEmpNumber()));
            $this->setDefault('description', $training->getDescription());
            $this->setDefault('titre', $training->getTitle());
            $this->setDefault('date_applied', set_datepicker_date_format($training->getDateApplied()));
            $this->setDefault('valider', $training->getValider());
            $this->setDefault('file', $training->getFilename());
            $this->setDefault('fileformation', $training->getFormFilename());

            
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