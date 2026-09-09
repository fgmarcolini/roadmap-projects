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
                    <input type="date" name="created_at" class="form-control">
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
                    <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                </a>
            @endforeach
        </div>

        <!-- Pagination Controls -->
        {{ $pageTotal = 0 }}
        {{ $pageNumber = 0 }}
        @if ($pageTotal > 1)
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $pageNumber == 0 ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ url('/home') }}?{{ http_build_query(['page' => $pageNumber - 1, 'title' => request('title', $articleFilterDto['title'] ?? ''), 'dateOfPublication' => request('dateOfPublication', $articleFilterDto['dateOfPublication'] ?? '')]) }}">Previous</a>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link">Page {{ $pageNumber + 1 }} of {{ $pageTotal }}</span>
                    </li>
                    <li class="page-item {{ $pageNumber + 1 == $pageTotal ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ url('/home') }}?{{ http_build_query(['page' => $pageNumber + 1, 'title' => request('title', $articleFilterDto['title'] ?? ''), 'dateOfPublication' => request('dateOfPublication', $articleFilterDto['dateOfPublication'] ?? '')]) }}">Next</a>
                    </li>
                </ul>
            </nav>
        @endif
    </main>
</body>

</html>
