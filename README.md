# plugin-VraCore
Use the VRA Core metadata standard in Omeka.

# Installation
Usual Omeka installation procedure. A "VRA Core" Element Set will be installed, as well as tables for VRA Elements and Attributes data.

# Usage
## Simplest settings
If you only want to use the display values (equivalent to the `<display>` element of a VRA `elementSet`), check all the boxes in the configuration form. This will hide all VRA cataloging data from forms and display.

## More complex settings
You can configure the plugin to note use the full depth of VRA core by hiding element and attribute inputs on the admin side. If you want to add that data, but not display it publicly, check the boxes for public side settings. Finally, since `@dataDate` values are created automatically and can cause clutter, you can hide only those values on the public side.

## VRA Core XML and Omeka data correspondences

This plugin negotiates the differences between Omeka's data model and the XML-based data model of VRA Core in the following ways.

The VRA Core Element Set provides the usual Omeka inputs for metadata, exactly parallel to Dublin Core. That "usual" input in Omeka corresponds to the `<display>` element of VRA Core within an `<{element}Set>`. All inputs should be considered child elements of a VRA `<{element}Set>`. 

In keeping with Omeka's emphasis on public, online, display, the display elements are allowed to contain HTML (which is typically not allowed in a VRA Core XML document). 

Non-display VRA elements are handled separately, and should not contain HTML. Inputs corresponding to those elements are provided below the display input.

For example, to represent something like

```xml
<titleSet>
  <display>Mona Lisa</display>
  <title type='common'>Mona Lisa</title> 
</titleSet>
```

the data entry would look like

![VRA Title Example](/../readme-screenshots/readme-screenshots/vraTitleExample.png?raw=true "VRA Title Example")

The various layers of subelements appear as expandable sections. Appropriate attributes also appear under each entry form for a VRA element. `@dataDate` attribute values are added automatically when the data is saved.

This system applies to Omeka Items, Collections, and Files, which could be used to correspond to VRA Core Works, Collections, and Images.

# Developer Gotchas

To display the VRA data, the plugin replaces Omeka's usual `record-metadata.php` template. If your theme overrides the default `record-metadata.php` template, you will want to adjust the file in your theme to include the added 'elements-show' hook:

```phtml
    <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
        <h3><?php echo html_escape(__($elementName)); ?></h3>
        <?php foreach ($elementInfo['texts'] as $text): ?>
            <div class="element-text"><?php echo $text; ?></div>
        <?php endforeach; ?>
        <?php fire_plugin_hook('elements_show',
                                array('view' => $this,
                                     'elementInfo' => $elementInfo,
                                     'record' => $record
                                     )
                               );
        ?>
    </div><!-- end element -->


```

If your theme does not contain a `common/record-metadata.php` file, there is no need to make changes.

# Importing data via CSV

With the VRA Core plugin installed and activated, use the CSV Importer plugin to import display data from your CSV file. Non-display data cannot be imported, and data cannot be imported from VRA Core XML files.
