<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $users = \App\Models\User::factory(5)->create();

        $users->each(function ($user) {
            $projects = \App\Models\Project::factory(2)->create(['owner_id' => $user->id]);

            // mỗi project có 3 task, assignee ngẫu nhiên
            $projects->each(function ($project) {
                \App\Models\Task::factory(3)->create([
                    'project_id' => $project->id,
                    'assignee_id' => \App\Models\User::inRandomOrder()->first()->id,
                ]);
            });

            // mỗi user có 3 posts
            $posts = \App\Models\Post::factory(3)->create(['user_id' => $user->id]);

            // mỗi post có 2 comments
            $posts->each(function ($post) use ($user) {
                \App\Models\Comment::factory(2)->create([
                    'post_id' => $post->id,
                    'user_id' => \App\Models\User::inRandomOrder()->first()->id,
                ]);
            });
        });
    }
}
