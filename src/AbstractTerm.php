<?php
/**
 *  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 *  All Rights Reserved
 *
 *  This file is part of the calculadora-triunfo project.
 *
 *  @project calculadora-triunfo
 *  @file AbstractTerm.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 10/08/18 at 18:04
 *  @copyright  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 */

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
