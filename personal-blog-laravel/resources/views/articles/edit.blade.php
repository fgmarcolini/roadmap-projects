<x-layout title="Update Article" heading="Update Article">
    <div class="bg-white p-4 rounded shadow-sm">
        <form method="POST" action="{{ route('articles.update', $article) }}">
            @csrf
            @method('PUT')

            <x-form-input name="title" label="Title" :value="$article->title" />
            <x-form-input name="dop" label="Date" type="date" :value="$article->dop" />
            <x-form-input name="content" label="Content" type="textarea" :value="$article->content" />

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <div class="mt-4">
        <a href="{{ route('articles.admin') }}" class="btn btn-outline-secondary btn-sm">Go back to dashboard</a>
    </div>
</x-layout>

<script>
    function setTodayDate() {
        const dateInput = document.getElementById("dop");
        if (!dateInput.value) {
            dateInput.valueAsDate = new Date();
        }
    }
    document.addEventListener("DOMContentLoaded", setTodayDate);
</script>
