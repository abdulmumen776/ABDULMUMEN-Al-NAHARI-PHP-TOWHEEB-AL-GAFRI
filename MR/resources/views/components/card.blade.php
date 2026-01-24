@props([
    'title' => null,
    'subtitle' => null,
    'image' => null,
    'imageAlt' => null,
    'hover' => false,
    'border' => true,
    'shadow' => true,
    'padding' => 'normal',
    'class' => '',
    'headerClass' => '',
    'bodyClass' => '',
    'footerClass' => '',
])

@php
    $baseClasses = 'bg-white rounded-lg overflow-hidden';
    
    $shadowClasses = match($shadow) {
        'none' => '',
        'sm' => 'shadow-sm',
        'normal' => 'shadow',
        'lg' => 'shadow-lg',
        'xl' => 'shadow-xl',
        '2xl' => 'shadow-2xl',
        default => 'shadow'
    };
    
    $borderClasses = $border ? 'border border-gray-200' : '';
    
    $paddingClasses = match($padding) {
        'none' => '',
        'sm' => 'p-4',
        'normal' => 'p-6',
        'lg' => 'p-8',
        'xl' => 'p-10',
        default => 'p-6'
    };
    
    $hoverClasses = $hover ? 'hover:shadow-lg transition-shadow duration-300' : '';
    
    $allClasses = implode(' ', array_filter([$baseClasses, $shadowClasses, $borderClasses, $hoverClasses, $class]));
@endphp

<div class="{{ $allClasses }}">
    @if($image)
        <div class="relative h-48 bg-gray-200">
            <img src="{{ $image }}" alt="{{ $imageAlt ?? $title }}" class="w-full h-full object-cover">
            @if($title)
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute bottom-4 right-4 text-white">
                    <h3 class="text-lg font-semibold">{{ $title }}</h3>
                    @if($subtitle)
                        <p class="text-sm opacity-90">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif
        </div>
    @else
        @if($title || $subtitle)
            <div class="px-6 py-4 border-b border-gray-200 {{ $headerClass }}">
                @if($title)
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
        @endif
    @endif
    
    <div class="{{ $image ? 'p-6' : $paddingClasses }} {{ $bodyClass }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 {{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif
</div>
