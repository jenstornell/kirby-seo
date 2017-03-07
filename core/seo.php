<?php

class Seo {
	public $type;
	public $content;
	
	public function __construct($type) {
		$this->type = $type;
		
		$controller = SeoCore::panel(page());
		$this->content = $controller[$type]['full-replaced'];
	}
	
	public function __toString() {
		$html = '';
		
		if ($this->type == 'title') {
			$html = '<title>' . $this->content . '</title>' . PHP_EOL;
		} elseif (($this->type == 'description') && (!empty($this->content))) {
			$html = '<meta name="description" content="' . $this->content . '">' . PHP_EOL;
		}
		
		return (string)$html;
	}
	
	public function value() {
		return (string)$this->content;
	}
}
