<?php
if (!isset($newSubelementCount)) {
    $newSubelementCount = 0;
}

if (!isset($added)) {
    $classes = '';
} elseif ($added == 'added') {
    $classes = 'added new';
}

if ($classes == '' && empty($datesElementObjects)) {
    $classes = 'new';
}

if (!isset($newAgentIndex) && isset($newElementCount)) {
    $newAgentIndex = $newElementCount;
} else {
    $newAgentIndex = 0;
}
if (!isset($agentId)) {
    $agentId = 'newElements';
}

$attributeNames = array_unique($attributeNames);

?>
<div class='vra-element-inputs vra-subelement dates <?php echo $classes; ?>'>
    <?php if ($classes == 'added new') {
    echo $this->partial('element-attribute-form.php',
                    array(
                         'attributeNames' => array_merge(array('type'), $attributeNames),
                         'attributeObjects' => $attributeObjects,
                         //kind of a cheat. put true at the front to produce a new set of attributes for new element
                         'vraElementObjects' => array(true),
                         'nameBase' => $nameBase."[$agentId][$newAgentIndex][newSubelements][$subelementName][$newSubelementCount]",
                         'label' => __('Dates Attributes'),
                         )
                    );
}
    ?>

    <?php if (empty($datesElementObjects)): ?>
    <div class='vra-subelement vra-element-inputs new'>
        <label><?php echo __('Earliest Date'); ?></label>
        <textarea name='<?php echo $nameBase; ?>[<?php echo $agentId; ?>][<?php echo $newAgentIndex; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][earliestDate][content]'></textarea>
            <?php echo $this->partial('element-attribute-form.php',
                    array(
                         'attributeNames' => array_merge(array('circa'), $attributeNames),
                         'attributeObjects' => $attributeObjects,
                         //kind of a cheat. put true at the front to produce a new set of attributes for new element
                         'vraElementObjects' => array(true),
                         'nameBase' => $nameBase."[$agentId][$newAgentIndex][newSubelements][$subelementName][$newSubelementCount][earliestDate]",
                         'label' => __('Earliest Date Attributes'),
                         )
                    );
            ?>
    </div>
    <div class='vra-subelement vra-element-inputs new'>
    
        <label><?php echo __('Latest Date'); ?></label>
        <textarea name='<?php echo $nameBase; ?>[<?php echo $agentId;?>][<?php echo $newAgentIndex; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][latestDate][content]'></textarea>
            <?php echo $this->partial('element-attribute-form.php',
                    array(
                         'attributeNames' => array_merge(array('circa'), $attributeNames),
                         'attributeObjects' => $attributeObjects,
                         //kind of a cheat. put true at the front to produce a new set of attributes for new element
                         'vraElementObjects' => array(true),
                         'nameBase' => $nameBase."[$agentId][$newAgentIndex][newSubelements][$subelementName][$newSubelementCount][latestDate]",
                         'label' => __('Latest Date Attributes'),
                         )
                    );
            ?>
    </div>
    <?php else: ?>
    <?php foreach ($datesElementObjects as $datesElementObject):?>
        <?php
            $earliestDateObjects = $datesElementObject->getSubelements('earliestDate');
            if (!empty($earliestDateObjects)) {
                $earliestDateObject = $earliestDateObjects[0];
            }
            $latestDateObjects = $datesElementObject->getSubelements('latestDate');
            if (!empty($latestDateObjects)) {
                $latestDateObject = $latestDateObjects[0];
            }
        ?>
        <?php if (isset($earliestDateObject)): ?>
        <div class="vra-subelement vra-element-inputs">
            <label><?php echo __('Earliest Date'); ?></label>
            <textarea name='<?php echo $nameBase; ?>[<?php echo $earliestDateObject->id; ?>][content]'><?php echo metadata($earliestDateObject, 'content'); ?></textarea>
                <?php echo $this->partial('element-attribute-form.php',
                        array(
                             'attributeNames' => array_merge(array('circa'), $attributeNames),
                             'attributeObjects' => $attributeObjects,
                             'vraElementObject' => $earliestDateObject,
                             //kind of a cheat. put true at the front to produce a new set of attributes for new element
                             'vraElementObjects' => array(true),
                             'nameBase' => $nameBase."[{$earliestDateObject->id}]",
                             'label' => __('Earliest Date Attributes'),
                             )
                        );
                ?>
        </div>
        
        <?php else: ?>
        <div class='vra-subelement vra-element-inputs new'>
            <label><?php echo __('Earliest Date'); ?></label>
            <textarea name='<?php echo $nameBase; ?>[existingDates][earliestDate][content]'></textarea>
            <input type='hidden'
                   name='<?php echo $nameBase; ?>[existingDates][earliestDate][dateId]' value='<?php echo $datesElementObject->id; ?>'></input>
                <?php echo $this->partial('element-attribute-form.php',
                        array(
                             'attributeNames' => array_merge(array('circa'), $attributeNames),
                             'attributeObjects' => $attributeObjects,
                             //kind of a cheat. put true at the front to produce a new set of attributes for new element
                             'vraElementObjects' => array(true),
                             'nameBase' => $nameBase.'[existingDates][earliestDate]',
                             'label' => __('Earliest Date Attributes'),
                             )
                        );
                ?>
        </div>
        <?php endif; ?>
        
        <!-- latest dates -->
        <?php if (isset($latestDateObject)): ?>
        <div class='vra-subelement vra-element-inputs'>
            <label><?php echo __('Latest Date'); ?></label>
            <textarea name='<?php echo $nameBase; ?>[<?php echo $latestDateObject->id; ?>][content]'><?php echo metadata($latestDateObject, 'content'); ?></textarea>
                <?php echo $this->partial('element-attribute-form.php',
                        array(
                             'attributeNames' => array_merge(array('circa'), $attributeNames),
                             'attributeObjects' => $attributeObjects,
                             'vraElementObject' => $latestDateObject,
                             //kind of a cheat. put true at the front to produce a new set of attributes for new element
                             'vraElementObjects' => array(true),
                             'nameBase' => $nameBase."[{$latestDateObject->id}]",
                             'label' => __('Latest Date Attributes'),
                             )
                        );
                ?>
        </div>
        <?php else: ?>
        <div class='vra-subelement vra-element-inputs new'>
        
            <label><?php echo __('Latest Date'); ?></label>
            <textarea name='<?php echo $nameBase; ?>[existingDates][latestDate][content]'></textarea>
            <input type='hidden'
                   name='<?php echo $nameBase; ?>[existingDates][latestDate][dateId]' value='<?php echo $datesElementObject->id; ?>'></input>
                <?php echo $this->partial('element-attribute-form.php',
                        array(
                             'attributeNames' => array_merge(array('circa'), $attributeNames),
                             'attributeObjects' => $attributeObjects,
                             //kind of a cheat. put true at the front to produce a new set of attributes for new element
                             'vraElementObjects' => array(true),
                             'nameBase' => $nameBase.'[existingDates][latestDate]',
                             'label' => __('Latest Date Attributes'),
                             )
                        );
                ?>
        </div>
        
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>