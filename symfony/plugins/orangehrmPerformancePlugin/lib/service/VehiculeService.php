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
class VehiculeService extends BaseService {

    public $dao;


    /**
     * @return VehiculeDao
     */
    public function getDao() {
        if ($this->dao != null) {
            return $this->dao;
        } else {
            return new VehiculeDao();
        }
    }


    /**
     * @param $dao
     */
    public function setDao($dao) {
        $this->dao = $dao;
    }

    /**
     * @param $vehicule
     * @return sfDoctrineRecord
     */
    public function saveVehicule($vehicule) {
        return $this->getDao()->saveVehicule($vehicule);
    }

    /**
     * @param $ids
     * @return bool
     */
    public function deleteVehicule($ids) {
        foreach($ids as $id){
            $vehicule = $this->getVehiculeById($id);
            if($vehicule instanceof Vehicule){
                $vehicule->delete();
            }
        }
        return $this->getDao()->deleteVehicule($ids);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getVehiculeById($id){
        return $this->getDao()->getVehiculeById($id);
    }


    /**
     *
     * @param array $parameters
     * @return Doctrine_Collection
     */
    public function searchVehicule($parameters = null) {
        return $this->getDao()->searchVehicule($parameters);
    }

    public function getVehiculeCount($serachParams) {
        $vehiculeList = $this->getDao()->searchVehicule($serachParams);
        return count($vehiculeList);
    }

    public function softDeleteVehicule($ids) {
        foreach($ids as $id){
            $vehicule = $this->getVehiculeById($id);
            if($vehicule instanceof Vehicule){
                $vehicule->delete();
            }
        }
    }
}

