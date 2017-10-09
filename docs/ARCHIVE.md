# SEO archive field

To get an overview of the SEO in all the pages there is now an SEO archive field.

![](https://raw.githubusercontent.com/jenstornell/kirby-seo/master/archive.gif)

## Panel page

I created a page in the panel called `admin` and inside that another page called `seo`.

## Blueprint

In your blueprint, add something like this:

```yml
title: SEO Archive
pages:
  hide: true
  limit: 25
  exclude:
    - error
    - some/child/page
files: false
options:
  preview: false
fields:
  seoarchive:
    type: seoarchive
```

## Pagination

Set a page limit like the example above to have a paginated SEO archive.

## Exclude pages

You can exclude pages from the result by adding their URI into a list in your blueprint, like the example above.

## Future

- List children or list all pages
