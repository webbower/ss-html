<?php

class HTML extends Object {
	
	private static $profile = 'xhtml11';
	
	private static $xml = true;

	private static $xml_empty_closer = ' /';

	private static $single_boolean_attrs = false;

	private static $attr_quotes_optional = false;

	private static $empty_tags = array(
		'img' => true,
		'br' => true,
		'hr' => true,
		'meta' => true,
		'embed' => true,
	);
	
	private static $supported_profiles = array(
		'html5',
		'html5-xml',
		'html4',
		'xhtml1-trans',
		'xhtml1-strict',
		'xhtml11',
	);
	
	static private function attrs($attrs=array()) {
		$out = '';
		foreach($attrs as $name => $value) {
			$out .= " {$name}=\"{$value}\"";
		}
		return $out;
	}

	static private function is_empty_tag($name) {
		return isset(self::$empty_tags[$name]);
	}

	// PUBLIC METHODS
	static public function tag($tagname, $content='', $attrs=array()) {
		$tag = "<{$tagname}";

		if(count($attrs) > 0) {
			$tag .= self::attrs($attrs);
		}
		
		if(self::is_empty_tag($tagname)) {
			if(self::$xml) {
				$tag .= self::$xml_empty_closer;
			}
			
			$tag .= '>';

		} else {

			$tag .= '>';
			if($content) {
				$tag .= $content;
			}
			$tag .= "</{$tagname}>";
		}
		
		return $tag;
	}
	
	static public function doctype() {
		$doctype = '';

		switch(self::$profile) {
			case 'html5':
			case 'html5-xml':
				$doctype = '<!DOCTYPE html>';
				break;
			case 'xhtml11':
				$doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
				break;
			case 'xhtml1-strict':
				$doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
				break;
			case 'xhtml1-trans':
				$doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
				break;
			case 'html4-trans':
				$doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
				break;
			case 'html4-strict':
				$doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
				break;
		}
		
		return $doctype;
	}
	
	static public function set_profile($profile) {
		if(!in_array($profile, self::$supported_profiles)) {
			return false;
		}
		
		switch(self::$profile) {
			case 'html5':
			case 'html4-strict':
			case 'html4-trans':
				self::$xml = false;
				self::$single_boolean_attrs = true;
				self::$attr_quotes_optional = true;
				break;
			case 'html5-xml':
			case 'xhtml11':
			case 'xhtml1-strict':
			case 'xhtml1-trans':
				self::$xml = true;
				self::$single_boolean_attrs = false;
				self::$attr_quotes_optional = false;
				break;
		}

		self::$profile = $profile;
	}

	static public function get_profile() {
		return self::$profile;
	}
}