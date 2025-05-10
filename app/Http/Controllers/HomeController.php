<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // You can fetch popular articles from database here
        $popularArticles = [
            'Judul Artikel Populer 1',
            'Judul Artikel Populer 2',
            'Judul Artikel Populer 3',
            'Judul Artikel Populer 4',
        ];

        // Sample article data - in a real app, this would come from database
        $articles = [
            [
                'image' => 'assets/lettuce-leaves-breakfast-lunch-salad-260nw-2518782599.webp',
                'title' => 'Judul Artikel 1'
            ],
            [
                'image' => 'assets/kebun tomat.webp',
                'title' => 'Judul Artikel 2'
            ],
            [
                'image' => 'assets/premium_photo-1679428402040-e3c93439ec13.jpg',
                'title' => 'Judul Artikel 3'
            ],
            [
                'image' => 'assets/kebun cabai.png',
                'title' => 'Judul Artikel 4'
            ],
            [
                'image' => 'assets/dfddb_anggur.jpg',
                'title' => 'Judul Artikel 5'
            ],
            [
                'image' => 'assets/kebunjagung.png',
                'title' => 'Judul Artikel 6'
            ],
        ];

        return view('home', [
            'popularArticles' => $popularArticles,
            'articles' => $articles,
            'user' => [
                'name' => 'HANNAN AZHARI BATUBARA',
                'role' => 'Mahasiswa'
            ]
        ]);
    }
}