<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\delete_pms\cron\task\core;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class delete_pms extends \phpbb\cron\task\base
{
	protected $phpbb_root_path;
	protected $php_ext;
	protected $config;
	protected $db;
	protected $table_prefix;

	/**
	* Constructor.
	*
	* @param string $phpbb_root_path The root path
	* @param string $php_ext The PHP extension
	* @param phpbb_config $config The config
	* @param phpbb_db_driver $db The db connection
	*/
	public function __construct($phpbb_root_path, $php_ext, \phpbb\config\config $config, \phpbb\db\driver\driver $db)
	{
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->config = $config;
		$this->db = $db;
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		global $db;
		$expire_date = time() - ($this->config['delete_pms_days'] * 86400);
		$user_list = array();
	
		$sql = 'SELECT p.msg_id, u.username, p.message_attachment FROM ' . PRIVMSGS_TABLE . ' p 
				LEFT JOIN ' . USERS_TABLE . ' u ON (u.user_id = p.author_id)
				WHERE p.message_time < ' . $expire_date;
		
		$result = $db->sql_query($sql);
	
		while ($row = $db->sql_fetchrow($result))
		{
			$msg_list[$row['msg_id']] = array('username' => $row['username'], 'attachment' => $row['message_attachment']);
		}
		$db->sql_freeresult($result);
	
		if (sizeof($msg_list))
		{
			foreach($msg_list as $key => $value)
			{
				if ($value['attachment'])
				{
					if (!function_exists('phpbb_unlink'))
					{
						include($phpbb_root_path . 'includes/functions_admin.php');
					}
					$sql = 'SELECT physical_filename FROM ' . ATTACHMENTS_TABLE . ' WHERE post_msg_id = ' . $key;
					$result = $db->sql_query($sql);
					while ($row = $db->sql_fetchrow($result))
					{
						phpbb_unlink($row['physical_filename'], $mode = 'file', true);
						$sql = 'DELETE FROM ' . ATTACHMENTS_TABLE . ' WHERE post_msg_id = ' . $key;
						$db->sql_query($sql);
					}
					$user_list[] = $value['username'];
				}
			}
			$db->sql_transaction('begin');
			$sql = 'DELETE FROM ' . PRIVMSGS_TO_TABLE . ' WHERE msg_id IN (' . implode(', ', array_keys($msg_list)) . ')';
			$db->sql_query($sql);

			$sql = 'DELETE FROM ' . PRIVMSGS_TABLE . ' WHERE msg_id IN (' . implode(', ', array_keys($msg_list)) . ')';
			$db->sql_query($sql);
			$db->sql_transaction('commit');
					
			add_log('admin', 'LOG_INACTIVE_DELETE', implode(', ', array_unique($user_list)));
		} else
		{
			add_log('admin', 'NO_INACTIVE_USERS');
		}
		$this->config->set('delete_pms_last_gc', time());
	}

	/**
	* Returns whether this cron task can run, given current board configuration.
	*
	* @return bool
	*/
	public function is_runnable()
	{
		return (bool) $this->config['delete_pms_days'];
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last run.
	*
	* @return bool
	*/
	public function should_run()
	{
		return $this->config['delete_pms_last_gc'] < time() - $this->config['delete_pms_gc'];
	}
}
