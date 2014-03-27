<?php
require_once $modx->getOption('manager_path',null,MODX_MANAGER_PATH).'controllers/default/resource/update.class.php';

class LunchboxUpdateManagerController extends ResourceUpdateManagerController {

    public $resource;

    public function loadCustomCssJs() {

        parent::loadCustomCssJs();
        
        $mgr_url = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL.'components/lunchbox/');
        $assets_url = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');
        
		//Add below for customization
        $this->addJavascript($assets_url . 'js/lunchbox.js');
    	$lunchbox_connector_url = $assets_url.'connector.php?';
    	$this->addHtml('
			<script type="text/javascript">
                var connector_url = "'.$lunchbox_connector_url.'";
                var site_url = "'.MODX_SITE_URL.'";
                    var breadcrumbs = {
                        items: [
                            {
                                xtype: "box",
                                autoEl: {cn: "<span onclick=\'javascript:alert(123);\'>Breadcrumb</span> >> Here >> Now"}
                            }
                        ]
                    };
				isProductContainerCreate = false;
				Ext.onReady(function(){
					renderLunchbox(isProductContainerCreate, MODx.config);
				});
			</script>');
			
        $this->addCss($assets_url.'components/lunchbox/css/mgr.css');

    }
        
    public function getLanguageTopics() {
        return array('resource','lunchbox:default');
    }
    /**
     * Return the pagetitle
     *
     * @return string
     */
/*
    public function getPageTitle() {
        return $this->modx->lexicon('container_update');
    }
*/
    /**
     * Used to set values on the resource record sent to the template for derivative classes (wtf?)
     * We're doing this in the store model instead...
     * @return void
     */
    public function prepareResource() {

    }    
}