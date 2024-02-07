<?php
/**
 *  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 *  All Rights Reserved
 *
 *  This file is part of the calculadora-triunfo project.
 *
 *  @project calculadora-triunfo
 *  @file 2018_08_09_163000_create_terms_table.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 09/08/18 at 16:48
 *  @copyright  Copyright (c) 2018 Webbing Brasil (http://www.webbingbrasil.com.br)
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'terms', function ( Blueprint $table ) {

            $table->increments('id');

            $table->string('name')->nullable();
            $table->boolean('shared')->default(false);
            $table->string('slug')->nullable()->unique();

            $table->timestamps();
            $table->softDeletes();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'terms' );
    }
}
