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

# =============================================
# THE PERSONS WIDGETS
# =============================================
class CBY_Persons_Widget extends WP_Widget
{
	protected $paymode;
	protected $deftitle;
	protected $defemptymsg;
	
	function __construct($widgetname, $paymode, $deftitle, $defemptymsg)
	{
		parent::__construct(false, $name = $widgetname);
		$this->paymode = $paymode;
		$this->deftitle = $deftitle;
		$this->defemptymsg = $defemptymsg;
	}

	function register()
	{
		register_widget(get_class());
	}
	
	function widget($args, $instance)
	{
		global $em;
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		$emptymsg = $instance['emptymsg'];
		
		if ($this->paymode == 'ALL')
		{
			$q = $em->createQuery("select p from Entities\Person p");
		}
		else if ($this->paymode == 'YES')
		{
			$q = $em->createQuery("select p from Entities\Person p where p.datepaid IS NOT NULL");
		}
		else if ($this->paymode == 'NO')
		{
			$q = $em->createQuery("select p from Entities\Person p where p.datepaid IS NULL");
		}
		
		$persons = $q->getResult();
		
		$personStrings = array();
		foreach ($persons as $person)
		{
			$personStrings[] = '<li>'.htmlspecialchars($person->getFullName()).'</li>';
		}
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		if (count($personStrings))
		{
			echo '<ul>'.implode('', $personStrings).'</ul>';
		}
		else
		{
			echo $emptymsg;
		}
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		return $new_instance;
	}
	
	function form($instance)
	{
		$instance = wp_parse_args((array)$instance, array(
			'title' => $this->deftitle,
			'emptymsg' => $this->defemptymsg,
		));
		
		$title = $instance['title'];
		$emptymsg = $instance['emptymsg'];
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo htmlspecialchars($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('emptymsg'); ?>"><?php _e('Empty Msg:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('emptymsg'); ?>" name="<?php echo $this->get_field_name('emptymsg'); ?>" type="text" value="<?php echo htmlspecialchars($emptymsg); ?>" />
		</p>
		<?php
	}
}

class CBY_Persons_Widget_Yes extends CBY_Persons_Widget
{
	function __construct()
	{
		parent::__construct("CBY Persons Paid", "YES", "Anmälda och Betalda", "<p><em>Här var det tomt</em></p>");
	}
}

class CBY_Persons_Widget_No extends CBY_Persons_Widget
{
	function __construct()
	{
		parent::__construct("CBY Persons Nonpaid", "NO", "Inväntar Betalning", "<p><em>Här var det tomt</em></p>");
	}
}

class CBY_Persons_Widget_All extends CBY_Persons_Widget
{
	function __construct()
	{
		parent::__construct("CBY Persons All", "ALL", "Inväntar Betalning", "<p><em>Här var det tomt</em></p>");
	}
}

# =============================================
# THE MAIN PLUGIN STATIC CLASS
# =============================================

class CBY
{
	public static $selects = array('ConsumerOption', 'ShirtOption', 'BedOption', 'OilOption');

	# =============================================
	# INIT
	# =============================================
	
