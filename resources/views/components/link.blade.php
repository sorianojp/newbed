<a
    {{ $attributes->merge(['type' => 'submit', 'class' => 'font-medium text-blue-600 dark:text-blue-500 hover:underline']) }}>
    {{ $slot }}
</a>
