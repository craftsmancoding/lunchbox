<?php
/**
 * @name Lunchbox
 * @description Loads CSS and other eye-candy for the manager
 * @PluginEvents OnManagerPageInit
 */

switch ($modx->event->name) {
    //------------------------------------------------------------------------------
    //! OnManagerPageInit
    //  Load up custom CSS for the manager
    //------------------------------------------------------------------------------
    case 'OnManagerPageInit':
        $assets_url = $modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');
        $modx->regClientCSS($assets_url.'css/lunchbox.css');
        break;       
}