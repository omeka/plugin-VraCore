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
    
    public function getSubelements()
    {
        if(! $this->subelements) {
            $this->subelements = $this->getDb()->getTable('VraCoreElement')
                ->findBy(array('vra_element_id' => $this->id));
        }
        return $this->subelements;
    }
}
