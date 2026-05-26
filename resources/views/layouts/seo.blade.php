<title>@yield('title')</title>

<meta name="description"
      content="@yield('description')">

<meta property="og:title"
      content="@yield('title')">

@php
    $canonicalBase = rtrim((string) config('app.url'), '/');
    $canonicalPath = request()->getPathInfo();
    $canonicalPath = $canonicalPath === '/' ? '/' : rtrim($canonicalPath, '/');
    $canonicalUrl = $canonicalBase !== ''
        ? $canonicalBase . ($canonicalPath === '/' ? '/' : $canonicalPath)
        : url()->current();
@endphp
<link rel="canonical"
      href="{{ $canonicalUrl }}">
