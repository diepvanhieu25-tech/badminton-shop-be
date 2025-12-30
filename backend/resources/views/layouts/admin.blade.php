<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 text-slate-800 antialiased">
<div class="min-h-screen flex">

    @include('admin.partials.sidebar')

    <div class="flex-1 min-w-0 flex flex-col">
        @include('admin.partials.header')

        <main class="flex-1 p-4 md:p-6">
            <div class="mx-auto w-full max-w-6xl">

                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5">✅</div>
                            <div class="font-medium">{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                        <div class="font-semibold mb-2">Có lỗi dữ liệu:</div>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>
</body>
</html>
