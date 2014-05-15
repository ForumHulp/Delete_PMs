<?php
/**
*
* @package Delete Pms
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\delete_pms\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
    /* @var \phpbb\controller\helper */
    protected $helper;
  
    /**
    * Constructor
    *
    * @param \phpbb\controller\helper    $helper        Controller helper object
    */
    public function __construct(\phpbb\controller\helper $helper)
    {
        $this->helper = $helper;
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.acp_board_config_edit_add'	=> 'load_config_on_setup',
			'core.user_setup'					=> 'load_language_on_setup'
		);
    }

    public function load_config_on_setup($event)
    {
		if ($event['mode'] == 'features')
		{
			$config_set_ext = $event['display_vars'];
			$config_set_vars = array_slice($config_set_ext['vars'], 0, 16, true);
			
			$config_set_vars['delete_pms_days'] = 
				array(
					'lang' 		=> 'DELETE_PMS_DAYS',
					'validate'	=> 'int',
					'type'		=> 'number:0:99',
					'explain'	=> true
				);

			$config_set_vars['delete_pms_read'] = 
				array(
					'lang' 		=> 'DELETE_PMS_READ',
					'validate'	=> 'bool',
					'type'		=> 'radio:yes_no',
					'explain'	=> true
				);
			$config_set_vars += array_slice($config_set_ext['vars'], 16, count($config_set_ext['vars']) - 1, true);
			$event['display_vars'] = array('title' => $config_set_ext['title'], 'vars' => $config_set_vars);
		}
    }
	
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'forumhulp/delete_pms',
			'lang_set' => 'delete_pms_common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
}