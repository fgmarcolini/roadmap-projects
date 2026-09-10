<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <header class="bg-primary text-white p-4 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Personal Blog</h1>
            <a href="/admin" class="btn btn-outline-light">Admin Login</a>
        </div>
    </header>
    <main class="container">
        <div class="container mb-4">
            <form action="{{ url('/home') }}" method="get" class="row g-3">
                <div class="col-auto">
                    <input type="text" name="title" class="form-control" placeholder="Filter by title...">
                </div>
                <div class="col-auto">
                    <input type="date" name="dop" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ url('/home') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>

        <div class="list-group">
            @foreach ($articles as $article)
                <a href="{{ url('/article/' . $article->id) }}"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <h5 class="mb-1">{{ $article->title }}</h5>
                    <small class="text-muted">{{ $article->updated_at->diffForHumans() }}</small>
                </a>
            @endforeach
        </div>

        <!-- Pagination Controls -->
        {{ $articles->links() }}
    </main>
</body>

</html>
