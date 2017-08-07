<?php
class SeoCore {
	public static function panel( $page, $field = null ) {
		$arrays = self::getCallBackArrays($page);

		$array = array();

		$array = self::getValues( $array, $arrays );

		$array = self::getField('title', $array, $page );
		$array = self::getField('description', $array, $page );

		$array = self::getMeta( 'title', 'template', $array, $arrays );
		$array = self::getMeta( 'title', 'prefix', $array, $arrays );
		$array = self::getMeta( 'title', 'suffix', $array, $arrays );

		$array = self::getMeta( 'description', 'template', $array, $arrays );
		$array = self::getMeta( 'description', 'prefix', $array, $arrays );
		$array = self::getMeta( 'description', 'suffix', $array, $arrays );

		if( isset( $field ) ) {
			$array = self::convertChars('title', 'field', $array);
			$array = self::convertChars('title', 'template', $array);
			$array = self::convertChars('title', 'prefix', $array);
			$array = self::convertChars('title', 'suffix', $array);

			$array = self::convertChars('description', 'field', $array);
			$array = self::convertChars('description', 'template', $array);
			$array = self::convertChars('description', 'prefix', $array);
			$array = self::convertChars('description', 'suffix', $array);
		} else {
			$array = self::convertCharsTitle('title', 'field', $array);
			$array = self::convertCharsTitle('title', 'template', $array);
			$array = self::convertCharsTitle('title', 'prefix', $array);
			$array = self::convertCharsTitle('title', 'suffix', $array);

			$array = self::convertCharsDescription('description', 'field', $array);
			$array = self::convertCharsDescription('description', 'template', $array);
			$array = self::convertCharsDescription('description', 'prefix', $array);
			$array = self::convertCharsDescription('description', 'suffix', $array);
		}

		$array = self::titleBackup($array, $page);
		$array = self::descriptionBackup($array, $page, $field);

		$array = self::titleFallback($array, $page);
		$array = self::descriptionFallback($array, $page, $field);

		if( empty( trim( $array['description']['fallback'] ) ) && isset( $field ) ) {
			$array['description']['fallback'] = l::get('description.empty');
		}

		$array = self::full('title', $array);
		$array = self::full('description', $array);

		$array = self::replaceValue('title', 'prefix', $array, false);
		$array = self::replaceValue('title', 'suffix', $array, false);

		$array = self::replaceValue('description', 'prefix', $array, false);
		$array = self::replaceValue('description', 'suffix', $array, false);

		$array = self::replaceValue('title', 'full', $array);
		$array = self::replaceValue('description', 'full', $array);

		$array = self::fill('title', 'template', $array);
		$array = self::fill('title', 'prefix', $array);
		$array = self::fill('title', 'suffix', $array);
		$array = self::fill('title', 'fallback', $array);
		$array = self::fill('title', 'full', $array);

		$array = self::fill('description', 'template', $array);
		$array = self::fill('description', 'prefix', $array);
		$array = self::fill('description', 'suffix', $array);
		$array = self::fill('description', 'fallback', $array);
		$array = self::fill('description', 'full', $array);

		$array['description']['limit'] = c::get('seo.description.limit', 155);
		$array['url']['edit'] = self::editSlugUrl($page);
		$array['url']['preview'] = self::urlPreview( $page->url() );

		return $array;
	}

	// Fill empty values
	public static function fill($type, $key, $array) {
		if(
			isset( $array ) &&
			isset( $array[$type] ) &&
			isset( $array[$type][$key] )
		) {
		} else {
			$array[$type][$key] = '';
		}
		return $array;
	}

	// Full
	public static function full($type, $array) {
		$prefix = '';
		$suffix = '';
		$fallback = '';
		if( isset( $array[$type] ) && isset( $array[$type]['prefix'] ) ) {
			$prefix = $array[$type]['prefix'];
		}
		if( isset( $array[$type] ) && isset( $array[$type]['suffix'] ) ) {
			$suffix = $array[$type]['suffix'];
		}
		if( isset( $array[$type] ) && isset( $array[$type]['fallback'] ) ) {
			$fallback = $array[$type]['fallback'];
		}
		$array[$type]['full'] = $prefix . $fallback . $suffix;
		return $array;
	}

