<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParaphraseController extends Controller
{
    public function showForm()
    {
        return view('paraphrase');
    }

    public function paraphrase(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $text = $request->input('text');
        $apiKey = env('GEMINI_API_KEY');
        $client = new Client();

        try {
            $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => "Paraphrase this: {$text}"]]]
                    ]
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            // Extract paraphrased text correctly
            if (!empty($body['candidates'][0]['content']['parts'][0]['text'])) {
                $paraphrasedText = $body['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $paraphrasedText = 'Error paraphrasing: No output received';
            }
        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
            $paraphrasedText = 'Error communicating with the API. Please check your API key or try again.';
        }

        return view('paraphrase', compact('paraphrasedText', 'text'));
    }
}
