<?php
/**
 * The abstract Manager Controller.
 * In this class, we define stuff we want on all of our controllers.
 */
abstract class LunchboxManagerController extends modExtraManagerController {
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = true;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = true;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    public $loadBaseJavascript = true;
    /** @var array An array of possible paths to this controller's templates directory. */
    public $templatesPaths = array();
    /** @var array An array of possible paths to this controller's directory. */
//    public $controllersPaths = array('controllers');
    /** @var modContext The current working context. */
    //public $workingContext;
    /** @var modMediaSource The default media source for the user */
    //public $defaultSource;
    /** @var string The current output content */
    //public $content = '';
    /** @var array An array of request parameters sent to the controller */
   // public $scriptProperties = array();
    /** @var array An array of css/js/html to load into the HEAD of the page */
    //public $head = array('css' => array(),'js' => array(),'html' => array(),'lastjs' => array());
    /** @var array An array of placeholders that are being set to the page */
    //public $placeholders = array();


    public $action; // &a=xxx for primary lunchbox action

    
    public $data = array(); // passed to views.
    
    
    
    /**
     * Map a function name to a MODX permission, e.g. 
     * 'edit_product' => 'edit_document'
     */
    private $perms = array(
        'edit_product' => 'edit_document',
    );
    
    /**
     * This is the permission tested against if nothing is explicitly defined
     * in the $perms array.
     */
    private $default_perm = 'view_document';

    public function __construct(&$modx) {

        $this->modx =& $modx;
        $this->core_path = $this->modx->getOption('lunchbox.core_path', null, MODX_CORE_PATH);

    }
    
    /**
     * Catch all for bad function requests.
     *
     */
    public function __call($name,$args) {
        $this->modx->log(modX::LOG_LEVEL_ERROR,'[lunchbox] Invalid function name '.__FUNCTION__);
        return $this->_send401($args);
    }

    
    private function _send401() {
        header('HTTP/1.0 401 Unauthorized');
        print 'Unauthorized';
        exit;
    }

  
    
    /**
     * Load a view file. We put in some commonly used variables here for convenience
     *
     * @param string $file: name of a file inside of the "views" folder
     * @param array $data: an associative array containing key => value pairs, passed to the view
     * @return string
     */
    private function _load_view($file, $data=array(),$return=false) {
        $file = basename($file);
    	if (file_exists($this->core_path.'components/lunchbox/views/'.$file)) {
    	    if (!isset($return) || $return == false) {
    	        ob_start();
    	        include ($this->core_path.'components/lunchbox/views/'.$file);
    	        $output = ob_get_contents();
    	        ob_end_clean();
    	    }     
    	} 
    	else {
    		$output = $this->modx->lexicon('view_not_found', array('file'=> 'views/'.$file));
    	}
    
    	return $output;
    
    }

    private function _testing($test) {
        return 'blah ' . $test;
    }
    


    /**
     * Initializes the main manager controller. You may want to load certain classes,
     * assets that are shared across all controllers or configuration. 
     *
     * All your other controllers in this namespace should extend this one.
     *
     */
    public function initialize() {
        //$this->addHtml();
        $this->assets_url = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL);
        $this->mgr_url = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->connector_url = $this->assets_url.'components/lunchbox/connector.php?f=';
        $this->modx->addPackage('lunchbox',$this->core_path.'components/lunchbox/model/','moxy_');
    }
    /**
     * Defines the lexicon topics to load in our controller.
     * @return array
     */
    public function getLanguageTopics() {
        return array('lunchbox:default');
    }
    /**
     * We can use this to check if the user has permission to see this controller
     * @return bool
     */
    public function checkPermissions() {
        return true; // TODO
    }
    
    /*
Array
(
    [id] => 84
    [namespace] => lunchbox
    [controller] => index
    [haslayout] => 1
    [lang_topics] => lunchbox:default
    [assets] => 
    [help_url] => 
    [namespace_name] => lunchbox
    [namespace_path] => /Users/everett2/Sites/revo8/html/assets/repos/lunchbox/core/components/lunchbox/
    [namespace_assets_path] => /Users/everett2/Sites/revo8/html/assets/repos/lunchbox/assets/components/lunchbox/
)    

     * Get a URL for a given action in the manager
     *
     * @param string $action
     * @param array $args any additional url parameters
     * @return string
     */
    public function getUrl($action, $args=array()) {
        $url = '';
        foreach ($args as $k => $v) {
            if (is_scalar($k) && is_scalar($v)) {
                $url .= '&'.$k.'='.$v;
            }
        }
        return MODX_MANAGER_URL . '?a='.$this->config['id'].'&action='.$action.$url;
    }


}