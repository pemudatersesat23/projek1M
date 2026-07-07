<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Program;
use App\Models\PartnerCampus;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        // Static pages
        $staticPages = [
            ['url' => url('/'),                    'changefreq' => 'weekly',  'priority' => '1.0'],
            ['url' => route('programs.index'),     'changefreq' => 'weekly',  'priority' => '0.9'],
            ['url' => route('pages.alur'),         'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => route('pages.faq'),          'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        // Dynamic pages: Programs
        $programs = Program::where('status', 'aktif')->get();
        $programPages = $programs->map(function ($program) {
            return [
                'url'        => route('programs.show', $program->slug),
                'lastmod'    => $program->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ];
        });

        // Dynamic pages: Berita (News)
        $beritas = Berita::published()->latest()->get();
        $beritaPages = $beritas->map(function ($berita) {
            return [
                'url'        => route('berita.show', $berita),
                'lastmod'    => $berita->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.6',
            ];
        });

        // Dynamic pages: Partner Campuses
        $partners = PartnerCampus::all();
        $partnerPages = $partners->map(function ($partner) {
            return [
                'url'        => route('partner.show', $partner),
                'lastmod'    => $partner->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority'   => '0.5',
            ];
        });

        // Merge all pages
        $pages = collect($staticPages)
            ->merge($programPages)
            ->merge($beritaPages)
            ->merge($partnerPages);

        $content = view('sitemap', compact('pages'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
