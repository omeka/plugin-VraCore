<?php

class VraCorePlugin extends Omeka_Plugin_AbstractPlugin
{
    public $_hooks = array(
            'install',
            'uninstall',
            'initialize',
            'after_save_item',
            'elements_show',
            'config',
            'config_form',
            'admin_head'
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
            'Cultural Context' => array('attrs' => array()),
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
        //VRA id omeka element is a bit weird, as it's a VRA attribute on the Omeka record
        //thus, it isn't in the elementsData
        add_filter(array('ElementForm', 'Item', "VRA Core", 'ID'), array($this, 'filterVraIdForm'), 1);
        add_filter(array('ElementInput', 'Item', "VRA Core", 'ID'), array($this, 'filterVraIdInput'), 1);
        add_filter(array('ElementForm', 'Collection', "VRA Core", 'ID'), array($this, 'filterVraIdForm'), 1);
        add_filter(array('ElementInput', 'Collection', "VRA Core", 'ID'), array($this, 'filterVraIdInput'), 1);
        add_filter(array('ElementForm', 'File', "VRA Core", 'ID'), array($this, 'filterVraIdForm'), 1);
        add_filter(array('ElementInput', 'File', "VRA Core", 'ID'), array($this, 'filterVraIdInput'), 1);
        foreach ($elements as $element) {
            add_filter(array('ElementForm', 'Item', "VRA Core", $element), array($this, 'addVraInputs'), 1);
            add_filter(array('ElementForm', 'Collection', "VRA Core", $element), array($this, 'addVraInputs'), 1);
            add_filter(array('ElementForm', 'File', "VRA Core", $element), array($this, 'addVraInputs'), 1);
        }
        $view = get_view();
        $view->addHelperPath(__DIR__ . '/helpers', 'VraCore_View_Helper_');
    }
    
    public function hookInstall()
    {
        $db = $this->_db;
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->VraCoreElement` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `record_id` int(11) NOT NULL,
          `record_type` tinytext COLLATE utf8_unicode_ci NOT NULL,
          `element_id` int(10) unsigned NOT NULL,
          `vra_element_id` int(11) DEFAULT NULL,
          `name` text COLLATE utf8_unicode_ci NOT NULL,
          `content` text COLLATE utf8_unicode_ci NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
        ";
        $db->query($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->VraCoreAttribute` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `record_id` int(11) NOT NULL,
          `record_type` tinytext COLLATE utf8_unicode_ci NOT NULL,
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
        $sql = "DROP TABLE `$db->VraCore_Element`";
        $db->query($sql);
    }
    
    public function hookConfig($args)
    {
        $post = $args['post'];
        set_option('vra-core-ignore-attributes', $post['vra-core-ignore-attributes']);
        set_option('vra-core-ignore-elements', $post['vra-core-ignore-elements']);
    }

    public function hookConfigForm($args)
    {
        include('config_form.php');
    }
    
    public function hookAdminHead($args)
    {
        queue_css_file('vra-core');
        queue_js_file('vra-core');
    }
    
