<?php
/**
 *  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 *  All Rights Reserved
 *
 *  This file is part of the calculadora-triunfo project.
 *
 *  @project calculadora-triunfo
 *  @file 2018_08_09_163020_create_taxables_table.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 09/08/18 at 16:48
 *  @copyright  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'taxables', function ( Blueprint $table ) {
            $table->integer('taxonomy_id')
                ->nullable()
                ->unsigned()
                ->references('id')
                ->on('taxonomies');

            $table->nullableMorphs('taxable');
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'taxables' );
    }
}
