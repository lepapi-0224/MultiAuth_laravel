<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Permission;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'create-post',
            'display_name' => 'Create Posts', // optional
            'description' => 'create new blog posts', // optional
            ]);

            Permission::create([
                'name' => 'update-post',
                'display_name' => 'Update Post', // optional
                'description' => 'Update blog posts', // optional
                ]);

                 Permission::create([
                    'name' => 'read-post',
                    'display_name' => 'Read Posts', // optional
                    'description' => 'Read blog posts', // optional
                    ]);

                    Permission::create([
                        'name' => 'delete-post',
                        'display_name' => 'Delete Posts', // optional
                        'description' => 'Delete blog posts', // optional
                        ]);
        Post::factory(15);
        
    }
}
