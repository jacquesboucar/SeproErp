<?php

/**
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

class  KpiGroupForm extends BasePefromanceSearchForm {

    
    public function configure() {
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'kpi_group' => new sfWidgetFormInputText()
        ));
        $this->setValidators(array(
            'id' => new sfValidatorString(array('required' => false)),
            'kpi_group' => new sfValidatorString(array('required' => true)),
        ));
        $this->getWidgetSchema()->setNameFormat('kpiGroup[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }


   
    protected function getFormLabels() {
       
        $requiredMarker = '&nbsp;<span class="required">*</span>';
        $labels = array(
            'kpi_group' =>  __('Groupe') . $requiredMarker,
        );
        return $labels;
    }

    public function saveForm() {
        $values = $this->getValues();
        
          $kpiGroup = new KpiGroup();
          $kpiGroup->setKpiGroupName($values['kpi_group']);
          $this->getKpiGroupService()->saveKpiGroup($kpiGroup);
 
    }

}

