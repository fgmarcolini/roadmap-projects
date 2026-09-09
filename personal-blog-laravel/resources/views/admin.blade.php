<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <header class="bg-primary text-white p-4 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Personal Blog Dashboard</h1>
            <a href="{{ url('/new') }}" class="btn btn-light">+ Add Article</a>
        </div>
    </header>
    <div class="container mb-3">
        <a href="{{ url('/home') }}" class="btn btn-outline-primary">Return to Home</a>
    </div>

    <div class="container mb-4">
        <form action="{{ url('/admin') }}" method="get" class="row g-3">
            <div class="col-auto">
                <input type="text" name="title" class="form-control" placeholder="Filter by title...">
            </div>
            <div class="col-auto">
                <input type="date" name="created_at" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ url('/admin') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <main class="container">
        <nav>
            <ul class="list-group">
                @foreach ($articles as $article)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a class="text-decoration-none h5 mb-0"
                            href="{{ url('/article/' . $article->id) }}">{{ $article->title }}</a>
                        <div>
                            <a class="btn btn-sm btn-outline-secondary me-2"
                                href="{{ url('/edit/' . $article->id) }}">Edit</a>
                            <a class="btn btn-sm btn-outline-danger"
                                href="{{ url('/delete/' . $article->id) }}">Delete</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </nav>

    </main>
</body>

</html>
