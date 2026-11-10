<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIMS') - Student Information Management System</title>
    <style>
        :root {
            --primary:#4f46e5; --primary-dark:#4338ca; --primary-soft:#eef2ff;
            --bg:#f6f7fb; --surface:#fff; --text:#111827; --muted:#6b7280; --border:#e5e7eb;
            --danger:#dc2626; --sidebar-w:250px; --header-h:64px;
            --radius:12px; --shadow:0 1px 2px rgba(16,24,40,.05), 0 1px 3px rgba(16,24,40,.08);
        }
        * { box-sizing:border-box; }
        body { margin:0; font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; background:var(--bg); color:var(--text); -webkit-font-smoothing:antialiased; }
        a { color:var(--primary); }
        svg.i { width:18px; height:18px; flex-shrink:0; stroke:currentColor; fill:none; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }

        /* ---------- Layout ---------- */
        .layout { display:flex; min-height:100vh; }
        .sidebar { width:var(--sidebar-w); background:#fff; border-right:1px solid var(--border); display:flex; flex-direction:column; position:sticky; top:0; height:100vh; flex-shrink:0; }
        .brand { height:var(--header-h); display:flex; align-items:center; gap:.7rem; padding:0 1.25rem; border-bottom:1px solid var(--border); font-weight:700; font-size:1.05rem; }
        .brand .logo { width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg,var(--primary),#7c3aed); color:#fff; display:grid; place-items:center; }
        .brand small { display:block; font-weight:400; font-size:.7rem; color:var(--muted); line-height:1; margin-top:2px; }
        .menu { flex:1; padding:1rem .75rem; overflow-y:auto; }
        .menu-label { padding:.9rem .75rem .4rem; font-size:.68rem; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; font-weight:600; }
        .menu a { display:flex; align-items:center; gap:.75rem; padding:.6rem .75rem; margin-bottom:2px; border-radius:8px; color:#4b5563; text-decoration:none; font-size:.93rem; font-weight:500; transition:.15s; }
        .menu a:hover { background:#f3f4f6; color:var(--text); }
        .menu a.active { background:var(--primary-soft); color:var(--primary); }
        .sidebar-foot { padding:1rem 1.25rem; border-top:1px solid var(--border); font-size:.75rem; color:var(--muted); }

        .content { flex:1; min-width:0; display:flex; flex-direction:column; }
        .topbar { height:var(--header-h); background:rgba(255,255,255,.9); backdrop-filter:blur(8px); border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; padding:0 1.75rem; position:sticky; top:0; z-index:10; }
        .topbar .page { font-weight:600; font-size:1.05rem; }
        .topbar .right { display:flex; align-items:center; gap:1rem; }
        .who { display:flex; align-items:center; gap:.7rem; }
        .avatar { width:36px; height:36px; border-radius:50%; background:var(--primary-soft); color:var(--primary); display:grid; place-items:center; font-weight:700; }
        .who .name { font-size:.88rem; font-weight:600; line-height:1.1; } .who .role { font-size:.72rem; color:var(--muted); text-transform:capitalize; }
        .topbar form { margin:0; }
        main { width:100%; max-width:100%; margin:0; padding:1.75rem; }

        /* ---------- Components ---------- */
        .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:1.4rem; box-shadow:var(--shadow); margin-bottom:1.25rem; }
        h1 { margin:0 0 1rem; font-size:1.45rem; letter-spacing:-.01em; } h2 { font-size:1rem; margin:0 0 .75rem; }
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; padding:.7rem .75rem; font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); font-weight:600; background:#f9fafb; border-bottom:1px solid var(--border); }
        td { padding:.75rem; border-bottom:1px solid #f1f2f4; font-size:.9rem; }
        tbody tr:hover { background:#fafafe; } tbody tr:last-child td { border-bottom:0; }
        .btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem 1rem; border-radius:8px; border:1px solid transparent; background:var(--primary); color:#fff; text-decoration:none; cursor:pointer; font-size:.88rem; font-weight:500; font-family:inherit; transition:.15s; }
        .btn:hover { background:var(--primary-dark); }
        .btn.secondary { background:#fff; color:#374151; border-color:var(--border); } .btn.secondary:hover { background:#f3f4f6; }
        .btn.danger { background:#fef2f2; color:var(--danger); border-color:#fecaca; } .btn.danger:hover { background:var(--danger); color:#fff; }
        .btn.small { padding:.3rem .65rem; font-size:.78rem; }
        label { display:block; font-weight:500; margin:1rem 0 .3rem; font-size:.85rem; color:#374151; }
        input, select { width:100%; padding:.6rem .75rem; border:1px solid #d1d5db; border-radius:8px; font-size:.92rem; font-family:inherit; background:#fff; transition:.15s; }
        input:focus, select:focus { outline:0; border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.15); }
        input.invalid, select.invalid { border-color:var(--danger); }
        input[type=checkbox] { width:auto; }
        .error { color:var(--danger); font-size:.8rem; margin-top:.25rem; }
        .alert { padding:.8rem 1rem; border-radius:10px; margin-bottom:1.25rem; font-size:.9rem; }
        .alert.error-alert { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        .inline-form { margin:0; } .inline-form select { width:auto; padding:.3rem .5rem; font-size:.85rem; }
        .alert.success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .toolbar { display:flex; justify-content:space-between; align-items:center; gap:.75rem; margin-bottom:1.1rem; flex-wrap:wrap; }
        .toolbar h1 { margin:0; } .toolbar form { display:flex; gap:.5rem; } .toolbar input, .toolbar select { width:230px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:1.25rem; margin-bottom:1.25rem; }
        .grid .card { margin-bottom:0; }
        .stat-card { display:flex; align-items:center; gap:1rem; }
        .stat-icon { width:46px; height:46px; border-radius:12px; display:grid; place-items:center; background:var(--primary-soft); color:var(--primary); }
        .stat-icon.green { background:#ecfdf5; color:#059669; } .stat-icon.amber { background:#fffbeb; color:#d97706; } .stat-icon.pink { background:#fdf2f8; color:#db2777; }
        .stat { font-size:1.8rem; font-weight:700; line-height:1; } .muted { color:var(--muted); font-size:.82rem; }
        .badge { display:inline-block; padding:.15rem .6rem; border-radius:999px; font-size:.72rem; font-weight:600; background:#f3f4f6; color:#374151; }
        .badge.enrolled { background:#dbeafe; color:#1e40af; } .badge.completed { background:#dcfce7; color:#166534; } .badge.dropped { background:#fee2e2; color:#991b1b; }
        .actions { display:flex; gap:.35rem; justify-content:flex-end; } .actions form { margin:0; }
        /* Unified data table */
        .datatable .toolbar-right { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }
        .datatable .filters { display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; }
        .datatable .filters input, .datatable .filters select { width:210px; }
        /* Data table: square corners, horizontal (top/bottom) lines only, no left/right borders */
        .table-wrap { overflow-x:auto; border:0; border-top:1px solid var(--border); border-bottom:1px solid var(--border); border-radius:0; }
        .table-wrap table, .table-wrap th, .table-wrap td { border-left:0; border-right:0; border-radius:0; }
        .table-wrap tbody tr:last-child td { border-bottom:0; }
        .table-wrap th.r, .table-wrap td.actions { text-align:right; }
        .table-wrap td.actions { display:table-cell; white-space:nowrap; }
        .table-wrap td.actions > * { display:inline-flex; vertical-align:middle; }
        .icon-btn { width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; border:1px solid var(--border); background:#fff; color:#4b5563; cursor:pointer; padding:0; transition:.15s; }
        .icon-btn svg.i { width:16px; height:16px; }
        .icon-btn:hover { background:#f3f4f6; color:var(--text); }
        .icon-btn.view:hover { background:#eff6ff; border-color:#bfdbfe; color:#2563eb; }
        .icon-btn.edit:hover { background:var(--primary-soft); border-color:#c7d2fe; color:var(--primary); }
        .icon-btn.delete { color:var(--danger); } .icon-btn.delete:hover { background:var(--danger); border-color:var(--danger); color:#fff; }
        td.empty { text-align:center; color:var(--muted); padding:2.5rem 1rem; }
        .table-foot { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-top:1rem; flex-wrap:wrap; }
        .pager { display:flex; gap:.3rem; flex-wrap:wrap; }
        .pager .pg { min-width:36px; height:36px; padding:0 .7rem; display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--border); border-radius:8px; background:#fff; color:#374151; text-decoration:none; font-size:.85rem; font-weight:500; transition:.15s; }
        .pager a.pg:hover { background:var(--primary-soft); border-color:var(--primary); color:var(--primary); }
        .pager .pg.active { background:var(--primary); border-color:var(--primary); color:#fff; }
        .pager .pg.disabled { color:#c4c7cd; background:#f9fafb; cursor:not-allowed; }
        .pager .pg.dots { border:0; background:none; min-width:auto; }

        /* ---------- Guest pages ---------- */
        .guest { min-height:100vh; display:grid; place-items:center; padding:1.5rem; background:radial-gradient(circle at 20% 10%,#e0e7ff,transparent 45%),radial-gradient(circle at 80% 90%,#ede9fe,transparent 45%),var(--bg); }
        .auth { width:100%; max-width:420px; margin:0; padding:2rem; }
        .auth .brand { height:auto; border:0; padding:0 0 1.25rem; }
        .auth h1 { font-size:1.35rem; }
        .auth .btn { justify-content:center; }
        .auth .row { display:flex; align-items:center; justify-content:space-between; margin-top:1.25rem; gap:1rem; }
        .auth .hint { margin-top:1.25rem; font-size:.8rem; color:var(--muted); text-align:center; }

        /* Mobile drawer, hamburger, overlay, back-to-top */
        .topbar .left { display:flex; align-items:center; gap:.75rem; min-width:0; }
        .menu-toggle, .sidebar-close { display:none; width:38px; height:38px; align-items:center; justify-content:center; border:1px solid var(--border); border-radius:8px; background:#fff; color:#374151; cursor:pointer; padding:0; }
        .menu-toggle svg.i, .sidebar-close svg.i { width:20px; height:20px; }
        .sidebar-close { margin-left:auto; border:0; }
        .overlay { display:none; position:fixed; inset:0; background:rgba(17,24,39,.5); z-index:40; opacity:0; transition:opacity .25s; }
        .to-top { position:fixed; right:1.25rem; bottom:1.25rem; width:44px; height:44px; border-radius:50%; border:0; background:var(--primary); color:#fff; display:grid; place-items:center; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,.4); opacity:0; visibility:hidden; transform:translateY(10px); transition:.25s; z-index:30; padding:0; }
        .to-top.show { opacity:1; visibility:visible; transform:none; } .to-top:hover { background:var(--primary-dark); }
        .to-top svg.i { width:20px; height:20px; }

        @media (max-width: 860px) {
            .sidebar { position:fixed; top:0; left:0; bottom:0; height:100%; z-index:50; transform:translateX(-100%); transition:transform .25s ease; box-shadow:none; }
            body.nav-open .sidebar { transform:none; box-shadow:0 0 40px rgba(0,0,0,.25); }
            body.nav-open { overflow:hidden; }
            .overlay { display:block; pointer-events:none; } body.nav-open .overlay { opacity:1; pointer-events:auto; }
            .menu-toggle, .sidebar-close { display:inline-flex; }
            .topbar { padding:0 1rem; } main { padding:1rem; }
            .who .meta { display:none; }
            .toolbar input, .toolbar select { width:100%; } .toolbar form, .datatable .filters { width:100%; } .datatable .filters input, .datatable .filters select { width:100%; flex:1 1 100%; }
            .datatable .toolbar-right { width:100%; }
            .card { overflow-x:auto; }
        }
    </style>
</head>
<body>
@auth
@php
    $icons = [
        'dashboard' => '<path d="M3 13h8V3H3zM13 21h8V11h-8zM13 3v6h8V3zM3 21h8v-6H3z"/>',
        'students' => '<path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'subjects' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/>',
        'enrollments' => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
        'users' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'menu' => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'close' => '<path d="M18 6L6 18M6 6l12 12"/>',
        'up' => '<path d="M12 19V5M5 12l7-7 7 7"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/>',
    ];
    $icon = fn ($n) => new \Illuminate\Support\HtmlString('<svg class="i" viewBox="0 0 24 24">'.$icons[$n].'</svg>');
@endphp
<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <div class="logo">{{ $icon('students') }}</div>
            <div>SIMS<small>Student Information System</small></div>
            <button type="button" class="sidebar-close" data-nav-close aria-label="Close menu" title="Close menu">{{ $icon('close') }}</button>
        </div>
        <nav class="menu">
            <div class="menu-label">Overview</div>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">{{ $icon('dashboard') }} Dashboard</a>
            <div class="menu-label">Records</div>
            <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">{{ $icon('students') }} Students</a>
            <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}">{{ $icon('subjects') }} Subjects</a>
            <a href="{{ route('enrollments.index') }}" class="{{ request()->routeIs('enrollments.*') ? 'active' : '' }}">{{ $icon('enrollments') }} Enrollments</a>
            @if (auth()->user()->isAdmin())
            <div class="menu-label">Administration</div>
            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">{{ $icon('users') }} Users</a>
            @endif
        </nav>
        <div class="sidebar-foot">&copy; {{ date('Y') }} SIMS</div>
    </aside>
    <div class="content">
        <header class="topbar">
            <div class="left">
                <button type="button" class="menu-toggle" data-nav-toggle aria-label="Open menu" title="Open menu">{{ $icon('menu') }}</button>
                <div class="page">@yield('title')</div>
            </div>
            <div class="right">
                <div class="who">
                    <div class="avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="meta"><div class="name">{{ auth()->user()->name }}</div><div class="role">{{ auth()->user()->role }}</div></div>
                </div>
                <form method="POST" action="{{ route('logout') }}">@csrf <button class="btn secondary small">{{ $icon('logout') }} Logout</button></form>
            </div>
        </header>
        <main>
            @if (session('success'))<div class="alert success">{{ session('success') }}</div>@endif
            @yield('content')
        </main>
    </div>
</div>
<div class="overlay" data-nav-close></div>
<button type="button" class="to-top" id="toTop" aria-label="Back to top" title="Back to top">{{ $icon('up') }}</button>
<script>
    (function () {
        var body = document.body;
        var close = function () { body.classList.remove('nav-open'); };
        document.querySelector('[data-nav-toggle]').addEventListener('click', function () { body.classList.add('nav-open'); });
        document.querySelectorAll('[data-nav-close]').forEach(function (el) { el.addEventListener('click', close); });
        document.querySelectorAll('.menu a').forEach(function (el) { el.addEventListener('click', close); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
        window.addEventListener('resize', function () { if (window.innerWidth > 860) close(); });

        var top = document.getElementById('toTop');
        var toggleTop = function () { top.classList.toggle('show', window.scrollY > 300); };
        window.addEventListener('scroll', toggleTop, { passive: true });
        toggleTop();
        top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    })();
</script>
@else
<div class="guest">
    <div style="width:100%;max-width:420px">
        @if (session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @yield('content')
    </div>
</div>
@endauth
</body>
</html>
