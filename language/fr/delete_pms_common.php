<?php
/**
 *
 * Delete Pm's. An extension for the phpBB Forum Software package.
 * French translation by Galixte (http://www.galixte.com)
 *
 * @copyright (c) 2017 ForumHulp.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'DELETE_PMS_DAYS' 			=> 'Délais de suppression des MP',
	'DELETE_PMS_DAYS_EXPLAIN'	=> 'Permet de saisir le nombre jours avant lequel la tâche CRON du forum supprimera les MP.',
	'DELETE_PMS_READ'			=> 'Supprimer uniquement les MP lus',
	'DELETE_PMS_READ_EXPLAIN'	=> 'Permet de sélectionner si seuls les MP lus sont supprimés ou tous les MP selon le nombre de jours d’ancienneté saisi.',

	'LOG_DELETE_PMS'			=> '<strong>MP supprimés selon le nombre de jour suivant :</strong><br />» %s',
	'NO_DELETE_PMS'				=> 'Au MP n’a été supprimé',
	'DELETEPMS_NOTICE'			=> '<div class="phpinfo" style="max-width:556px;margin-right:auto;margin-left:auto;"><p class="entry">Les paramètres de cette extension sont accessibles depuis : %1$s » %2$s » %3$s.</p></div>',
));
