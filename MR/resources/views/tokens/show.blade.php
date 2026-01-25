@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">U�O-U.U? OU,O�U^U�U+</p>
            <h2 class="text-3xl font-bold text-gradient">{{ $token->name }}</h2>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('tokens.edit', $token) }}" class="btn btn-primary">
                <span class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    O�O1O_USU,
                </span>
            </a>
            <a href="{{ route('tokens.index') }}" class="btn btn-secondary">
                OU,U^O�O�
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">O�O�O_USO� OU,O�U^U�U+</h3>
                @php
                    $statusText = match($token->status) {
                        'active' => 'U+O\'O�',
                        'inactive' => 'O�USO� U+O\'O�',
                        'expired' => 'U.U+O�U�US',
                        default => $token->status,
                    };
                @endphp
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�O�O_USO�</dt>
                        <dd class="text-base text-gray-900 font-semibold">{{ $token->token }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�O�U.O\u0015U,US</dt>
                        <dd class="text-base text-gray-900">{{ optional($token->created_at)->diffForHumans() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�O�O_U� O�U^U�U+O\u0015O�</dt>
                        <dd class="text-base text-gray-900">{{ $token->formatted_token }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�O-O�OUS</dt>
                        <dd>
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                {{ $statusText }}
                            </span>
                        </dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">O�U^O�O_</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($token->abilities ?? [] as $ability)
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">{{ $ability }}</span>
                        @empty
                            <span class="text-sm text-gray-500">U,O O�U^O�O_ O�U,OO-USOO�</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">O�U,U.O�OU,O\"Oc &amp; O�U+O\"USU�</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�U,U.O�OU,O\"Oc O3O�OrO_U.</dt>
                        <dd class="text-base text-gray-900">{{ $tokenPayload['last_used_at'] ?? 'O,U. USU?O3O�OrO_U. O\"O1O_' }}</dd>
                        <p class="text-xs text-gray-500">{{ $tokenPayload['days_since_last_use'] ? $tokenPayload['days_since_last_use'] . ' USU^U. U�U,U.O�OU,O\"Oc' : '' }}</p>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�O3O�USU, O�O�O_U�</dt>
                        <dd class="text-base text-gray-900">{{ $tokenPayload['expires_at'] ?? 'U,O USU+O�U�US' }}</dd>
                        <p class="text-xs text-gray-500">
                            {{ $tokenPayload['remaining_days'] ? $tokenPayload['remaining_days'] . ' USU^U. O�O-O�OUS' : 'O�O�U?USOc U,O USU+O�U�US' }}
                        </p>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�O�U.SU, O�O,O�O_</dt>
                        <dd class="text-base text-gray-900">{{ $tokenPayload['usage_stats']['created_at'] ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">O�O�O_U� O�U^U�U+O\u0015O� O�O-U.OS</dt>
                        <dd class="text-base text-gray-900">
                            {{ $tokenPayload['usage_stats']['status'] ?? $token->status }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">O�U^U�U+O\u0015O� O�O-O�OUS</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">O�U,O�U^U�U+O\u0015O� O\u0015U,U+O'O�Oc</p>
                            <p class="text-xs text-gray-500">O�O�O_USO� O�O-O�OUS</p>
                        </div>
                        <span class="text-lg font-semibold text-green-600">{{ $tokenPayload['usage_stats']['status'] ?? '---' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">O�O�O_U� O�U^U�U+O\u0015O� O�O-O�OUS</p>
                            <p class="text-xs text-gray-500">O�O3O�USU, O�O�O_U�</p>
                        </div>
                        <span class="text-lg font-semibold text-blue-600">{{ $tokenPayload['remaining_days'] ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
