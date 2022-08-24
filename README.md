# Legacy CodeIgniter3 to Laravel

This project helps you upgrade your CodeIgniter3 apps to Laravel.

- The goal is to reduce upgrade costs.
- It provides compatible interfaces for common use cases in CodeIgniter3 apps.
- It also provides compatible interfaces to test code using [ci-phpunit-test]().
- It does not aim to be 100% compatible.
- **This project is under early development.**
- **This project is under early development.**
- **This project is under early development.**
  - We welcome Pull Requests!

## Requirements

- Laravel 8.x or later
- PHP 7.3 or later

## Installation

You can install the package via composer:

``` bash
composer require agungsugiarto/legacy-to-laravel
```

## Usage
If you use *legacy-to-laravel*, You can run the following code on Laravel.

### Connect to Database
Open file `config/database.php` to configuration
```diff
'connections' => [
    'mysql' => [
+        // codeigniter3 legacy config database
+        'dsn'          => env('DATABASE_URL'),
+        'hostname'     => env('DB_HOST', '127.0.0.1'),
+        'dbdriver'     => 'mysqli',
+        'dbprefix'     => '',
+        'pconnect'     => false,
+        'db_debug'     => (env('APP_ENV') !== 'production'),
+        'cache_on'     => false,
+        'cachedir'     => '',
+        'char_set'     => 'utf8mb4',
+        'dbcollat'     => 'utf8mb4_unicode_ci',
+        'swap_pre'     => '',
+        'encrypt'      => false,
+        'compress'     => false,
+        'stricton'     => false,
+        'failover'     => [],
+        'save_queries' => true
    ],
],
```

*app/Http/Controllers/News.php*
```php
<?php

namespace App\Http\Controllers;

use App\Models\News_model;
use OpenDesa\Legacy\Core\CI_Controller;
use OpenDesa\Legacy\Library\CI_Form_validation;

/**
 * @property News_model $news_model
 * @property CI_Form_validation $form_validation
 */
class News extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('news_model');
        $this->load->helper('url_helper');
    }

    public function index()
    {
        $data['news']  = $this->news_model->get_news();
        $data['title'] = 'News archive';

        $this->load->view('templates/header', $data);
        $this->load->view('news/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($slug = null)
    {
        $data['news_item'] = $this->news_model->get_news($slug);

        if (empty($data['news_item'])) {
            show_404();
        }

        $data['title'] = $data['news_item']['title'];

        $this->load->view('templates/header', $data);
        $this->load->view('news/view', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Create a news item';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('news/create');
            $this->load->view('templates/footer');
        } else {
            $this->news_model->set_news();
            $this->load->view('news/success');
        }
    }
}
```

*app/Models/News_model.php*
```php
<?php

namespace App\Models;

use OpenDesa\Legacy\Core\CI_Model;

class News_model extends CI_Model
{
    public function get_news($slug = false)
    {
        if ($slug === false) {
            $query = $this->db->get('news');
            return $query->result_array();
        }

        $query = $this->db->get_where('news', ['slug' => $slug]);
        return $query->row_array();
    }

    public function set_news()
    {
        $this->load->helper('url');

        $slug = url_title($this->input->post('title'), '-', true);

        $data = [
            'title' => $this->input->post('title'),
            'slug'  => $slug,
            'text'  => $this->input->post('text')
        ];

        return $this->db->insert('news', $data);
    }
}
```

*/resources/views/news/create.php*
```php
<h2><?php echo $title; ?></h2>

<?php echo validation_errors(); ?>

<?php echo form_open('news/create'); ?>

    <label for="title">Title</label>
    <input type="input" name="title" /><br />

    <label for="text">Text</label>
    <textarea name="text"></textarea><br />

    <input type="submit" name="submit" value="Create news item" />

</form>
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are very welcome.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kenji Suzuki](https://github.com/kenjis)
- [Agung Sugiarto](https://github.com/agungsugiarto)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.