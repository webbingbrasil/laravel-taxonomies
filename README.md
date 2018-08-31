# Taxonomies

Terms & Taxonomies (similar to WordPress) for Laravel 5.

## Installation
Install package via composer

```bash
composer require "webbingbrasil/laravel-taxonomies=1.0.0"
```

Next, if you are using Laravel prior to 5.5, register the service provider in the providers array of your config/app.php configuration file:

```php
Cviebrock\EloquentSluggable\ServiceProvider::class,
WebbingBrasil\Taxonomies\Providers\TaxonomyServiceProvider::class,
```

To get started, you'll need to publish the vendor assets and migrate:

```php
php artisan vendor:publish --provider="WebbingBrasil\Taxonomies\Providers\TaxonomyServiceProvider" && php artisan migrate
```

## Usage
Add our `HasTaxonomies` trait to your model.
        
```php
<?php namespace App\Models;

use WebbingBrasil\Taxonomies\Traits\HasTaxonomies;

class Post extends Model
{
    use HasTaxonomies;

    // ...
}
?>
```

You can also create specific classes of taxonomies
       
```php
<?php namespace App\Models;

use WebbingBrasil\Taxonomies\AbstractTerm;

class Category extends AbstractTerm
{
    /**
     * Taxonomy name
     *
     * @return string
     */
    public function getTaxonomy()
    {
       return 'category';
    }
    
    // ...
}
?>
```    
```php
<?php namespace App\Models;

use WebbingBrasil\Taxonomies\AbstractTerm;

class Tag extends AbstractTerm
{
    /**
     * Taxonomy name
     *
     * @return string
     */
    public function getTaxonomy()
    {
       return 'tag';
    }
    
    // ...
}
?>
```
