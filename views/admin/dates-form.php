<div class='vra-subelement vra-element-inputs new'>
    <label><?php echo __('Earliest Date'); ?></label>
    <textarea name='<?php echo $nameBase; ?>[newElements][0][newSubelements][<?php echo $subelementName; ?>][0][earliestDate][content]' value=''></textarea>

        
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => array_merge(array('circa'), $attributeNames),
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][0][newSubelements][$subelementName][0][earliestDate]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>
<div class='vra-subelement vra-element-inputs new'>
    <label><?php echo __('Latest Date'); ?></label>
    <textarea name='<?php echo $nameBase; ?>[newElements][0][newSubelements][<?php echo $subelementName; ?>][0][latestDate][content]' value=''></textarea>

        
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => array_merge(array('circa'), $attributeNames),
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][0][newSubelements][$subelementName][0][latestDate]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>
