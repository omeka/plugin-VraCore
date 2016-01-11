# plugin-VraCore
Use the VRA Core metadata standard in Omeka

# Installation
Usual Omeka installation procedure. A "VRA Core" Element Set will be installed, as well as tables for VRA Elements and Attributes data.

# Usage
## Simplest settings
If you only want to use the display values (equivalent to the `<display>` element of a VRA `elementSet`), check all the boxes in the configuration form. This will hide all VRA cataloging data from forms and display.

## Medium settings
If you do not want to store or show attribute data, check the "Hide Attributes" configuration option.

## VRA Core XML and Omeka data correspondences

This plugin negotiates the differences between Omeka's data model and the XML-based data model of VRA Core in the following ways.

* The VRA Core Element Set provides the usual Omeka inputs for metadata, exactly parallel to Dublin Core. That "usual" input in Omeka corresponds to the `<display>` element of VRA Core within an `<elementSet>`. All inputs should be considered child elements of a VRA `<elementSet>`. For example, to represent something like

```xml
<titleSet>
  <display>Mona Lisa</display>
  <title type='common'>Mona Lisa</title> 
</titleSet>
```

the data entry would look like

@todo

The various layers of subelements appear as expandable sections. Appropriate attributes also appear under each entry form for a VRA element. `@dataDate` attribute values are added automatically when the data is saved.



