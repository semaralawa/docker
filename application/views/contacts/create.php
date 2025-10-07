<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Aplikasi Kontak</span>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Kontak Baru</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php echo form_open_multipart('contacts/store'); ?>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control"
                                id="nama"
                                name="nama"
                                value="<?php echo set_value('nama'); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="<?php echo set_value('email'); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control"
                                id="nomor_telepon"
                                name="nomor_telepon"
                                value="<?php echo set_value('nomor_telepon'); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file"
                                class="form-control"
                                id="foto"
                                name="foto"
                                accept="image/*">
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo site_url('contacts'); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>