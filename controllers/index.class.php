<?php
require_once dirname(dirname(__FILE__)) .'/model/lunchbox/lunchbox.class.php';
require_once dirname(__FILE__) .'/abstract/lunchboxmanagercontroller.class.php';
/**
 * The name of the controller is based on the action (home) and the
 * namespace. This home controller is loaded by default because of
 * our IndexManagerController.
 */
class LunchboxIndexManagerController extends LunchboxManagerController {
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = true;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = true;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    public $loadBaseJavascript = true;
    
    public $props = array();
    
    /**
     * Any specific processing we want to do here. Return a string of html.
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array()) {
        return '<div id="lunchbox_cmp"></div>';
    }
    
    /**
     * The pagetitle to put in the <title> attribute.
     * @return null|string
     */
    public function getPageTitle() {
        return 'Lunchbox : Paginated Site Browser';
    }
    
    /**
     * Register needed assets. Using this method, it will automagically
     * combine and compress them if that is enabled in system settings.
     */
    public function loadCustomCssJs() {
        
        $assets_url = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');

        $page_id = (isset($_GET['id'])) ? $_GET['id'] : null;

        $Lunchbox = new Lunchbox($this->modx);
        $this->addJavascript($assets_url . 'js/lunchbox.js');
    	$lunchbox_connector_url = $Lunchbox->getControllerUrl();
    	$this->addHtml('
			<script type="text/javascript">
                var hierarchy = [];
                var connector_url = '.json_encode($lunchbox_connector_url).';
                var site_url = "'.MODX_SITE_URL.'";
				Ext.onReady(function(){
                    var myPanel = new Ext.Panel({
                        renderTo : "lunchbox_cmp",
                        height   : 600,
                        width    : 800,
                        title    : "Lunchbox : Paginated Site Browser",
                        items: getChildrenTabContent(MODx.config),
                        frame    : true
                    });
					//setBreadcrumbs('.$page_id.');
					//renderLunchbox(MODx.config);
				});
			</script>');
			

/*
        $this->addCss('url/to/some/css_file.css');
        $this->addJavascript('url/to/some/javascript.js');
        $this->addLastJavascript('url/to/some/javascript_load_last.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            // We could run some javascript here
        });
        </script>');
*/
    }
}
/*EOF*/