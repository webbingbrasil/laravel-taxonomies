<?php
/**
 *  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 *  All Rights Reserved
 *
 *  This file is part of the calculadora-triunfo project.
 *
 *  @project calculadora-triunfo
 *  @file 2018_08_09_163010_create_taxonomies_table.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 09/08/18 at 16:48
 *  @copyright  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'taxonomies', function ( Blueprint $table ) {
            $table->increments('id');

            $table->integer('term_id')
                ->nullable()
                ->unsigned()
                ->references('id')
                ->on('terms')
                ->onDelete('cascade');

            $table->string('taxonomy')->default('default');
            $table->string('desc')->nullable();

            $table->integer('parent')->unsigned()->default(0);

            $table->smallInteger('sort')->unsigned()->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['term_id', 'taxonomy']);
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'taxonomies' );
    }
}
