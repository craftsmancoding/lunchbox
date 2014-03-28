<?php
require_once dirname(__FILE__) .'/abstract/lunchboxmanagercontroller.class.php';
/**
 * The name of the controller is based on the action (named "children") and the
 * namespace. 
 */
class LunchboxChildrenManagerController extends LunchboxManagerController {
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = false;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = false;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    public $loadBaseJavascript = false;
    
    public $props = array();
    
    /**
     * Any specific processing we want to do here. Return a string of html.
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array()) {
    
        $limit = (int) $this->modx->getOption('default_per_page');
        $start = (int) $this->modx->getOption('start',$scriptProperties,0);
        $sort = $this->modx->getOption('sort',$scriptProperties,'menuindex');
        $dir = $this->modx->getOption('dir',$scriptProperties,'ASC');
        $parent = (int) $this->modx->getOption('parent',0);
        
        
        $criteria = $this->modx->newQuery('modResource');
        if ($parent) {
            $criteria->where(array('parent'=>$parent));
        }
        
        $total_pages = $this->modx->getCount('modResource',$criteria);
        
        $criteria->limit($limit, $start); 
        $criteria->sortby($sort,$dir);
        // Both array and string input seem to work
        $criteria->select(array('id','pagetitle','longtitle','isfolder','description','published',
            'introtext','template','deleted','hidemenu','uri'));
        $rows = $this->modx->getCollection('modResource',$criteria);
        
        // Init our array
        $data = array(
            'results'=>array(),
            'total' => $total_pages,
        );
        
        if ($rows===false) {
            header('HTTP/1.0 401 Unauthorized');
            print 'Operation not allowed.';
            exit;
        }
        
        foreach ($rows as $r) {
            $data['results'][] = $r->toArray('',false,true);
        }
        
        return json_encode($data);
    }
    
    /**
     * The pagetitle to put in the <title> attribute.
     * @return null|string
     */
    public function getPageTitle() {
        return 'Lunchbox';
    }
}
/*EOF*/