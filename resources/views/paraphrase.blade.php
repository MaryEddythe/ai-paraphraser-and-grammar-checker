<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paraphraser</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-xl font-bold mb-4">AI Paraphraser</h1>
        
        <form action="/paraphrase" method="POST">
            @csrf
            <textarea name="text" rows="5" class="w-full p-2 border rounded-md" placeholder="Enter text to paraphrase...">{{ old('text', $text ?? '') }}</textarea>
            
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Paraphrase</button>
        </form>
        

        @if(isset($paraphrasedText))
            <div class="mt-4 p-3 bg-gray-100 border rounded-md">
                <strong>Paraphrased Text:</strong>
                <p class="mt-2 text-gray-700">{{ $paraphrasedText }}</p>
            </div>
        @endif
    </div>
</body>
</html>
