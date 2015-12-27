# CZPagination

### Codeigniter Pagination Library
This library is using Twitter Bootstrap and Font-awesome html-css based style. But, it may also configured for another style as you wish. 

Most of configurations are using identical parameters from built-in codeigniter pagination library, so you might be able to adapt easily using this library.
The difference is that this library will not just create pagination links, but also provide data result from your query by passing the 'sql' and 'params' variable.

Another advantage is that this library will generate LIMIT for your query. Hence, we just need to pass 'sql' without counting and adding LIMIT (e.g `"SELECT * FROM table_name"`)

# Requirements

* Codeigniter v3+
* Twitter Bootstrap (optional)
* Font-awesome (if you don't want to load the icons, then configurations for first,prev,next and last link must be modified)

# Installation

1. Put this file into your codeigniter's application/library folder
2. Load the library

# Usage

Load the library

```php
$this->load->library('CZPagination');
```

Setup SQL and Parameters (no need to add LIMIT)

```php
$sql = "SELECT * FROM table_name WHERE ID = ?";
$params = array($id);
```

Define the page number (assumes using page segment)

```php
$page_number = $this->uri->segment(3);
```

Setup config and execute (see examples)

**Basic Example:**

```php
$config = array(
    'base_url' => 'http://domain',
    'sql' => $sql,
    'params' => $params,
    'per_page' => 20,
    'page_number' => $page_number
);

$cz = new CZPagination();
$cz->initialize($config);
$data = $cz->get_data();
$pagination = $cz->create_links();
```

**More Configurations Example:**

```php
*Notes: show_current will be ignored if show_digits is set to TRUE (it will be showned anyway)*

$config = array(
    'base_url'          => 'http://domain',
    'sql'               => $sql, 
    'params'            => $params,
    'page_number'       => $page_number,
    'full_tag_open'     => '<ul class="pagination">',
    'full_tag_close'    => '</ul>',
    'page_tag_open'     => '<li>',
    'page_tag_close'    => '</li>',
    'first_link'        => '<span><i class="fa fa-angle-double-left"></i></span>',
    'last_link'         => '<span><i class="fa fa-angle-double-right"></i></span>',
    'prev_link'         => '<span><i class="fa fa-angle-left"></i></span>',
    'next_link'         => '<span><i class="fa fa-angle-right"></i></span>',
    'cur_tag_open'      => '<li class="active">',
    'cur_tag_close'     => '</li>'
    'show_first'        => true,
    'show_prev'         => true,
    'show_next'         => true,
    'show_last'         => true,
    'show_current'      => false,
    'show_digits'       => true
);

$cz = new CZPagination();
$data = $cz->initialize($config)->get_data();
$pagination = $cz->create_links();

```

# License
**The MIT License (MIT)**

Copyright (c) 2015 akangcupez

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# Notes
All brands i.e [Codeigniter](http://www.codeigniter.com/), [Twitter Bootstrap](http://getbootstrap.com/) and [Font Awesome](https://fortawesome.github.io/Font-Awesome/) are trademarks of their respective owners.