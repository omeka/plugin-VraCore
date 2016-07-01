<?php
$db = get_db();
$vraElements = $db->getTable('VraCoreElement')->findBy(array('record_type' => 'Collection',
                                                'record_id'   => $collection->id,
                                                'vra_element_id' => false,
                                    ));

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
<vra xmlns="http://www.vraweb.org/vracore4.htm"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.vraweb.org/vracore4.htm http://www.loc.gov/standards/vracore/vra.xsd">

    <work>
        <?php foreach($vraElementSets as $elementKey => $vraElementSet): ?>
            <<?php echo lcfirst($elementKey) . 'Set'; ?>>
                <?php
                        //wonky workaround to match notes by vra element id
                        //yes, I've done things I'm not proud of
                        $sampleElement = $vraElementSet[0];
                        $currentVraElementId = $sampleElement->element_id;
                ?>
                <display><?php echo metadata($collection, array('VRA Core', $elementKey)); ?></display>
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
    </work>
</vra>