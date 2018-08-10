<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 09/08/2018
 * Time: 19:09
 */

namespace WebbingBrasil\Taxonomies;


use WebbingBrasil\Taxonomies\Data\Models\Term;
use WebbingBrasil\Taxonomies\Traits\UseTaxonomy;

abstract class AbstractTerm extends Term
{
    use UseTaxonomy;
}
