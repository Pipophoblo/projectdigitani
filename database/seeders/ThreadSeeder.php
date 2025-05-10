<?php

namespace Database\Seeders;

use App\Models\Thread;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Seeder;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and categories
        $users = User::where('role', '!=', 'Admin')->get();
        $categories = Category::all();
        
        // Sample threads data
        $threads = [
            [
                'user_email' => 'syamsul@example.com',
                'category' => 'Pertanian',
                'title' => 'Cara mencegah hama pada tanaman padi',
                'content' => 'Saya ingin berbagi pengalaman dan bertanya tentang cara efektif mencegah hama pada tanaman padi. Belakangan ini tanaman padi saya diserang banyak hama dan mengurangi hasil panen. Apakah ada yang punya solusi alami untuk ini?',
            ],
            [
                'user_email' => 'rita@example.com',
                'category' => 'Pertanian',
                'title' => 'Saya ingin menanam anggur di pekarangan rumah',
                'content' => 'Saya ingin menanam anggur di pekarangan rumah tapi tidak tahu varietas apa yang cocok untuk iklim Indonesia. Apakah ada yang punya pengalaman menanam anggur di rumah? Saya tinggal di daerah dengan iklim cukup panas. Terima kasih sebelumnya!',
            ],
            [
                'user_email' => 'reno@example.com',
                'category' => 'Pertanian',
                'title' => 'Cara mengatasi bulai pada jagung',
                'content' => 'bahan aktif apa yang ampuh dan bagaimana cara membunuh virus bulai pada tanaman jagung? Tanaman jagung saya mulai menunjukkan gejala bulai dan saya khawatir akan menyebar ke seluruh ladang.',
            ],
        ];

        // Create threads
        foreach ($threads as $threadData) {
            // Find user
            $user = User::where('email', $threadData['user_email'])->first();
            // Find category
            $category = Category::where('name', $threadData['category'])->first();
            
            if ($user && $category) {
                $thread = Thread::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'title' => $threadData['title'],
                    'content' => $threadData['content'],
                    'created_at' => now()->subDays(rand(0, 20)),
                    'updated_at' => now()->subDays(rand(0, 20)),
                ]);
                
                // Add random comments
                $commentCount = rand(0, 3);
                for ($i = 0; $i < $commentCount; $i++) {
                    $commentUser = $users->random();
                    Comment::create([
                        'user_id' => $commentUser->id,
                        'thread_id' => $thread->id,
                        'content' => 'Ini adalah komentar dari ' . $commentUser->name . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                        'created_at' => $thread->created_at->addHours(rand(1, 48)),
                    ]);
                }
                
                // Add random likes
                $likeCount = rand(0, 5);
                $likedUsers = $users->random($likeCount);
                foreach ($likedUsers as $likedUser) {
                    Like::create([
                        'user_id' => $likedUser->id,
                        'thread_id' => $thread->id,
                        'created_at' => $thread->created_at->addHours(rand(1, 72)),
                    ]);
                }
            }
        }
    }
}