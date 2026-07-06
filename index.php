<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Informasi Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<main class="auth-page">
    <div class="container">
        <div class="row align-items-center justify-content-center g-4">
            <div class="col-lg-5 auth-side">
                 
                <h1 class="display-6 fw-bold">Masuk ke Ruang Petugas</h1>
                <p class="lead mb-0">Kelola buku, anggota, dan peminjaman perpustakaan.</p>
            </div>

            <div class="col-md-8 col-lg-5">
                <div class="auth-card">
                    <h2 class="h4 section-title mb-1">Login Akun</h2>
                    <p class="text-muted mb-4">Gunakan akun admin atau petugas.</p>

                    <form action="#" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="required">*</span></label>
                            <input type="text" id="username" name="username" class="form-control" required minlength="4">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="required">*</span></label>
                            <input type="password" id="password" name="password" class="form-control" required minlength="6">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="ingat" name="ingat">
                            <label class="form-check-label" for="ingat">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>

                    <p class="text-center mt-4 mb-0">
                        Belum punya akun? <a href="register.php">Daftar petugas</a>
                    </p>
                    <p class="text-center mt-2 mb-0">
                         
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>