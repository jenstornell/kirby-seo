# Controllers

There are two types of controllers.

1. Template controller
2. Site controller

## Fallbacks

If for example the title is not set in the panel, it will fall back to the template controller.

If the template controller does not contain a title template, it will fall back to the site controller title template.

If the site controller does not contain a title template, it will fall back to the site title.

## Template controller

A template controller matches your intended template name, for example:

```
site/controllers/seo/default.php
```

## Site controller

A site controller matches `site.php`, for example:

```
site/controllers/seo/site.php
```

## Change controller path

You can change this path by adding this in your `config.php`:

```php
c::set('seo.controller.path', kirby()->roots()->controllers() . DS . 'seo');
```

## Example

Here is a working example:

```php
<?php
return function($site, $pages, $page) {
  return [
    'title' => [
      'template' => '{{product}} makes {{category}}',
      'suffix' => ' - {{sitename}}',
      'prefix' => ''
    ],
    'description' => [
      'template' => 'We sell {{product}} {{category}}. Welcome to {{sitename}}!',
    ],
    'values' => [
        'product' => $page->title(),
        'category' => ( $page->category()->isNotEmpty() ) ? $page->category() : 'shoes',
        'sitename' => 'example.com'
    ]
  ];
};
```

The above can be a template controller or a site controller. They have exactly the same parameters.

## Controllers in the panel

The controllers will help you in the panel as well.

### Template helper

Below the edit title and description you will find a small helper table if you have added a controller.

From there you can see what the fallback will be if no title is set and which dynamic values can be used.

### Value replacement

If you have `product` as a key in your controller with a value, you can add {{product}} in your title and description. It will be replaced with the value set in your controller.

If the value changes, this will be reflected in the title and description.
