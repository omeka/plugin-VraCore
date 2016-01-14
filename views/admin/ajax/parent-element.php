<div class='vra-element new added'>
    <div class='vra-element-header'>
        <div class='drawer closed'></div><label class='vra-elements-label'><?php echo $omekaElementName . ' ' . __('Subelements'); ?></label>
    </div>
    <fieldset style='display: none;'>
        <input type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][name]' value='<?php echo $omekaElementName; ?>'></input>
        <input type='hidden' name='<?php echo $nameBase; ?>[newElements][<?php echo $newElementCount; ?>][hasSubelements]' value='1'></input>
        <div class='vra-subelements'>
        <?php foreach($elementsData[$omekaElementName]['subelements'] as $subelementName): ?>
            <?php debug($subelementName); ?>
            
            <?php if($subelementName == 'dates'): ?>
            
            <div class='vra-subelement-container'>
                <div class='vra-element-header'>
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                </div>
            
                <fieldset style='display:none'>
                    <input class='subelement-add' type='submit' value='Add Dates Element' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElement->name; ?>'></input>
                    <?php echo $this->partial('element-attribute-form.php', 
                            array(
                                 'attributeNames'   => $attributeNames,
                                 //kind of a cheat. put true at the front to produce a new set of attributes for new element
                                 'vraElementObjects' => array(true),
                                 'nameBase'         => $nameBase . "[newElements][0][newSubelements][$subelementName][0]",
                                 'label'            => __('Attributes')
                                 )
                            );
                    ?>
                <?php
                        echo $this->partial('dates-form.php',
                            array(
                                  'elementsData'     => $this->elementsData,
                                  'subelementsData'  => $this->subelementsData,
                                  'subelementName'   => $subelementName,
                                  'nameBase'         => $nameBase,
                                    //@todo : how many of these are still actually used?
                                  'globalAttributes' => $this->globalAttrs,
                                  'attributeNames'    => $attributeNames,
                                  'attributeObjects' => array()
                            )
                        );
                ?>
                </fieldset>
            </div>
            
            
            
            
            
            
            
            
            
            <?php else: ?>
            <div class='vra-subelement-container'>
                <div class='vra-element-header'>
                    <div class='drawer closed'></div><label class='vra-subelement-label'><?php echo $subelementName; ?></label>
                </div>
                <fieldset style='display: none'>
                    <input class='subelement-add' type='submit' value='Add Input' data-namebase='<?php echo $nameBase; ?>' data-subelement-name='<?php echo $subelementName; ?>' data-omeka-element-name='<?php echo $omekaElementName; ?>'></input>
                    <div class='vra-subelement vra-element-inputs new'>
                        
                        <?php
                            if (isset($subelementsData[$subelementName])) {
                                $attributeNames = array_merge($subelementsData[$subelementName]['attrs'], $globalAttrs);
                            } else {
                                $attributeNames = $globalAttrs;
                            }
                        ?>
                        
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
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </fieldset>
</div>