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
class TrainingService extends BaseService {

    public $dao;

    /**
     *
     * @return KpiDao
     */
    public function getDao() {
        if ($this->dao != null) {
            return $this->dao;
        } else {
            return new TrainingDao();
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
    public function saveTraining($training) {
        return $this->getDao()->saveTraining($training);
    }
    
    /**
     *
     * @param array $parameters
     * @return Doctrine_Collection 
     */
    public function searchTraining($parameters = null) {
        return $this->getDao()->searchTraining($parameters);
    }

    public function getTrainingCount($serachParams) {
        $trainingList = $this->getDao()->searchTraining($serachParams);
        return count($trainingList);
    }
    
    /**
     * Get KPI Groups
     * 
     * @return Doctrine_Collection 
     */
    public function deleteTraining($ids) {
        foreach($ids as $id){
            $training = $this->getTrainingById($id);
            if($training instanceof Training){
                $training->delete();
            }
        }
        return $this->getDao()->deleteTraining($ids);
    }

    
    
    public function getTrainingById($id){
        return $this->getDao()->getTrainingById($id);
    }
    
}

