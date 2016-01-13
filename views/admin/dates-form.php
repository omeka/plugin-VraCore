<?php 
if (! isset($newSubelementCount)) {
    $newSubelementCount = 0;
}

//dig up existing data

//roll through vraeelments to get dates elements
//then roll through again to again to align earliestDate and latestDate with those
//display accordingly
?>

<div class='vra-subelement vra-element-inputs new'>
    <label><?php echo __('Earliest Date'); ?></label>
    <textarea name='<?php echo $nameBase; ?>[newElements][<?php echo $newSubelementCount; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][earliestDate][content]' value=''></textarea>
        
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => array_merge(array('circa'), $attributeNames),
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][$newSubelementCount][newSubelements][$subelementName][$newSubelementCount][earliestDate]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>
<div class='vra-subelement vra-element-inputs new'>
    <label><?php echo __('Latest Date'); ?></label>
    <textarea name='<?php echo $nameBase; ?>[newElements][<?php echo $newSubelementCount; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][latestDate][content]' value=''></textarea>

        
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => array_merge(array('circa'), $attributeNames),
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][$newSubelementCount][newSubelements][$subelementName][$newSubelementCount][latestDate]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>


<?php

foreach($datesElementObjects as $datesElementObject):?>
<?php 
$earliestDateObject = $datesElementObject->getSubelements('earliestDate')[0];
$latestDateObject = $datesElementObject->getSubelements('latestDate')[0];
?>
<div class='vra-subelement vra-element-inputs'>
    <label><?php echo __('Earliest Date'); ?></label>
    <textarea name='<?php echo $nameBase; ?>[newElements][<?php echo $newSubelementCount; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][earliestDate][content]' value='<?php echo $earliestDateObject->content; ?>'><?php echo $earliestDateObject->content; ?></textarea>
        
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => array_merge(array('circa'), $attributeNames),
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][$newSubelementCount][newSubelements][$subelementName][$newSubelementCount][earliestDate]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>

<div class='vra-subelement vra-element-inputs'>
    <label><?php echo __('Latest Date'); ?></label>
    <textarea name='<?php echo $nameBase; ?>[newElements][<?php echo $newSubelementCount; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][latestDate][content]' value='<?php echo $latestDateObject->content; ?>'><?php echo $latestDateObject->content; ?></textarea>

        
        <?php echo $this->partial('element-attribute-form.php', 
                array(
                     'attributeNames'   => array_merge(array('circa'), $attributeNames),
                     'attributeObjects' => $attributeObjects,
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase'         => $nameBase . "[newElements][$newSubelementCount][newSubelements][$subelementName][$newSubelementCount][latestDate]",
                     'label'            => __('Attributes')
                     )
                );
        ?>
</div>





<?php endforeach; ?>
