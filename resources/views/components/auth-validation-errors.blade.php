@props(['errors'])
@if ($errors->any())
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
        @foreach ($errors->all() as $error)
            <div class="font-light">{{ $error }}</div>
        @endforeach
    </div>
@endif
