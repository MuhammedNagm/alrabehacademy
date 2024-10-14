<?php

namespace Modules\Components\Utility\database\migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        if (!schemaHasTable('utility_ratings')) {
            \Schema::create('utility_ratings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('rating');
                $table->string('title');
                $table->string('body');
                $table->morphs('reviewrateable');
                $table->morphs('author');

                $table->unsignedInteger('created_by')->nullable()->index();
                $table->unsignedInteger('updated_by')->nullable()->index();

                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        \Schema::dropIfExists('utility_ratings');
    }
}
