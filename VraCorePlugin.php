<?php

class VraCorePlugin extends Omeka_Plugin_AbstractPlugin
{
    public $_hooks = array(
            'install',
            'uninstall',
            'initialize',
            'after_save_item',
            'elements_show'
            );
    
    public $_filters = array(
            
            );
    
    protected $elementsData = array(
            'Title' => array('attrs' => array('type')),
            'Agent' => array(
                    'attrs' => array(), 
                    'subelements' => array('name', 'culture', 'dates', 'role', 'attribution'),
                    'subelementObjects' => array()
                    ),
            'Cultural Context' => array('attrs' => array(), 'subelements' => array()), 
            'Date' => array(
                    'attrs' => array('type'),
                    'subelements' => array('earliestDate', 'latestDate'),
                    'subelementObjects' => array()
                    ),
            'Description' => array('attrs' => array()),
            'Inscription' => array(
                    'attrs' => array(),
                    'subelements' => array('author', 'position', 'text'),
                    'subelementObjects' => array()
                    ),
            'Location' => array(
                    'attrs' => array('type'),
                    'subelements' => array('name', 'refid'),
                    'subelementObjects' => array()
                    ),
            'Material' => array('attrs' => array('type')),
            'Measurements' => array('attrs' => array('type', 'unit')),
            'Relation' => array(
                    'attrs' => array('type', 'relids'),
                    ),
            'Rights' => array(
                    'attrs' => array('type'),
                    'subelements' => array('rightsHolder', 'text'),
                    'subelementObjects' => array()
                    ),
            'Source' => array(
                    'attrs' => array(), 
                    'subelements' => array('name', 'refid'),
                    'subelementObjects' => array()
                    ),
            'State Edition' => array(
                    'attrs' => array('type', 'num', 'count'),
                    'subelements' => array('name', 'description'),
                    'subelementObjects' => array()
                    ),
            'Style Period' => array('attrs' => array()),
            'Subject' => array(
                    'attrs' => array(),
                    'subelements' => array('term'),
                    'subelementObjects' => array()
                    ),
            'Technique' => array('attrs' => array()),
            'Textref' => array(
                    'attrs' => array(),
                    'subelements' => array('name', 'refid'),
                    'subelementObjects' => array()
                    ),
            'Worktype' => array('attrs' => array())
        );
    
    protected $subelementsData = array(
            'dates' => array(
                    'attrs' => array('type'),
                    'subelements' => array('earliestDate', 'latestDate')
                    ),
            'name' => array(
                    'attrs' => array('type')
                    ),
            'text' => array(
                    'attrs' => array('type')
                    ),
            'refid' => array(
                    'attrs' => array('type')
                    ),
            'term' => array(
                    'attrs' => array('type')
                    ),
            'earliestDate' => array(
                    'attrs' => array('circa')
                    ),
            'latestDate' => array(
                    'attrs' => array('circa')
                    ),
            );
    
    protected $globalAttrs = array(
          //  'dataDate',
            'extent',
            'href',
            'pref',
            'refid',
            'rules',
            'source',
            'vocab',
            'xml:id',
            'xml:lang'
            );
    
    public function hookInitialize()
    {
        $elements = array_keys($this->elementsData);
        foreach ($elements as $element) {
            add_filter(array('ElementForm', 'Item', "VRA Core", $element), array($this, 'addVraInputs'), 1);
        }
        $view = get_view();
        $view->addHelperPath(__DIR__ . '/helpers', 'VraCore_View_Helper_');
    }
    
    public function hookInstall()
    {
        $db = $this->_db;
        
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->VraCore_Subelement` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `element_id` int(10) unsigned NOT NULL,
          `name` text COLLATE utf8_unicode_ci NOT NULL,
          `content` text COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
        ";
        
        $db->query($sql);
        
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->VraCore_Attribute` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `element_id` int(10) unsigned NOT NULL,
          `vra_element_id` int(10) unsigned NULL,
          `name` text COLLATE utf8_unicode_ci NOT NULL,
          `content` text COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
        ";
        
        $db->query($sql);
        
        $this->installVraCoreElements();
    }
    
    public function hookUninstall()
    {
        $db = $this->_db;
        $sql = "DROP TABLE `$db->VraCore_Attribute`";
        $db->query($sql);
        $sql = "DROP TABLE `$db->VraCore_Subelement`";
        $db->query($sql);
    }
    
    public function hookAfterSaveItem($args)
    {
        $insert = $args['insert'];
        
        //figure out whether there is a subelement in the data
        //save subelement first, so I have the ID
        //and make sure the data posted makes it possible to line up correct
        //attributes with the right subelements, if they're there
        
        
        $vraElement = null;
        if ($insert) {
            $this->insertAttributes($args);
            $this->insertSubelements($args);
        } else {
            $this->updateAttributes($args);
            $this->updateSubelements($args);
        }
    }
    
