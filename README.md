![Coverage](https://img.shields.io/badge/coverage-99.14%25-green)
![GitHub repo size](https://img.shields.io/github/repo-size/mkgor/puff)
![Packagist](https://img.shields.io/packagist/l/mkgor/puff)
![GitHub All Releases](https://img.shields.io/github/downloads/mkgor/puff/total)

# Puff
![Logo](https://i.imgur.com/6wEUCeH.jpg)

Hackable and lightning fast template engine for PHP, which is inspired by Twig. 

## Contents
- [Requirments](https://github.com/mkgor/puff#requirments)
- [Installation](https://github.com/mkgor/puff#installation)
- [Quickstart](https://github.com/mkgor/puff#quickstart)
- [Specification](https://github.com/mkgor/puff#specification) 
    - [For](https://github.com/mkgor/puff#for)
    - [If-else](https://github.com/mkgor/puff#if-else)
    - [Import](https://github.com/mkgor/puff#import)
    - [Set](https://github.com/mkgor/puff#set)
    - [Extends and Position](https://github.com/mkgor/puff#extends-and-position)
- [Filters](https://github.com/mkgor/puff#filters-system)
- [Extensions system](https://github.com/mkgor/puff#extensions-system)
    - [Making new statement(element)](https://github.com/mkgor/puff#making-new-statement-element)
    - [Making new filter](https://github.com/mkgor/puff#making-new-filter)
- [Syntax editing](https://github.com/mkgor/puff#syntax-editing)
- [Escaping tags](https://github.com/mkgor/puff#escaping-tags)

## Requirments

- PHP 7.1 or higher (for Puff core)
- Mbstring extension (for UpperCaseFilter)

## Installation

Install Puff via composer

````bash
composer require mkgor/puff
````

## Quickstart

````php
<?php

require_once "vendor/autoload.php";

$engine = new \Puff\Engine([
    'modules' => [
        new \Puff\Modules\Core\CoreModule()
    ]
]);

echo $engine->render(__DIR__ . '/template.puff.html', [
    'variable' => 'Puff'
]);
````

````html 
<html>
<body>
Hello, i am [[ variable ]]
</body>
</html>
````

**Important!** Don't forget to initialze CoreModule here if you need all basic statements, such as **for**, **if-else**, etc.  
**Also important!** Puff automatically converts '-' and '.' symbols into '_' in variable's name.

## Specification

Instructions for executing basic statements, such as **if-else**, **for**, **import**, etc. indicated by combination of characters ````[% %]````

To display variable's value, you should use ````[[ ]]````

### For
Working like PHP's **foreach** cycle

````html
<div class='products'>
  [% for products in item %]
  <div class='item'>
    <b>Id: </b> [[ item.id ]]
    <b>Name: </b> [[ item.name ]]
  </div>
  [% end %]
</div>
````

### If-else
Simple if-else implementation

````html
[% if variable == true %]
  <b>[[ variable ]] is true</b>
[% else %]
  <b>[[ variable ]] is false</b>
[% end %]
````

### Import
You can import template into another using **import**

If you are not specified templates directory path, you should set template path relative to project root 

**Important!** Don't forget, that you are injecting all variables from current template into importing template. If template which you are importing using some variable (for example - it is displaying page title in header), you should specify it in **render** method
````html
[% import src='base.puff.html' %]

<body>
<div class='content'>
...
</body>
````

### Set
Creates/Updates variable

````html
[% set variable = 'test' %]

<!-- will display 'test' -->
<b>[[ variable ]]</b>

[% set variable = 'test2' %]

<!-- will display 'test2' -->
<b>[[ variable ]]</b>
````

### Extends and Position
Use this tags to define the parent template and load data from the current template into it using the Position tag.

Usage:

Main template
````html
[% position name="title" %]
Home page
[% endposition %]

[% position name="content" %]
Hello, [[ name ]]
[% endposition %]

[% extends src="base.puff.html" %]
````

Parent template
````html
<html>
<head>
<title>[% position for="title" %]</title>
</head>
<body>
[% position for="content' %]
</body>
</html>
````

## Filters system

You can modify some variables before displaying them or if some statement supports filters, you can modify variable before using it in it.

To specify filters for variable, you should specify them by **~** sign.

Example:

````html
[[ variable ~ uppercase ~ transliterate ]]
````

```` html
[[ int_variable ~ round(1) ]]
````

You also can use filters in **for** statement

````html
[% for products ~ uppercase in item %]
  <!-- uppercase filter recursiely transforms all items of array into uppercase -->
  [[ item.name ]]
[% end %]
````

You can create your own filters. Read how to do it in **Extensions system** block

## Extensions system
Puff is extensible, so you can create your own modules, which can contain your own statements and filters. 

To create module, just create class which implements **Puff\Modules\ModuleInterface** and insert in into **modules** array of **Engine** configuration

````php
$engine = new \Puff\Engine([
    'modules' => [
        new \Puff\Modules\Core\CoreModule()
        new \Puff\Modules\NewModule\MyModule()
    ]
]);
````

**Important!** Don't forget to initialze CoreModule here if you need all basic statements, such as **for**, **if-else**, etc.

### Making new statement (element)

To make new element, you should create class, which should extend **Puff\Compilation\Element\AbstractElement**

You should specify element's class in your **Module** class's setUp() method:

````php
...
/**
     * Returns an array of elements and filters which will be initialized
     *
     * @return array
     */
    public function setUp(): array
    {
        return [
            'elements' => [
                'new_element' => new NewElement(),
                'another_new_element' => new AnotherNewElement()
            ],
            ...
        ];
    }
    ...
````

Now, your element is accessible in template by **test_element** keyword

Element's **process** method should return PHP code.

````php
<?php

use Puff\Compilation\Element\AbstractElement;

/**
 * Class NewElement
 */
class NewElement extends AbstractElement
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function process(array $attributes)
    {
        return "<?php echo 'Some result of processing'; ?>";
    }
}

````

You can provide some attributes handling rules. By default, it is handling like this:
````
[% element attribute='some_attribute' %]

Process method will get an array:

[
  'attribute' => 'some_attribute'
]
````

But if you want to make statement like **for** (which don't use attributes like "attribute='attr'") you can provide your own attributes handling rules by specifying **handleAttributes** method in your element class

It is getting an array of all elements from tokenizer and **should return an array**

For example:

````
[% new_element attribute anotherAttribute ~ 123 %]

handleAttributes() will get an array:

[
  'new_element',
  'attribute',
  'anotherAttribute',
  '~',
  '123'
]
````


````php

<?php

use Puff\Compilation\Element\AbstractElement;

/**
 * Class NewElement
 */
class NewElement extends AbstractElement
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function process(array $attributes)
    {
        return "<?php echo $attributes['result']; ?>";
    }
    
    public function handleAttributes(array $tokenAttributes) 
    {
      if(array_search('attribute', $tokenAttributes)) {
        return ['result' => 'attribute found'];
      } else {
        return ['result' => 'attribute not found'];
      }
    }
}
````

````
Testing for example above:

[% new attribute %]

Will display: attribute found

[% new %]

Will display: attribute not found
````

### Making new filter

To make new element, you should create class, which should extend **Puff\Compilation\Element\AbstractElement**

You should specify element's **class name** in **Module's** class setUp() method:

````php
/**
     * Returns an array of elements and filters which will be initialized
     *
     * @return array
     */
    public function setUp(): array
    {
        return [
            ...
            'filters' => [
                'new_filter' => NewFilter::class
            ]
        ];
    }
    ...
````

Your filter class should implement **Puff\Compilation\Filter\FilterInterface**

For example, discover **UpperCaseFilter** code to understand how it works

````php
<?php

namespace Puff\Compilation\Filter;

/**
 * Class UpperCaseFilter
 * @package Puff\Compilation\Filter
 */
class UpperCaseFilter implements FilterInterface
{
    /**
     * @param $variable
     * @param array $args
     * @return string|array
     */
    public static function handle($variable, ...$args) {
        mb_internal_encoding('UTF-8');

        if(!is_array($variable)) {
            return mb_strtoupper($variable);
        } else {
            array_walk_recursive($variable, function(&$item) {
                if(!is_array($item)) {
                    $item = mb_strtoupper($item);
                }
            });

            return $variable;
        }
    }
}
````

Value, which **handle** method returns, will be assigned to variable

## Syntax editing

You can configure some elements of syntax, such as symbols which are Puff using in tags,
equality symbol, filter separator symbol and etc.

To do this, you should create new class, which can implements **Puff\Tokenization\Syntax\SyntaxInterface** or 
extends **Puff\Tokenization\Syntax\AbstractSyntax**. Let's see how it works with **AbstractSyntax**

````php
<?php


namespace Puff\Tokenization\Syntax;

/**
 * Class NewSyntax
 * @package Puff\Tokenization\Syntax
 */
class NewSyntax extends AbstractSyntax
{
    public function getElementTag() : array{
        return ["(@", "@)"];
    }
}
````

So, we are specified new element tag's symbols. To make it work, you should set it in the configuration array in **Engine**
constructor, or set it in **Module**'s setUp() method.

````php
<?php
$engineInstance = new Engine([
    'modules' => [
        new \Puff\Modules\Core\CoreModule(),
    ],
    'syntax' => new MySyntax()
]);
````

Now, all tags should use new syntax, let's see how we should update template

````html
(@ if variable == 1 @)
    <span>Syntax updated!</span>
(@ end @)
````

### Escaping tags

To escape tag, you should set escaping symbols before some tag to tell compiler to ignore it. 

Default escaping symbols in Puff is ``//``, but you can edit it by setting your own Syntax class

````html
[% set variable = 1 %]
[[ variable ]]

//[[variable]]
````

Will display:
````
1
[[ variable ]]
````
