<?php

class VraCoreSubelement extends Omeka_Record_AbstractRecord
{

    public $item_id;
    
    public $element_id;
    
    public $vra_element_id;
    
    public $name;
    
    public $content;
    
    protected $attributes;
    
    
    public function getAttributes()
    {
        if(! $this->attributes) {
            $this->attributes = $this->getDb()->getTable('VraCoreAttribute')
                ->findBy(array('vra_element_id' => $this->id));
        }
        return $this->attributes;
    }
}
