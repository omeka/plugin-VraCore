<?php

class VraCorePlugin extends Omeka_Plugin_AbstractPlugin
{
    public $_hooks = array(
            'install',
            'uninstall',
            'upgrade',
            'initialize',
            'after_save_item',
            'after_save_collection',
            'after_save_file',
            'after_delete_item',
            'after_delete_collection',
            'after_delete_file',
            'elements_show',
            'config',
            'config_form',
            'admin_head',
            'public_head',
            );

    public $_filters = array(
            'action_contexts',
            'response_contexts',
            );

    public $_options = array(
            'vra-core-ignore-attributes',
            'vra-core-ignore-elements',
            'vra-core-hide-public-details',
            'vra-core-hide-public-attributes',
            'vra-core-hide-public-datadate',
            );

    protected $searchTexts = '';

    protected $elementsData = array(
            'Toplevel' => array('attrs' => array()), //mostly fake, to filter out and allow top-level attributes
            'Title' => array('attrs' => array('type')),
            'Agent' => array(
                    'attrs' => array(),
                    'subelements' => array('name', 'culture', 'dates', 'role', 'attribution'),
                    'subelementObjects' => array(),
                    ),
            'Cultural Context' => array('attrs' => array()),
            'Date' => array(
                    'attrs' => array('type'),
                    'subelements' => array('earliestDate', 'latestDate'),
                    'subelementObjects' => array(),
                    ),
            'Description' => array('attrs' => array()),
            'Inscription' => array(
                    'attrs' => array(),
                    'subelements' => array('author', 'position', 'text'),
                    'subelementObjects' => array(),
                    ),
            'Location' => array(
                    'attrs' => array('type'),
                    'subelements' => array('name', 'refid'),
                    'subelementObjects' => array(),
                    ),
            'Material' => array('attrs' => array('type')),
            'Measurements' => array('attrs' => array('type', 'unit')),
            'Relation' => array(
                    'attrs' => array('type', 'relids'),
                    ),
            'Rights' => array(
                    'attrs' => array('type'),
                    'subelements' => array('rightsHolder', 'text'),
                    'subelementObjects' => array(),
                    ),
            'Source' => array(
                    'attrs' => array(),
                    'subelements' => array('name', 'refid'),
                    'subelementObjects' => array(),
                    ),
            'State Edition' => array(
                    'attrs' => array('type', 'num', 'count'),
                    'subelements' => array('name', 'description'),
                    'subelementObjects' => array(),
                    ),
            'Style Period' => array('attrs' => array()),
            'Subject' => array(
                    'attrs' => array(),
                    'subelements' => array('term'),
                    'subelementObjects' => array(),
                    ),
            'Technique' => array('attrs' => array()),
            'Textref' => array(
                    'attrs' => array(),
                    'subelements' => array('name', 'refid'),
                    'subelementObjects' => array(),
                    ),
            'Worktype' => array('attrs' => array()),
        );

    protected $subelementsData = array(
            'dates' => array(
                    'attrs' => array('type'),
                    'subelements' => array('earliestDate', 'latestDate'),
                    ),
            'name' => array(
                    'attrs' => array('type'),
                    ),
            'text' => array(
                    'attrs' => array('type'),
                    ),
            'refid' => array(
                    'attrs' => array('type'),
                    ),
            'term' => array(
                    'attrs' => array('type'),
                    ),
            'earliestDate' => array(
                    'attrs' => array('circa'),
                    ),
            'latestDate' => array(
                    'attrs' => array('circa'),
                    ),
            );

    protected $globalAttrs = array(
            'extent',
            'href',
            'pref',
            'refid',
            'id',
            'rules',
            'source',
            'vocab',
            'xml:lang',
            );

