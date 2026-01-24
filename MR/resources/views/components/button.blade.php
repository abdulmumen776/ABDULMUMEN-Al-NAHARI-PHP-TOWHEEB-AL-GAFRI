@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'fullWidth' => false,
    'href' => null,
    'onClick' => null,
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
        default => 'px-4 py-2 text-sm'
    };
    
    $variantClasses = match($variant) {
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'info' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500',
        'light' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500',
        'dark' => 'bg-gray-800 text-white hover:bg-gray-900 focus:ring-gray-500',
        'outline' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500',
        'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
        'link' => 'text-blue-600 hover:text-blue-700 focus:ring-blue-500',
        default => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500'
    };
    
    $widthClass = $fullWidth ? 'w-full' : '';
    
    $allClasses = implode(' ', array_filter([$baseClasses, $sizeClasses, $variantClasses, $widthClass, $class]));
@endphp

@if($href)
    <a href="{{ $href }}" 
       class="{{ $allClasses }}"
       {{ $disabled ? 'disabled' : '' }}
       {{ $onClick ? 'onclick="' . $onClick . '"' : '' }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        
        @if($icon && $iconPosition === 'left' && !$loading)
            <svg class="w-5 h-5 {{ $variant === 'link' ? 'mr-2' : 'ml-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{ $icon }}
            </svg>
        @endif
        
        <span>{{ $slot }}</span>
        
        @if($icon && $iconPosition === 'right' && !$loading)
            <svg class="w-5 h-5 {{ $variant === 'link' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{ $icon }}
            </svg>
        @endif
    </a>
@else
    <button type="{{ $type }}" 
            class="{{ $allClasses }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $onClick ? 'onclick="' . $onClick . '"' : '' }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        
        @if($icon && $iconPosition === 'left' && !$loading)
            <svg class="w-5 h-5 {{ $variant === 'link' ? 'mr-2' : 'ml-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{ $icon }}
            </svg>
        @endif
        
        <span>{{ $slot }}</span>
        
        @if($icon && $iconPosition === 'right' && !$loading)
            <svg class="w-5 h-5 {{ $variant === 'link' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{ $icon }}
            </svg>
        @endif
    </button>
@endif
