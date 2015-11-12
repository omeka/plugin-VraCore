
<div class='vra-data'>
<?php
// attributes for the vra:display for the {element}Set
echo $this->partial('attribute-edit-form.php',
            array('element'          => $element,
                  'record'           => $record,
                  'elementsData'     => $elementsData,
                  'subelementsData'  => $subelementsData,
                  'globalAttributes' => $globalAttributes,
                  "attributeValues"  => $attributeValues
            ));
?>
<?php $vraElementName = lcfirst($element->name); ?>
<?php 
$nameBase = "vra-element[{$element->id}]"; 
//new vraelement
//$nameBase[new][0][data][attrs]
//$nameBase[new][0][data][content]
//$nameBase[new][0][data][subelements]


//$nameBase[

//existing vraelement
//$nameBase[$vraElementId]


?>
<h2><?php echo $element->name; ?> Elements</h2>
<div class='vra-element'>
    <textarea name='<?php echo $nameBase; ?>[new][0][data][content]' value=''></textarea>
    <div class='vra-attributes'>
    
    
    </div>
</div>

<?php if(isset($elementsData[$element->name]['subelements'])): ?>
<?php //if(false): ?>
    <h3>Subelements</h3>
    <div class='vra-subelements'>
    <?php foreach($elementsData[$element->name]['subelements'] as $subelement ):?>
    
        <h3><?php echo $subelement?></h3>
        <div class='vra-subelement'>
            <textarea name='vra-subelement[<?php echo $element->id; ?>][<?php echo $subelement; ?>][new][][content]'></textarea>
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