    public function hookAfterSaveItem($args)
    {
        $insert = $args['insert'];
        $post = $args['post'];
        $vraElementData = $post['vra-element'];
        $omekaRecord = $args['record'];
        $omekaRecordData = array('id' => $omekaRecord->id, 'type' => get_class($omekaRecord));
        //figure out whether there is a element in the data
        //save element first, so I have the ID
        //and make sure the data posted makes it possible to line up correct
        //attributes with the right subelements, if they're there
        foreach($vraElementData as $omekaElementId => $elementArray) {
            $displayAttributes = $elementArray['display'];
            $this->storeAttributes($displayAttributes['attrs'], $omekaRecordData, $omekaElementId);
            //elementArray has keys display, newElements, and existing VRAelement ids
            unset($elementArray['display']);

            $newElements = $elementArray['newElements'];
            foreach($newElements as $newElementData) {
                $this->processNewElement($omekaRecord, $omekaElementId, $newElementData);
            }

            unset($elementArray['newElements']);

            foreach($elementArray as $vraElementId => $existingElementData) {
                //see @todo below
                if ($vraElementId != 'notes') {
                    $this->processExistingElement($omekaRecord, $omekaElementId, $vraElementId, $existingElementData);
                }
                
            }

            //@TODO hunt down the duplication that makes this work here, but not above
            $notes = $elementArray['notes'];
            if(! empty($notes)) {
                $notesObject = $this->_db->getTable('VraCoreElement')->findNotesForRecordElement($omekaRecord, $omekaElementId);
                if (! $notesObject) {
                    $notesObject = new VraCoreElement();
                    $notesObject->record_id = $omekaRecord->id;
                    $notesObject->record_type = get_class($omekaRecord);
                    $notesObject->element_id = $omekaElementId;
                    $notesObject->name = 'notes';
                }
                $notesObject->content = $notes;
                $notesObject->save(true);
            }
        }
    }

    public function hookElementsShow($args)
    {
        $record = $args['record'];
        $element = $args['elementInfo']['element'];
        $elementSet = $this->_db->getTable('ElementSet')->find($element->element_set_id);
        if ($elementSet->name == 'VRA Core') {
            $attributes = $this->_db->getTable('VraCoreAttribute')
                            ->findBy(array('record_id'    => $record->id,
                                           'record_type' => get_class($record),
                                           'element_id' => $element->id,
                                           'vra_element_id' => false
                               ));
            $elements = $this->_db->getTable('VraCoreElement')
                            ->findBy(array('record_id'    => $record->id,
                                           'record_type' => get_class($record),
                                           'element_id' => $element->id,
                                           'vra_element_id' => false
                               ));

            if (empty($attributes) && empty($elements)) {
                return;
            }
            $groupedElements = array();
            foreach($elements as $element) {
                $groupedElements[$element->name][] = $element;
            }

            $html = '<h4>Display Element Attributes</h4>';
            $html .= "<ul class='vra-core-attributes'>";
            foreach($attributes as $attribute) {
                $html .= "<li><span class='vra-core-attribute-name'>" . metadata($attribute, 'name') . "</span>";
                $html .= metadata($attribute, 'content') . "</li>";
            }
            $html .= "</ul>";

            if (count($groupedElements) != 0) {
                $html .= '<h4>Elements</h4>';
                foreach($groupedElements as $name => $elements) {
                    $html .= "<h5>$name</h5>";
                    
                    foreach($elements as $element) {
                        $subelements = $element->getSubelements();
                        if (empty($subelements)) {
                            $html .= metadata($element, 'content');
                            $html .= "<h6>Attributes</h6>";
                            $html .= "<ul class='vra-core-attributes'>";
                            foreach($element->getAttributes() as $attribute) {
                                $html .= "<li><span class='vra-core-attribute-name'>" . metadata($attribute, 'name') . "</span>";
                                $html .= metadata($attribute, 'content') . "</li>";
                            }
                        } else {
                            foreach($subelements as $subelement) {
                                $html .= metadata($subelement, 'content');
                                $html .= "<h6>Attributes</h6>";
                                $html .= "<ul class='vra-core-attributes'>";
                                foreach($subelement->getAttributes() as $attribute) {
                                    $html .= "<li><span class='vra-core-attribute-name'>" . metadata($attribute, 'name') . "</span>";
                                    $html .= metadata($attribute, 'content') . "</li>";
                                }
                            }
                        }

                        $html .= "</ul>";
                    }
    
                }                
            }

            echo $html;
        }
    }
    
    public function filterVraIdInput($components, $args)
    {
        $components['html_checkbox'] = false;
        return $components;
    }
    
    public function filterVraIdForm($components, $args)
    {
        $components['add_input'] = '';
        return $components;
    }

