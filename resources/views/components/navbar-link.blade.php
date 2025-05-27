@props(['active' => false])
<li>
    <a {{ $attributes }}
        class="flex text-sm font-medium text-gray-900 hover:text-primary-700 dark:text-white dark:hover:text-primary-500">
        {{ $slot }}
    </a>
</li>
