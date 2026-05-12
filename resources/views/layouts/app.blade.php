<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ระบบจัดการครุภัณฑ์และวัสดุ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        .btn { border-radius: 0.375rem; }
        .card { border-radius: 0.5rem; border: 1px solid #e5e7eb; }
        .form-control, .form-select { border-radius: 0.375rem; }
    </style>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
        }
        .navbar-brand { font-weight: 600; }
        .sidebar { min-height: calc(100vh - 56px); }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
    </style>
    @stack('styles')
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-box-seam me-2"></i>ระบบจัดการครุภัณฑ์
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">แดชบอร์ด</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('assets.index') }}">ครุภัณฑ์</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('locations.index') }}">สถานที่</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('inspections.index') }}">การตรวจสอบ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('transfers.index') }}">การโยกย้าย</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('loans.index') }}">ยืม-คืน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('repairs.index') }}">การซ่อม</a>
                        </li>
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('manual') }}">
                                <i class="bi bi-question-circle me-1"></i>คู่มือ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('scan') }}">
                                <i class="bi bi-qr-code-scan me-1"></i>สแกน QR
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">เข้าสู่ระบบ</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('manual') }}">คู่มือการใช้งาน</a></li>
                                @if(auth()->user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}">ผู้ใช้งาน</a></li>
                                    <li><a class="dropdown-item" href="{{ route('audit-logs.index') }}">บันทึกการใช้งาน</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('reports.index') }}">รายงาน</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">ออกจากระบบ</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="py-4 mt-auto border-top bg-white">
        <div class="container text-center text-muted">
            <p class="mb-0 small">พัฒนาโดยน้าอ๋อง ที่นั่งห้องเซิพ</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        // Refresh session every 5 minutes to prevent CSRF timeout (Error 419)
        setInterval(function() {
            fetch('/ping').catch(() => {});
        }, 5 * 60 * 1000);
    </script>
</body>
</html>