	public static function init()
	{
		wp_register_script('jquery.validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.js', array('jquery'));
		wp_register_script('jquery.tablesorter', plugins_url('js/jquery.tablesorter.js', __FILE__), array('jquery'));
		
		wp_register_script('jquery.autogrow', plugins_url('js/jquery.autogrow.js', __FILE__), array('jquery'));
		wp_register_script('jquery.maskedinput', plugins_url('js/jquery.maskedinput-1.3.js', __FILE__), array('jquery'), "1.3");
		
		wp_register_script('jquery.jeditable', plugins_url('js/jquery.jeditable.js', __FILE__), array('jquery'));
		wp_register_script('jquery.jeditable.autogrow', plugins_url('js/jquery.jeditable.autogrow.js', __FILE__), array('jquery.jeditable', 'jquery.autogrow'));
		wp_register_script('jquery.jeditable.masked', plugins_url('js/jquery.jeditable.masked.js', __FILE__), array('jquery.jeditable', 'jquery.maskedinput'));
		
		wp_register_script('cbyfront.js', plugins_url('js/front.js', __FILE__), array('jquery', 'jquery.validate', 'jquery.tablesorter', 'jquery.jeditable', 'jquery.jeditable.autogrow', 'jquery.jeditable.masked'));
		wp_register_script('cbyback.js', plugins_url('js/back.js', __FILE__), array('jquery', 'jquery.validate', 'jquery.tablesorter', 'jquery.jeditable', 'jquery.jeditable.autogrow', 'jquery.jeditable.masked'));
		wp_register_script('cbyboth.js', plugins_url('js/both.js', __FILE__), array('jquery', 'jquery.validate', 'jquery.tablesorter', 'jquery.jeditable', 'jquery.jeditable.autogrow', 'jquery.jeditable.masked'));
		
		wp_enqueue_script('cbyboth.js');
		if (is_admin())
		{
			wp_enqueue_script('cbyback.js');
		}
		else
		{
			wp_enqueue_script('cbyfront.js');
		}
		
		wp_register_style('cbystyle', plugins_url('style.css', __FILE__));
		wp_enqueue_style('cbystyle');
		
		add_shortcode('cbystatus', array(__CLASS__, 'status_shortcode'));	
		
		add_action('init', array(__CLASS__, 'signup_process'));
		add_shortcode('cbysignup', array(__CLASS__, 'signup_shortcode'));	
		
		add_action('admin_menu', array(__CLASS__, 'register_cby_menu_page'));
		add_action('widgets_init', array(__CLASS__, 'register_cby_widgets'));
		add_action('wp_ajax_cby_person_save', array(__CLASS__, 'ajax_cby_person_save'));
		
		// Print The JS data.
		add_action('admin_head', array(__CLASS__, 'print_js_head_data'));
		add_action('wp_head', array(__CLASS__, 'print_js_head_data'));
	}
	
	# =============================================
	# REGISTER
	# =============================================
	
	public static function register_cby_menu_page()
	{
		add_menu_page('Anmälda till CBY', 'CBY', 'administrator', 'cby/page/list.php', '', plugins_url('cby/img/icon.png'), 6);
	}
	
	public static function register_cby_widgets()
	{
		// register the widgets
		register_widget("CBY_Persons_Widget_Yes");
		register_widget("CBY_Persons_Widget_No");
		register_widget("CBY_Persons_Widget_All");
	}
	
	# =============================================
	# AJAX
	# =============================================
	
	public static function print_js_head_data()
	{
		echo '<script type="text/javascript">'."\n";
		echo 'var cbydata = ' . json_encode(self::get_js_head_data());
		echo "\n".'</script>'."\n";
	}
	
	public static function get_js_head_data()
	{
		$ret = array();
		$ret['editoption'] = self::get_editoption_datas();
		return $ret;
	}
	
	public static function get_editoption_datas()
	{
		global $cbyconf;
		$ret = array();
		foreach ($cbyconf['fieldinfo'] as $key => $info)
		{
			if ( ! $info['option']) continue;
			$ret[$key] = self::get_editoption_data($info['type']);
		}
		return $ret;
	}
	
	public static function get_editoption_data($option)
	{
		global $em;
		$response = array();
		
		// Generate the response
		$entities = $em->getRepository('Entities\\'.$option)->findAll();
		foreach($entities as $entity)
		{
			$response[$entity->getId()] = $entity->getDesc();
		}
		
		// Response output
		return $response;
	}
	
	// http://www.appelsiini.net/projects/jeditable
	public static function ajax_cby_person_save()
	{
		global $em;
		global $cbyconf;
		$val = $_POST['value'];
		$exploded = explode(".",$_POST['id']);
		$person_id = $exploded[1];
		$key = $exploded[2];
		$field_info = $cbyconf['fieldinfo'][$key];
		
		// Save the new val
		$person = $em->getRepository("Entities\Person")->find($person_id);
		$person->{"set".$key}($val);
		$em->flush();
		
		// Response output
		header( "Content-Type: text/plain" );
		echo $person->{'desc'.$key}();
		exit;
	}
	
	# =============================================
	# THE STATUS
	# =============================================
	