	// Title fallback
	public static function titleBackup($array, $page) {
		if( isset( $array['title'] ) ) {
			if( isset( $array['title']['template'] ) ) {
				$array['title']['backup'] = $array['title']['template'];
			} elseif( $page->title()->isNotEmpty() ) {
				$array['title']['backup'] = $page->title()->value();
			} else {
				$array['title']['backup'] = $page->slug();
			}
		}
		return $array;
	}

	// Title fallback with field value
	public static function titleFallback($array, $page) {
		if( isset( $array['title'] ) ) {
			if( ! empty( $array['title']['field'] ) ) {
				$array['title']['fallback'] = $array['title']['field'];
			} else {
				$backup = self::titleBackup($array, $page);
				if( isset( $backup['title']['backup'] ) ) {
					$array['title']['fallback'] = $backup['title']['backup'];
				}
			}
		}
		return $array;
	}

	// Description backup
	public static function descriptionBackup($array, $page, $field) {
		if( isset( $array['description'] ) ) {
			if( isset( $array['description']['template'] ) ) {
				$array['description']['backup'] = $array['description']['template'];
			} else {
				$array['description']['backup'] = self::texts($page, $field);
			}
		}
		return $array;
	}

	// Description fallback with field value
	public static function descriptionFallback($array, $page, $field) {
		if( isset( $array['description'] ) ) {
			if( ! empty( $array['description']['field'] ) ) {
				$array['description']['fallback'] = $array['description']['field'];
			} else {
				$backup = self::descriptionBackup($array, $page, $field);
				if( isset( $backup['description']['backup'] ) ) {
					$array['description']['fallback'] = $backup['description']['backup'];
				}
			}
		}
		return $array;
	}

	// Replace value
	public static function replaceValue( $type, $key, $array, $extend = true ) {
		$suffix = ( $extend === true ) ? '-replaced' : '';
		if( isset( $array[$type] ) && isset( $array[$type][$key] ) ) {
			if( isset( $array['values'] ) ) {
				$haystack = $array[$type][$key];
				foreach( $array['values'] as $needle => $value ) {
					$haystack = str_replace( '{{' . $needle . '}}', $value, $haystack );
				}
				$array[$type][$key . $suffix] = $haystack;
			} else {
				$array[$type][$key . $suffix] = $array[$type][$key];
			}
		}
		return $array;
	}

	// Get values
	public static function getValues($array, $arrays) {
		if( isset( $arrays ) ) {
			foreach( $arrays as $item ) {
				foreach( $item['values'] as $key => $value ) {
					$array['values'][$key] = (string)$value;
				}
			}
		}
		return $array;
	}

	// Convert chars
	public static function convertChars( $type, $key, $array ) {
		if( isset( $array[$type] ) && isset( $array[$type][$key] ) ) {
			$array[$type][$key] = str_replace( array("'"), array("&#039;"), $array[$type][$key] );
		}
		return $array;
	}

	// Convert chars
	public static function convertCharsTitle( $type, $key, $array ) {
		if( isset( $array[$type] ) && isset( $array[$type][$key] ) ) {
			$array[$type][$key] = htmlspecialchars($array[$type][$key], ENT_NOQUOTES, 'UTF-8');
		}
		return $array;
	}

	// Convert chars
	public static function convertCharsDescription( $type, $key, $array ) {
		if( isset( $array[$type] ) && isset( $array[$type][$key] ) ) {
			$array[$type][$key] = htmlspecialchars($array[$type][$key], ENT_COMPAT, 'UTF-8');
		}
		return $array;
	}

	// Get meta
	public static function getMeta( $type, $key, $array, $arrays ) {
		if( self::hasMeta( 'template', $type, $key, $arrays ) ) {
			$array[$type][$key] = $arrays['template'][$type][$key];
		} elseif( self::hasMeta( 'site', $type, $key, $arrays ) ) {
			$array[$type][$key] = $arrays['site'][$type][$key];
		}

		return $array;
	}

