<?php
/**
*
* @package Delete Pms
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\deletepms\migrations;

class install_deletepms extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['delete_pms_version']) && version_compare($this->config['delete_pms_version'], '3.1.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('delete_pms_gc', 86400)),
			array('config.add', array('delete_pms_last_gc', '0', 1)),
			array('config.add', array('delete_pms_days', 30)),
			array('config.add', array('delete_pms_read', 0)),
			array('config.add', array('delete_pms_version', '3.1.0'))
		);
	}
}
