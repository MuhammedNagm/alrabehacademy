<?php

namespace Modules\Components\Utility\database\migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        if (!schemaHasTable('utility_comments')) {
            \Schema::create('utility_comments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('content')->nullable();
                $table->string('commentable_type');
                $table->unsignedInteger('commentable_id');
                 $table->unsignedInteger('parent_id')->nullable();
                $table->unsignedInteger('author_id')->nullable();
                $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->tinyInteger('status')->default(0);
                $table->unsignedInteger('created_by')->nullable()->index();
                $table->unsignedInteger('updated_by')->nullable()->index();
                $table->foreign('parent_id')->references('id')->on('utility_comments')->onUpdate('cascade')->onDelete('set null');

                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        \Schema::dropIfExists('utility_comments');
    }
}
