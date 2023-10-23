<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PostController extends Controller
{

    public function fetchInstagramPosts()
    {
        $client = new Client();
        $response = $client->get('https://graph.instagram.com/me/media', [
            'query' => [
                'fields' => 'id,caption,media_url',
                'access_token' => env('INSTAGRAM_ACCESS_TOKEN'),
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);

        foreach ($data['data'] as $post) {
            Post::updateOrCreate(
                ['id' => $post['id']],
                [
                    'caption' => $post['caption'] ?? '',
                    'media_url' => $post['media_url']
                ]
            );
        }
    }
}
