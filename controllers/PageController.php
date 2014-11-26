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

        $this->modx->regClientStartupScript($this->config['assets_url'].'js/lunchbox.js');


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

        $items = $this->getBcRecords($scriptProperties);
         $items = json_decode($items,true);
        $last = array_pop($items);


        $this->setPlaceholder('links', $items);
        $this->setPlaceholder('last', $last['pagetitle']);
        $this->setPlaceholder('in_modal', $in_modal);
        return $this->fetchTemplate('main/breadcrumbs.php');
    }


    /**
     * getBreadcrumbs
     * @param array $scriptProperties
     * @return html markup
     */
    public function getBreadcrumbsmodal(array $scriptProperties = array()) {
        $this->loadHeader = false;
        $this->loadFooter = false;
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 

        $items = $this->getBcRecords($scriptProperties);
         $items = json_decode($items,true);
        $last = array_pop($items);

        $this->setPlaceholder('links', $items);
        $this->setPlaceholder('last', $last['pagetitle']);
        $this->setPlaceholder('in_modal', $in_modal);
        return $this->fetchTemplate('main/modal.breadcrumbs.php');
    }

    public function getBcRecords(array $scriptProperties = array()) {
        $this->loadHeader = false;
        $this->loadFooter = false;
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 

       $page_id = $this->modx->getOption('page_id',$scriptProperties);
       $in_modal = $this->modx->getOption('in_modal',$scriptProperties);
        $items = array();
        while ($page = $this->modx->getObject('modResource', $page_id)) {
            array_unshift($items, array(
                    'id' => $page->get('id'),
                    'pagetitle' => $page->get('pagetitle')
                )
            );
            $page_id = $page->get('parent');
        }
        return json_encode($items);
    }
     /**
     * getChildren
     * @param array $scriptProperties
     * @return json
     */
    public function getChildren(array $scriptProperties = array()) {
 
        $this->loadHeader = false;
        $this->loadFooter = false;
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 
        $this->modx->log(\modX::LOG_LEVEL_INFO, print_r($scriptProperties,true),'','Lunchbox PageController:'.__FUNCTION__);

        $limit = (int) $this->modx->getOption('lunchbox.results_per_page','',$this->modx->getOption('default_per_page'));
        $search = $this->modx->getOption('search_term',$scriptProperties);

        $sort = $this->modx->getOption('sort',$scriptProperties,$this->modx->getOption('lunchbox.sort_col','','pagetitle'));
        $dir = $this->modx->getOption('dir',$scriptProperties,'ASC');

        $selected = $this->modx->getOption('selected',$scriptProperties,0);
    
        $parent = (int) $this->modx->getOption('parent',$scriptProperties,0);
        $offset = (int) $this->modx->getOption('offset',$scriptProperties,0);

        $data = $this->getRecords($scriptProperties);
        $data = json_decode($data,true);

        $this->setPlaceholder('offset', $offset);
        $this->setPlaceholder('parent', $parent);
        $this->setPlaceholder('site_url', $this->modx->getOption('site_url'));
        $this->setPlaceholder('baseurl', $this->page('children',array('parent'=>$data['parent'])));
        $this->setPlaceholder('results', $data['results']);
        $this->setPlaceholder('count', $data['total']);
        $this->setPlaceholder('columns', $data['cols']);  
        $this->setPlaceholder('controller_url', $this->config['controller_url']); 
        $this->setPlaceholder('selected', $selected);
              
        return $this->fetchTemplate('main/children.php');
    }

    /**
     * getParents
     * @param array $scriptProperties
     * @return html markup
     */
    public function getParents(array $scriptProperties = array()) {
       $this->loadHeader = false;
        $this->loadFooter = false;
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 
        $this->modx->log(\modX::LOG_LEVEL_INFO, print_r($scriptProperties,true),'','Lunchbox PageController:'.__FUNCTION__);

        $limit = (int) $this->modx->getOption('lunchbox.results_per_page','',$this->modx->getOption('default_per_page'));
        $search = $this->modx->getOption('search_term',$scriptProperties);

        $sort = $this->modx->getOption('sort',$scriptProperties,$this->modx->getOption('lunchbox.sort_col','','pagetitle'));
        $dir = $this->modx->getOption('dir',$scriptProperties,'ASC');

        $selected = $this->modx->getOption('selected',$scriptProperties,0);
    
        $parent = (int) $this->modx->getOption('parent',$scriptProperties,0);
        $offset = (int) $this->modx->getOption('offset',$scriptProperties,0);

        $data = $this->getRecords($scriptProperties);
        $data = json_decode($data,true);

        $this->setPlaceholder('offset', $offset);
        $this->setPlaceholder('parent', $parent);
        $this->setPlaceholder('site_url', $this->modx->getOption('site_url'));
        $this->setPlaceholder('baseurl', $this->page('children',array('parent'=>$data['parent'])));
        $this->setPlaceholder('results', $data['results']);
        $this->setPlaceholder('count', $data['total']);
        $this->setPlaceholder('columns', $data['cols']);  
        $this->setPlaceholder('controller_url', $this->config['controller_url']); 
        $this->setPlaceholder('selected', $selected);

        return $this->fetchTemplate('main/modal.children.php');        
    }


    public function getRecords(array $scriptProperties = array()) {
        $this->loadHeader = false;
        $this->loadFooter = false;
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 
        $this->modx->log(\modX::LOG_LEVEL_INFO, print_r($scriptProperties,true),'','Lunchbox PageController:'.__FUNCTION__);
        $limit = (int) $this->modx->getOption('lunchbox.results_per_page','',$this->modx->getOption('default_per_page'));
        $search = $this->modx->getOption('search_term',$scriptProperties);

        $sort = $this->modx->getOption('sort',$scriptProperties,$this->modx->getOption('lunchbox.sort_col','','pagetitle'));
        $dir = $this->modx->getOption('dir',$scriptProperties,'ASC');

        $selected = $this->modx->getOption('selected',$scriptProperties,0);
    
        $parent = (int) $this->modx->getOption('parent',$scriptProperties,0);
        $offset = (int) $this->modx->getOption('offset',$scriptProperties,0);
        $cols = $this->_setChildrenColumns(); 
        $in_modal = (int) $this->modx->getOption('in_modal',$scriptProperties,false);
        $criteria = $this->modx->newQuery('modResource');


        if ($parent) {
            $criteria->where(array('parent'=>$parent));
        }

        if($search != '' || !empty($search)) {
            $criteria->where(array('pagetitle:LIKE'=>"%$search%"));
        }

        $total_pages = $this->modx->getCount('modResource',$criteria);
        
        $criteria->limit($limit, $offset); 

        $pos = strpos($sort, 'tv.');
        // if false use regular sort
        if ($pos === false) {
             $criteria->sortby($sort,$dir);
        } else {
             $this->_sortbyTV( 'firstname', $criteria, $dir);
        }

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

        $tvs = $this->_get_tvs($cols);
        foreach ($rows as $r) {
            $tv_vals = array();
            $page = $r->toArray('',false,true);
            if(!empty($tvs)) {
               $tv_vals = $this->_addtvValues($tvs,$page['id']);
            }
            $page = array_merge($page,$tv_vals);
            $data['results'][] =$page;
        }
        $data['cols'] = $cols;
        return json_encode($data);
    }


    public function postSetParent(array $scriptProperties = array()) {
        $this->loadHeader = false;
        $this->loadFooter = false;
        // GFD... this can't be set at runtime. See improvised addStandardLayout() function
        $this->loadBaseJavascript = false; 
        $this->modx->log(\modX::LOG_LEVEL_INFO, print_r($scriptProperties,true),'','Lunchbox PageController:'.__FUNCTION__);
        $result = array('success'=>false,'msg'=>'Faled to Set Parent');
        $page = $this->modx->getObject('modResource', array('id' => $scriptProperties['id']));
        $page->set('parent', $scriptProperties['parent']);
        $page->set('show_in_tree', 1);
        if ($page->save()) {
            $result['success'] = true;
            $result['msg'] = 'Successfully set Parent';
            return json_encode($result);
        }
        return json_encode($result);  
    }

    /**
     * _addtvValues
     * @param array $tvs
     * @param int $id
     * @return array $tv_vals
     */
    private function _addtvValues($tvs,$id) {
        $tv_vals = array();
       foreach ($tvs as $tv) {
            $sql = "SELECT {$tv['name']}.value {$tv['name']}
                FROM modx_site_content doc
                LEFT JOIN modx_site_tmplvar_contentvalues {$tv['name']} ON doc.id = {$tv['name']}.contentid
                WHERE doc.id ={$id}
                AND {$tv['name']}.tmplvarid ={$tv['id']};";
    
            $result = $this->modx->query($sql);
            $vals = $result->fetchAll(\PDO::FETCH_ASSOC);
            if(count($vals) != 0) {
                 $tv_vals[key($vals[0])] = $vals[0][$tv['name']];
            }
           
       }
       return $tv_vals;
    }

    /**
     * _setChildrenColumns
     *
     */
    private function _setChildrenColumns() {
        if ($cols = $this->modx->getOption('lunchbox.children_columns')) {
            $cols = json_decode($cols,true);
        }
        if (empty($cols) || !is_array($cols)) {
            $cols = array('pagetitle'=>'Pagetitle','id'=>'ID','published'=>'Published');
        }
        return $cols; 
    }

    /**
     * _get_tvs : find tvs on the specified cols setting
     * @param array $cols
     * @return array
     */
    private function _get_tvs($cols) {
        $where_clause = '';
        foreach ($cols as $c => $v) {
            $where_clause .= " name ='{$c}' OR";
        }
        $where_clause = substr($where_clause, 0, -3);

        $sql = "SELECT id,name FROM modx_site_tmplvars where 1=1 AND {$where_clause};";
        
        $result = $this->modx->query($sql);
        $tvs = $result->fetchAll(\PDO::FETCH_ASSOC);
        if(count($tvs) == 0) {
            return array();
        }

        return $tvs;

    }

    /**
    * Excerpt from GetResources to sort documents by Tvs
    * use as sortbyTV( 'myTVName', $c, 'ASC');
    */
    private function _sortbyTV( $sortbyTV, $criteria, $sortdirTV='ASC', $sortbyTVType='string')
    {         
        $columns = $this->modx->getSelectColumns('modResource', 'modResource');
        $criteria->select($columns);
         
        if (!empty($sortbyTV)) {
            $criteria->leftJoin('modTemplateVar', 'tvDefault', array(
                "tvDefault.name" => $sortbyTV
            ));
            $criteria->leftJoin('modTemplateVarResource', 'tvSort', array(
                "tvSort.contentid = modResource.id",
                "tvSort.tmplvarid = tvDefault.id"
            ));
            if (empty($sortbyTVType)) $sortbyTVType = 'string';
            if ($this->modx->getOption('dbtype') === 'mysql') {
                switch ($sortbyTVType) {
                    case 'integer':
                        $criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS SIGNED INTEGER) AS sortTV");
                        break;
                    case 'decimal':
                        $criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV");
                        break;
                    case 'datetime':
                        $criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV");
                        break;
                    case 'string':
                    default:
                        $criteria->select("IFNULL(tvSort.value, tvDefault.default_text) AS sortTV");
                        break;
                }
            } elseif ($this->modx->getOption('dbtype') === 'sqlsrv') {
                switch ($sortbyTVType) {
                    case 'integer':
                        $criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS BIGINT) AS sortTV");
                        break;
                    case 'decimal':
                        $criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV");
                        break;
                    case 'datetime':
                        $criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV");
                        break;
                    case 'string':
                    default:
                        $criteria->select("ISNULL(tvSort.value, tvDefault.default_text) AS sortTV");
                        break;
                }
            }
            $criteria->sortby("sortTV", $sortdirTV);
        }
    }
        
}
/*EOF*/