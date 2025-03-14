@extends('layout')

@section('title', 'Grammar Checker')

@section('content')
    <div class="container-box">
        <h1>Grammar Checker</h1>
        <p>Enter text below to check for grammar errors and set your writing goals.</p>

        <form id="grammar-form" action="/check-grammar" method="POST">
            @csrf
            <div class="mb-3">
                <label for="text" class="form-label">Your Text:</label>
                <textarea name="text" id="text" rows="8" class="form-control" placeholder="Enter text to check grammar..." required>{{ old('text', $text ?? '') }}</textarea>
            </div>
        
            <!-- Hidden fields for writing goals -->
            <input type="hidden" name="audience" id="audience" value="{{ old('audience', $audience ?? '') }}">
            <input type="hidden" name="formality" id="formality" value="{{ old('formality', $formality ?? '') }}">
            <input type="hidden" name="domain" id="domain" value="{{ old('domain', $domain ?? '') }}">
            <input type="hidden" name="intent" id="intent" value="{{ old('intent', $intent ?? '') }}">
        
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#writingGoalsModal">
                Set Writing Goals
            </button>
            <button type="submit" class="btn btn-primary">Check Grammar</button>
        </form>

        <!-- Modal for Writing Goals -->
        <div class="modal fade" id="writingGoalsModal" tabindex="-1" aria-labelledby="writingGoalsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="writingGoalsModalLabel">Set Writing Goals</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="writing-goals-form">
                            <div class="mb-3">
                                <label for="modal-audience" class="form-label">Audience:</label>
                                <select name="audience" id="modal-audience" class="form-select">
                                    <option value="general">General</option>
                                    <option value="knowledgeable">Knowledgeable</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="modal-formality" class="form-label">Formality:</label>
                                <select name="formality" id="modal-formality" class="form-select">
                                    <option value="informal">Informal</option>
                                    <option value="neutral">Neutral</option>
                                    <option value="formal">Formal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="modal-domain" class="form-label">Domain:</label>
                                <select name="domain" id="modal-domain" class="form-select">
                                    <option value="academic">Academic</option>
                                    <option value="business">Business</option>
                                    <option value="creative">Creative</option>
                                    <option value="technical">Technical</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="modal-intent" class="form-label">Intent:</label>
                                <select name="intent" id="modal-intent" class="form-select">
                                    <option value="inform">Inform</option>
                                    <option value="describe">Describe</option>
                                    <option value="convince">Convince</option>
                                    <option value="tell-a-story">Tell a Story</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save-writing-goals">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const writingGoalsModal = new bootstrap.Modal(document.getElementById('writingGoalsModal'));
            const saveWritingGoalsBtn = document.getElementById('save-writing-goals');
            const grammarForm = document.getElementById('grammar-form');

            document.querySelector('[data-bs-toggle="modal"]').addEventListener('click', function () {
                writingGoalsModal.show();
            });

            saveWritingGoalsBtn.addEventListener('click', function () {
                const audience = document.getElementById('modal-audience').value;
                const formality = document.getElementById('modal-formality').value;
                const domain = document.getElementById('modal-domain').value;
                const intent = document.getElementById('modal-intent').value;

                document.getElementById('audience').value = audience;
                document.getElementById('formality').value = formality;
                document.getElementById('domain').value = domain;
                document.getElementById('intent').value = intent;

                writingGoalsModal.hide();
            });
        });

        function displaySuggestions(text, suggestions) {
        let highlightedText = text;
        suggestions.forEach(suggestion => {
            const { start, end, message, replacement, type } = suggestion;
            const problemText = text.slice(start, end);
            let underlinedText;

            if (type === 'clarity' || type === 'conciseness') {
                underlinedText = `<span class="suggestion clarity" data-message="${message}" data-replacement="${replacement}">${problemText}</span>`;
            } else {
                underlinedText = `<span class="suggestion correctness" data-message="${message}" data-replacement="${replacement}">${problemText}</span>`;
            }

            highlightedText = highlightedText.replace(problemText, underlinedText);
        });

        colorCodedText.innerHTML = highlightedText;

        const underlinedSuggestions = document.querySelectorAll('.suggestion');
        underlinedSuggestions.forEach(suggestion => {
            suggestion.addEventListener('mouseover', function () {
                showTooltip(this);
            });
            suggestion.addEventListener('mouseout', function () {
                hideTooltip();
            });
            suggestion.addEventListener('click', function () {
                applySuggestion(this);
            });
        });
    }
    </script>
@endsection