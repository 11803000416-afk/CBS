<!-- Light Theme Password Input Component -->
<!-- Usage: Include this component from parent views with desired field props. -->
<div class="relative">
    <label class="label-light flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
        {{ $label ?? 'Password' }} {{ isset($required) && $required ? '*' : '' }}
    </label>
    <div class="relative">
        <input 
            name="{{ $name ?? 'password' }}" 
            type="password"
            placeholder="{{ $placeholder ?? 'Enter password' }}"
            class="input-field-light pr-12 {{ $class ?? '' }}"
            value="{{ old($name ?? 'password') }}"
            {{ isset($required) && $required ? 'required' : '' }}
            {{ $inputAttributes ?? '' }}
        >
        <button 
            type="button"
            data-password-toggle="{{ $name ?? 'password' }}"
            class="password-toggle text-slate-500 hover:text-slate-700"
            title="Show password"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l6.364 2.121M9.172 9.172L5.964 5.964M15.828 9.172l3.208-3.208"></path>
            </svg>
        </button>
    </div>
    @error($name ?? 'password')
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>
