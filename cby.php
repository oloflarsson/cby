<?php
/*
Plugin Name: CBY
Plugin URI: http://campburnyourself.se
Description: desc desc desc.
Version: 1.0
Author: Olof Larsson
Author URI: http://oloflarsson.se
*/

use Entities\Person;

require_once 'bootstrap.php';

class CBY
{	
	public static function init()
	{
		wp_register_script('jquery.validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.js', array('jquery'));
		wp_register_script('cbyscript', plugins_url('script.js', __FILE__), array('jquery', 'jquery.validate'));
		wp_register_style('cbystyle', plugins_url('style.css', __FILE__));
		
		wp_enqueue_script('jquery.validate');
		wp_enqueue_script('cbyscript');
		wp_enqueue_style('cbystyle');
		
		add_shortcode('cbysignup', array(__CLASS__, 'shortcode_cbysignup'));
		
		/*global $em;
		
		$person = new Person;
		$person->setFirstname('Sven');
		$person->setNick('Svempa');
		$person->setLastname('Svensson');
		$person->setSsn(1212121212);
		$person->setPhone('070121212');
		$person->setComment('Wooop opoop po poop po o popo');
		$person->setAllergycomment('jag er elergisk mot hest');
		
		$em->persist($person);
		$em->flush();*/
	}
	
	# =============================================
	# THE SIGNUP FORM
	# =============================================
	
	public static $signupfields = array(
	'firstname' => array('Förnamn', 'text'),
	'nick' => array('Eldsjäls-nick', 'text'),
	'lastname' => array('Efternamn', 'text'),
	'ssn' => array('Personnummer', 'text'),
	'email' => array('Email', 'text'),
	'phone' => array('Mobilnummer', 'text'),
	'tshirttype' => array('T-Shirt', 'tshirttypes'),
	'consumertype' => array('Mat', 'consumertypes'),
	'allergycomment' => array('Allergier', 'textarea'),
	'comment' => array('Kommentar', 'textarea'),
	);
	
	public static function shortcode_cbysignup($atts)
	{
		global $em;
		$data = self::get_signup_postdata();
		
		// Did someone post?
		if (isset($_POST['firstname']) && wp_verify_nonce($_POST['cby_signup_nonce'], 'cby_signup'))
		{
			$errors = self::get_errors_from_signup_postdata($data);
			
			// No errors?? return success message instead!
			if (count($errors) == 0)
			{
				$person = self::create_person_from_data($data);
				return "SUCCESS! :D Skriv ut info om att mail har skickats, vad som ska betalas etc.";
			}
		}
		
		// Build the form
		$ret = '<form id="cby_signupform" method="post"><table>';
		$ret .= wp_nonce_field('cby_signup', 'cby_signup_nonce', true, false);
		
		foreach (self::$signupfields as $field_id => $field_settings)
		{
			$ret .= '<tr><td class="col1"><label for="'.$field_id.'">'.$field_settings[0].'</label><br>';
			if ($field_settings[1] == 'text')
			{
				$ret .= '<input id="'.$field_id.'" name="'.$field_id.'" type="text" value="'.htmlspecialchars($data[$field_id]).'">';
			}
			else if ($field_settings[1] == 'textarea')
			{
				$ret .= '<textarea id="'.$field_id.'" name="'.$field_id.'">'.htmlspecialchars($data[$field_id]).'</textarea>';
			}
			else if ($field_settings[1] == 'tshirttypes')
			{
				$ret .= '<select id="'.$field_id.'" name="'.$field_id.'">';
				
				foreach ($em->getRepository('Entities\TShirtType')->findAll() as $type)
				{
					$ret .= '<option data-price="'.$type->getPrice().'" value="'.$type->getId().'"' . ($data[$field_id] == $type->getId() ? 'selected="selected"': '') . '>'.$type->getName().' - '.$type->getPrice().'kr</option>';
				}
				$ret .= '</select>';
			}
			else if ($field_settings[1] == 'consumertypes')
			{
				$ret .= '<select id="'.$field_id.'" name="'.$field_id.'">';
				
				foreach ($em->getRepository('Entities\ConsumerType')->findAll() as $type)
				{
					$ret .= '<option value="'.$type->getId().'"' . ($data[$field_id] == $type->getId() ? 'selected="selected"': '') . '>'.$type->getName().'</option>';
				}
				$ret .= '</select>';
			}
			
			$ret .= '</td><td class="col2 cby_error">'.((isset($errors) AND isset($errors[$field_id])) ? $errors[$field_id] : '').'</td></tr>';
		}
		$ret .= '<tr><td colspan="2"><input type="submit" value="Sign me up!"></td></tr></table></form>';
		
		return $ret;
	}
	
	public static function create_person_from_data($data)
	{
		global $em;
		
		$p = new Person;
		$p->setFromArray($data);
		$em->persist($p);
		$em->flush();
		
		// Skicka email.
		
		return $p;
	}
	
	public static function get_signup_postdata()
	{
		$ret = array();
		foreach (self::$signupfields as $field_id => $field_settings)
		{
			$ret[$field_id] = (isset($_POST[$field_id]) ? trim($_POST[$field_id]) : '');
		}
		
		// Remove non digits from the social security number.
		$ret['ssn'] = preg_replace('/[^0-9]*/','', $ret['ssn']);
		// Prepend '19' if length is 10
		if (strlen($ret['ssn']) == 10)
		{
			$ret['ssn'] = '19'.$ret['ssn'];
		}
		
		return $ret;
	}
	
	public static function get_errors_from_signup_postdata($data)
	{
		global $em;
		$ret = array();
		
		if ( ! $em->find('Entities\TShirtType', $data['tshirttype']))
		{
			$ret['tshirttype'] = 'Välj ett existerande alternativ tack.';
		}
		
		if ( ! $em->find('Entities\ConsumerType', $data['consumertype']))
		{
			$ret['consumertype'] = 'Välj ett existerande alternativ tack.';
		}
		
		if ($em->getRepository('Entities\Person')->findByEmail($data['email']))
		{
			$ret['email'] = 'Denna email är redan använd.';
		}
		
		if ($em->getRepository('Entities\Person')->findBySsn($data['ssn']))
		{
			$ret['email'] = 'Detta personnummer är redan använt.';
		}
		
		if ( ! self::valid_swedish_ssn($data['ssn']))
		{
			$ret['ssn'] = 'Kontakta en arrangör om du saknar svenskt personnummer.';
		}
		
		if ( ! self::valid_email($data['email']))
		{
			$ret['email'] = 'Ej korrekt email';
		}
		
		foreach ($data as $field_id => $field_val)
		{
			if ($field_id == 'nick' || $field_id == 'allergycomment' || $field_id == 'comment') continue;
			
			if ($field_val == '')
			{
				$ret[$field_id] = 'Obligatorisk';
			}
		}
		
		return $ret;
	}
	
	public static function valid_swedish_ssn($ssn)
	{
		if (strlen($ssn) == 12) 
		{
			$ssn = substr($ssn, 2);
		}
		
		if ( ! preg_match("/^[0-9]{10}$/", $ssn)) return false;
		
		$n = 2;
		for ($i=0; $i<9; $i++)
		{
			$tmp = $ssn[$i] * $n; 
			($tmp > 9) ? $sum += 1 + ($tmp % 10) : $sum += $tmp; ($n == 2) ? $n = 1 : $n = 2; 
		}

		return ! ( ($sum + $ssn[9]) % 10);
	}
	
	public static function valid_email($email)
	{
		return preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email);
	}
}
CBY::init();