    public function hookInitialize()
    {
        $elements = array_keys($this->elementsData);
        foreach ($elements as $element) {
            if ($element == 'Toplevel') {
                add_filter(array('ElementForm', 'Item', 'VRA Core', $element), array($this, 'filterRecordLevelInput'), 1);
                add_filter(array('ElementForm', 'Collection', 'VRA Core', $element), array($this, 'filterRecordLevelInput'), 1);
                add_filter(array('ElementForm', 'File', 'VRA Core', $element), array($this, 'filterRecordLevelInput'), 1);
            } else {
                add_filter(array('ElementForm', 'Item', 'VRA Core', $element), array($this, 'addVraInputs'), 1);
                add_filter(array('ElementForm', 'Collection', 'VRA Core', $element), array($this, 'addVraInputs'), 1);
                add_filter(array('ElementForm', 'File', 'VRA Core', $element), array($this, 'addVraInputs'), 1);
            }
        }
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
          `element_id` int(10) unsigned NULL,
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
        $sql = "DROP TABLE `$db->VraCoreAttribute`";
        $db->query($sql);
        $sql = "DROP TABLE `$db->VraCoreElement`";
        $db->query($sql);
        
        $this->_uninstallOptions();
    }

    public function hookUpgrade($args)
    {
        $oldVersion = $args['old_version'];
        $newVersion = $args['new_version'];
        $db = $this->_db;
        if (version_compare($newVersion, '1.1-rc', '>')) {
            $sql = "
            ALTER TABLE `$db->VraCoreAttribute` CHANGE `element_id` `element_id` INT( 10 ) UNSIGNED NULL ;
            ";
            $db->query($sql);

            $vraIdElement = $db->getTable('Element')->findByElementSetNameAndElementName('VRA Core', 'ID');
            if ($vraIdElement) {
                $vraIdElTexts = $db->getTable('ElementText')->findByElement($vraIdElement->id);
                foreach ($vraIdElTexts as $vraIdElText) {
                    $vraIdElText->delete();
                }
                $vraIdElement->delete();
            }
        }
    }

    public function hookConfig($args)
    {
        $post = $args['post'];
        set_option('vra-core-ignore-attributes', $post['vra-core-ignore-attributes']);
        set_option('vra-core-ignore-elements', $post['vra-core-ignore-elements']);
        set_option('vra-core-hide-public-details', $post['vra-core-hide-public-details']);
        set_option('vra-core-hide-public-attributes', $post['vra-core-hide-public-attributes']);
        set_option('vra-core-hide-public-datadate', $post['vra-core-hide-public-datadate']);
    }

    public function hookConfigForm($args)
    {
        include 'config_form.php';
    }

    public function hookAdminHead($args)
    {
        queue_css_file('vra-core');
        $js = "var OmekaWebDir = '".WEB_DIR."';";
        queue_js_string($js);
        queue_js_file('vra-core');
    }

    public function hookPublicHead($args)
    {
        queue_css_file('vra-core');
    }

    public function hookElementsShow($args)
    {
        $hidePublicDetails = (bool) get_option('vra-core-hide-public-details');
        if (!is_admin_theme() && $hidePublicDetails) {
            return;
        }
        $record = $args['record'];
        $element = $args['elementInfo']['element'];
        $elementSet = $this->_db->getTable('ElementSet')->find($element->element_set_id);
        if ($elementSet->name == 'VRA Core') {
            $attributes = $this->_db->getTable('VraCoreAttribute')
                            ->findBy(array('record_id' => $record->id,
                                           'record_type' => get_class($record),
                                           'element_id' => $element->id,
                                           'vra_element_id' => false,
                               ));
            $elements = $this->_db->getTable('VraCoreElement')
                            ->findBy(array('record_id' => $record->id,
                                           'record_type' => get_class($record),
                                           'element_id' => $element->id,
                                           'vra_element_id' => false,
                               ));

            if (empty($attributes) && empty($elements)) {
                return;
            }
            $groupedElements = array();
            foreach ($elements as $element) {
                $groupedElements[$element->name][] = $element;
            }
            $view = get_view();
            echo $view->partial('vra-core-data-display.php',
                    array('attributes' => $attributes,
                          'groupedElements' => $groupedElements,
                            )
                    );
        }
    }

