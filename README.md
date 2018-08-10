# Taxonomies

Terms & Taxonomies (similar to WordPress) for Laravel 5.

## Installation
Install package via composer

```bash
composer require "webbingbrasil/laravel-taxonomies=0.1.0"
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
