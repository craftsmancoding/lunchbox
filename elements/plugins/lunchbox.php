<?php
/**
 * @name Lunchbox
 * @description Loads CSS and other eye-candy for the manager
 * @PluginEvents OnManagerPageInit,OnDocFormSave
 */
$core_path = $modx->getOption('lunchbox.core_path', null, MODX_CORE_PATH.'components/lunchbox/');
include_once $core_path .'vendor/autoload.php';

// Has Valid License Key?
if ($modx->event->name =='OnManagerPageInit')
{
    if (!$modx->getOption('lunchbox.license_key') )
    {
        // Try not to annoy the nice users too often
        if (!$modx->cacheManager->get('lunchbox.warning_displayed'))
        {
            $modx->lexicon->load('lunchbox:default');
            $displayed = true; // gotta pass arg 2 as a var
            $modx->cacheManager->set('lunchbox.warning_displayed', $displayed, 60*5);
            $modx->regClientStartupHTMLBlock("<script>Ext.onReady(function() {
            MODx.msg.alert('".$modx->lexicon('warning')."',".json_encode('reqs_license_msg').");
            });</script>");
        }
    }
    else
    {
        $License = new \Lunchbox\License($modx);
        $status = $License->check($modx->getOption('lunchbox.license_key'));
        // Activate License if needed
        if ($status == 'inactive')
        {
            if (!$License->activate($modx->getOption('lunchbox.license_key')))
            {
                $modx->regClientStartupHTMLBlock("<script>Ext.onReady(function() {
            MODx.msg.alert('".$modx->lexicon('error')."',".json_encode($modx->lexicon('activation_problem_msg')).");
            });</script>");
                return;
            }
        }
        // Check it again, in case
        $status = $License->check($modx->getOption('lunchbox.license_key'));
        if ($status != 'valid')
        {
            $modx->regClientStartupHTMLBlock("<script>Ext.onReady(function() {
            MODx.msg.alert('".$modx->lexicon('warning')."',".json_encode($modx->lexicon('invalid_expired_msg')).");
            });</script>");
            return; // Invalid license.
        }
        else
        {
            $modx->log(\xPDO::LOG_LEVEL_INFO, 'Lunchbox License status: '. $status);
        }

    }

}

//
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