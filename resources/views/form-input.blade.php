<!-- Professional Form Input Component -->
<!-- Usage: @include('form-input', ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'placeholder' => 'you@example.com', 'required' => true]) -->
<div class="mb-6">
    @if($label ?? false)
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
        {{ $label }}
        @if($required ?? false)
            <span class="text-red-600">*</span>
        @endif
    </label>
    @endif
    
    @if($type === 'textarea' || $type === 'textarea')
        <textarea 
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder ?? '' }}"
            rows="{{ $rows ?? 4 }}"
            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all backdrop-blur-sm"
            {{ $required ?? false ? 'required' : '' }}
            {{ $attributes }}
        >{{ $value ?? old($name) }}</textarea>
    @elseif($type === 'select')
        <select 
            id="{{ $name }}"
            name="{{ $name }}"
            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
            {{ $required ?? false ? 'required' : '' }}
            {{ $attributes }}
        >
            <option value="">{{ $placeholder ?? 'Select an option' }}</option>
            @if($options ?? false)
                @foreach($options as $val => $label)
                    <option value="{{ $val }}" {{ ($value ?? old($name)) == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            @endif
        </select>
    @else
        <input 
            type="{{ $type ?? 'text' }}"
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder ?? '' }}"
            value="{{ $value ?? old($name) }}"
            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all backdrop-blur-sm"
            {{ $required ?? false ? 'required' : '' }}
            {{ $attributes }}
        />
    @endif
    
    @error($name)
        <p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>
    @enderror
</div>
