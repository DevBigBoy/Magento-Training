In Magento 2, you can disable observers from other modules using several approaches. Here are the most effective methods:


Replace `observer_name_to_disable` with the actual observer name you want to disable.

## Using events.xml

Create an `events.xml` file in your module's `etc` directory (or `etc/frontend` or `etc/adminhtml` for specific areas):

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Event/etc/events.xsd">
    <event name="event_name">
        <observer name="observer_name_to_disable" disabled="true" />
    </event>
</config>
```





## Finding Observer Names

To find the observer name you want to disable, check the target module's `events.xml` file or search in the codebase. Observer names are typically defined in the `name` attribute of the `<observer>` tag.

The most common and cleanest approach is **Method 2** using `events.xml` with the `disabled="true"` attribute, as it's the most straightforward and doesn't require creating additional classes.

Remember to clear cache and recompile after making these changes:
```bash
php bin/magento cache:clean
php bin/magento setup:di:compile
```
