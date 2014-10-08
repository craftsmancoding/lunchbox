<?php

return array(
 'first' => '<span onclick="javascript:get_children([+parent+],[+offset+]);" class="linklike">&laquo; First</span>  ',
            'last' => ' <span onclick="javascript:get_children([+parent+],[+offset+]);" class="linklike">Last &raquo;</span>',
            'prev' => '<span onclick="javascript:get_children([+parent+],[+offset+]);" class="linklike">&lsaquo; Prev.</span> ',
            'next' => ' <span onclick="javascript:get_children([+parent+],[+offset+]);" class="linklike">Next &rsaquo;</span>',
            'current' => ' <span>[+page_number+]</span> ',
            'page' => ' <span onclick="javascript:get_children([+parent+],[+offset+]);" class="linklike">[+page_number+]</span> ',
            'outer' => '
                <style>
                    span.linklike { cursor: pointer; }
                    span.linklike:hover { color:blue; text-decoration:underline; }
                </style>
                <div id="pagination">[+content+]<br/>
    				<div class="page-count">Page [+current_page+] of [+page_count+]</div>
    				<div class="displaying-page">Displaying records [+first_record+] thru [+last_record+] of [+record_count+]</div>
    			</div>',
);