    public function hookElementsShow($args)
    {
        $record = $args['record'];
        $element = $args['elementInfo']['element'];
        $elementSet = $this->_db->getTable('ElementSet')->find($element->element_set_id);
        if ($elementSet->name == 'VRA Core') {
            $attributes = $this->_db->getTable('VraCoreAttribute')
                            ->findBy(array('item_id'    => $record->id,
                                           'element_id' => $element->id
                               ));
            if (empty($attributes)) {
                return;
            }
            $html = '<h4>Attributes</h4>';
            $html .= "<ul class='vra-core-attributes'>";
            foreach($attributes as $attribute) {
                $html .= "<li><span class='vra-core-attribute-name'>" . metadata($attribute, 'name') . "</span>";
                $html .= metadata($attribute, 'content') . "</li>";
            }
            $html .= "</ul>";
            echo $html;
        }
    }

    public function addVraInputs($components, $args)
    {
        
        $record = $args['record'];
        $element = $args['element'];
        if ($record->exists()) {
            $attributeValues = $this->_db->getTable('VraCoreAttribute')->findBy(array('element_id' => $element->id,
                                                                               'item_id' => $record->id
                                                                ));
            $keyedValues = array();
            foreach($attributeValues as $valueObject) {
                $keyedValues[$valueObject->name] = metadata($valueObject, 'content');
                //$keyedValues['xmllang'] = metadata($valueObject, 'content');
            }
        } else {
            $keyedValues = array();
        }
        $view = get_view();
        $valuesVariableName = 'attributeValues' . $element->id;
        $html = $view->partial('edit-form.php',
            array('element'          => $element,
                  'record'           => $record,
                  'elementsData'     => $this->elementsData,
                  'subelementsData'  => $this->subelementsData,
                  'globalAttributes' => $this->globalAttrs,
                  "attributeValues"  => $keyedValues
            ));
        
        
        $components['inputs'] .= $html;
        return $components;
    }
    
    protected function insertAttributes($args, $subelement = null)
    {
        $post = $args['post'];
        $record = $args['record'];
        $insert = $args['insert'];
        if (isset($args['attributes'])) {
            $attributesArray = $args['attributes'];
        } else {
            $attributesArray = $post['vra-attr'];
        }
        $attrTable = $this->_db->getTable('VraCoreAttribute');
        
        foreach($attributesArray as $elementId => $attributeData) {
            foreach($attributeData as $attr => $valueData) {
                if (empty($valueData['value'])) {
                    continue;
                }
                $vraAttribute = new VraCoreAttribute;
                $vraAttribute->item_id = $record->id;
                $vraAttribute->element_id = $elementId;
                $vraAttribute->name = $attr;
                $vraAttribute->content = $valueData['value'];
                if ($subelement) {
                    $vraAttribute->vra_element_id = $subelement->id;
                }
                $vraAttribute->save();
            }
        }
    }
    
    protected function insertSubelements($args)
    {
        $post = $args['post'];
        $record = $args['record'];
        foreach($post['vra-subelement'] as $elementId=>$subElementData) {
            foreach($subElementData as $subelementName=>$valuesData) {
                foreach($valuesData as $id => $content) {
                    if ($content['content'] != '') {
                        $vraSubelement = new VraCoreSubelement();
                        $vraSubelement->element_id = $elementId;
                        $vraSubelement->item_id = $record->id;
                        $vraSubelement->name = $subelementName;
                        $vraSubelement->content = $content['content'];
                        $vraSubelement->save();
                        $args['attributes'] = $content['attrs'];
                        $this->insertAttributes($args, $vraSubelement);
                    }
                }
            }
        }
    }
    
    protected function updateSubelements($args)
    {
        $post = $args['post'];
        $record = $args['record'];
        foreach($post['vra-subelement'] as $elementId=>$subElementData) {
            foreach($subElementData as $subelementName=>$valuesData) {
                foreach($valuesData as $id => $content) {
                    if ($content['content'] != '') {
                        $vraSubelement = new VraCoreSubelement();
                        $vraSubelement->element_id = $elementId;
                        $vraSubelement->item_id = $record->id;
                        $vraSubelement->name = $subelementName;
                        $vraSubelement->content = $content['content'];
                        $vraSubelement->save();
                        $args['attributes'] = $content['attrs'];
                        $this->insertAttributes($args, $vraSubelement);
                    }
                }
            }
        }
    }
    
