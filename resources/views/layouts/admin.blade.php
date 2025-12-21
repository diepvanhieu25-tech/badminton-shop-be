<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>

    <!-- Tailwind CDN (dev nhanh). Production sẽ chuyển sang build sau -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
<div class="min-h-screen flex">

    @include('admin.partials.sidebar')

    <div class="flex-1 min-w-0">
        @include('admin.partials.header')

        <main class="p-4 md:p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
