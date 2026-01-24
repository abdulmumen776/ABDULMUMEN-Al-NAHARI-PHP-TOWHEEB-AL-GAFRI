@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'helper' => null,
    'size' => 'md',
    'variant' => 'default',
    'icon' => null,
    'iconPosition' => 'left',
    'class' => '',
    'model' => null,
])

@php
    $baseClasses = 'block w-full rounded-lg border focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
        default => 'px-4 py-2 text-sm'
    };
    
    $variantClasses = match($variant) {
        'default' => 'border-gray-300 focus:border-blue-500 focus:ring-blue-500',
        'success' => 'border-green-300 focus:border-green-500 focus:ring-green-500',
        'warning' => 'border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500',
        'error' => 'border-red-300 focus:border-red-500 focus:ring-red-500',
        'dark' => 'border-gray-600 bg-gray-800 text-white focus:border-blue-500 focus:ring-blue-500',
        default => 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'
    };
    
    $stateClasses = '';
    if ($error) {
        $stateClasses = 'border-red-300 focus:border-red-500 focus:ring-red-500';
    }
    
    $allClasses = implode(' ', array_filter([$baseClasses, $sizeClasses, $variantClasses, $stateClasses, $class]));
    
    $inputId = $id ?? $name ?? 'input-' . uniqid();
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium mb-2 text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {{ $icon }}
                </svg>
            </div>
        @endif
        
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $model ? 'x-model="' . $model . '"' : '' }}
            class="{{ $allClasses }}"
            {{ $icon && $iconPosition === 'left' ? 'pr-10' : '' }}
            {{ $icon && $iconPosition === 'right' ? 'pl-10' : '' }}
        />
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {{ $icon }}
                </svg>
            </div>
        @endif
    </div>
    
    @if($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
    
    @if($helper && !$error)
        <p class="mt-2 text-sm text-gray-500">{{ $helper }}</p>
    @endif
</div>
