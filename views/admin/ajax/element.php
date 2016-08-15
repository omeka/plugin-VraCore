<div class='vra-element-inputs new added'>
    <textarea class='vra-new' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][content]' value=''></textarea>
    <input class='vra-new' type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][name]' value='<?php echo $omekaElementName; ?>'></input>
        <?php echo $this->partial('element-attribute-form.php',
                array(
                     'attributeNames' => $attributeNames,
                     'attributeObjects' => array(),
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase' => $nameBase."[newElements][$newElementCount]",
                     'label' => __('Attributes'),
                     )
                );
        ?>
</div>
