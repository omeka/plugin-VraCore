<?php
$db = get_db();
$recordType = get_class($record);
$vraElements = $db->getTable('VraCoreElement')->findBy(array('record_type' => $recordType,
                                                'record_id'   => $record->id,
                                                'vra_element_id' => false,
                                    ));

$recordAttributes = $db->getTable('VraCoreAttribute')->findBy(array('record_type' => $recordType,
                                                'record_id'   => $record->id,
                                                'vra_element_id' => false,
                                                'element_id' => false,
                                    ));

switch($recordType) {
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
$recordId = $xmlElement . "_" . $record->id;
$recordRefid = $record->id;

//id, href, and refid ID get to have defaults on output
//so handle those specially. Others just line up in a string
$recordAttributesHtml = ' ';
foreach($recordAttributes as $vraAttribute) {
    switch($vraAttribute->name) {
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
foreach($vraElements as $vraElement) {
    $elementKey = $vraElement->name;
    //$elName = lcfirst($elName); this gives a whitescreen of death. no idea why
    if($vraElement->name == 'notes') {
        $vraNotes[$vraElement->element_id] = $vraElement;
    } else {
        $vraElementSets[$elementKey][] = $vraElement;
    }

}
ksort($vraElementSets);
?>

    <<?php echo $xmlElement; ?> href='<?php echo $recordHref; ?>'
           id='<?php echo $recordId; ?>'
           refid='<?php echo $recordRefid; ?>'
           <?php echo $recordAttributesHtml; ?>
   >
        <?php foreach($vraElementSets as $elementKey => $vraElementSet): ?>
            <<?php echo lcfirst($elementKey) . 'Set'; ?>>
                <?php
                        //wonky workaround to match notes by vra element id
                        //yes, I've done things I'm not proud of
                        $sampleElement = $vraElementSet[0];
                        $currentVraElementId = $sampleElement->element_id;
                ?>
                <display><?php echo metadata($record, array('VRA Core', $elementKey)); ?></display>
                <?php if(isset($vraNotes[$currentVraElementId])): ?>
                    <?php $currentVraNotes = $vraNotes[$currentVraElementId]; ?>
                <notes><?php echo $currentVraNotes->content; ?></notes>
                <?php endif;?>
                <?php foreach($vraElementSet as $vraElement): ?>
                    <?php $subelements = $vraElement->getSubelements(); ?>
                    <?php if (count($subelements) == 0): ?>
                    <<?php echo lcfirst($elementKey); ?><?php echo $vraElement->getAttributesAsHtml(); ?>><?php echo $vraElement->content; ?></<?php echo lcfirst($elementKey); ?>>
                    <?php else: ?>
                    <<?php echo lcfirst($elementKey); ?><?php echo $vraElement->getAttributesAsHtml(); ?>>
                        <?php foreach($subelements as $subelement): ?>
                            <?php if($subelement->name == 'dates'): ?>
                                <?php 
                                    $subDatesElements = $subelement->getSubelements();
                                ?>
                            <<?php echo $subelement->name; echo $subelement->getAttributesAsHtml(); ?>><?php echo $subelement->content; ?>
                                <?php foreach($subDatesElements as $subDatesElement): ?>
                                    <<?php echo $subDatesElement->name; echo $subDatesElement->getAttributesAsHtml(); ?>><?php echo $subDatesElement->content; ?></<?php echo $subDatesElement->name; ?>>
                                <?php endforeach;?>
                            </<?php echo $subelement->name; ?>>
                            <?php else: ?>
                            <<?php echo $subelement->name; echo $subelement->getAttributesAsHtml(); ?>><?php echo $subelement->content; ?></<?php echo $subelement->name; ?>>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </<?php echo lcfirst($elementKey); ?>>
                    <?php endif;?>
                <?php endforeach; ?>
            </<?php echo lcfirst($elementKey) . 'Set'; ?>>
        <?php endforeach;?>
    </<?php echo $xmlElement; ?>>
