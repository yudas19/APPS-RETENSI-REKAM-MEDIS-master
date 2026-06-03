<?php 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = getBerkas($id);

if (!$data) {
    header('Location: index.php?page=dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rekam Medis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">Detail Rekam Medis</h3>
                <a href="?page=dashboard" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-lg text-gray-800"><?= htmlspecialchars($data['nama_pasien']) ?></p>
                        <p class="text-gray-500">No RM: <?= htmlspecialchars($data['no_rm']) ?></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Lahir</p>
                        <p class="font-semibold text-gray-800">
                            <?= $data['tgl_lahir'] ? date('d/m/Y', strtotime($data['tgl_lahir'])) : '-' ?>
                        </p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Status</p>
                        <?php
                        $statusColors = [
                            'Aktif' => 'bg-green-100 text-green-700',
                            'Inaktif' => 'bg-amber-100 text-amber-700',
                            'Musnah' => 'bg-red-100 text-red-700'
                        ];
                        $color = $statusColors[$data['status']] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $color ?>">
                            <?= $data['status'] ?>
                        </span>
                    </div>
                </div>
                
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Retensi</p>
                    <p class="font-medium text-gray-800">
                        <?= $data['tgl_retensi'] ? date('d/m/Y', strtotime($data['tgl_retensi'])) : 'Belum ditentukan' ?>
                    </p>
                </div>
                
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                    <p class="font-medium text-gray-800">
                        <?= htmlspecialchars($data['keterangan'] ?? '-') ?>
                    </p>
                </div>
                
                <?php if ($data['file_pdf']): ?>
                <div class="p-3 bg-red-50 rounded-xl">
                    <p class="text-xs text-red-500 mb-2">File Dokumen</p>
                    <a href="uploads/berkas/<?= $data['file_pdf'] ?>" target="_blank" 
                       class="flex items-center gap-2 text-red-600 font-medium hover:underline">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <?= $data['file_pdf'] ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="flex gap-4 pt-4">
                    <a href="?page=dashboard" 
                       class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-all text-center">
                        Kembali
                    </a>
                    <a href="?page=form&id=<?= $data['id'] ?>" 
                       class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg transition-all text-center">
                        Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>