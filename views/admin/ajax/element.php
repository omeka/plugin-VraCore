<div class='vra-element-inputs new'>

    <textarea name='<?php echo $nameBase; ?>[newElements][0][content]' value=''></textarea>
    <input type='hidden' name='<?php echo $nameBase; ?>[newElements][0][name]' value='<?php echo $omekaElementName; ?>'></input>
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $attributeNames,
                     'attributeObjects' => array(),
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][0]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>