    public function hookAfterSaveItem($args)
    {
        $this->afterSaveRecord($args);
    }

    public function hookAfterSaveCollection($args)
    {
        $this->afterSaveRecord($args);
    }

    public function hookAfterSaveFile($args)
    {
        $this->afterSaveRecord($args);
    }

    public function hookAfterDeleteItem($args)
    {
        $this->afterDeleteRecord($args);
    }

    public function hookAfterDeleteCollection($args)
    {
        $this->afterDeleteRecord($args);
    }

    public function hookAfterDeleteFile($args)
    {
        $this->afterDeleteRecord($args);
    }

    public function filterActionContexts($contexts, $args)
    {
        $db = get_db();
        $controller = $args['controller'];
        $params = $controller->getAllParams();
        $vraControllers = array('items', 'files', 'collections');
        if (in_array($params['controller'], $vraControllers) && $params['action'] == 'show') {
            $type = Inflector::classify($params['controller']);
            $record = $db->getTable($type)->find($params['id']);
            $vraElementCount = $db->getTable('VraCoreElement')
                                  ->count(array('record_type' => get_class($record),
                                                'record_id' => $record->id,
                                    ));
            $contexts['show'][] = 'vra';
        }

        return $contexts;
    }

    public function filterResponseContexts($contexts)
    {
        $contexts['vra'] = array('suffix' => 'vra',
                                 'headers' => array('Content-Type' => 'text/xml'), );

        return $contexts;
    }

    public function filterRecordLevelInput($components, $args)
    {
        if (get_option('vra-core-hide-public-attributes')) {
            return $components;
        }
        $db = $this->_db;
        $view = get_view();
        $record = $args['record'];
        $recordClass = get_class($record);
        switch ($recordClass) {
            case 'Item':
                $label = __('Work Attributes');
            break;
            case 'File':
                $label = __('Image Attributes');
            break;
            case 'Collection':
                $label = __('Collection Attributes');
            break;
        }
        $components['add_input'] = '';
        $components['label'] = "<label>$label</label>";
        $globalAttributes = $this->getGlobalAttrs();

        if ($record->exists()) {
            $attributeObjects = $db->getTable('VraCoreAttribute')
                                    ->findBy(array(
                                         'record_id' => $record->id,
                                         'record_type' => $recordClass,
                                         'vra_element_id' => false,
                                         'element_id' => false,
                                        ));
            $keyedAttributeObjects = array('recordLevel' => array());
            if (!empty($attributeObjects)) {
                foreach ($attributeObjects as $attributeObject) {
                    $keyedAttributeObjects['recordLevel'][$attributeObject->name] = $attributeObject;
                }
            }
        } else {
            $keyedAttributeObjects = array();
        }
        $partialArgs = array(
                     'attributeNames' => $globalAttributes,
                     'attributeObjects' => $keyedAttributeObjects,
                     'nameBase' => 'vra-element[recordLevel]', //not really element, but forces into the pattern elsewhere
                     'label' => $label,
                     'topLevel' => 'recordLevel',
                     );
        $html = $view->partial('element-attribute-form.php', $partialArgs);
        $components['inputs'] = $html;

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
                ->findBy(array('element_id' => $omekaElement->id,
                               'record_id' => $record->id,
                               'record_type' => get_class($record),
                        ));
            foreach ($attributes as $attribute) {
                if (is_null($attribute->vra_element_id)) {
                    $attributeObjects['display'][$attribute->name] = $attribute;
                } else {
                    $attributeObjects[$attribute->vra_element_id][$attribute->name] = $attribute;
                }
            }

            $elements = $this->_db
                ->getTable('VraCoreElement')
                ->findBy(array('element_id' => $omekaElement->id,
                               'record_id' => $record->id,
                               'record_type' => get_class($record),
                               'vra_element_id' => false,
                        ));

            foreach ($elements as $element) {
                //exclude notes element, and align by id
                if ($element->name != 'notes') {
                    $vraElementObjects[$element->id] = $element;
                }
            }
        }

