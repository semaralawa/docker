<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kontak</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Kontak</h2>
            <a href="<?php echo site_url('contacts/create'); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kontak
            </a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Foto</th>
                                <th width="20%">Nama</th>
                                <th width="25%">Email</th>
                                <th width="15%">Nomor Telepon</th>
                                <th width="15%">Tanggal Dibuat</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($contacts)): ?>
                                <?php $no = 1; foreach ($contacts as $contact): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <?php if ($contact->foto): ?>
                                                <img src="<?php echo base_url('uploads/' . $contact->foto); ?>" 
                                                     alt="<?php echo $contact->nama; ?>" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($contact->nama); ?></td>
                                        <td><?php echo htmlspecialchars($contact->email); ?></td>
                                        <td><?php echo htmlspecialchars($contact->nomor_telepon); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($contact->created_at)); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo site_url('contacts/edit/' . $contact->id); ?>" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?php echo site_url('contacts/delete/' . $contact->id); ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus kontak ini?')"
                                                   title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">Belum ada data kontak</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
