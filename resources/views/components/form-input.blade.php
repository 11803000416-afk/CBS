<!-- Reusable Form Input Component -->
<div class="mb-6">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-300 mb-2">
        {{ $label ?? ucfirst($name) }}
        @if($required ?? false)
            <span class="text-red-400">*</span>
        @endif
    </label>
    <input 
        type="{{ $type ?? 'text' }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value ?? '') }}"
        placeholder="{{ $placeholder ?? '' }}"
        {{ $required ?? false ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200']) }}
    >
    @error($name)
        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l10.1-10.1z" clip-rule="evenodd"/></svg>
            {{ $message }}
        </p>
    @enderror
</div>
