<?php

namespace WebbingBrasil\Taxonomies\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webbing\CMS\Model\Traits\Sluggable;
use WebbingBrasil\EloquentSTI\CanBeInherited;

/**
 * Class Term
 * @package WebbingBrasil\Taxonomies\Data\Models
 * @mixin \Eloquent
 */
class Term extends Model
{
    use SoftDeletes, Sluggable, CanBeInherited;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'terms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Get the taxonomies this term belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taxonomies() {
        return $this->hasMany(Taxonomy::class);
    }

    /**
     * Get route parameters.
     *
     * @param  string  $taxonomy
     * @return mixed
     */
    public function getRouteParameters($taxonomy)
    {
        $taxonomy = Taxonomy::taxonomy($taxonomy)
            ->term($this->name)
            ->with('parent')
            ->first();
        $parameters = $this->getParentSlugs($taxonomy);
        array_push($parameters, $taxonomy->taxonomy);
        return array_reverse($parameters);
    }

    /**
     * Get slugs of parent terms.
     *
     * @param  Taxonomy  $taxonomy
     * @param  array     $parameters
     * @return array
     */
    function getParentSlugs(Taxonomy $taxonomy, $parameters = [])
    {
        array_push($parameters, $taxonomy->term->slug);
        if (($parents = $taxonomy->parent()) && ($parent = $parents->first()))
            return $this->getParentSlugs($parent, $parameters);
        return $parameters;
    }
}
