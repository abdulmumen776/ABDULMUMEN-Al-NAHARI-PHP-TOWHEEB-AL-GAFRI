@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">تفاصيل العميل: {{ $client->name }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('clients.edit', $client) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                تعديل
            </a>
            <a href="{{ route('clients.dashboard', $client) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                لوحة التحكم
            </a>
            <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-900">
                العودة للقائمة
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Client Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        معلومات العميل
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        المعلومات الأساسية للعميل
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                اسم العميل
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $client->name }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                الصناعة
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $client->industry ?? 'غير محدد' }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                معلومات الاتصال
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($client->contact_email)
                                    <div class="mb-2">
                                        <span class="font-medium">البريد الإلكتروني:</span> {{ $client->contact_email }}
                                    </div>
                                @endif
                                @if($client->contact_phone)
                                    <div>
                                        <span class="font-medium">الهاتف:</span> {{ $client->contact_phone }}
                                    </div>
                                @endif
                                @if(!$client->contact_email && !$client->contact_phone)
                                    <span class="text-gray-400">لا يوجد معلومات اتصال</span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                الحالة
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : ($client->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $client->status === 'active' ? 'نشط' : ($client->status === 'inactive' ? 'غير نشط' : 'محظور') }}
                                </span>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                تاريخ الإنشاء
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $client->created_at->format('Y-m-d H:i:s') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 100 4h2a2 2 0 100-4h-.01zM7 8a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zM8 12a1 1 0 011-1h4a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        العمليات
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $client->operations->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        الـ APIs
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $client->apis->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        المقاييس
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $client->operations->sum(function($operation) { return $operation->performanceMetrics->count(); }) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 8h12v8H4V8z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        آخر تحديث
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $client->updated_at->format('M d, Y') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Operations -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        العمليات الأخيرة
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        آخر 5 عمليات للعميل
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    @if($client->operations->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($client->operations->take(5) as $operation)
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $operation->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $operation->type ?? 'غير محدد' }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $operation->status === 'active' ? 'bg-green-100 text-green-800' : ($operation->status === 'completed' ? 'bg-blue-100 text-blue-800' : ($operation->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $operation->status === 'active' ? 'نشط' : ($operation->status === 'completed' ? 'مكتمل' : ($operation->status === 'scheduled' ? 'مجدول' : 'ملغي')) }}
                                            </span>
                                            <a href="{{ route('operations.show', $operation) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                                عرض
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                            لا توجد عمليات لهذا العميل
                        </div>
                    @endif
                </div>
            </div>

            <!-- APIs -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        واجهات برمجة التطبيقات (APIs)
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        الـ APIs المرتبطة بالعميل
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    @if($client->apis->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($client->apis as $api)
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $api->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $api->base_url }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $api->status === 'monitored' ? 'bg-green-100 text-green-800' : ($api->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $api->status === 'monitored' ? 'مُراقب' : ($api->status === 'inactive' ? 'غير نشط' : 'خطأ') }}
                                            </span>
                                            <a href="{{ route('apis.show', $api) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                                عرض
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                            لا توجد واجهات برمجة تطبيقات مرتبطة بهذا العميل
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
