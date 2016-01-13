<?php

class VraCoreElement extends Omeka_Record_AbstractRecord
{

    public $record_id;
    
    public $record_type;
    
    public $element_id;
    
    public $vra_element_id;
    
    public $name;
    
    public $content;
    
    protected $attributes;
    
    protected $subelements;
    
    
    public function getAttributes()
    {
        if(! $this->attributes) {
            $this->attributes = $this->getDb()->getTable('VraCoreAttribute')
                ->findBy(array('vra_element_id' => $this->id));
        }
        return $this->attributes;
    }

    public function getSubelements($name = null)
    {
    
        $params = array('vra_element_id' => $this->id);
        if ($name) {
            $params['name'] = $name;
        }
        $this->subelements = $this->getDb()->getTable('VraCoreElement')
            ->findBy($params);
        return $this->subelements;
    }

    public function updateDataDate()
    {
        $dataDateAttr = $this->getDb()->getTable('VraCoreAttribute')->fetchElementDataDate($this);
        if ($dataDateAttr) {
            $dataDateAttr->content = date('Y-m-d H:i:s');
            $dataDateAttr->save();
        } else {
            $dataDateAttr = new VraCoreAttribute();
            $dataDateAttr->name = 'dataDate';
            $dataDateAttr->vra_element_id = $this->id;
            $dataDateAttr->record_id = $this->record_id;
            $dataDateAttr->record_type = $this->record_type;
            $dataDateAttr->element_id = $this->element_id;
            $dataDateAttr->content = date('Y-m-d H:i:s');
            $dataDateAttr->save();
        }
    }

    protected function afterDelete()
    {
        $attributes = $this->getAttributes();
        $subElements = $this->getSubelements();
        foreach($subElements as $subelement) {
            $subelement->delete();
        }
        foreach($attributes as $attribute)
        {
            $attribute->delete();
        }
    }

}
