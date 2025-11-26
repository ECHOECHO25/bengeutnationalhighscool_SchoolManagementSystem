@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-gray-900 focus:ring-gray-200 focus:ring-2  rounded-xl shadow-sm']) }}>