    public function addVraInputs($components, $args)
    {
        $components['add_input'] = '';
        
        if (get_option('vra-core-ignore-elements')) {
            return $components;
        }
        $record = $args['record'];
        $omekaElement = $args['element'];
        $attributeObjects = array();
        $vraElementObjects = array();
        if ($record->exists()) {
            
            $notesObject = $this->_db->getTable('VraCoreElement')->findNotesForRecordElement($record, $omekaElement->id);
            
            $attributes = $this->_db
                ->getTable('VraCoreAttribute')
                ->findBy(array('element_id'  => $omekaElement->id,
                               'record_id'   => $record->id,
                               'record_type' => get_class($record)
                        ));
            foreach($attributes as $attribute) {
                if(is_null($attribute->vra_element_id)) {
                    $attributeObjects['display'][$attribute->name] = $attribute;
                } else {
                    $attributeObjects[$attribute->vra_element_id][$attribute->name] = $attribute;
                }
            }

            $elements = $this->_db
                ->getTable('VraCoreElement')
                ->findBy(array('element_id'  => $omekaElement->id,
                               'record_id'   => $record->id,
                               'record_type' => get_class($record)
                        ));

            foreach ($elements as $element) {
                if(is_null($element->vra_element_id)) {
                    //$vraElementObjects['set'] = $element;
                } else {
                    $vraElementObjects[$element->id] = $element;
                }
            }
        }
        $attributeNames = array_merge($this->elementsData[$omekaElement->name]['attrs'], $this->globalAttrs);

        $view = get_view();
        $valuesVariableName = 'attributeValues' . $omekaElement->id;
        $partialArgs = array('omekaElement'          => $omekaElement,
                  'record'           => $record,
                  'elementsData'     => $this->elementsData,
                  'subelementsData'  => $this->subelementsData,
                  'notesObject'      => isset($notesObject) ? $notesObject : null,
                    
                  'globalAttributes' => $this->globalAttrs,
                  'attributeNames'    => $attributeNames,
                  'vraElementObjects' => $vraElementObjects,
                  'attributeObjects'  => $attributeObjects
            );
        
        if (isset($this->elementsData[$omekaElement->name]['subelements'])) {
            $html = $view->partial('nested-element-edit-form.php', $partialArgs);
        } else {
            $html = $view->partial('simple-element-edit-form.php', $partialArgs);
        }
        
        

        $components['inputs'] .= $html;
        return $components;
    }

    protected function storeAttributes($attributesData, $omekaRecordData, $omekaElementId, $vraElementId = null)
    {
        foreach($attributesData as $id => $attributeContent) {
            
            foreach($attributeContent as $attrName=>$content) {
                if ($id == 'new') {
                    $vraAttribute = new VraCoreAttribute();
                } else {
                    $vraAttribute = $this->_db->getTable('VraCoreAttribute')->find($id);
                }
                if (empty($content)) {
                    //oops. can't delete a nonexistant record!
                    $vraAttribute->delete();
                } else {
                    $vraAttribute->record_id = $omekaRecordData['id'];
                    $vraAttribute->record_type = $omekaRecordData['type'];
                    $vraAttribute->element_id = $omekaElementId;
                    $vraAttribute->vra_element_id = $vraElementId;
                    $vraAttribute->name = $attrName;
                    $vraAttribute->content = $content;
                    $vraAttribute->save();
                }
            }
        }
    }

    /*
     * 
     * $vraElementId the id of the VraCoreElement record, not the optional vra_element_id column value
     */
    protected function storeElement($elementData, $omekaRecordData, $omekaElementId, $vraElementId = null)
    {
        echo $vraElementId;
        if ($vraElementId) {
            $vraElement = $this->_db->getTable('VraCoreElement')->find($vraElementId);
        } else {
            $vraElement = new VraCoreElement();
            $vraElement->record_type = $omekaRecordData['type'];
            $vraElement->record_id = $omekaRecordData['id'];
            $vraElement->element_id = $omekaElementId;
            $vraElement->vra_element_id = isset($elementData['vra_element_id']) ? $elementData['vra_element_id'] : null;
            $vraElement->name = $elementData['name'];
        }
        
        
        $vraElement->content = isset($elementData['content']) ? $elementData['content'] :  null;
        $vraElement->save();
        return $vraElement;
    }
    
