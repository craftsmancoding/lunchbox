<?php
/**
 * The menu 'text' and 'description' fields should be Lexicon keys.
 *
 *
 */
return array(
    array(
        'text' => 'lunchbox',
        'description' => 'lunchbox_desc',
        'parent' => 'components',
        'action' => 0,
        'icon' => '',
        'menuindex' => 0,
        'params' => '',
        'handler' => '',
        'permissions' => '',
        'Action' => array (
            //'action' => 0, // Omit this so that it will inherit from the related object
            // This will create an ERROR: Attempt to set NOT NULL field action to NULL
            // But it's safe to ignore in this case.
            'namespace' => 'lunchbox',
            'controller' => 'index',
            'haslayout' => 1,
            'lang_topics' => 'lunchbox:default',
            'assets' => '',
            'help_url' =>  '',
        ),
    ),
);
/*EOF*/