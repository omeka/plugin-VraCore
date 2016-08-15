<?php
$db = get_db();
$recordType = get_class($record);
$vraElements = $db->getTable('VraCoreElement')->findBy(array('record_type' => $recordType,
                                                'record_id' => $record->id,
                                                'vra_element_id' => false,
                                    ));

$recordAttributes = $db->getTable('VraCoreAttribute')->findBy(array('record_type' => $recordType,
                                                'record_id' => $record->id,
                                                'vra_element_id' => false,
                                                'element_id' => false,
                                    ));

switch ($recordType) {
    case 'Item':
        $xmlElement = 'work';
    break;

    case 'Collection':
        $xmlElement = 'collection';
    break;

    case 'File':
        $xmlElement = 'image';
    break;
}

$recordHref = record_url($record, 'show', true);
$recordId = $xmlElement.'_'.$record->id;
$recordRefid = $record->id;

//id, href, and refid ID get to have defaults on output
//so handle those specially. Others just line up in a string
$recordAttributesHtml = ' ';
foreach ($recordAttributes as $vraAttribute) {
    switch ($vraAttribute->name) {
        case 'href':
            $recordHref = $vraAttribute->content;
        break;

        case 'id':
            $recordId = $vraAttribute->content;
        break;

        case 'refid':
            $recordRefid = $vraAttribute->content;
        break;

        default:
            $recordAttributesHtml .= " {$vraAttribute->name}='{$vraAttribute->content}' ";
        break;
    }
}

$vraElementSets = array();
$vraNotes = array();
foreach ($vraElements as $vraElement) {
    $elementKey = $vraElement->name;
    //$elName = lcfirst($elName); this gives a whitescreen of death. no idea why
    if ($vraElement->name == 'notes') {
        $vraNotes[$vraElement->element_id] = $vraElement;
    } else {
        $vraElementSets[$elementKey][] = $vraElement;
    }
}

//generate existing implicit relations, ie between collections and items, and items and files
if (isset($relation)) {
    if (!isset($vraElementSets['Relation'])) {
        $vraElementSets['Relation'] = array();
    }
    $webRoot = WEB_ROOT; //srsly?
    $relationHtml = "<relation type='{$relation['type']}' source='$webRoot' refid='{$relation['refid']}'></relation>";
    $vraElementSets['Relation'][] = $relationHtml;
}

//force in a key when a display value is recorded
$elementData = array(
            'Title',
            'Agent',
            'Cultural Context',
            'Date',
            'Description',
            'Inscription',
            'Location',
            'Material',
            'Measurements',
            'Relation',
            'Rights',
            'Source',
            'State Edition',
            'Style Period',
            'Subject',
            'Technique',
            'Textref',
            'Worktype',
        );

foreach ($elementData as $elementKey) {
    if (!empty(metadata($record, array('VRA Core', $elementKey))) &&
         !isset($vraElementSets[$elementKey])) {
        $vraElementSets[$elementKey] = array();
    }
}

ksort($vraElementSets);
?>

<<?php echo $xmlElement; ?> href='<?php echo $recordHref; ?>'
       id='<?php echo $recordId; ?>'
       <?php echo $recordAttributesHtml; ?>
>
    <?php foreach ($vraElementSets as $elementKey => $vraElementSet): ?>
        <?php $elementName = str_replace(' ', '', $elementKey); ?>
        <<?php echo lcfirst($elementName).'Set'; ?>>
            <?php
                    //wonky workaround to match notes by vra element id
                    //yes, I've done things I'm not proud of
                    if (isset($vraElementSet[0])) {
                        $sampleElement = $vraElementSet[0];
                        if (is_object($sampleElement)) {
                            $currentVraElementId = $sampleElement->element_id;
                        } else {
                            $currentVraElementId = 'no';
                        }
                    } else {
                        $currentVraElementId = 'no';
                    }

            ?>
            <display><?php echo metadata($record, array('VRA Core', $elementKey)); ?></display>
            <?php if (isset($vraNotes[$currentVraElementId])): ?>
                <?php $currentVraNotes = $vraNotes[$currentVraElementId]; ?>
            <notes <?php echo $currentVraNotes->getAttributesAsHtml(); ?>  ><?php echo $currentVraNotes->content; ?></notes>
            <?php endif;?>
            <?php foreach ($vraElementSet as $vraElement): ?>
                <?php if (is_string($vraElement)): ?>
                    <?php echo $vraElement; ?>
                <?php else: ?>
                <?php $subelements = $vraElement->getSubelements(); ?>
                <?php if (count($subelements) == 0): ?>
                <<?php echo lcfirst($elementName); ?><?php echo $vraElement->getAttributesAsHtml(); ?>><?php echo $vraElement->content; ?></<?php echo lcfirst($elementName); ?>>
                <?php else: ?>
                <<?php echo lcfirst($elementName); ?><?php echo $vraElement->getAttributesAsHtml(); ?>>
                    <?php foreach ($subelements as $subelement): ?>
                        <?php if ($subelement->name == 'dates'): ?>
                            <?php 
                                $subDatesElements = $subelement->getSubelements();
                            ?>
                        <<?php echo $subelement->name; echo $subelement->getAttributesAsHtml(); ?>><?php echo $subelement->content; ?>
                            <?php foreach ($subDatesElements as $subDatesElement): ?>
                                <<?php echo $subDatesElement->name; echo $subDatesElement->getAttributesAsHtml(); ?>><?php echo $subDatesElement->content; ?></<?php echo $subDatesElement->name; ?>>
                            <?php endforeach;?>
                        </<?php echo $subelement->name; ?>>
                        <?php else: ?>
                        <<?php echo $subelement->name; echo $subelement->getAttributesAsHtml(); ?>><?php echo $subelement->content; ?></<?php echo $subelement->name; ?>>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </<?php echo lcfirst($elementName); ?>>
                <?php endif;?>
                <?php endif; ?>
            <?php endforeach; ?>
        </<?php echo lcfirst($elementName).'Set'; ?>>
    <?php endforeach;?>
</<?php echo $xmlElement; ?>>

<?php
if ($recordType == 'Item') {
    $idAttributes = $db->getTable('VraCoreAttribute')->findBy(array('record_type' => 'Item',
                                                'record_id' => $record->id,
                                                'name' => 'id',
                                                'vra_element_id' => false,
                                                'element_id' => false,
                                    ));

    if (!empty($idAttributes)) {
        $idAttribute = $idAttributes[0];
        $refid = $idAttribute->content;
    } else {
        $refid = 'work_'.$record->id;
    }

    foreach ($record->Files as $file) {
        echo $this->partial('record-xml-partial.php',
            array(
                    'record' => $file,
                    'relation' => array('type' => 'imageOf',
                                         'refid' => $refid,
                                        ),
                 )
            );
    }
}

?>

<?php
if ($recordType == 'Collection') {
    $idAttributes = $db->getTable('VraCoreAttribute')->findBy(array('record_type' => 'Collection',
                                                'record_id' => $record->id,
                                                'name' => 'id',
                                                'vra_element_id' => false,
                                                'element_id' => false,
                                    ));

    if (!empty($idAttributes)) {
        $idAttribute = $idAttributes[0];
        $refid = $idAttribute->content;
    } else {
        $refid = 'collection_'.$record->id;
    }

    $items = $db->getTable('Item')->findBy(array('collection' => $record));
    foreach ($items as $item) {
        echo $this->partial('record-xml-partial.php',
            array(
                    'record' => $item,
                    'relation' => array('type' => 'partOf',
                                         'refid' => $refid,
                                        ),
                 )
            );
    }
}

?>