	public static function status_shortcode($atts)
	{
		global $em;
		global $cbyconf;
		$ret = '';
		
		$code = $_GET['code'];
		$person = null;
		$error = false;
		
		if ($code)
		{
			$person = $em->getRepository('Entities\\Person')->findOneByCode($code);
			if ( ! $person)
			{
				$ret .= '<p class="cby_error">Invalid Code D:<p>';
				$error = true;
			}
		}
		else
		{
			$error = true;
		}
		
		if ($error)
		{
			$ret = '<form method="GET"><strong>Ange din kod:</strong><br><input type="text" name="code"/><input type="submit" value="OK"/></form>' . $ret;
			return $ret;
		}
		
		$payto = $cbyconf['economy']['payto'];
		
		$ret .= '<p>';
		$ret .= '<strong>Namn:</strong> ' . htmlspecialchars($person->getFullName()) . '<br>';
		$ret .= '<strong>Personnummer:</strong> ' . htmlspecialchars($person->getSsn()) . '<br>';
		$ret .= '<strong>Email:</strong> ' . htmlspecialchars($person->getEmail()) . '<br>';
		$ret .= '<strong>Mobil:</strong> ' . htmlspecialchars($person->getPhone()) . '<br>';
		$ret .= '<strong>Allergier: </strong>'.str_replace("\n", "\n<br>", htmlspecialchars($person->getAllergycomment())) . '<br>';
		$ret .= '<strong>Kommentar: </strong>'.str_replace("\n", "\n<br>", htmlspecialchars($person->getComment())) . '<br>';
		$ret .= '</p>';
		
		$ret .= '<p>';
		$ret .= 'Du har betalat <strong>'.$person->getAmountPaid().' av '.$person->getPriceSum().'kr</strong>.' . '<br>';
		$ret .= 'Betalning ska göras till <strong>' . $payto . '</strong>.';
		$ret .= '</p>';
		
		$ret .= '<ul>';
		foreach ($person->getPriceLines() as $key => $val)
		{
			$ret .= '<li>';
			$ret .= $key;
			$ret .= ': ';
			$ret .= $val;
			$ret .= 'kr';
			$ret .= '</li>';
		}
		$ret .= '</ul>';
		
		$ret .= '<p>';
		$ret .= '<strong>Intyg från målsman inlämnat:</strong> ' . htmlspecialchars($person->descReceivedGuardianOption()) . '<br>';
		$ret .= '<strong>Incheckad:</strong> ' . htmlspecialchars($person->descCheckedIn()) . '<br>';
		$ret .= '<strong>Fått T-Shirt:</strong> ' . htmlspecialchars($person->descReceivedShirtOption()) . '<br>';
		$ret .= '<strong>Fått Lampolja:</strong> ' . htmlspecialchars($person->descReceivedOilOption()) . '<br>';
		$ret .= '</p>';
		
		return $ret;
	}
	
	# =============================================
	# THE SIGNUP FORM
	# =============================================
	
	public static function signup_process()
	{
		global $cbyconf;
		global $cbysignup_errors;
		global $cbysignup_data;
		
		$cbysignup_data = self::get_signup_postdata();
		
		// Did someone post?
		$frontfields = $cbyconf['frontfields'];
		if (isset($_POST[$frontfields[0]]) && wp_verify_nonce($_POST['cbysignup_nonce'], 'cbysignup'))
		{
			$cbysignup_errors = self::get_errors_from_signup_postdata($cbysignup_data);
			
			// No errors?? return success message instead!
			if (count($cbysignup_errors) == 0)
			{
				$person = self::create_person_from_data($cbysignup_data);
				
				$msg = '';
				$msg .= 'Hejsan \:D/'."\n";
				$msg .= "\n";
				$msg .= 'Detta är din kod: '.$person->getCode()."\n";
				$msg .= 'Detta är din nya favoritsida: '.$person->getStatusLink()."\n";
				$msg .= "\n";
				$msg .= 'Favoritsidan uppdateras tex när du betalat.'."\n";
				$msg .= 'Kolla ofta och alltid :P';
				
				wp_mail($person->getEmail(), 'Din CBY-Kod', $msg);
				$location = site_url('/status?code='.$person->getCode());
				wp_redirect($location);
				exit;
				//return $person->getMessagePaymentToDo();
			}
		}
	}
	