    protected function processNewElement($omekaRecord, $omekaElementId, $elementData)
    {
        $hasSubelements = $this->hasNewSubelements($elementData);
        $omekaRecordData = array('id' => $omekaRecord->id, 'type' => get_class($omekaRecord));
        //$newSubelementsData = $elementData['newSubelements'];
        //debug(print_r($elementData, true));
        debug('processing new element ' . $elementData['name'] );
        if ($hasSubelements) {
            $newVraElement = $this->storeElement($elementData, $omekaRecordData, $omekaElementId);
            
            //$this->storeAttributes($elementData['attrs'], $omekaRecordData, $omekaElementId, $vraElement->id );
            debug('has subelements');
            foreach($elementData['newSubelements'] as $subelementName => $subelementsData) {
                foreach($subelementsData as $subelementData) {
                    if (empty($subelementData['content'])) {
                        continue;
                    }
                    $subelementData['name'] = $subelementName;
                    $this->processNewSubelement($omekaRecord, $omekaElementId, $newVraElement, $subelementData);
                }
                
            }
        } else {
            $content = $elementData['content'];
            if (empty($content)) {
                 return;
            }
            $newVraElement = $this->storeElement($elementData, $omekaRecordData, $omekaElementId);
            $this->storeAttributes($newElement['attrs'], $omekaRecordData, $omekaElementId, $newVraElement->id );
        }
        return $newVraElement;
    }
    
    protected function processExistingElement($omekaRecord, $omekaElementId, $vraElementId, $elementData)
    {
        
        $hasSubelements = !empty($elementData['hasSubElements']);
        $omekaRecordData = array('id' => $omekaRecord->id, 'type' => get_class($omekaRecord));
        $vraElementObject = $this->_db->getTable('VraCoreElement')->find($vraElementId);
        if ($hasSubelements) {
        } else {
            if (empty($elementData['content'])) {
            //$vraElementObject->delete();
            } else {
                $vraElementObject->content = $elementData['content'];
                $vraElementObject->save();
                $this->storeAttributes($elementData['attrs'], $omekaRecordData, $omekaElementId, $vraElementId);
            }
        }

    }
    
    protected function processNewSubelement($omekaRecord, $omekaElementId, $parentVraElement, $elementData)
    {
        debug('new subelement');
        
        //this is the id of the element in Omeka's Elements table
        $vraElementId = $parentVraElement->element_id;
        //this value is actually the parent vra xml element
        $elementData['vra_element_id'] = $parentVraElement->id;
        $omekaRecordData = array('id' => $omekaRecord->id, 'type' => get_class($omekaRecord));
        $this->storeElement($elementData, $omekaRecordData, $omekaElementId);
        
    }
    
    protected function processExistingSubelement($omekaRecord, $omekaElementId, $vraElementId, $elementData)
    {
        $omekaRecordData = array('id' => $omekaRecord->id, 'type' => get_class($omekaRecord));
    }
    
    protected function hasNewSubelements($elementData)
    {
        if (empty($elementData['hasSubelements'])) {
            return false;
        }
        
        foreach($elementData['newSubelements'] as $subelementName => $subelementsData) {
            foreach($subelementsData as $subelementData) {
                if (! empty($subelementData['content'])) {
                    return true;
                }
            }
        }
        return false;
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
                'name'           => 'ID',
                'description'    => 'The ID to apply to a VRA Work, Image, or Collection (corresponds to vra id attribute).'
            ),
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
