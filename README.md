# Navigation

Navigation is a Laravel based (AdminArchitect oriented) package to handle dynamic menus.
LinksProvider, RoutesProvider, EloquentProvider are provided out of the box.

# Caution
Note, that `adminarchitect/navigation` is based on (AdminArchitect)['http://adminarchitect.com'] package and won't work without it.

# Installation

```
composer require adminarchitect/navigation
```

Add following lines to your config/app.php

1, add `Terranet\Navigation\ServiceProvider::class` line to `providers` array
2. add `'Navigation' => Terranet\Navigation\Facade::class,` line to `aliases` array

Run:
```
php artisan vendor:publish --provider="Terranet\\Navigation\\ServiceProvider"
php artisan navigation:table
php artisan migrate
```

# Providers

Navigation is based on Providers, each of them can provide a colletion of navigable items and should realize one of provided contracts or define new one.

* `LinksProvider`: Provides a way to add static links: url => title;
* `RoutesProvider`: Provides a way to add routes to menu;
* `EloquentProvider`: Provides a way to add any Eloquent model to navigation collection.

All usable providers are registered via config/navigation.php file -> `providers` array.

To create a new provider, run: `php artisan navigation <Name>`, then register it in config/navigation.php.

Any provider which extends EloquentProvider should provide a collection of items which implement NavigationItem contract.
NavigationItem contract requires 3 methods to be defined:
1. navigationKey => should return item unique key, usually 'id';
2. navigationTitle => shluld return item title, may be: 'title', 'name', whatever identifies a model title.
3. navigationUrl => should return item specific url, may return url('') or route('')

for instance, to allow adding Posts to a navigation you have to register a PostsProvider and modify your Post model to look like in following example:

```
php artisan navigation:provider PostsProvider
```

will generate app\Http\Terranet\Administrator\Navigation\Providers\PostsProvider:

```
<?php

namespace App\Http\Terranet\Administrator\Navigation\Providers;

use Terranet\Navigation\Providers\EloquentProvider;

class PostsProvider extends EloquentProvider
{
    /**
     * Eloquent model.
     */
    protected $model;
}
```

now you only have to provide a valid eloquent $model:

```
protected $model = \App\Post::class;
```

and register it in config/navigation.php

`config\navigation.php`:
```
'providers' => [
    ...
    \App\Http\Terranet\Administrator\Navigation\Providers\PostsProvider::class,
    ...
]
```

your `app\Post.php` should be like:

```
class Post extends Model implements NavigationItem
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'published', 'image',
    ];

    public function navigationKey()
    {
        return $this->id;
    }

    public function navigationTitle()
    {
        return $this->title;
    }

    public function navigationUrl()
    {
        return route('posts.show', ['slug' => $this->slug]);
    }
}
```

# Enjoy!