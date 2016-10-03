<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * Description of DefineKpiForm
 *
 * @author nadeera
 */

class AddPretImmobilierForm extends BasePefromanceSearchForm {
    
    public $pretimmobilierService;

    /**
     *
     * @return \TrainingService 
     */
    public function getPretImmobilierService() {

        //if ($this->$trainingService == null) {
            return new PretImmobilierService();
        // } else {
        //     return $this->$trainingService;
        // }
    }

    public function configure() {

        $this->setWidgets(array(
            //'txtEmpID' => new sfWidgetFormInputHidden(),
            'montant_pret' => new sfWidgetFormInputText(),
            'objet' => new sfWidgetFormInputText(),
            'nombre_mois' => new sfWidgetFormInputText(),
            'date_accord' => new ohrmWidgetDatePicker(array(), array('id' => 'date_accord')),
            'date_prelevement' => new ohrmWidgetDatePicker(array(), array('id' => 'date_prelevement')),
            'quotite_saisissable' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'montant_pret' => new sfValidatorNumber(),
            'objet' => new sfValidatorString(array('max_length' => 255)),
            'nombre_mois' => new sfValidatorNumber(),
            'date_accord' => new ohrmDateValidator(array('required' => false)),
            'date_prelevement' =>new ohrmDateValidator(array('required' => false)),
            'quotite_saisissable' => new sfValidatorNumber()
        ));
        $this->getWidgetSchema()->setNameFormat('addPretImmobilier[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }



   

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'montant_pret' => __('Motant du pret').$requiredMarker,
            'objet' => __('Objet').$requiredMarker,
            'nombre_mois' => __('Nombre de mois').$requiredMarker,
            'date_accord' => __('Date accord'),
            'date_prelevement' => __('Date prelevement'),
            'quotite_saisissable' => __('Quotite saisissable')
        );
        return $labels;
    }

    public function saveForm() {
        // Get logged user employee Id
        $user = sfContext::getInstance()->getUser();
        $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        $values = $this->getValues();

        $pretimmobilier = new PretImmobilier();
        $pretimmobilier->setMontantPret($values['montant_pret']);
        $pretimmobilier->setObjet($values['objet']);
        $pretimmobilier->setNombreMois($values['nombre_mois']);
        $pretimmobilier->setDateAccord($values['date_accord']);
        $pretimmobilier->setDatePrelevement($values['date_prelevement']);
        $pretimmobilier->setQuotiteSaisissable($values['quotite_saisissable']);
        $pretimmobilier->setEmpNumber($loggedInEmpNumber);
        $this->getPretImmobilierService()->savePretImmobilier($pretimmobilier);
          
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
    public function searchPretImmobilier($page) {
        
        $serachParams ['objet'] =  $this->getValue('objet');
        $serachParams ['page'] =  $page;
        $serachParams ['limit'] =  sfConfig::get('app_items_per_page');        

        return $this->getPretImmobilierService()->searchPretImmobilier($serachParams);
    }
    
    public function getPretImmobilierCount(){
        $serachParams ['objet'] =  $this->getValue('objet');
        $serachParams['limit'] = null;
        
        return $this->getPretImmobilierService()->getPretImmobilierCount($serachParams);
    }
}