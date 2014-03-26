<?php
/** 
 * This file handles Ajax requests made by lunchbox.  It provides stores of data
 * for controllers in the manager.  This file is normally accessed via post,
 * but it can also be accessed directly for debugging purposes, e.g. 
 * http://yoursite.com/assets/components/lunchbox/connector.php
 *
 * PARAMETERS
 *
 *  @param integer limit -- limit the number of results returned.
 *  @param integer start -- offset for query, used for pagination
 *  @param string sort -- column name to be used for default sorting
 *  @param string dir (ASC|DESC) -- sort direction
 *  @param string 
 */

// It's hard to find stuff when you're developing
// We climb up the dir structure looking for config.core.php...
$docroot = dirname(dirname(dirname(dirname(__FILE__))));
while (!file_exists($docroot.'/config.core.php')) {
    if ($docroot == '/') {
        die('Failed to locate config.core.php');
    }
    $docroot = dirname($docroot);
}
if (!file_exists($docroot.'/config.core.php')) {
    die('Failed to locate config.core.php');
}

include_once $docroot . '/config.core.php';

if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
 
$modx = new modX();
$modx->initialize('mgr');


$limit = (int) $modx->getOption('default_per_page');
$start = (int) $modx->getOption('start',$args,0);
$sort = $modx->getOption('sort',$args,'menuindex');
$dir = $modx->getOption('dir',$args,'ASC');
$parent = (int) (isset($_GET['parent'])) ? $_GET['parent'] : 0;


$criteria = $modx->newQuery('modResource');
if ($parent) {
    $criteria->where(array('parent'=>$parent));
}

$total_pages = $modx->getCount('modResource',$criteria);

$criteria->limit($limit, $start); 
$criteria->sortby($sort,$dir);
// Both array and string input seem to work
$criteria->select(array('id','pagetitle','longtitle','isfolder','description','published','introtext','template','deleted','hidemenu','uri'));
//$criteria->select('id,pagetitle,longtitle,isfolder,description');
$rows = $modx->getCollection('modResource',$criteria);

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

$out = json_encode($data);
print $out;
exit;
/*EOF*/