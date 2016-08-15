<?php if ($showAttributes && (count($attributes) > 0)): ?>
<?php $hideDataDate = (boolean) get_option('vra-core-hide-public-datadate'); ?>
<ul class='vra-core-attributes'>
<?php foreach ($attributes as $attribute): ?>
    <?php
        switch ($attribute->name) {
            case 'href':
                $href = metadata($attribute, 'content');
                $value = "<a href='$href'>$href</a>";
            break;

            case 'dataDate':
                if ($hideDataDate) {
                    continue 2;
                } else {
                    $value = metadata($attribute, 'content');
                }
            break;

            default:
                $value = metadata($attribute, 'content');
            break;
        }

    ?>
    <li><span class='vra-core-attribute-name'>@<?php echo metadata($attribute, 'name'); ?></span>
        <?php echo $value; ?>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
