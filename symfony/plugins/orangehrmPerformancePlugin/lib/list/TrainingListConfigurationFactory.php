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
 * Description of MyPerformanceReviewListConfigurationFactory
 *
 */
class TrainingListConfigurationFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $headerArray = array();

        $header1 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Employee',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'link',
            'elementProperty' => array(
                'labelGetter' => array('getEmployee', 'getFullName'),
                'placeholderGetters' => array('id' => 'getId'),
                'urlPattern' => 'index.php/training/saveTraining?hdnEditId={id}'),
        ));

        $headerArray [] = $header1;

        $header4 = new ListHeader();
        $header4->populateFromArray(array(
            'name' => 'Description',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getDescription')

        ));

        $headerArray [] = $header4;

        $header4 = new ListHeader();
        $header4->populateFromArray(array(
            'name' => 'Titre',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getTitle')

        ));

        $headerArray [] = $header4;

        $header4 = new ListHeader();
        $header4->populateFromArray(array(
            'name' => 'Cout Formation',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getCoutFormation')

        ));

        $headerArray [] = $header4;

        $header6 = new ListHeader();
        $header6->populateFromArray(array(
            'name' => 'Date Soumission',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getDateApplied')

        ));

        $headerArray [] = $header6;

        $header1 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Valider',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => null,
            'elementProperty' => array('getter' => 'getValider')
        ));

        $headerArray [] = $header1;
        $this->headers = $headerArray;
    }

    /**
     *
     * @return string
     */
    public function getClassName() {
        return 'Training';
    }

}