	// Check if meta exist
	public static function hasMeta( $state, $type, $key, $arrays ) {
		if(
		isset( $arrays[$state] ) &&
		isset( $arrays[$state][$type] ) &&
		isset( $arrays[$state][$type][$key] ) ) {
			return true;
		}
	}

	// Get callback arrays
	public static function getCallBackArrays($page) {
		$callbacks['site'] = self::callback($page, 'site');
		$callbacks['template'] = self::callback($page, $page->intendedTemplate());
		$callback_arrays = self::callbacksToArrays($callbacks, $page);
		return $callback_arrays;
	}

	// Load callback from file
	public static function callback($page, $filename) {
		$dir = c::get( 'seo.controller.path', kirby()->roots()->controllers() . DS . 'seo');
		$filename = $filename . '.php';
		$path = $dir . DS . $filename;

		if( file_exists( $path ) ) {
			return require $path;
		}
	}

	// Convert callbacks to array
	public static function callbacksToArrays($callbacks, $page) {
		$arrays = array();
		foreach( $callbacks as $key => $callback ) {
			if( is_callable( $callback) ) {
				$array = (array)call_user_func_array($callback, array(
					site(),
					site()->children(),
					$page,
				));
				$arrays[$key] = $array;
			}
		}
		return $arrays;
	}

	// Uri
	public static function uri($url) {
		$url_parts = parse_url($url);
		$uri = substr( $url_parts['path'], 1 );
		return $uri;
	}

	// Url preview
	public static function urlPreview( $url ) {
		$url_parts = parse_url($url);
		$scheme = ( $url_parts['scheme'] == 'https' ) ? 'https://' : '';
		if( empty($url_parts['path'] ) ) {
			$uri = $scheme . $url_parts['host'];
		} else {
			$uri = $scheme . $url_parts['host'] . $url_parts['path'];
		}
		return $uri;
	}

	// Edit slug url
	public static function editSlugUrl($page) {
		$url = u() . '/panel/pages/' . $page->id() . '/url';
		return $url;
	}

	// Inside panel, get texts
	public static function texts( $page, $field = null ) {
		$textareas = '';
		if( $field ) {
			$fields = $field->page()->blueprint()->fields($field)->toArray();
			$textareas = self::textsByFields($page, $fields);
		}
		return $textareas;
	}

	public static function textsByFields($page, $fields) {
		$textareas = '';
		if( ! empty( $fields ) ) {
			foreach( $fields as $item ) {
				$name = $item['name'];
				$type = $item['type'];

				if( $type == 'textarea') {
					$textareas .= strip_tags( $page->$name()->kirbytext() ) . ' ';
				}
			}
			$textareas = preg_replace('/\s+/', ' ', $textareas);
			$textareas = htmlspecialchars($textareas, ENT_QUOTES, 'UTF-8');
		}
		return $textareas;
	}

	// Get field from content
	public static function getField($fieldname, $array, $page) {
		$seo_key = c::get('seo.field.key', 'seo');
		$array[$fieldname]['field'] = '';

		if( $page->$seo_key()->isNotEmpty() ) {
			$seo = $page->$seo_key()->yaml();

			//print_r($seo);

			if( ! empty( $seo ) && ! empty( $seo[0] ) && ! empty( $seo[0]['seo-' . $fieldname] ) ) {
				$array[$fieldname]['field'] = $seo[0]['seo-' . $fieldname];
			}
		}

		return $array;
	}

	// Check if plugin exists
	public static function hasPlugin() {
		$plugin_path = kirby()->roots()->plugins() . DS . 'seo';
		if( file_exists($plugin_path) ) {
			return true;
		}
	}

	// Load language file
	public static function loadLanguage() {
		$language_code = substr( site()->user()->language(), 0, 2 );

		$dir = __DIR__ . DS . '..' . DS . 'languages';
		$url = $dir . DS . $language_code . '.php';
		if( file_exists( $url ) ) {
			require_once $url;
		} else {
			require_once $dir . DS . 'en.php';
		}
	}

	// Snippet
	public static function snippet($filename, $data) {
		$path = __DIR__ . DS . '..' . DS . 'snippets';
		return tpl::load( $path . DS . $filename . '.php', $data );
	}
}