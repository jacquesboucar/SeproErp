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
class KpiGroupService extends BaseService {

    public $dao;

    /**
     *
     * @return KpiDao
     */
    public function getDao() {
        if ($this->dao != null) {
            return $this->dao;
        } else {
            return new KpiGroupDao();
        }
    }

    /**
     *
     * @param KpiDao $dao 
     */
    public function setDao($dao) {
        $this->dao = $dao;
    }

    /**
     *
     * @param Kpi $performanceReviewTemplate
     * @return Kpi 
     */
    public function saveKpiGroup($kpiGroup) {
        return $this->getDao()->saveKpiGroup($kpiGroup);
    }

    
    /**
     * Get KPI Groups
     * 
     * @return Doctrine_Collection 
     */
    public function deleteKpiGroup($ids) {
        foreach($ids as $id){
            $kpi = $this->getKpiGroupById($id);
            if($kpi instanceof KpiGroup){
                $kpi->delete();
            }
        }
        return $this->getDao()->deleteKpi($ids);
    }

    
    
    public function getKpiGroupById($id){
        return $this->getDao()->getKpiGroupById($id);
    }
    
    public function getKpiGroupList() {
        return $this->getDao()->getKpiGroupList();
    }
}

