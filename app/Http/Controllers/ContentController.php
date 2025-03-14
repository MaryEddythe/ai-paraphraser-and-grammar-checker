<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContentController extends Controller
{
    public function showForm()
    {
        $active = 'paraphrase';
        return view('paraphrase', compact('active'));
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

        $active = 'paraphrase';

        return view('paraphrase', compact('paraphrasedTexts', 'text', 'mode', 'active'));
    }

    public function showGrammarForm()
    {
        $active = 'grammar'; 
        return view('grammar', compact('active'));
    }

    public function checkGrammar(Request $request)
{
    $request->validate([
        'text' => 'required|string',
        'audience' => 'required|string',
        'formality' => 'required|string',
        'domain' => 'required|string',
        'intent' => 'required|string',
    ]);

    $text = $request->input('text');
    $audience = $request->input('audience');
    $formality = $request->input('formality');
    $domain = $request->input('domain');
    $intent = $request->input('intent');

    $prompt = "Check and correct the grammar of the following text. ";
    $prompt .= "The audience is {$audience}, the formality level is {$formality}, ";
    $prompt .= "the domain is {$domain}, and the intent is {$intent}. ";
    $prompt .= "Here is the text: {$text}";

    $apiKey = env('GEMINI_API_KEY');
    $client = new Client();

    try {
        $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        $rawResult = $body['candidates'][0]['content']['parts'][0]['text'] ?? 'Error: No output received from the API.';

        $grammarResult = $this->parseGrammarSuggestions($rawResult);

    } catch (\Exception $e) {
        \Log::error('API Error: ' . $e->getMessage());
        $grammarResult = [['text' => 'Error communicating with the API. Please check your API key or try again.', 'type' => 'error']];
    }

    $active = 'grammar'; 
    return view('grammar', compact('grammarResult', 'active', 'text'));
}

private function parseGrammarSuggestions($text)
{
    $suggestions = [];

    $lines = explode("\n", $text);
    foreach ($lines as $line) {
        if (str_contains($line, 'spelling error')) {
            $suggestions[] = [
                'text' => $line,
                'type' => 'correctness',
                'message' => 'Correct the spelling.',
                'replacement' => str_replace('error', 'correction', $line), // Example replacement
            ];
        } elseif (str_contains($line, 'unclear')) {
            $suggestions[] = [
                'text' => $line,
                'type' => 'clarity',
                'message' => 'Improve clarity.',
                'replacement' => str_replace('unclear', 'clear', $line), // Example replacement
            ];
        }
    }

    return $suggestions;
}
}

