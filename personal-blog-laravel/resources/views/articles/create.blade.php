<x-layout title="New Article" heading="New Article">
    <div class="bg-white p-4 rounded shadow-sm">
        <form method="post" action="{{ route('articles.store') }}">
            @csrf

            <x-form-input name="title" label="Title" placeholder="Article Title" />
            <x-form-input name="dop" label="Date" type="date" />
            <x-form-input name="content" label="Content" type="textarea" placeholder="Content" />

            <button type="submit" class="btn btn-primary">Publish</button>
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
