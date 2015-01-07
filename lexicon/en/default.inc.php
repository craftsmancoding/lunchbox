<?php
/**
 * en default topic lexicon file for lunchbox extra
 *
 * Copyright 2013 by Everett Griffiths everett@craftsmancoding.com
 * Created on 07-05-2013
 *
 * lunchbox is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * lunchbox is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * moxycart; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package lunchbox
 */

$_lang['lunchbox'] = 'Lunchbox'; 
$_lang['lunchbox_desc'] = 'MODx Paginated Container';

$_lang['lunchbox.layout.title'] = 'Welcome to Lunchbox';
$_lang['lunchbox.layout.subtitle'] = 'Have your lunch and eat it too!  Navigate your deeply hierarchical content without the performance headaches of the resource tree.';
$_lang['lunchbox.layout.noresult'] = 'Sorry No Page Found.';


// License stuff
$_lang['status'] = 'Status';
$_lang['valid'] = 'Valid';
$_lang['invalid'] = 'Invalid';
$_lang['expired'] = 'Expired';

// Warning Boxes
$_lang['warning'] = 'Warning';
$_lang['error'] = 'Error';
$_lang['invalid_expired_msg'] = 'Your license for Lunchbox is invalid or expired. <strong>lunchbox.license_key</strong> System Setting.  <a href="https://craftsmancoding.com/products/lunchbox/">Renew License Key</a>';
$_lang['activation_problem_msg'] = 'There was a problem activating your license.';
$_lang['reqs_license_msg'] = 'The Lunchbox extra requires a license key. Paste it into your <strong>lunchbox.license_key</strong> System Setting.  <a href="https://craftsmancoding.com/products/lunchbox/">Get License Key</a>';


//-------------------------------------
// SETTINGS
//-------------------------------------
$_lang['setting_lunchbox.license_key'] = 'Lunchbox License Key';
$_lang['setting_lunchbox.license_key_desc'] = 'Lunchbox requires a license key to run.  You can obtain a license at <a href="https://craftsmancoding.com/products/lunchbox/">https://craftsmancoding.com/products/lunchbox/</a>';

$_lang['setting_lunchbox.results_per_page'] = 'Results per Page';
$_lang['setting_lunchbox.results_per_page_desc'] = 'This affects pagination inside the Lunchbox windows. The value will default to the default_per_page System Setting';
$_lang['setting_lunchbox.sort_col'] = 'Sort by Column';
$_lang['setting_lunchbox.sort_col_desc'] = 'Which column do you want to sort results by?  You can sort by a TV if you prefix the name with "tv." Default: pagetitle';
$_lang['setting_lunchbox.children_columns'] = 'Column Headings';
$_lang['setting_lunchbox.children_columns_desc'] = 'When viewing paginated results, which columns do you want to see? Customize this as a JSON key/value hash: keys are column names, values are labels. E.g. {"pagetitle":"Page Title","id":"ID"}';

//---------------------------------------
// CM Pages Menu
//---------------------------------------
$_lang['lunchbox.menu.manage'] = 'Manage';
$_lang['lunchbox.menu.library'] = 'Library';
$_lang['lunchbox.menu.settings'] = 'Settings';
$_lang['lunchbox.menu.groups'] = 'Groups';
$_lang['lunchbox.menu.verify'] = 'Verify';
$_lang['lunchbox.menu.donation'] = 'Make a Donation';
$_lang['lunchbox.menu.bug'] = 'Report a Bug';
$_lang['lunchbox.menu.wiki'] = 'Wiki';
$_lang['lunchbox.menu.support'] = 'Get Paid Support';


$_lang['lunchbox_create_here'] = 'Create Lunchbox Here';

$_lang['lunchbox.form.search'] = 'Search';

$_lang['lunchbox.action.edit'] = 'Edit';
$_lang['lunchbox.action.preview'] = 'Preview';
$_lang['lunchbox.action.selectparent'] = 'Select Parent';
$_lang['lunchbox.action.selectchildren'] = 'Manage Children';
$_lang['lunchbox.action.addpage'] = 'Add Page';
$_lang['lunchbox.action.cancel'] = 'Cancel';
$_lang['lunchbox.action.updatechildren'] = 'Update Children';
$_lang['lunchbox.action.addqueue'] = 'Add to queue';
$_lang['lunchbox.action.close'] = 'Close';
