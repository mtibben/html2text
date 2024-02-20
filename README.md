# Html2Text

A PHP library for converting HTML to formatted plain text.

[![Build Status](https://travis-ci.org/mtibben/html2text.png?branch=master)](https://travis-ci.org/mtibben/html2text)

## Installing

```
composer require html2text/html2text
```

## Basic Usage
```php
$html2text = new \Html2Text\Html2Text('Hello, &quot;<b>world</b>&quot;');

echo $html2text->getText();  // Hello, "WORLD"
```

## Options

The constructor takes an optional 2nd parameter which is an array of options.
```php
$html2text = new \Html2Text\Html2Text(string $html, array $options = []);
```

Options and defaults are:
```php
[
        'do_links' => 'inline', // 'none'
                                // 'inline' (show links inline)
                                // 'nextline' (show links on the next line)
                                // 'table' (if a table of link URLs should be listed after the text.
                                // 'bbcode' (show links as bbcode)

        'width' => 70,          //  Maximum width of the formatted text, in columns.
                                //  Set this value to 0 (or less) to ignore word wrapping
                                //  and not constrain text to a fixed-width column.
        
        'plain_text' => false,  //  If true then disables various pseudo formatting:
                                //  No converting bold, th or headings to upper case.
                                //  No character conversion to simulate strike through for <del>
                                //  No adding _ around italic text (<i> <em> and <ins> tags)
                                //  <hr> is replaced by "\n\n" rather than "\n-------------------------\n"
];
```

## History

This library started life on the blog of Jon Abernathy http://www.chuggnutt.com/html2text

A number of projects picked up the library and started using it - among those was RoundCube mail. They made a number of updates to it over time to suit their webmail client.

Now it has been extracted as a standalone library. Hopefully it can be of use to others.
