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
class PretImmobilierService extends BaseService {

    public $dao;

    /**
     *
     * @return PretImmobilierDao
     */
    public function getDao() {
        if ($this->dao != null) {
            return $this->dao;
        } else {
            return new PretImmobilierDao();
        }
    }

    /**
     *
     * @param PretImmobilierDao $dao
     */
    public function setDao($dao) {
        $this->dao = $dao;
    }


    public function savePretImmobilier($pretimmobilier) {
        return $this->getDao()->savePretImmobilier($pretimmobilier);
    }
    
    /**
     *
     * @param array $parameters
     * @return Doctrine_Collection 
     */
    public function searchPretImmobilier($parameters = null) {
        return $this->getDao()->searchPretImmobilier($parameters);
    }

    public function getPretImmobilierCount($serachParams) {
        $pretimmobilierList = $this->getDao()->searchPretImmobilier($serachParams);
        return count($pretimmobilierList);
    }


    /**
     * @param $ids
     * @return bool
     */
    public function deletePretImmobilier($ids) {
        foreach($ids as $id){
            $pretimmobilier = $this->getPretImmobilierById($id);
            if($pretimmobilier instanceof PretImmobilier){
                $pretimmobilier->delete();
            }
        }
        return $this->getDao()->deletePretImmobilier($ids);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getPretImmobilierById($id){
        return $this->getDao()->getPretImmobilierById($id);
    }
    
}

