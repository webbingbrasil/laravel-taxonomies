<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 10/08/2018
 * Time: 16:56
 */
namespace WebbingBrasil\Taxonomies\Providers;

use Illuminate\Support\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    protected $migrations = [
        'CreateTermsTable' => 'create_terms_table',
        'CreateTaxonomiesTable' => 'create_taxonomies_table',
        'CreateTaxablesTable' => 'create_taxables_table',
    ];

    /**
     * Publish package migration
     */
    public function boot()
    {
        $this->handleMigrations();
    }

    /**
     * Publish migrations.
     *
     * @return void
     */
    private function handleMigrations()
    {
        foreach ($this->migrations as $class => $file) {
            if (! class_exists($class)) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__ .'/../database/migrations/'. $file .'.php.stub' =>
                        database_path('migrations/'. $timestamp .'_'. $file .'.php')
                ], 'migrations');
            }
        }
    }
}
