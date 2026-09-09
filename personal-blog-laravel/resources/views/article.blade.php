<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Article {{ $article->id }}</title>
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
            <h1>{{ $article->title }}</h1>
            <p class="lead">{!! $article->content !!}</p>
        </article>
        <hr />
        <footer>
            <p class="text-muted">Published on: {{ $article->dop }}</p>
            <a href="{{ url('/home') }}" class="btn btn-outline-primary btn-sm">Go back to home</a>
        </footer>
    </main>
</body>

</html>
