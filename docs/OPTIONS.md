# Options

## A list of options

If you want to override any of the sensible default values, add the option to your `site/config/config.php` file. Default values are set below:

```php
// Path to SEO controller file
c::set('seo.controller.path', kirby()->roots()->controllers() . DS . 'seo');
// Limit for the length of the meta description
c::set('seo.description.limit', 155);
// Name of SEO field in blueprints
c::set('seo.field.key', 'seo');
```
