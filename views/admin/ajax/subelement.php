<?php if ($subelementName == 'dates'): ?>


    <?php
        //somehow type is getting added twice, so clear it out here
        if (($key = array_search('type', $attributeNames)) !== false) {
            unset($attributeNames[$key]);
        }
    ?>

    <?php echo $this->partial('dates-form.php',
                          array(
                              'elementsData' => $this->elementsData,
                              'subelementsData' => $this->subelementsData,
                              'subelementName' => $subelementName,
                              'nameBase' => $nameBase,
                              'agentId' => $vraParentId,
                              'vraElementObjects' => array(true),
                              'added' => 'added',
                              'newAgentIndex' => $newAgentIndex,
                              'newSubelementCount' => $newSubelementCount,
                                //@todo : how many of these are still actually used?
                              'globalAttributes' => $this->globalAttrs,
                              'attributeNames' => $attributeNames,
                              'attributeObjects' => array(),
                        )
            );
    ?>

<?php else: ?>


<?php
    switch ($subelementName) {
        case 'earliestDate':
            $subelementLabel = __('Earliest Date');
        break;
        case 'latestDate':
            $subelementLabel = __('Latest Date');
        break;
        default:
            $subelementLabel = ucfirst(__($subelementName));
        break;
    }
?>

<div class='vra-subelement vra-element-inputs new added'>
    <textarea class='vra-new' name='<?php echo $nameBase; ?>[<?php echo $vraParentId; ?>][newSubelements][<?php echo $subelementName; ?>][<?php echo $newSubelementCount; ?>][content]'></textarea>
        <?php echo $this->partial('element-attribute-form.php',
                array(
                     'attributeNames' => $attributeNames,
                     'attributeObjects' => array(),
                     //kind of a cheat. put true at the front to produce a new set of attributes for new element
                     'vraElementObjects' => array(true),
                     'nameBase' => $nameBase."[$vraParentId][newSubelements][$subelementName][$newSubelementCount]",
                     'label' => __('%s Attributes', $subelementLabel),
                     )
                );
        ?>
</div>
<?php endif;?>
