<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Paraphraser</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <div class="container-box">
            <h1>AI Paraphraser</h1>
            <p>Enter text on the left, and the paraphrased result will appear on the right.</p>

            <div class="row">
                <!-- Left Side: Input Text -->
                <div class="col-md-6">
                    <form action="/paraphrase" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="text" class="form-label">Your Text:</label>
                            <textarea name="text" id="text" rows="8" class="form-control" placeholder="Enter text to paraphrase..." required>{{ old('text', $text ?? '') }}</textarea>
                        </div>

                        <!-- Mode Selection -->
                        <div class="mb-3">
                            <label class="form-label">Choose Paraphrase Mode:</label>
                            <select name="mode" id="mode" class="form-select">
                                <option value="fluency" {{ old('mode', $mode ?? '') == 'fluency' ? 'selected' : '' }}>Fluency Mode</option>
                                <option value="formal" {{ old('mode', $mode ?? '') == 'formal' ? 'selected' : '' }}>Formal Mode</option>
                                <option value="academic" {{ old('mode', $mode ?? '') == 'academic' ? 'selected' : '' }}>Academic Mode</option>
                                <option value="seo" {{ old('mode', $mode ?? '') == 'seo' ? 'selected' : '' }}>SEO Mode</option>
                                <option value="concise" {{ old('mode', $mode ?? '') == 'concise' ? 'selected' : '' }}>Concise Mode</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Paraphrase</button>
                    </form>
                </div>

                <!-- Right Side: Paraphrased Result -->
                <div class="col-md-6">
                    <div class="result-box">
                        <label class="form-label">Paraphrased Text:</label>
                        @if(isset($paraphrasedTexts) && is_array($paraphrasedTexts))
                            @foreach(array_slice($paraphrasedTexts, 0, 2) as $index => $paraphrasedText)
                                <div class="paraphrase-option border p-2 mt-2">
                                    <strong>Option {{ $index + 1 }}:</strong>
                                    <textarea class="form-control" rows="4" readonly>{{ $paraphrasedText }}</textarea>
                                </div>
                            @endforeach
                        @else
                            <textarea class="form-control" rows="8" readonly>{{ $paraphrasedText ?? 'Your paraphrased text will appear here...' }}</textarea>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