    protected function updateAttributes($args, $subelement = null)
    {
        $post = $args['post'];
        $record = $args['record'];
        $insert = $args['insert'];
        
        if (isset($args['attributes'])) {
            $attributesArray = $args['attributes'];
        } else {
            $attributesArray = $post['vra-attr'];
        }
        
        $attrTable = $this->_db->getTable('VraCoreAttribute');

        foreach($attributesArray as $elementId => $attributeData) {
            foreach($attributeData as $attr => $valueData) {
                if (empty($valueData['value'])) {
                    continue;
                }
                $attributes = $attrTable->findBy(
                                array('element_id' => $elementId,
                                      'item_id' => $record->id,
                                      'name' => $attr
                                ));
                if (empty($attributes)) {
                    $vraAttribute = new VraCoreAttribute;
                    $vraAttribute->item_id = $record->id;
                    $vraAttribute->element_id = $elementId;
                    $vraAttribute->name = $attr;
                } else {
                    $vraAttribute = $attributes[0];
                }
                if ($subelement) {
                    $vraAttribute->vra_element_id = $subelement->id;
                }
                $vraAttribute->content = $valueData['value'];
                $vraAttribute->save();
            }
        }
    }
    
    protected function installVraCoreElements()
    {
        $VraCoreElementSet = $this->_db->getTable('ElementSet')->findByName('VRA Core');
        if ($VraCoreElementSet) {
            return;
        }
        
        $elementSetMetadata = array(
            'name'        => 'VRA Core', 
            'description' => 'VRA Core standard for artistic pieces and cultural heritage artifacts.
                                The first input will be treated as a <display> element. More detailed
                                VRA Core metadata is available below that input.
                             '
        );
        $elements = array(
            array(
                'name'           => 'Title',
                'description'    => 'The title or identifying phrase given to a Work or an Image.',
            ),
            array(
                'name'           => 'Agent',
                'description'    => 'The names, appellations, or other identifiers assigned to an individual, group, or corporate body that has contributed to the design, creation, production, manufacture, or alteration of the work or image.',
            ),
            array(
                'name'           => 'Cultural Context',
                'description'    => 'The name of the culture, people (ethnonym), or adjectival form of a country name fromwhich a Work, Collection, or Image originates, or the cultural context with which the Work, Collection, or Image has been associated.',
            ),
            array(
                'name'           => 'Date',
                'description'    => 'Date or range of dates associated with the creation, design, production, presentation, performance, construction, or alteration, etc. of the work or image. Dates may be expressed as free text or numerical.  In format yyyy-mm-dd yyyy-mm-dd.',
            ),
            array(
                'name'           => 'Description',
                'description'    => 'A free-text note about the Work, Collection, or Image, including comments, description, or interpretation, that gives additional information not recorded in other categories.',
            ),
            array(
                'name'           => 'Inscription',
                'description'    => 'All marks or written words added to the object at the time of production or in its subsequent history, including signatures, dates, dedications, texts, and colophons, as well as marks, such as the stamps of silversmiths, publishers, or printers. ',
            ),
            array(
                'name'           => 'Location',
                'description'    => 'The geographic location and/or name of the repository, building, site, or other entity whose boundaries include the Work or Image.',
            ),
            array(
                'name'           => 'Material',
                'description'    => 'The substance of which a work or an image is composed.',
            ),
            array(
                'name'           => 'Measurements',
                'description'    => 'The physical size, shape, scale, dimensions, or format of the Work or Image. Dimensions may include such measurements as volume, weight, area or running time.',
            ),
            array(
                'name'           => 'Relation',
                'description'    => 'Terms or phrases describing the identity of the related work and the relationship between the work being cataloged and the related work or image.',
            ),
            array(
                'name'           => 'Rights',
                'description'    => 'Information about the copyright status and the rights holder for a work, collection, or image',
            ),
            array(
                'name'           => 'Source',
                'description'    => 'A reference to the source of the information recorded about the work or the image.',
            ),
            array(
                'name'           => 'State Edition',
                'description'    => 'The identifying number and/or name assigned to the state or edition of a work that exists in more than one form and the placement of that work in the context of prior or later issuances of multiples of the same work.',
            ),
            array(
                'name'           => 'Style Period',
                'description'    => 'A defined style, historical period, group, school, dynasty, movement, etc. whose characteristics are represented in the Work or Image.',
            ),
            array(
                'name'           => 'Subject',
                'description'    => 'Terms or phrases that describe, identify, or interpret the Work or Image and what it depicts or expresses. These may include generic terms that describe the work and the elements that it comprises, terms that identify particular people, geographic places, narrative and iconographic themes, or terms that refer to broader concepts or interpretations.',
            ),
            array(
                'name'           => 'Technique',
                'description'    => 'The production or manufacturing processes, techniques, and methods incorporated in the fabrication or alteration of the work or image.',
            ),
            array(
                'name'           => 'Textref',
                'description'    => 'Contains the name of a related textual reference and any type of unique identifier that text assigns to a Work or Collection that is independent of any repository.',
            ),
            array(
                'name'           => 'Worktype',
                'description'    => 'Identifies the specific type of WORK, COLLECTION, or IMAGE being described in the record.',
            )
        );
        insert_element_set($elementSetMetadata, $elements);
    }
    

}
