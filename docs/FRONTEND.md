# Frontend usage

You need to call a function in your template / snippet / pattern to make it work.

**In my `header.php` I do like this:**

The function `seo()` will return the values with added HTML tags:

```html
<?php echo seo('title'); ?>
<?php echo seo('description'); ?>
```

Output:

```html
<title>Some title</title>
<meta name="description" content="Some description">
```

It's also possible to just return the values, without the HTML tags:

```html
<?php echo seo('title')->value(); ?>
<?php echo seo('description')->value(); ?>
```

Output:

```html
Some title Some description
```
