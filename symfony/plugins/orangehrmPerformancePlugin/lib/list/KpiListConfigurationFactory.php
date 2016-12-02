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
 */

/**
 * Description of KpiListConfigurationFactory
 *
 */
class KpiListConfigurationFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $headerArray = array();
        
        $header1 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Key Performance Indicator',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'link',
            'elementProperty' => array(
                'labelGetter' => 'getKpiIndicators',
                'placeholderGetters' => array('id' => 'getId'),
                'urlPattern' => 'index.php/performance/saveKpi?hdnEditId={id}'),
        ));

        $headerArray [] = $header1;

        $header2= new ListHeader();
        $header2->populateFromArray(array(
            'name' => 'Kpi Groupe',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array(
                'getter' => array('getKpiGroup', 'getKpiGroup'))
        ));

        $headerArray [] = $header2;

        $header3 = new ListHeader();
        $header3->populateFromArray(array(
            'name' => 'Job Title',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array(
                'getter' => array('getJobTitle', 'getJobTitleName'))
                
        ));        
        $headerArray [] = $header3;
        
        $header4 = new ListHeader();
        $header4->populateFromArray(array(
            'name' => 'Objectifs',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getObjectif')
                
        ));

        $headerArray [] = $header4;
        
        $header5 = new ListHeader();
        $header5->populateFromArray(array(
            'name' => 'Mode Calcul',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getModeCalcul')
                
        ));
        $headerArray [] = $header5;

        /*
        $header6 = new ListHeader();
        $header6->populateFromArray(array(
            'name' => 'Poids',
            'isSortable' => false,
            'textAlignmentStyle' => 'center',
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getMaxRating')

        ));
        $headerArray [] = $header6;*/
        
        $header7 = new ListHeader();
        $header7->populateFromArray(array(
            'name' => 'Périodicité',
            'isSortable' => false,
            'sortField' => null, 
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => 'getDelai'),
        ));
         $headerArray [] = $header7;
        
        $this->headers = $headerArray;
    }

    /**
     *
     * @return string 
     */
    public function getClassName() {
        return 'Kpi';
    }
}

