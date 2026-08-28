<div class='vra-element vra-element-inputs new added'>
    <textarea class='vra-new' aria-label="<?php echo __('Content %s', $newElementCount); ?>" name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][content]' value=''></textarea>
    <input class='vra-new' type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][name]' value='<?php echo $omekaElementName; ?>'></input>
    <?php
    echo $this->partial('element-attribute-form.php', [
        'attributeNames' => $attributeNames,
        'attributeObjects' => [],
        //kind of a cheat. put true at the front to produce a new set of attributes for new element
        'vraElementObjects' => [true],
        'nameBase' => $nameBase."[newElements][$newElementCount]",
        'label' => __('Attributes (%s)', $newElementCount),
    ]);
    ?>
</div>
