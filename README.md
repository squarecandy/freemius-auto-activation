# freemius-auto-activation

Enables entering Freemius plugin activation codes via wp-config.php

## Get started

1. Install and activate this plugin

2. Locate the "shortcode" of the plugin you want to activate. This is different from the standard slug for the plugin or the slug that will be found in the database and freemius database settings in the options table. It may be easiest to ask the plugin developer, but you can also look through the plugin code for a function that looks like this: `function my_prefix_fs() { global $my_prefix_fs;`. In this case the shortcode is `my_prefix_fs`. The function typically contains the `fs_dynamic_init` function, so try searching for that string.

3. Add the following to your `wp-config.php` file.  

   ```php
   // freemius activation
   define( 'FS_SHORTCODES', 'my_prefix_fs' );
   define( 'WP__MY_PREFIX_FS__LICENSE_KEY', '<your_license_key_here>' );
   ```

   If you have multiple freemius plugins, the FS_SHORTCODES definition can also be an array:  
   ```php
   // freemius activation
   define( 'FS_SHORTCODES', array( 'my_prefix_fs', 'my_other_prefix_fs' );
   define( 'WP__MY_PREFIX_FS__LICENSE_KEY', '<your_license_key_here>' );
   define( 'WP__MY_OTHER_PREFIX_FS__LICENSE_KEY', '<your_license_key_here>' );
   ```

4. Visit the plugin activation page in the dashboard. It should already be authorized with your key!

5. This process actually still adds your keys to the database, just as if you had typed it in manually. So if you prefer, when you're done with the activation, you can remove the settings definitions from wp-config and deactivate and/or delete this plugin.
