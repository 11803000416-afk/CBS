<!-- Dark Theme Password Input Component -->
<!-- Usage: Include this component from parent views with desired field props. -->
<div class="relative">
    <label class="label-primary">
        {{ $label ?? 'Password' }}
    </label>
    <div class="relative">
        <input 
            name="{{ $name ?? 'password' }}" 
            type="password"
            placeholder="{{ $placeholder ?? 'Enter password' }}"
            class="input-field pr-12 {{ $class ?? '' }}"
            value="{{ old($name ?? 'password') }}"
            {{ $attributes ?? '' }}
        >
        <button 
            type="button"
            data-password-toggle="{{ $name ?? 'password' }}"
            class="password-toggle"
            title="Show password"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l6.364 2.121M9.172 9.172L5.964 5.964M15.828 9.172l3.208-3.208"></path>
            </svg>
        </button>
    </div>
    @error($name ?? 'password')
        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>
