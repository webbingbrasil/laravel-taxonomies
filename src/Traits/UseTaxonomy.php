<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 09/08/2018
 * Time: 16:57
 */

namespace WebbingBrasil\Taxonomies\Traits;

use WebbingBrasil\Taxonomies\Data\Models\Taxonomy;
use WebbingBrasil\Taxonomies\Data\Models\Term;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TermAbstract
 * @package WebbingBrasil\Taxonomies\Data\Models
 */
trait UseTaxonomy
{

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootUseTaxonomy()
    {
        $taxonomy = (new static)->getTaxonomy();

        static::addGlobalScope('taxonomy', function (Builder $builder) use ($taxonomy) {
            $builder->whereHas('taxonomies', function(Builder $query) use ($taxonomy) {
                $query->where('taxonomy', $taxonomy );
            });
        });

        // Save relation with taxonomy
        static::saving(function($model) {
            $found = Term::where('name', $model->name)->first();
            if ($found){
                $model->id = $found->id;
                $model->exists = true;
            }
        });

        // Save relation with taxonomy
        static::saved(function($model) use ($taxonomy) {
            /** @var Taxonomy $taxonomy */
            Taxonomy::firstOrCreate([
                'taxonomy' => $taxonomy,
                'term_id' => $model->getKey(),
                'parent' => 0,
                'sort' => 0
            ]);
        });
    }

    /**
     * Taxonomy name
     * 
     * @return string
     */
    abstract public function getTaxonomy();
}
