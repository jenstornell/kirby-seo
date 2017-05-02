<?php
require_once __DIR__ . DS . 'core' . DS . 'seocore.php';
require_once __DIR__ . DS . 'core' . DS . 'seo.php';

$kirby->set('field', 'seo',  __DIR__ . DS . 'fields' . DS . 'seo');
$kirby->set('field', 'seoarchive',  __DIR__ . DS . 'fields' . DS . 'seoarchive');

// Main seo function
function seo($type) {
	return new Seo($type);
}
