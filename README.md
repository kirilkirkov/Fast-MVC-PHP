# Fast-MVC-PHP
Small and fast MVC micro-framework for PHP with FastRoute routing and file based caching.

![Fast MVC PHP](https://raw.githubusercontent.com/kirilkirkov/Fast-MVC-PHP/main/public/images/fast-mvc-php.png "Fast MVC PHP")

**Fast MVC PHP** is made for small, mostly read-only websites (catalogs, blogs, landing pages) where response time matters more than features. There is no magic inside: plain PHP templates, PDO prepared statements and a core that you can read in one sitting.

## Donate
<p>If this project help you reduce time to develop, you can give me a cup of coffee to continue its development. Thank you! :)</p>

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=W5BR6K29BQX7E)

## What's inside
- **Routing** - [nikic/fast-route](https://github.com/nikic/FastRoute) with compiled route cache. Route params are passed to the controller method by name (`{id}` → `$id`).
- **Views** - plain PHP templates with layouts and partials. Optional HTML minify, which leaves `<pre>`, `<textarea>`, `<script>` and `<style>` untouched.
- **Query builder** - builds SQL strings for PDO prepared statements: `select`, `insert`, `update`, `delete`.
- **Query cache** - SELECT results are saved as PHP files, so they are loaded through OPcache. The database connection is opened lazily, so pages served from cache never connect to the database.
- **IoC container** - bindings and singletons in `config/services.php`.
- **Error handling** - uncaught exceptions are logged with `error_log()` and the 500 page is shown. Exception details are visible only with `DEBUG_MODE`.
- **CLI** - cache clearing.

## Limitations
Please read these before you choose it for a project:
- It is not an ORM. The query builder only builds SQL strings.
- Made for MySQL (the charset is set in the MySQL DSN).
- The query cache has no expiration. After you change data, clear it with `php fast-mvc cache:clear pdo`. That's why it is meant for the front-end part only - it would be best if you split the administration into a separate system.
- Views are not escaped automatically. Use `<?= e($value) ?>` for everything that comes from users or the database.
- No middleware, sessions, CSRF protection or request validation.
- Closure route handlers can't be cached, so use them only with `ROUTE_CACHE_ENABLED` set to `false`.

## Installation
- composer install
- Point your vhost to the /public directory
- Set database, cache and debug options in `env.php`

## Quick example
```php
// routes/routes.php
Route::add('GET', '/user/{id:\d+}', 'Users\UserController@show');
```

```php
// app/Controllers/Users/UserController.php
namespace App\Controllers\Users;

use Core\DB;

class UserController extends \Core\Controller
{
    public const LAYOUT = 'layouts/main';

    public function show($id)
    {
        $db = DB::getInstance();
        $query = $db->query()->select('id', 'email')->from('users')->where('id = :id');

        $this->view->render('users/show', [
            'user' => $db->first($query, ['id' => $id]), // cached SELECT
        ]);
    }
}
```

```php
<!-- resources/views/users/show.php -->
<p><?= e($user['email']) ?></p>
```

Write queries are executed with `statement()` and are never cached:
```php
$query = $db->query()->update('users')->set('email')->where('id = :id');
$db->statement($query, ['email' => $email, 'id' => $id]);
```

### Available CLI Commands
- php fast-mvc cache:clear - Clear all application cache
- php fast-mvc cache:clear pdo - Clear pdo cache
- php fast-mvc cache:clear custom - Clear custom cache
- php fast-mvc cache:clear fast-route - Clear fast route cache

### Tests
```
composer install
composer test
```

#### Dependencies
- PHP >=8.1
- PDO Extension (MySQL)

<p>Read more about what php frameworks are</p>
<a href="https://www.youtube.com/watch?v=EW5UmMeCqtU" target="_blank">
 <img src="https://img.youtube.com/vi/EW5UmMeCqtU/mqdefault.jpg?g=1" alt="Watch the video" border="10" />
</a>

#### Execution time
<p> It takes approximately 0.00039100646972656 seconds to execute the script </p>
