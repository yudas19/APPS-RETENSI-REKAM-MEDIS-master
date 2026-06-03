<?php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = null;
$isEdit = false;

if ($id > 0) {
    $data = getBerkas($id);
    $isEdit = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formData = [
        'no_rm' => $_POST['no_rm'],
        'nama_pasien' => $_POST['nama_pasien'],
        'tgl_lahir' => $_POST['tgl_lahir'] ?? null,
        'nama_berkas' => $_POST['nama_berkas'] ?? '',
        'status' => $_POST['status'],
        'tgl_retensi' => $_POST['tgl_retensi'] ?? null,
        'keterangan' => $_POST['keterangan'] ?? '',
        'file_pdf' => $data['file_pdf'] ?? ''
    ];
    
    // Handle file upload
    if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == 0) {
        $upload_result = uploadFile($_FILES['file_pdf']);
        if ($upload_result) {
            $formData['file_pdf'] = $upload_result;
        }
    }
    
    if ($isEdit) {
        $result = updateBerkas($id, $formData);
        $message = "Data berhasil diperbarui!";
    } else {
        $result = createBerkas($formData);
        $message = "Data berhasil ditambahkan!";
    }
    
    if ($result) {
        header("Location: index.php?page=dashboard&success=" . urlencode($message));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit' : 'Tambah' ?> Data - Retensi Rekam Medis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">
                    <?= $isEdit ? 'Edit Data Rekam Medis' : 'Tambah Data Rekam Medis' ?>
                </h3>
                <a href="?page=dashboard" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No Rekam Medis *</label>
                        <input type="text" name="no_rm" required 
                               value="<?= htmlspecialchars($data['no_rm'] ?? '') ?>"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all"
                               placeholder="Contoh: 00123">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pasien *</label>
                        <input type="text" name="nama_pasien" required 
                               value="<?= htmlspecialchars($data['nama_pasien'] ?? '') ?>"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all"
                               placeholder="Nama lengkap">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" 
                               value="<?= $data['tgl_lahir'] ?? '' ?>"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                        <select name="status" required 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all bg-white">
                            <option value="">Pilih Status</option>
                            <option value="Aktif" <?= isset($data['status']) && $data['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Inaktif" <?= isset($data['status']) && $data['status'] == 'Inaktif' ? 'selected' : '' ?>>Inaktif</option>
                            <option value="Musnah" <?= isset($data['status']) && $data['status'] == 'Musnah' ? 'selected' : '' ?>>Musnah</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Retensi</label>
                    <input type="date" name="tgl_retensi" 
                           value="<?= $data['tgl_retensi'] ?? '' ?>"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" 
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all resize-none"
                              placeholder="Masukkan keterangan (opsional)"><?= htmlspecialchars($data['keterangan'] ?? '') ?></textarea>
                </div>
                
                <!-- File Upload -->
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">Upload File PDF (Maks 5MB)</label>
                    
                    <?php if ($isEdit && !empty($data['file_pdf'])): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?= $data['file_pdf'] ?></p>
                                    <p class="text-sm text-gray-500">File tersimpan</p>
                                </div>
                            </div>
                            <label class="cursor-pointer text-sm text-blue-600 hover:text-blue-700">
                                <span>Ganti</span>
                                <input type="file" name="file_pdf" accept=".pdf" class="hidden">
                            </label>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <input type="file" id="file-input" name="file_pdf" accept=".pdf" class="hidden" onchange="updateFileName(this)">
                            <div onclick="document.getElementById('file-input').click()" class="cursor-pointer">
                                <div class="w-16 h-16 bg-blue-50 rounded-full mx-auto flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600 font-medium">Klik untuk upload file PDF</p>
                                <p class="text-gray-400 text-sm mt-1">atau drag and drop</p>
                            </div>
                            <p id="file-name" class="text-sm text-gray-600 mt-2 hidden"></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <a href="?page=dashboard" 
                       class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg transition-all">
                        <?= $isEdit ? 'Update' : 'Simpan' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function updateFileName(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024).toFixed(1) + ' KB';
                const fileNameEl = document.getElementById('file-name');
                fileNameEl.textContent = `File: ${fileName} (${fileSize})`;
                fileNameEl.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>