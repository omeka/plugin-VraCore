
<div class='vra-data'>
<?php
$nameBase = "vra-element[{$element->id}]"; 
//new vraelement
//$nameBase[new][0][data][attrs]
//$nameBase[new][0][data][content]
//$nameBase[new][0][data][subelements]


?>

<p>Display Element Attributes</p>

    <?php echo $this->partial('element-attribute-form.php', 
            array(
                 'attributeNames'   => $globalAttributes,
                 'attributeObjects' => $attributeObjects,
                 'nameBase'         => $nameBase
                 )
            ); 
    ?>




<p><?php echo $element->name; ?> Elements</p>
    <div class='vra-element'>
        <textarea name='<?php echo $nameBase; ?>[new][][data][content]' value=''></textarea>
        
    <?php echo $this->partial('element-attribute-form.php', 
            array(
                 'attributeNames'   => $attributeNames,
                 'attributeObjects' => $attributeObjects,
                 'nameBase'         => $nameBase
                 )
            ); 
    ?>
    </div>
    <?php if (!empty($vraElementObjects)): ?>
    <p>Subelements</p>
    <?php foreach($vraElementObjects as $vraElementObject): ?>
        <?php $attributeObjects = $vraElementObject->getAttributes(); ?>
            <p><?php echo $vraElementObject->name; ?>
            <div class='vra-element'>
                <textarea name='<?php echo $nameBase; ?>[new][0][data][content]' value=''></textarea>
                <?php echo $this->partial('element-attribute-form.php', 
                        array(
                             'attributeNames'   => $attributeNames,
                             'attributeObjects' => $attributeObjects,
                             'vraElementObject' => $vraElementObject,
                             'nameBase'         => $nameBase
                             )
                        ); ?>
            </div>    
        
        <?php endforeach; ?>
    <?php endif; ?>

</div>