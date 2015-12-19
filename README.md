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

```
$this->load->library('CZPagination');
```

Setup SQL and Parameters (no need to add LIMIT)

```
$sql = "SELECT * FROM table_name WHERE ID = ?";
$params = array($id);
```

Define the page number (assumes using page segment)

```
$page_number = $this->uri->segment(3);
```

Setup config and execute (see examples)

**Basic Example:**

```
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

```
$config = array(
    'base_url' => 'http://domain',
    'sql' => $sql, 
    'params' => $params,
    'page_number' => $page_number,
    'full_tag_open' => '<ul class="pagination">',
    'full_tag_close' => '</ul>',
    'page_tag_open' => '<li>',
    'page_tag_close' => '</li>',
    'first_link' => '<span><i class="fa fa-angle-double-left"></i></span>',
    'last_link' => '<span><i class="fa fa-angle-double-right"></i></span>',
    'prev_link' => '<span><i class="fa fa-angle-left"></i></span>',
    'next_link' => '<span><i class="fa fa-angle-right"></i></span>',
    'cur_tag_open' => '<li class="active">',
    'cur_tag_close' => '</li>'
);

$cz = new CZPagination();
$data = $cz->initialize($config)->get_data();
$pagination = $cz->create_links();

```
