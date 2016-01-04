<div class='vra-element'>
    <div class='vra-element-header'>
        <div class='drawer closed'></div><label class='vra-elements-label'><?php echo $omekaElementName . ' ' . __('Subelements'); ?></label>
    </div>
    <fieldset style='display: none;'>
        <input type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][name]' value='<?php echo $omekaElementName; ?>'></input>
        <input type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][hasSubelements]' value='1'></input>
        <div class='vra-subelements'>
        <?php foreach($elementsData[$omekaElementName]['subelements'] as $subelementName): ?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header'>
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                </div>
                <fieldset style='display: none'>
                    <input class='subelement-add' type='submit' value='Add Input' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElementName; ?>'></input>
                    <div class='vra-subelement vra-element-inputs new'>
                        <textarea name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][newSubelements][<?php echo $subelementName; ?>][0][content]' value=''></textarea>
                            <?php echo $this->partial('element-attribute-form.php', 
                                    array(
                                         'attributeNames'   => $attributeNames,
                                         'attributeObjects' => array(),
                                         //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                         'vraElementObjects' => array(true),
                                         'nameBase'         => $nameBase . "[newElements][$newElementCount][newSubelements][$subelementName][0]",
                                         'label'            => __('Attributes')
                                         )
                                    );
                            ?>
                    </div>
                </fieldset>
            </div>
        <?php endforeach; ?>
        </div>
    </fieldset>
</div>