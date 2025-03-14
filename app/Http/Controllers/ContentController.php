<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContentController extends Controller
{
    public function showForm()
    {
        return view('paraphrase');
    }

    public function paraphrase(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'mode' => 'required|string',
        ]);

        $text = $request->input('text');
        $mode = $request->input('mode');
        $apiKey = env('GEMINI_API_KEY');
        $client = new Client();

        $modePrompts = [
            'fluency' => 'Enhance readability and improve grammar',
            'formal' => 'Make the text more professional and polished',
            'academic' => 'Rewrite using scholarly language with precise wording',
            'seo' => 'Optimize text with keywords for better search engine ranking',
            'concise' => 'Shorten the text while keeping key ideas intact'
        ];

        $prompt = $modePrompts[$mode] ?? 'Paraphrase this text';

        $paraphrasedTexts = [];

        try {
            $response1 = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => "{$prompt}: {$text}"]]]
                    ]
                ],
            ]);

            $body1 = json_decode($response1->getBody(), true);
            $paraphrasedTexts[] = $body1['candidates'][0]['content']['parts'][0]['text'] ?? 'Error paraphrasing: No output received';

            $response2 = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => "{$prompt}: {$text}"]]]
                    ]
                ],
            ]);

            $body2 = json_decode($response2->getBody(), true);
            $paraphrasedTexts[] = $body2['candidates'][0]['content']['parts'][0]['text'] ?? 'Error paraphrasing: No output received';

        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
            $paraphrasedTexts = ['Error communicating with the API. Please check your API key or try again.'];
        }

        return view('paraphrase', compact('paraphrasedTexts', 'text', 'mode'));
    }
}

