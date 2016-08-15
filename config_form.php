<div class="field">
    <div id="vra-core-ignore-attributes" class="two columns alpha">
        <label for="vra-core-ignore-attributes"><?php echo __('Skip VRA Core attributes on admin side?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'If checked, VRA input forms will only ask for a display value and element values, not attributes.'
        ); ?></p>
        <?php echo get_view()->formCheckbox('vra-core-ignore-attributes', true,
        array('checked' => (boolean) get_option('vra-core-ignore-attributes'))); ?>
    </div>
</div>

<div class="field">
    <div id="vra-core-ignore-elements" class="two columns alpha">
        <label for="vra-core-ignore-elements"><?php echo __('Skip VRA Core elements on admin side?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'If checked, VRA input forms will only ask for a display value, not members of a VRA Element Set.'
        ); ?></p>
        <?php echo get_view()->formCheckbox('vra-core-ignore-elements', true,
        array('checked' => (boolean) get_option('vra-core-ignore-elements'))); ?>
    </div>
</div>

<div class="field">
    <div id="vra-core-hide-public-details" class="two columns alpha">
        <label for="vra-core-hide-public-details"><?php echo __('Hide VRA Core details on public side?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'If checked, only VRA Core display data will be shown publicly.'
        ); ?></p>
        <?php echo get_view()->formCheckbox('vra-core-hide-public-details', true,
        array('checked' => (boolean) get_option('vra-core-hide-public-details'))); ?>
    </div>
</div>

<div class="field">
    <div id="vra-core-hide-public-attributes" class="two columns alpha">
        <label for="vra-core-hide-public-attributes"><?php echo __('Hide VRA Core attributes on public side?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'If checked, and the details setting above is not checked, the VRA Core attribute data will not be displayed publicly.'
        ); ?></p>
        <?php echo get_view()->formCheckbox('vra-core-hide-public-attributes', true,
        array('checked' => (boolean) get_option('vra-core-hide-public-attributes'))); ?>
    </div>
</div>

<div class="field">
    <div id="vra-core-hide-public-datadate" class="two columns alpha">
        <label for="vra-core-hide-public-datadate"><?php echo __('Hide VRA Core dataDate attribute on public side?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'If checked, and the details setting above is not checked, the VRA Core dataDate attribute will not be displayed publicly.'
        ); ?></p>
        <?php echo get_view()->formCheckbox('vra-core-hide-public-datadate', true,
        array('checked' => (boolean) get_option('vra-core-hide-public-datadate'))); ?>
    </div>
</div>
