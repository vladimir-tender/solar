<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('comments')->insert(
            [
                ['id' => '1', 'parent_id' => null, 'comment' => 'Comment 1'],
                ['id' => '2', 'parent_id' => '1', 'comment' => 'Comment 2'],
                ['id' => '3', 'parent_id' => null, 'comment' => 'Comment 3'],
                ['id' => '4', 'parent_id' => '1', 'comment' => 'Comment 4'],
                ['id' => '5', 'parent_id' => '2', 'comment' => 'Comment 5'],
                ['id' => '6', 'parent_id' => '2', 'comment' => 'Comment 6'],
                ['id' => '7', 'parent_id' => '1', 'comment' => 'Comment 7']
            ]
        );
    }
}
