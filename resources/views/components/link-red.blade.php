<a
    {{ $attributes->merge(['type' => 'submit', 'class' => 'font-medium text-red-600 dark:text-red-500 hover:underline']) }}>
    {{ $slot }}
</a>
