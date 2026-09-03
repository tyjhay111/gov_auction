@props(['message', 'action', 'href'])

<div class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center dark:border-zinc-700 dark:bg-zinc-900/50">
    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ $message }}</p>
    <a href="{{ $href }}" class="mt-4 inline-block rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">{{ $action }}</a>
</div>
