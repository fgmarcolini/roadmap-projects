<x-layout :title="$article->title" heading="Personal Blog">
    <h1>
        <u>{{ $article->title }}</u>
    </h1>
    <div class="bg-white p-4 rounded shadow-sm">
        <article>
            <x-markdown>
                {{ $article->content }}
            </x-markdown>
        </article>
        <hr />
        <footer>
            <p class="text-muted">Published on: {{ date_format($article->dop, 'M j, Y') }}</p>
            <a href="{{ route('articles.index') }}" class="btn btn-outline-primary btn-sm">Go back to home</a>
        </footer>
    </div>
</x-layout>
