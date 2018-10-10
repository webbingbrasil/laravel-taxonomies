<?php
/**
 *  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 *  All Rights Reserved
 *
 *  This file is part of the calculadora-triunfo project.
 *
 *  @project calculadora-triunfo
 *  @file HasTaxonomies.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 10/08/18 at 17:09
 *  @copyright  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 09/08/2018
 * Time: 16:26
 */

namespace WebbingBrasil\Taxonomies\Traits;

use Illuminate\Database\Eloquent\Builder;
use WebbingBrasil\Taxonomies\Data\Models\Taxonomy;
use WebbingBrasil\Taxonomies\Data\Models\Term;
use WebbingBrasil\Taxonomies\AbstractTerm;

/**
 * Trait HasTaxonomies
 *
 * @property \Illuminate\Database\Eloquent\Collection|Taxonomy $taxonomies
 * @package WebbingBrasil\taxonomies\src\Traits
 */
trait HasTaxonomies
{
    /**
     * Return a collection of taxonomies related to the taxed model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Builder|Taxonomy[]
     */
    public function taxonomies()
    {
        return $this->morphToMany(Taxonomy::class, 'taxable');
    }

    /**
     * Return a collection of terms related to the taxed model.
     *
     * @param null|string $taxonomy
     * @return \Illuminate\Support\Collection|Term[]
     */
    public function terms($taxonomy = null)
    {
        /** @var Builder $query */
        $query = $this->taxonomies()->with('term');

        if(!empty($taxonomy)) {
            $query->where('taxonomy', $taxonomy);
        }

        $terms = collect();

        $query->each(function(Taxonomy $taxonomy) use ($terms) {
            if($taxonomy->term) {
                $terms->push($taxonomy->term);
            }
        });

        return $terms;
    }

    /**
     * Add one or multiple taxonomies
     *
     * @param array|integer|string|AbstractTerm|Term $terms
     * @param string $taxonomy
     * @param int  $parent
     * @param int  $order
     * @return $this
     */
    public function assignTerm($terms, $taxonomy = 'category', $parent = 0, $order = 0)
    {
        if(!is_array($terms)) {
            $terms = [$terms];
        }

        $taxonomies = $this->getTaxonomiesForTerms($terms, $taxonomy, $parent, $order)
            ->filter(function ($value) {
                if ($this->taxonomies()->where('id', $value)->exists()) {
                    return false;
                }

                return !empty($value);
            })->all();

        $this->taxonomies()->attach($taxonomies);

        return $this;
    }

    /**
     * @param array|integer|string|AbstractTerm|Term $terms
     * @param string $taxonomy
     * @param int  $parent
     * @param int  $order
     * @return \Illuminate\Support\Collection
     */
    protected function getTaxonomiesForTerms($terms, $taxonomy = 'category', $parent = 0, $order = 0)
    {
        if(!is_array($terms)) {
            $terms = [$terms];
        }

        return collect($terms)
            ->flatten()
            ->map(function ($term) use ($taxonomy, $parent, $order) {
                return $this->getStoredTaxonomy($term, $taxonomy, $parent, $order);
            });
    }

    /**
     * Remove one or more taxonomies
     *
     * @param array|integer|string|AbstractTerm|Term $terms
     * @param string $taxonomy
     */
    public function removeTerm($terms, $taxonomy = 'category')
    {
        $taxonomies = $this->getTaxonomiesForTerms($terms, $taxonomy)->all();

        $this->taxonomies()->detach($taxonomies);
    }


    /**
     * Remove old taxonomies and add new
     *
     * @param array|integer|string|AbstractTerm|Term $terms
     * @param string $taxonomy
     * @param int  $parent
     * @param int  $order
     * @return $this
     */
    public function syncTerms($terms, $taxonomy = 'category', $parent = 0, $order = 0)
    {
        $this->taxonomies()->detach();
        return $this->assignTerm($terms, $taxonomy, $parent, $order);
    }

    /**
     * Check if entity has term attached
     *
     * @param integer|string|AbstractTerm|Term $term
     * @param string|null $taxonomy
     * @return bool
     */
    public function hasTerm($term, $taxonomy = null)
    {
        /** @var Builder $query */
        $query = $this->taxonomies();

        if (is_string($term)) {
            $query->whereHas('term', function(Builder $query) use ($term) {
                $query->where('slug', $term);
            });
        }

        if ($term instanceof AbstractTerm) {
            $taxonomy = $term->getTaxonomy();
        }

        if ($term instanceof Term) {
            $query->whereHas('term', function(Builder $query) use ($term) {
                $query->where($term->getKeyName(), $term->getKey());
            });
        }

        if(!empty($taxonomy)) {
            $query->where('taxonomy', $taxonomy);
        }

        return $query->exists();
    }

    /**
     * Check if entity has any term attached
     *
     * @param array $terms
     * @param string|null $taxonomy
     * @return bool
     */
    public function hasAnyTerm($terms, $taxonomy = null)
    {
        foreach ($terms as $term) {
            if ($this->hasTerm($term, $taxonomy)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if entity has all term sattached
     *
     * @param array $terms
     * @param string|null $taxonomy
     * @return bool
     */
    public function hasAllTerms($terms, $taxonomy = null)
    {
        $terms = collect($terms)->unique();

        foreach ($terms as $term) {
            if (!$this->hasTerm($term, $taxonomy)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param integer|string|AbstractTerm|Term $term
     * @param string $taxonomy
     * @param int  $parent
     * @param int  $order
     * @return Taxonomy|null
     */
    protected function getStoredTaxonomy($term, $taxonomy = 'category', $parent = 0, $order = 0)
    {

        if (is_numeric($term)) {
            $founded = app(Taxonomy::class)->where('term_id', $term)->where('taxonomy', $taxonomy)->first();
            if($founded){
                return object_get($founded, 'id');
            }
        }

        if (!is_numeric($term)) {
            $term = Term::firstOrNew(['slug' => $term]);
        }

        if ($term instanceof AbstractTerm) {
            $taxonomy = $term->getTaxonomy();
        }

        if ($term instanceof Term) {
            $term->save();
            $term = $term->id;
        }

        if($taxonomy instanceof Taxonomy == false) {
            /** @var Taxonomy $taxonomy */
            $taxonomy = Taxonomy::firstOrCreate([
                'taxonomy' => $taxonomy,
                'term_id' => $term,
                'parent' => $parent,
                'sort' => $order
            ]);
        }

        return object_get($taxonomy, 'id');
    }
}
