<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $article->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <header class="bg-primary text-white p-4 mb-4">
        <div class="container">
            <h1>Personal Blog</h1>
        </div>
    </header>
    <main class="container bg-white p-4 rounded shadow-sm">
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
    </main>
</body>

</html>
