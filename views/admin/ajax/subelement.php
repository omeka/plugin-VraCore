
<div class='vra-subelement vra-element-inputs new'>
    <textarea name='<?php echo $nameBase; ?>[newElements][0][newSubelements][<?php echo $subelementName; ?>][0][content]' value=''></textarea>
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $attributeNames,
                     'attributeObjects' => array(),
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][0][newSubelements][$subelementName][0]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>
