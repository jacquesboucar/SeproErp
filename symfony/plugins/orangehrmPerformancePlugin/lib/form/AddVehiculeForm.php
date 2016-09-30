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
            'date_applied' => new sfWidgetFormInputText()
        ));
        $this->setValidators(array(
            'marque' => new sfValidatorString(array('max_length' => 100)),
            'energie' => new sfValidatorString(array('max_length' => 100)),
            'matricule_vehicule' => new sfValidatorString(array('max_length' => 50)),
            'dotation_carburant' => new sfValidatorString(array('max_length' => 50))
        ));
        $this->getWidgetSchema()->setNameFormat('addVehicule[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
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
        
        $vehicule = new Vehicule();
        $vehicule->setMarque($values['marque']);
        $vehicule->setEnergie($values['energie']);
        $vehicule->setMatriculeVehicule($values['matricule_vehicule']);
        $vehicule->setDateApplied(date('Y-m-d H:i:s'));
        $vehicule->setEmpNumber($loggedInEmpNumber);
        $this->getVehiculeService()->saveVehicule($vehicule);
          
    }
}