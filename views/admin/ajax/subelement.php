
<div class='vra-subelement vra-element-inputs new'>
    <textarea name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][content]' value=''></textarea>
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $attributeNames,
                     'attributeObjects' => array(),
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][$newElementCount][newSubelements][$subelementName][$newSubelementCount]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>
