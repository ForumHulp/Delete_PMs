<?php
/**
*
* @package Delete Pms
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
        exit;
}

if (empty($lang) || !is_array($lang))
{
        $lang = array();
}

$lang = array_merge($lang, array(
    'DELETE_PMS_DAYS' 			=> 'Delete pm\'s',
	'DELETE_PMS_DAYS_EXPLAIN'	=> 'Days before cron wil delete pms',
	'DELETE_PMS_READ'			=> 'Delete read PM\'s',
	'DELETE_PMS_READ_EXPLAIN'	=> 'Wether to delete only read PM\'s or all PM\'s older then days',
	
	'LOG_DELETE_PMS'			=> '<strong>PM\'s deleted from</strong><br />» %s',
	'NO_DELETE_PMS'				=> 'No PM\'s deleted'
));