	public static function signup_shortcode($atts)
	{
		global $em;
		global $cbyconf;
		global $cbysignup_errors;
		global $cbysignup_data;
		
		$frontfields = $cbyconf['frontfields'];
		
		// Build the form
		$ret = '<form id="cby_signupform" method="post"><table>';
		$ret .= wp_nonce_field('cbysignup', 'cbysignup_nonce', true, false);
		
		foreach ($frontfields as $key)
		{
			$info = $cbyconf['fieldinfo'][$key];
			$name = $info['frontname'];
			$type = $info['type'];
			$required = $info['required'];
			
			$ret .= '<tr><td class="col1"><label for="'.$key.'">'.$name.'</label><br>';
			if (in_array($type, array('text', 'email', 'ssn')))
			{
				$ret .= '<input id="'.$key.'" name="'.$key.'" type="text" value="'.htmlspecialchars($cbysignup_data[$key]).'">';
			}
			else if ($type == 'textarea')
			{
				$ret .= '<textarea id="'.$key.'" name="'.$key.'">'.htmlspecialchars($cbysignup_data[$key]).'</textarea>';
			}
			else if ($info['option'])
			{
				$ret .= '<select id="'.$key.'" name="'.$key.'">';
				
				foreach ($em->getRepository('Entities\\'.$type)->findAll() as $option)
				{
					$disabled = '';
					if ( ! $option->getAvailable())
					{
						$disabled = ' disabled="disabled"';
					}
					
					$selected = '';
					if ($cbysignup_data[$key] == $option->getId())
					{
						$selected = ' selected="selected"';
					}
				
					$ret .= '<option value="'.$option->getId().'"' . $selected . $disabled . '>'.htmlspecialchars($option->getDesc()).'</option>';
				}
				$ret .= '</select>';
			}
			
			$ret .= '</td><td class="col2 cby_error">'.((isset($cbysignup_errors) AND isset($cbysignup_errors[$key])) ? $cbysignup_errors[$key] : '').'</td></tr>';
		}
		$ret .= '<tr><td colspan="2"><input type="submit" value="Sign me up!"></td></tr></table></form>';
		
		return $ret;
	}
	
	public static function create_person_from_data($data)
	{
		global $em;
		$p = new Person;
		$p->setFromArray($data);
		$p->setReceivedDefaults();
		$em->persist($p);
		$em->flush();
		return $p;
	}
	
	public static function get_signup_postdata()
	{
		global $cbyconf;
		$ret = array();
		
		foreach ($cbyconf['frontfields'] as $frontfield)
		{
			$ret[$frontfield] = (isset($_POST[$frontfield]) ? trim($_POST[$frontfield]) : '');
			
			// Strip the horrible slashes.
			$ret[$frontfield] = stripslashes($ret[$frontfield]);
			
			// Do some extra fixes
			$info = $cbyconf['fieldinfo'][$frontfield];
			if ($info['type'] == 'ssn')
			{
				// Remove non digits from the social security number.
				$ret[$frontfield] = preg_replace('/[^0-9]*/','', $ret[$frontfield]);
				// Prepend '19' if length is 10
				if (strlen($ret[$frontfield]) == 10)
				{
					$ret[$frontfield] = '19'.$ret[$frontfield];
				}
			}
		}
		
		return $ret;
	}
	
	public static function get_errors_from_signup_postdata($data)
	{
		global $em;
		global $cbyconf;
		$ret = array();
		
		foreach ($data as $key => $val)
		{
			$info = $cbyconf['fieldinfo'][$key];
			$name = $info['frontname'];
			$type = $info['type'];
			$required = $info['required'];
			
			if ($required && $val == '')
			{
				$ret[$key] = 'Obligatorisk';
			}
			else if ($info['option'])
			{
				$o = $em->find('Entities\\'.$type, $val);
				if ( ! $o)
				{
					$ret[$key] = 'Välj ett existerande alternativ tack.';
				}
				else if ( ! $o->getAvailable())
				{
					$ret[$key] = 'Välj ett tillgängligt alternativ tack.';
				}
			}
			else if($type == 'email')
			{
				if ( ! self::valid_email($val))
				{
					$ret[$key] = 'Ej korrekt email';
				}
				else if ($em->getRepository('Entities\Person')->findByEmail($val))
				{
					$ret[$key] = 'Denna email är redan använd.';
				}
			}
			else if($type == 'ssn')
			{
				if ( ! self::valid_swedish_ssn($val))
				{
					$ret[$key] = 'Kontakta en arrangör om du saknar svenskt personnummer.';
				}
				else if ($em->getRepository('Entities\Person')->findBySsn($val))
				{
					$ret[$key] = 'Detta personnummer är redan använt.';
				}
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
	
	# =============================================
	# UTIL
	# =============================================
	public static function gencode($len)
	{
		$ret = "";
		$chars = "2346789ABCDEFGHJKMNPQRTUVWXYZ";
		$charslen = strlen($chars);
		for($i = 0; $i < $len; $i++)
		{
			$char = substr($chars, mt_rand(0, $charslen-1), 1);
			$ret .= $char;
		}
		return $ret;
	}
}
CBY::init();