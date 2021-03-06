<?php
/**
 * @name Lunchbox
 * @description Loads CSS and other eye-candy for the manager
 * @PluginEvents OnManagerPageInit,OnDocFormSave
 */
$core_path = $modx->getOption('lunchbox.core_path', null, MODX_CORE_PATH.'components/lunchbox/');
include_once $core_path .'vendor/autoload.php';

switch ($modx->event->name) {
    //------------------------------------------------------------------------------
    //! OnManagerPageInit
    //  Load up custom CSS for the manager
    //------------------------------------------------------------------------------
    case 'OnManagerPageInit':

        $assets_url = $modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');
        $modx->regClientCSS($assets_url.'css/lunchbox.css');
        break;  

    // Documents
    case 'OnDocFormSave':
        if ($resource->get('class_key') != 'Lunchbox' && $resource->get('lunchbox')) {
            $c = $modx->newQuery('modResource');
            $c->where(array('parent'=>$resource->get('id')));        
            $Children = $modx->getCollection('modResource', $c);
            foreach ($Children as $child) {
                $Child = $modx->getObject('modResource', array('id' => $child->get('id'),'show_in_tree' => 0));
                if($Child) {
                    $Child->set('show_in_tree', 1);
                    $Child->save();
                } 
            }
        }

        if ('Lunchbox' == $resource->get('class_key')) {

            $c = $modx->newQuery('modResource');
            $c->where(array('parent'=>$resource->get('id')));        
            $Children = $modx->getCollection('modResource', $c);
            foreach ($Children as $child) {
                $Child = $modx->getObject('modResource', array('id' => $child->get('id'),'show_in_tree' => 1));
                if($Child) {
                    $Child->set('show_in_tree', 0);
                    $Child->save();
                }
              
            }
            
        }
    
    break;      
}