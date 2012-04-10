# SilverStripe HTML Class

The purpose of this class is to faciliate consistent output of HTML tags from within PHP code. Features include:

* Consistently use XML or non-XML rules (duplicated boolean attributes, self-closing empty tags, optional quotes on attribute values, etc)
* Output appropriate DOCTYPE
* Choose an output profile to do a batch configuration (Profiles)

## Supported profiles

* HTML5 (XML or non-XML style)
* XHTML 1.1
* XHTML 1.0 Transitional or Strict
* HTML4

## Setup

In _config.php, add `HTML::set_profile($profile)`. `$profile` can be any of the following:

* `html5`
* `html5-xml`
* `html4`
* `xhtml1-trans`
* `xhtml1-strict`
* `xhtml11`

The class defaults to `xhtml11` since that's what the 2.4.x Blackcandy template uses for its DOCTYPE.

## API

To choose a profile, use `HTML::set_profile($profile)` in your _config.php.

To get the current profile, use `HTML::get_profile()`.

You can output the appropriate DOCTYPE with `HTML::doctype()`.

To generate a tag in your PHP code, use `HTML::tag($tagname, $content, $attrs)` where:

* `$tagname` is the name of the tag
* `$content` is the tag content. Pass in empty string for empty tags (this arg will be ignored for empty tags anyway).
* `$attrs` is an array of key/value pairs that will be turned into HTML attributes. For boolean attributes (ones where the value repeats the key for XML compliance, e.g. `selected` for select menus) will be output as appropriate based on the selected profile.

## Example Uses

### Page::MetaTags

Used to override the default `SiteTree::MetaTags()` template method in your Page.php file. This example will output a meta-charset tag appropriate to the version of (X)HTML you're using, output the title tag if requested (in the best practice order), and apply trailing slashes to the meta tags if appropriate.

```PHP
public function MetaTags($includeTitle = true) {
	$tags = "";

	$charset = ContentNegotiator::get_encoding();
	if(strpos(HTML::get_profile(), 'html5') === 0) {
		$tags .= HTML::tag('meta', '', array('charset' => $charset)) . "\n";
	} else {
		$tags .= HTML::tag('meta', '', array(
			'http-equiv' => 'Content-Type',
			'content' => "text/html; charset=$charset",
		)) . "\n";
	}
	
	if($includeTitle === true || $includeTitle == 'true') {
		$tags .= HTML::tag('title', Convert::raw2xml(($this->MetaTitle) ? $this->MetaTitle : $this->Title)) . "\n";
	}

	if($this->MetaKeywords) {
		$tags .= HTML::tag('meta', '', array(
			'name' => 'keywords',
			'content' => Convert::raw2att($this->MetaKeywords),
		)) . "\n";
	}
	if($this->MetaDescription) {
		$tags .= HTML::tag('meta', '', array(
			'name' => 'description',
			'content' => Convert::raw2att($this->MetaDescription),
		)) . "\n";
	}
	if($this->ExtraMeta) { 
		$tags .= $this->ExtraMeta . "\n";
	} 

	$this->extend('MetaTags', $tags);

	return $tags;
}
```

## TODO

* Make attribute output properly handle boolean attributes
* Downgrade HTML5 tags when using non-HTML5 profiles
* Allow output of unquoted attributes