        $attributeNames = array_merge($this->elementsData[$omekaElement->name]['attrs'], $this->globalAttrs);

        $view = get_view();
        $partialArgs = array(
                  'omekaElement' => $omekaElement,
                  'record' => $record,
                  'elementsData' => $this->elementsData,
                  'subelementsData' => $this->subelementsData,
                  'notesObject' => isset($notesObject) ? $notesObject : null,
                    //@todo : how many of these are still actually used?
                  'globalAttributes' => $this->globalAttrs,
                  'attributeNames' => $attributeNames,
                  'vraElementObjects' => $vraElementObjects,
                  'attributeObjects' => $attributeObjects,
            );

        $html = '';
        if (isset($this->elementsData[$omekaElement->name]['subelements'])) {
            $html .= $view->partial('nested-element-edit-form.php', $partialArgs);
        } else {
            $html .= $view->partial('simple-element-edit-form.php', $partialArgs);
        }

        $components['inputs'] .= $html;

        return $components;
    }

    public function getGlobalAttrs()
    {
        return $this->globalAttrs;
    }

    public function getElementsData()
    {
        return $this->elementsData;
    }

    public function getSubelementsData()
    {
        return $this->subelementsData;
    }

    protected function storeAttributes($attributesData, $omekaRecord, $omekaElementId = null, $vraElementId = null)
    {
        foreach ($attributesData as $id => $attributeContent) {
            foreach ($attributeContent as $attrName => $content) {
                if ($id == 'new') {
                    $vraAttribute = new VraCoreAttribute();
                } else {
                    $vraAttribute = $this->_db->getTable('VraCoreAttribute')->find($id);
                }

                if ($vraAttribute->exists()) {
                    if (empty($content) || ($attrName == 'circa' && $content == 'delete')) {
                        $vraAttribute->delete();
                    } else {
                        if ($attrName == 'circa') {
                            $content = 'true';
                        }
                        $vraAttribute->content = $content;
                        $this->searchTexts .= ' '.$vraAttribute->content;
                        $vraAttribute->save();
                    }
                } else {
                    if ($attrName == 'circa') {
                        $content = 'true';
                    }
                    $vraAttribute->record_id = $omekaRecord->id;
                    $vraAttribute->record_type = get_class($omekaRecord);
                    $vraAttribute->element_id = $omekaElementId;
                    $vraAttribute->vra_element_id = $vraElementId;
                    $vraAttribute->name = $attrName;
                    $vraAttribute->content = $content;
                    $this->searchTexts .= ' '.$vraAttribute->content;
                    $vraAttribute->save();
                }
            }
        }
    }

    /**
     * $vraElementId the id of the VraCoreElement record, not the optional vra_element_id column value.
     */
    protected function storeElement($elementData, $omekaRecord, $omekaElementId, $vraElementId = null)
    {
        if ($vraElementId) {
            $vraElement = $this->_db->getTable('VraCoreElement')->find($vraElementId);
        } else {
            $vraElement = new VraCoreElement();
            $vraElement->record_type = get_class($omekaRecord);
            $vraElement->record_id = $omekaRecord->id;
            $vraElement->element_id = $omekaElementId;
            $vraElement->vra_element_id = isset($elementData['vra_element_id']) ? $elementData['vra_element_id'] : null;
            $vraElement->name = $elementData['name'];
        }
        $vraElement->content = isset($elementData['content']) ? $elementData['content'] : null;
        $this->searchTexts .= ' '.$vraElement->content.' ';
        $vraElement->save();
        $vraElement->updateDataDate();

        return $vraElement;
    }

    protected function processNewElement($omekaRecord, $omekaElementId, $elementData)
    {
        $hasSubelements = $this->hasNewSubelements($elementData);
        if ($hasSubelements) {
            $newVraElement = $this->storeElement($elementData, $omekaRecord, $omekaElementId);
            $this->storeAttributes($elementData['attrs'], $omekaRecord, $omekaElementId, $newVraElement->id);

            $this->processNewSubelements($omekaRecord,
                                         $omekaElementId,
                                         $elementData['newSubelements'],
                                         $newVraElement);
        } else {
            if (empty($elementData['content'])) {
                return;
            }
            $newVraElement = $this->storeElement($elementData, $omekaRecord, $omekaElementId);
            $this->storeAttributes($elementData['attrs'], $omekaRecord, $omekaElementId, $newVraElement->id);
        }

        return $newVraElement;
    }

    protected function processNewSubelements($omekaRecord, $omekaElementId, $subelementsData, $parentVraElement)
    {
        foreach ($subelementsData as $subelementName => $subelementsData) {
            //special handling for the dates subelement because it has only
        //it's own subelements. No other subelements go down this many levels
        if ($subelementName == 'dates') {
            foreach ($subelementsData as $datesSubelements) {
                if (empty($datesSubelements['earliestDate']['content'])
                    && empty($datesSubelements['latestDate']['content'])) {
                    continue;
                }
                $datesSubelementData = array(
                        'name' => 'dates',
                        'attrs' => $datesSubelements['attrs'],
                        );
                $datesSubelementObject = $this->processNewSubelement($omekaRecord,
                                                                     $omekaElementId,
                                                                     $parentVraElement,
                                                                     $datesSubelementData);

                $earliestDateData = $datesSubelements['earliestDate'];
                $earliestDateData['name'] = 'earliestDate';
                $latestDateData = $datesSubelements['latestDate'];
                $latestDateData['name'] = 'latestDate';

                if (!empty($earliestDateData['content'])) {
                    $newVraElement = $this->processNewSubelement($omekaRecord,
                                            $omekaElementId,
                                            $datesSubelementObject,
                                            $earliestDateData);
                }
                if (!empty($latestDateData['content'])) {
                    $newVraElement = $this->processNewSubelement($omekaRecord,
                                            $omekaElementId,
                                            $datesSubelementObject,
                                            $latestDateData);
                }
            }
        } else {
            foreach ($subelementsData as $subelementData) {
                if (empty($subelementData['content'])) {
                    continue;
                }

                if (isset($subelementData['vra_parent_id']) && is_numeric($subelementData['vra_parent_id'])) {
                    $parentVraElement = $subelementData['vra_parent_id'];
                }

                $subelementData['name'] = $subelementName;
                $this->processNewSubelement($omekaRecord,
                                            $omekaElementId,
                                            $parentVraElement,
                                            $subelementData);
            }
        }
        }
    }

    protected function processExistingElement($omekaRecord, $omekaElementId, $vraElementId, $elementData)
    {
        //elements to skip deletion. these are containers for other elements, so 
        //content is alway null
        $skipElements = array('Agent',
                              'Date',
                              'dates',
                              'Inscription',
                              'Location',
                              'Rights',
                              'Source',
                              'State Edition',
                              'Subject',
                              'Textref', );
        $vraElementObject = $this->_db->getTable('VraCoreElement')->find($vraElementId);

        //possible that an element was already deleted, so skip if not found
        if (!$vraElementObject) {
            return;
        }
        if ($vraElementObject->name == 'Agent') {
            foreach ($elementData as $key => $subElementData) {
                if (is_numeric($key)) {
                    $this->processNewSubelements($omekaRecord,
                                                 $omekaElementId,
                                                 $subElementData['newSubelements'],
                                                 $vraElementObject);
                }
            }
        }
        if (empty($elementData['content']) &&  !in_array($vraElementObject->name, $skipElements)) {
            $vraElementObject->delete();
        } else {
            if ($vraElementObject->content != $elementData['content']) {
                $vraElementObject->updateDataDate();
            }
            $vraElementObject->content = $elementData['content'];
            $this->processNewSubelements($omekaRecord,
                                         $omekaElementId,
                                         $elementData['newSubelements'],
                                         $vraElementObject);

            //@TODO: reuse the storeElement function if possible, or rename it for clarity
            $vraElementObject->save();
            $this->searchTexts .= ' '.$vraElementObject->content;
            if (!empty($elementData['attrs'])) {
                $this->storeAttributes($elementData['attrs'], $omekaRecord, $omekaElementId, $vraElementId);
            }
        }
    }

    protected function processNewSubelement($omekaRecord, $omekaElementId, $parentVraElement, $elementData)
    {
        //this is the id of the element in Omeka's Elements table
        if (is_numeric($parentVraElement)) {
            $elementData['vra_element_id'] = $parentVraElement;
        } else {
            $elementData['vra_element_id'] = $parentVraElement->id;
        }
        $newVraElement = $this->storeElement($elementData, $omekaRecord, $omekaElementId);
        $this->storeAttributes($elementData['attrs'], $omekaRecord, $omekaElementId, $newVraElement->id);

        return $newVraElement;
    }

    protected function hasNewSubelements($elementData)
    {
        if (empty($elementData['hasSubelements']) || !isset($elementData['newSubelements'])) {
            return false;
        }

        foreach ($elementData['newSubelements'] as $subelementName => $subelementsData) {
            foreach ($subelementsData as $subelementData) {
                if ($subelementName == 'dates') {
                    foreach ($subelementData as $datesData) {
                        if (!empty($datesData['content'])) {
                            return true;
                        }
                    }
                }
                if (!empty($subelementData['content'])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function afterDeleteRecord($args)
    {
        $omekaRecord = $args['record'];
        $recordType = get_class($omekaRecord);
        $recordId = $omekaRecord->id;
        $vraElements = get_db()
                            ->getTable('VraCoreElement')
                            ->findBy(array('record_type' => $recordType, 'record_id' => $recordId));
        foreach ($vraElements as $vraElement) {
            $vraElement->delete();
        }
    }

    public function afterSaveRecord($args)
    {
        $insert = $args['insert'];
        $post = $args['post'];
        $vraElementData = $post['vra-element'];
        $omekaRecord = $args['record'];
        //figure out whether there is a element in the data
        //save element first, so I have the ID
        //and make sure the data posted makes it possible to line up correct
        //attributes with the right subelements, if they're there

        if (!is_array($vraElementData)) {
            return;
        }

        if (isset($vraElementData['recordLevel'])) {
            $this->storeAttributes($vraElementData['recordLevel']['attrs'], $omekaRecord);
            unset($vraElementData['recordLevel']);
        }

        //omekaElementId is the VRA Element id
        foreach ($vraElementData as $omekaElementId => $elementArray) {
            if (isset($elementArray['display'])) {
                $displayAttributes = $elementArray['display'];
                $this->storeAttributes($displayAttributes['attrs'], $omekaRecord, $omekaElementId);
                //force in @dataDate. Assumes display was looked at, and so updates as such
                //lookup existing @dataDate
                if (isset($displayAttributes['is-changed'])) {
                    $attrTable = get_db()->getTable('VraCoreAttribute');
                    $dataDates = $attrTable->findBy(array(
                            'name' => 'dataDate',
                            'record_type' => get_class($omekaRecord),
                            'record_id' => $omekaRecord->id,
                            'element_id' => $omekaElementId,
                            ));
                    if (empty($dataDates)) {
                        $dataDateAttr = new VraCoreAttribute();
                        $dataDateAttr->name = 'dataDate';
                        $dataDateAttr->record_type = get_class($omekaRecord);
                        $dataDateAttr->record_id = $omekaRecord->id;
                        $dataDateAttr->element_id = $omekaElementId;
                    } else {
                        $dataDateAttr = $dataDates[0];
                    }
                    $dataDateAttr->content = date('Y-m-d H:i:s');
                    $dataDateAttr->save();
                }
            }
            //elementArray has keys display, newElements, and existing VRAelement ids
            unset($elementArray['display']);

            if (isset($elementArray['newElements'])) {
                $newElements = $elementArray['newElements'];
                foreach ($newElements as $newElementData) {
                    $this->processNewElement($omekaRecord, $omekaElementId, $newElementData);
                }
                unset($elementArray['newElements']);
            }

            //special handling for agent/dates
            //this handles situation where there is one of an earliestDate or latestDate,
            //but not both for an existing dates element
            if (isset($elementArray['existingDates'])) {
                foreach ($elementArray['existingDates'] as $elementName => $data) {
                    if (!empty($data['content'])) {
                        $newDatesSubelement = new VraCoreElement();
                        $newDatesSubelement->name = $elementName;
                        $newDatesSubelement->record_id = $omekaRecord->id;
                        $newDatesSubelement->record_type = get_class($omekaRecord);
                        $newDatesSubelement->element_id = $omekaElementId;
                        $newDatesSubelement->vra_element_id = $data['dateId'];
                        $newDatesSubelement->content = $data['content'];
                        $newDatesSubelement->save();
                        $newDatesSubelement->updateDataDate();
                        $this->storeAttributes($data['attrs'], $omekaRecord, $omekaElementId, $newDatesSubelement->id);
                    }
                }
                unset($elementArray['existingDates']);
            }

            foreach ($elementArray as $vraElementId => $existingElementData) {
                //see @todo below
                if ($vraElementId != 'notes') {
                    $this->processExistingElement($omekaRecord, $omekaElementId, $vraElementId, $existingElementData);
                }
            }

            //@TODO hunt down the duplication that makes this work here, but not above
            $notes = $elementArray['notes'];
            if (!empty($notes['content'])) {
                $notesObject = $this->_db->getTable('VraCoreElement')->findNotesForRecordElement($omekaRecord, $omekaElementId);
                if (!$notesObject) {
                    $notesObject = new VraCoreElement();
                    $notesObject->record_id = $omekaRecord->id;
                    $notesObject->record_type = get_class($omekaRecord);
                    $notesObject->element_id = $omekaElementId;
                    $notesObject->name = 'notes';
                    $notesObject->save(true);
                    $notesObject->updateDataDate();
                }
                if ($notesObject->content != $notes['content']) {
                    $notesObject->save(true);
                    $notesObject->updateDataDate();
                }
                $notesObject->content = $notes['content'];
                $notesObject->save();
                $this->storeAttributes($notes['attrs'], $omekaRecord, $omekaElementId, $notesObject->id);
            }
        }
        $this->updateSearchTexts($omekaRecord);
    }

    protected function updateSearchTexts($omekaRecord)
    {
        $searchText = $this->_db->getTable('SearchText')->findByRecord(get_class($omekaRecord), $omekaRecord->id);
        if (!$searchText) {
            $searchText = new SearchText();
            $searchText->record_type = get_class($omekaRecord);
            $searchText->record_id = $omekaRecord->id;
            $searchText->public = $omekaRecord->public;
            $searchText->title = metadata($omekaRecord, array('Dublin Core', 'Title'));
        }
        $searchText->text .= ' '.$this->searchTexts;
        $searchText->save();
    }

    protected function installVraCoreElements()
    {
        $VraCoreElementSet = $this->_db->getTable('ElementSet')->findByName('VRA Core');
        if ($VraCoreElementSet) {
            return;
        }

        $elementSetMetadata = array(
            'name' => 'VRA Core',
            'description' => 'VRA Core standard for artistic pieces and cultural heritage artifacts.
                                The first input will be treated as a <display> element. More detailed
                                VRA Core metadata is available below that input.
                             ',
        );
        $elements = array(
            array(
                'name' => 'ID',
                'description' => 'The ID to apply to a VRA Work, Image, or Collection (corresponds to vra id attribute).',
            ),
            array(
                'name' => 'Title',
                'description' => 'The title or identifying phrase given to a Work or an Image.',
            ),
            array(
                'name' => 'Agent',
                'description' => 'The names, appellations, or other identifiers assigned to an individual, group, or corporate body that has contributed to the design, creation, production, manufacture, or alteration of the work or image.',
            ),
            array(
                'name' => 'Cultural Context',
                'description' => 'The name of the culture, people (ethnonym), or adjectival form of a country name fromwhich a Work, Collection, or Image originates, or the cultural context with which the Work, Collection, or Image has been associated.',
            ),
            array(
                'name' => 'Date',
                'description' => 'Date or range of dates associated with the creation, design, production, presentation, performance, construction, or alteration, etc. of the work or image. Dates may be expressed as free text or numerical.  In format yyyy-mm-dd yyyy-mm-dd.',
            ),
            array(
                'name' => 'Description',
                'description' => 'A free-text note about the Work, Collection, or Image, including comments, description, or interpretation, that gives additional information not recorded in other categories.',
            ),
            array(
                'name' => 'Inscription',
                'description' => 'All marks or written words added to the object at the time of production or in its subsequent history, including signatures, dates, dedications, texts, and colophons, as well as marks, such as the stamps of silversmiths, publishers, or printers. ',
            ),
            array(
                'name' => 'Location',
                'description' => 'The geographic location and/or name of the repository, building, site, or other entity whose boundaries include the Work or Image.',
            ),
            array(
                'name' => 'Material',
                'description' => 'The substance of which a work or an image is composed.',
            ),
            array(
                'name' => 'Measurements',
                'description' => 'The physical size, shape, scale, dimensions, or format of the Work or Image. Dimensions may include such measurements as volume, weight, area or running time.',
            ),
            array(
                'name' => 'Relation',
                'description' => 'Terms or phrases describing the identity of the related work and the relationship between the work being cataloged and the related work or image.',
            ),
            array(
                'name' => 'Rights',
                'description' => 'Information about the copyright status and the rights holder for a work, collection, or image',
            ),
            array(
                'name' => 'Source',
                'description' => 'A reference to the source of the information recorded about the work or the image.',
            ),
            array(
                'name' => 'State Edition',
                'description' => 'The identifying number and/or name assigned to the state or edition of a work that exists in more than one form and the placement of that work in the context of prior or later issuances of multiples of the same work.',
            ),
            array(
                'name' => 'Style Period',
                'description' => 'A defined style, historical period, group, school, dynasty, movement, etc. whose characteristics are represented in the Work or Image.',
            ),
            array(
                'name' => 'Subject',
                'description' => 'Terms or phrases that describe, identify, or interpret the Work or Image and what it depicts or expresses. These may include generic terms that describe the work and the elements that it comprises, terms that identify particular people, geographic places, narrative and iconographic themes, or terms that refer to broader concepts or interpretations.',
            ),
            array(
                'name' => 'Technique',
                'description' => 'The production or manufacturing processes, techniques, and methods incorporated in the fabrication or alteration of the work or image.',
            ),
            array(
                'name' => 'Textref',
                'description' => 'Contains the name of a related textual reference and any type of unique identifier that text assigns to a Work or Collection that is independent of any repository.',
            ),
            array(
                'name' => 'Worktype',
                'description' => 'Identifies the specific type of WORK, COLLECTION, or IMAGE being described in the record.',
            ),
        );
        insert_element_set($elementSetMetadata, $elements);
    }
}
