<?php if ($subelementName == 'dates'): ?>

    <?php echo $this->partial('dates-form.php',
                          array(
                              
                              'elementsData'     => $this->elementsData,
                              'subelementsData'  => $this->subelementsData,
                              'subelementName'   => $subelementName,
                              'nameBase'         => $nameBase,
                              'vraElementObjects' => array(true),
                                //@todo : how many of these are still actually used?
                              'globalAttributes' => $this->globalAttrs,
                              'attributeNames'    => $attributeNames,
                              
                              'attributeObjects'  => array()
                              
                        )
            );
    
    ?>

<?php else: ?>
<div class='vra-subelement vra-element-inputs new'>
    <textarea name='<?php echo $nameBase; ?>[newElements][<?php echo $newSubelementCount; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][content]' value=''></textarea>
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => $attributeNames,
                     'attributeObjects' => array(),
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][$newSubelementCount][newSubelements][$subelementName][$newSubelementCount]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>
<?php endif;?>
