
<div class='vra-data'>
<?php

echo $this->partial('attribute-edit-form.php',
            array('element'          => $element,
                  'record'           => $record,
                  'elementsData'     => $elementsData,
                  'subelementsData'  => $subelementsData,
                  'globalAttributes' => $globalAttributes,
                  "attributeValues"  => $attributeValues
            ));
?>

<?php if(isset($elementsData[$element->name]['subelements'])): ?>
    <h3>Subelements</h3>
    <div class='vra-subelements'>
    <?php foreach($elementsData[$element->name]['subelements'] as $subelement ):?>
    
        <h3><?php echo $subelement?></h3>
        <div class='vra-subelement'>
            <textarea name='vra-subelement[<?php echo $element->id; ?>][<?php echo $subelement; ?>][0][content]'></textarea>
            <?php
            
            echo $this->partial('attribute-edit-form.php',
                        array('element'          => $element,
                              'record'           => $record,
                              'elementsData'     => $elementsData,
                              'subelementsData'  => $subelementsData,
                              'globalAttributes' => $globalAttributes,
                              'attributeValues'  => $attributeValues,
                              'subelement'       => $subelement
                        ));
            ?>
        </div>
    
        <?php foreach($elementsData[$element->name]['subelementObjects'] as $subelementObj ):?>
        <div class='vra-subelement'>
            <textarea name='vra-subelement[<?php echo $element->id; ?>][<?php echo $subelement; ?>][<?php echo $subelementObj->id; ?>][content]'></textarea>
            <?php
            
            echo $this->partial('attribute-edit-form.php',
                        array('element'          => $element,
                              'record'           => $record,
                              'elementsData'     => $elementsData,
                              'subelementsData'  => $subelementsData,
                              'globalAttributes' => $globalAttributes,
                              'attributeValues'  => $attributeValues,
                              'subelement'       => $subelementObj
                        ));
            ?>
        </div>    
        <?php endforeach; ?>
    
    <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>