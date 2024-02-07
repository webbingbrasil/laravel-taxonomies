<?php
/**
 *  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 *  All Rights Reserved
 *
 *  This file is part of the calculadora-triunfo project.
 *
 *  @project calculadora-triunfo
 *  @file UseTaxonomy.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 10/08/18 at 17:43
 *  @copyright  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 */

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
use Illuminate\Database\Eloquent\Model;

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
        $term = (new static);
        $taxonomy = $term->getTaxonomy();
        $shared = (boolean) $term->isShared();

        static::addGlobalScope('taxonomy', function (Builder $builder) use ($taxonomy) {
            $builder->whereHas('taxonomies', function(Builder $query) use ($taxonomy) {
                $query->where('taxonomy', $taxonomy );
            });
        });

        // Save relation with taxonomy
        static::slugged(function($model) use ($shared) {
            $model->shared = $shared;

            if($shared) {
                $found = Term::where('name', $model->name)->where('shared', true)->first();
                if ($found) {
                    $model->id = $found->id;
                    $model->slug = $found->slug;
                    $model->exists = true;
                }
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
     * Query scope for finding "similar" slugs, used to determine uniqueness.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $attribute
     * @param array $config
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindSimilarSlugs(Builder $query, Model $model, $attribute, $config, $slug)
    {
        $separator = $config['separator'];
        return $query->withoutGlobalScope('taxonomy')->where(function (Builder $q) use ($attribute, $slug, $separator) {
            $q->where($attribute, '=', $slug)
                ->orWhere($attribute, 'LIKE', $slug . $separator . '%');
        });
    }

    /**
     * @return bool
     */
    public function isShared() {
        return false;
    }

    /**
     * Taxonomy name
     * 
     * @return string
     */
    abstract public function getTaxonomy();
}
