<?php

class HTML extends Object {
	
	private static $version = 'html5';
	
	private static $doctype = 'trans';
	
	private static $xml = false;

	private static $xml_empty_closer = ' /';

	private static $empty_tags = array('img' => 1, 'br' => 1, 'hr' => 1, 'meta' => 1);
	
	private static $supported_versions = array('html5', 'html4', 'xhtml1', 'xhtml11');
	
	static public function tag($name, $content='', $attrs=array()) {
		$tag = "<{$name}";

		if(count($attrs) > 0) {
			$tag .= self::attrs($attrs);
		}
		
		if(self::is_empty_tag($name)) {
			if(self::$xml) {
				$tag .= self::$xml_empty_closer;
			}
			
			$tag .= '>';

		} else {

			$tag .= '>';
			if($content) {
				$tag .= $content;
			}
			$tag .= "</{$name}>";
		}
		
		return $tag;
	}
	
	static private function attrs($attrs=array()) {
		$out = '';
		foreach ($attrs as $name => $value) {
			$out .= " {$name}=\"{$value}\"";
		}
		return $out;
	}
	
	static private function is_empty_tag($name) {
		return isset(self::$empty_tags[$name]);
	}

	static public function is_html5() {
		return self::get_version() == 'html5';
	}
	
	static public function is_html4() {
		return self::get_version() == 'html4';
	}
	
	static public function is_xhtml1() {
		return self::get_version() == 'xhtml1';
	}
	
	static public function is_xhtml11() {
		return self::get_version() == 'xhtml11';
	}
	
	static public function set_version($version) {
		if(!in_array($version, self::$supported_versions)) {
			return false;
		}
		
		if(strpos($version, 'xhtml') !== false) {
			self::$xml = true;
		} else {
			self::$xml = false;
		}
		
		self::$version = $version;
	}

	static public function get_version() {
		return self::$version;
	}
	
	static public function set_xml($bool) {
		if(self::is_html4()) {
			self::$xml = false;
		} else {
			self::$xml = (bool)$bool;
		}
	}
	
	static public function get_xml() {
		return self::$xml;
	}
	
	static public function is_xml() {
		return self::get_xml();
	}
	
}