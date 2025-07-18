<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class IdeaController extends Controller
{
    private function getImageUrl(array $post): string
{
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $post['content'], $matches)) {
        return $matches[1];
    }

    return $post['medium_image'][0]['url'] ??
           $post['small_image'][0]['url'] ??
           asset('images/hero.jpg');
}
    public function index()
    {
        $page = request('page', 1);
        $size = request('size', 10);
        $sort = request('sort', '-published_at');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get('https://suitmedia-backend.suitdev.com/api/ideas', [
            'page[number]' => $page,
            'page[size]' => $size,
            'sort' => $sort,
            'append[]' => 'small_image',
            'append[]' => 'medium_image',
        ]);

        if (!$response->successful()) {
            dd($response->status(), $response->body());
        }

        $data = $response->json();
        $ideas = $data['data'] ?? [];

        foreach ($ideas as &$idea) {
            $idea['first_image'] = null;

            // Prioritaskan gambar dari HTML content jika ada
            if (!empty($idea['content'])) {
                preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $idea['content'], $matches);
                $idea['first_image'] = $matches[1] ?? null;
            }

            if (!$idea['first_image'] && !empty($idea['medium_image'][0]['url'])) {
                $idea['first_image'] = $idea['medium_image'][0]['url'];
            }

            if (!$idea['first_image'] && !empty($idea['small_image'][0]['url'])) {
                $idea['first_image'] = $idea['small_image'][0]['url'];
            }

            if (!$idea['first_image']) {
                $idea['first_image'] = 'https://via.placeholder.com/300x200?text=No+Image';
            }
            $idea['image_loader'] = 'data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQv///wAAAAAAAAAAACH5BAEAAAEALAAAAAAQABAAAAIgjI+py+0Po5yUFQA7';
        }

        return view('ideas', [
            'ideas' => $ideas,
            'meta' => $data['meta'] ?? [],
            'currentPage' => $page,
            'pageSize' => $size,
            'sort' => $sort,
            'bannerImage' => 'https://suitmedia.static-assets.id/storage/files/tinymce/Y9N4q1q3jmLEmbuXcUqfGXWZfOKy7AbVADhQ7xZ3.png',
        ]);
    }
}
