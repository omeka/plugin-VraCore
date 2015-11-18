<?php

class Table_VraCoreElement extends Omeka_Db_Table
{
    public function getSelectForFindBy($params)
    {
        if (isset($params['vra_element_id']) && !$params['vra_element_id']) {
            unset($params['vra_element_id']);
            $select = parent::getSelectForFindBy($params);
            $select->where('vra_element_id IS NULL');
        } else {
            $select = parent::getSelectForFindBy($params);
        }
        return $select;
    }
}