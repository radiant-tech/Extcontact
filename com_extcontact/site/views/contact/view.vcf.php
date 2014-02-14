<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 */
class ExtcontactViewContact extends JViewLegacy
{
	protected $state;

	protected $item;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		
		// Get model data.
		$item = $this->get('Item');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		JFactory::getDocument()->setMetaData('Content-Type', 'text/directory', true);

		// Get the parameters
		$params = JComponentHelper::getParams('com_extcontact');
		// Merge the item parameters
		$params->merge($item->params);

		$item->name = trim($item->name);

		// Use sortname fields if present for lastname, firstname and middlename
		// Otherwise use older sorting method
		if (!empty($item->sortname1) && !empty($item->sortname2))
		{
			$lastname = (string) $item->sortname1;
			$firstname = (string) $item->sortname2;
			$middlename = (!empty($item->sortname3) ? (string) $item->sortname3 : '');
			$card_name = $firstname . ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;
		}
		else 
		{
			// "Lastname, Firstname Midlename" format support
			// e.g. "de Gaulle, Charles"
			$namearray = explode(',', $item->name);
			if (count($namearray) > 1 )
			{
				$lastname = $namearray[0];
				$card_name = $lastname;
				$name_and_midname = trim($namearray[1]);
			
				$firstname = '';
				if (!empty($name_and_midname))
				{
					$namearray = explode(' ', $name_and_midname);
			
					$firstname = $namearray[0];
					$middlename = (count($namearray) > 1) ? $namearray[1] : '';
					$card_name = $firstname . ' ' . ($middlename ? $middlename . ' ' : '') .  $card_name;
				}
			}
			// "Firstname Middlename Lastname" format support
			else {
				$namearray = explode(' ', $item->name);
			
				$middlename = (count($namearray) > 2) ? $namearray[1] : '';
				$firstname = array_shift($namearray);
				$lastname = count($namearray) ? end($namearray) : '';
				$card_name = $firstname . ($middlename ? ' ' . $middlename : '') . ($lastname ? ' ' . $lastname : '');
			}
		}
		
		// Set up address fields
		$address	= (!empty($item->address) ? $item->address : $params->get('default_street_address', ''));
		$suburb		= (!empty($item->suburb) ? $item->suburb : $params->get('default_suburb', ''));
		$state		= (!empty($item->state) ? $item->state : $params->get('default_state', ''));
		$postcode	= (!empty($item->postcode) ? $item->postcode : $params->get('default_postcode', ''));
		$country	= (!empty($item->country) ? $item->country : ''); 

		$rev = date('c', strtotime($item->modified));

		$app->setHeader('Content-disposition', 'attachment; filename="'.$card_name.'.vcf"', true);

		$vcard = array();
		$vcard[] .= 'BEGIN:VCARD';
		$vcard[] .= 'VERSION:3.0';
		$vcard[]  = 'N:'.$lastname.';'.$firstname.';'.$middlename;
		$vcard[]  = 'FN:'. $item->name;
		$vcard[]  = 'TITLE:'.$item->con_position;
		$vcard[]  = 'TEL;TYPE=WORK,VOICE:'.$item->telephone;
		$vcard[]  = 'TEL;TYPE=WORK,FAX:'.$item->fax;
		$vcard[]  = 'TEL;TYPE=WORK,MOBILE:'.$item->mobile;
		$vcard[]  = 'ADR;TYPE=WORK:;;'.$address.';'.$suburb.';'.$state.';'.$postcode.';'.$country;
		$vcard[]  = 'LABEL;TYPE=WORK:'.$address."\n".$suburb."\n".$state."\n".$postcode."\n".$country;
		$vcard[]  = 'EMAIL;TYPE=PREF,INTERNET:'.$item->email_to;
		$vcard[]  = 'URL:'.$item->webpage;
		$vcard[]  = 'REV:'.$rev.'Z';
		$vcard[]  = 'END:VCARD';

		echo implode("\n", $vcard);
		return true;
	}
}
