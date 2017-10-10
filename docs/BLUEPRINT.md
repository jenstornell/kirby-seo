# Blueprint

Add your SEO field to the blueprint like this:

```yaml
fields:
  seo:
    type: seo
```

## Custom field key

If you want some other key than `seo` it's really important to add an option to your `config.php`:

```php
c::set('seo.field.key', 'seo');
```

You can add a label on it if you like, but you don't need to.

## Global field definitions

To not repeat yourself in every blueprint I highly recommend to use [Global Field Definitions](https://getkirby.com/docs/panel/blueprints/global-field-definitions).

**Add a file:** `/site/blueprints/fields/seo.yml`

In that file, add this:

```yaml
type: seo
```

Call it from the blueprint like this:

```yaml
fields:
  seo: seo
```
