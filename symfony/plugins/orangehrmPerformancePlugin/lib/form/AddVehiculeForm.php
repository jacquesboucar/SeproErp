<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of DefineKpiForm
 *
 * @author nadeera
 */

class AddVehiculeForm extends BasePefromanceSearchForm {
    
    public $vehiculeService;

    /**
     *
     * @return \VehiculeService
     */
    public function getVehiculeService() {

            return new VehiculeService();
    }

    /**
     *
     */
    public function configure() {
        $type = array('En cours'=>'En cours','Valider'=>'Valider', 'Rejetter'=>'Rejetter');
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'marque' => new sfWidgetFormInputText(),
            'energie' => new sfWidgetFormInputText(),
            'matricule_vehicule' => new sfWidgetFormInputText(),
            'dotation_carburant' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
            'valider' => new sfWidgetFormSelect(array('choices' => $type), array('class' => 'formSelect')),
            'file' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => ''))
        ));
        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'marque' => new sfValidatorString(array('max_length' => 100)),
            'energie' => new sfValidatorString(array('max_length' => 100)),
            'matricule_vehicule' => new sfValidatorString(array('max_length' => 50)),
            'dotation_carburant' => new sfValidatorString(array('max_length' => 50)),
            'description' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 2000)),
            'valider' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($type))),
            'file' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false))

        ));

        $this->getWidgetSchema()->setNameFormat('addVehicule[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }


    /**
     * @param $kpiId
     */
    public function loadFormData($vehiculeId) {

        if ($vehiculeId > 0) {
            $vehicule = $this->getVehiculeService()->getVehiculeById(array('id' => $vehiculeId));

            $this->setDefault('id', $vehicule->getId());
            $this->setDefault('marque', $vehicule->getMarque());
            $this->setDefault('energie', $vehicule->getEnergie());
            $this->setDefault('matricule_vehicule', $vehicule->getMatriculeVehicule());
            $this->setDefault('dotation_carburant', $vehicule->getDotationCarburant());
            $this->setDefault('description', $vehicule->getDescription());
            $this->setDefault('valider', $vehicule->getValider());
            $this->setDefault('file', $vehicule->getFilename());
            //var_dump($file);die;

        }
    }
   

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'marque' => __('Marque'). $requiredMarker,
            'energie' => __('Energie'). $requiredMarker,
            'matricule_vehicule' => __('Matricule vehicule'). $requiredMarker,
            'dotation_carburant' => __('Dotation_carburant'). $requiredMarker,
            'description' => __('Description'),
            'valider' => __('Valider'),
            'file' => __('Televersement')
        );
        return $labels;
    }

    /**
     *
     */
    public function saveForm() {
        // Get logged user employee Id
        $user = sfContext::getInstance()->getUser();
        $values = $this->getValues();
        $file = $this->getValue('file');
        $vehicule = new Vehicule();
        if($values['id']>0){
            $vehicule = $this->getVehiculeService()->getVehiculeById($values['id']);
            $loggedInEmpNumber=$vehicule->getEmpNumber();
        }else{
            $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        }
        if(!empty($file)){
            $filetype= $file->getType();
            $filename= $file->getOriginalName();
            $filesize= $file->getSize();
            $fileTmpName= file_get_contents($file->getTempName());
        }else{
            $filetype=null;
            $filename=null;
            $filesize=null;
            $fileTmpName=null;
        }

        $vehicule->setMarque($values['marque']);
        $vehicule->setEnergie($values['energie']);
        $vehicule->setDotationCarburant($values['dotation_carburant']);
        $vehicule->setMatriculeVehicule($values['matricule_vehicule']);
        $vehicule->setValider($values['valider']);
        $vehicule->setDateApplied(date('Y-m-d H:i:s'));

        $vehicule->setFilecontent($fileTmpName);
        $vehicule->setFiletype($filetype);
        $vehicule->setFilesize($filesize);
        $vehicule->setFilename($filename);
        $vehicule->setDescription($values['description']);
        $vehicule->setEmpNumber($loggedInEmpNumber);
        $this->getVehiculeService()->saveVehicule($vehicule);
          
    }
}