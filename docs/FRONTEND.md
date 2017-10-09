# Frontend usage

You need to call a function in your template / snippet / pattern to make it work.

**In my `header.php` I do it like this:**

Using the following example it will output the HTML as well.

```html
<?php echo seo('title'); ?>
<?php echo seo('description'); ?>
```

Output:

```html
<title>Some title</title>
<meta name="description" content="Some description">
```

Using this example it will only return the value.

This way you just return its value o suit your needs without wrapping it in HTML. If you want HTML, wrap it yourself or create a snippet / pattern for it.

```html
<?php echo seo('title', array(), true); ?>
<?php echo seo('description', array(), true); ?>
```

Output:

```html
Some title Some description
```
