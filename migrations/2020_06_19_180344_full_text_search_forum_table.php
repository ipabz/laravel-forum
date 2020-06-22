<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FullTextSearchForumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE forum_categories ADD FULLTEXT fc_fulltext_index (title)');
        DB::statement('ALTER TABLE forum_threads ADD FULLTEXT ft_fulltext_index (title)');
        DB::statement('ALTER TABLE forum_posts ADD FULLTEXT fp_fulltext_index (content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE forum_categories DROP INDEX fc_fulltext_index;');
        DB::statement('ALTER TABLE forum_threads DROP INDEX ft_fulltext_index;');
        DB::statement('ALTER TABLE forum_posts DROP INDEX fp_fulltext_index;');
    }
}
