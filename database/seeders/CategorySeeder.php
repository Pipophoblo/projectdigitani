<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pertanian',
                'image' => 'assets/pertanian.png',
                'description' => 'Diskusi tentang teknik bertani, tanaman, pupuk, dan segala hal yang berkaitan dengan pertanian.'
            ],
            [
                'name' => 'Peternakan',
                'image' => 'assets/peternakan.png',
                'description' => 'Forum untuk peternak dan penggemar hewan ternak. Diskusikan tentang pemeliharaan, nutrisi, dan manajemen peternakan.'
            ],
            [
                'name' => 'Kehutanan',
                'image' => 'assets/kehutanan.png',
                'description' => 'Diskusi tentang pengelolaan hutan, konservasi, tanaman hutan, dan segala hal yang berkaitan dengan kehutanan.'
            ],
            [
                'name' => 'Perikanan',
                'image' => 'assets/perikanan.png',
                'description' => 'Forum untuk pembudidaya ikan dan penggemar perikanan. Diskusikan tentang pemeliharaan ikan, jenis-jenis ikan, dan manajemen perikanan.'
            ],
            [
                'name' => 'Sosial Humaniora',
                'image' => 'assets/socialhumaniora.png',
                'description' => 'Diskusi tentang masalah sosial di lingkungan pertanian, humanisme, budaya, dan hal-hal lain yang berkaitan dengan sosial humaniora.'
            ],
            [
                'name' => 'Kesehatan Hewan',
                'image' => 'assets/kesehatanhewan.png',
                'description' => 'Forum untuk berdiskusi tentang kesehatan hewan, penyakit hewan, pengobatan, dan cara menjaga kesehatan hewan.'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}