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

        $this->setWidgets(array(
            'marque' => new sfWidgetFormInputText(),
            'energie' => new sfWidgetFormInputText(),
            'matricule_vehicule' => new sfWidgetFormInputText(),
            'dotation_carburant' => new sfWidgetFormInputText(),
            'valider' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox', 'value' => true)),
            'file' => new sfWidgetFormInputFileEditable(array('edit_mode'=>false,'with_delete' => false,'file_src' => ''))
        ));
        $this->setValidators(array(
            'marque' => new sfValidatorString(array('max_length' => 100)),
            'energie' => new sfValidatorString(array('max_length' => 100)),
            'matricule_vehicule' => new sfValidatorString(array('max_length' => 50)),
            'dotation_carburant' => new sfValidatorString(array('max_length' => 50)),
            'valider' => new sfValidatorPass(),
            'file' =>  new sfValidatorFile(array('max_size' => 1024000,'required' => false))

        ));
        $this->setDefault('valider', false);
        $this->getWidgetSchema()->setNameFormat('addVehicule[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }


    /**
     * @param $kpiId
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
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'marque' => __('Marque'). $requiredMarker,
            'energie' => __('Energie'). $requiredMarker,
            'matricule_vehicule' => __('Matricule vehicule'). $requiredMarker,
            'dotation_carburant' => __('Dotation_carburant'). $requiredMarker,
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
        $vehicule = new Vehicule();
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
        $vehicule->setEmpNumber($loggedInEmpNumber);
        $this->getVehiculeService()->saveVehicule($vehicule);
          
    }
}