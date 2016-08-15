<?php
//check if public side, and if attributes should be shown
if (is_admin_theme()) {
    $showAttributes = true;
} elseif (get_option('vra-core-hide-public-attributes')) {
    $showAttributes = false;
} else {
    $showAttributes = true;
}

?>
<div class='vra-subelements'>
    <?php
    echo $this->partial('vra-core-attributes.php', array(
            'showAttributes' => $showAttributes,
            'attributes' => $attributes,
         )
    );
    ?>

    <?php if (isset($groupedElements['notes'])): ?>
        <?php $notesElement = $groupedElements['notes'][0]; ?>
        <?php
        echo $this->partial('vra-core-element.php', array(
                'element' => $notesElement,
                'elementName' => __('Notes'),
                'subelements' => false,
                'showAttributes' => $showAttributes,
             )
        );
        ?>
        <?php unset($groupedElements['notes']); ?>
    <?php endif;?>

    <?php if (count($groupedElements) != 0): ?>
        <h4>Elements</h4>
        <?php foreach ($groupedElements as $name => $elements): ?>
            <?php foreach ($elements as $element): ?>
                <?php $subelements = $element->getSubelements(); ?>
                <?php if (empty($subelements)): ?>
                    <?php $subelements = false; ?>
                <?php endif; ?>
                <?php
                echo $this->partial('vra-core-element.php', array(
                        'element' => $element,
                        'elementName' => $name,
                        'subelements' => $subelements,
                        'showAttributes' => $showAttributes,
                     )
                );
                ?>
            <?php endforeach; ?>
        <?php endforeach;?>
    <?php endif; ?>
</div>