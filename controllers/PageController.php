<?php
/**
 * This HTML controller is what generates HTML pages (as opposed to JSON responses
 * generated by the other controllers).  The reason is testability: most of the 
 * manager app can be tested by $scriptProperties in, JSON out.  The HTML pages
 * generated by this controller end up being static HTML pages (well... ideally, 
 * anyway). 
 *
 * See http://stackoverflow.com/questions/10941249/separate-rest-json-api-server-and-client
 *
 * See the IndexManagerController class (index.class.php) for routing info.
 *
 * @package assman
 */
namespace Lunchbox;
class PageController extends BaseController {

    public $loadHeader = true;
    public $loadFooter = true;
    // GFD... this can't be set at runtime. See improvised addStandardLayout() function
    public $loadBaseJavascript = true; 
    // Stuff needed for interfacing with Assman API (mapi)
    public $client_config = array();
    
    function __construct(\modX &$modx,$config = array()) {
        parent::__construct($modx,$config);
        static::$x =& $modx;

        $this->config['controller_url'] = self::url();
        $this->config['core_path'] = $this->modx->getOption('lunchbox.core_path', null, MODX_CORE_PATH.'components/lunchbox/');
        $this->config['assets_url'] = $this->modx->getOption('lunchbox.assets_url', null, MODX_ASSETS_URL.'components/lunchbox/');
                
        $this->modx->regClientCSS($this->config['assets_url'] . 'css/mgr.css'); 
        $this->modx->regClientCSS($this->config['assets_url'] . 'css/lunchbox.css'); 

    }

    
    //------------------------------------------------------------------------------
    //! Index
    //------------------------------------------------------------------------------
    /**
     * @param array $scriptProperties
     */
    public function getIndex(array $scriptProperties = array()) {
        $this->modx->log(\modX::LOG_LEVEL_INFO, print_r($scriptProperties,true),'','Lunchbox PageController:'.__FUNCTION__);
        return $this->fetchTemplate('main/index.php');
    }


    /**
     * getBreadcrumbs
     * @param array $scriptProperties
     * @return html markup
     */
    public function getBreadcrumbs(array $scriptProperties = array()) {
        $this->loadHeader = false;
        $this->loadFooter = false;
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 

       $page_id = $this->modx->getOption('page_id',$scriptProperties);
        $items = array();
        while ($page = $this->modx->getObject('modResource', $page_id)) {
            array_unshift($items, array(
                    'id' => $page->get('id'),
                    'pagetitle' => $page->get('pagetitle')
                )
            );
            $page_id = $page->get('parent');
        }

        $last = array_pop($items);
        
        $out = '<div id="lunchbox_breadcrumbs">';
        foreach ($items as $i) {
            $out .= '<span onclick="javascript:drillDown('.$i['id'].');" class="lunchbox_breadcrumb">'.$i['pagetitle'].'</span> &raquo; ';
        }
        $out .= '<span>'.$last['pagetitle'].'</span>';
        $out .= '</div>';

        return  $out;
    }

     /**
     * getChildren
     * @param array $scriptProperties
     * @return json
     */
    public function getChildren(array $scriptProperties = array()) {
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 
        $this->modx->log(\modX::LOG_LEVEL_INFO, print_r($scriptProperties,true),'','Lunchbox PageController:'.__FUNCTION__);
        $limit = 10;
        //$limit = (int) $this->modx->getOption('default_per_page');
        $start = (int) $this->modx->getOption('start',$scriptProperties,0);
        $sort = $this->modx->getOption('sort',$scriptProperties,'menuindex');
        $dir = $this->modx->getOption('dir',$scriptProperties,'ASC');
        $parent = (int) $this->modx->getOption('parent',$scriptProperties,0);
        
        
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
        $this->setPlaceholder('results', $data['results']);
        return $this->fetchTemplate('main/children.php');
        //return json_encode($data);

    }
        
}
/*EOF*/