<?php
/*-----------------------------------------------------------------
For descriptions here, you must create some lexicon entries:
Name: setting_ + $key
Description: setting_ + $key + _desc
-----------------------------------------------------------------*/
return array(
    array(
        'key'  =>     'lunchbox.results_per_page',
		'value'=>     '10',
		'xtype'=>     'textfield',
		'namespace' => 'lunchbox',
		'area' => 'lunchbox:default'
    ),
    array(
        'key'  =>     'lunchbox.sort_col',
		'value'=>     'pagetitle',
		'xtype'=>     'textfield',
		'namespace' => 'lunchbox',
		'area' => 'lunchbox:default'
    ),
    // For quick edit
    array(
        'key'  =>     'lunchbox.children_columns',
		'value'=>     '{"pagetitle":"Pagetitle","id":"ID","published":"Published"}',
		'xtype'=>     'textfield',
		'namespace' => 'lunchbox',
		'area' => 'lunchbox:default'
    )
);
/*EOF*/