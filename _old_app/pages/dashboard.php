<?php

$page_title = "Dashboard";
$current_page = "dashboard";
$stats = getStats();
$berkas = getAllBerkas($_GET['filter'] ?? null);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Retensi Rekam Medis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-item:hover { transform: translateX(4px); }
        .card-stat { transition: all 0.3s ease; }
        .card-stat:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
        .table-row:hover { background: rgba(59, 130, 246, 0.05); }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide { animation: slideIn 0.3s ease; }
        .modal-backdrop { backdrop-filter: blur(4px); }
    </style>
</head>
<body class="bg-slate-100 h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white flex-shrink-0 flex flex-col">
            <div class="p-6 border-b border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-lg">Rekam Medis</span>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
                <a href="?page=dashboard" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl <?= !isset($_GET['filter']) ? 'bg-blue-600 text-white' : 'hover:bg-slate-700 text-slate-300 hover:text-white' ?> transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="?page=dashboard&filter=Aktif" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl <?= isset($_GET['filter']) && $_GET['filter'] == 'Aktif' ? 'bg-blue-600 text-white' : 'hover:bg-slate-700 text-slate-300 hover:text-white' ?> transition-all">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Data Aktif</span>
                </a>
                
                <a href="?page=dashboard&filter=Inaktif" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl <?= isset($_GET['filter']) && $_GET['filter'] == 'Inaktif' ? 'bg-blue-600 text-white' : 'hover:bg-slate-700 text-slate-300 hover:text-white' ?> transition-all">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Data Inaktif</span>
                </a>
                
                <a href="?page=dashboard&filter=Musnah" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl <?= isset($_GET['filter']) && $_GET['filter'] == 'Musnah' ? 'bg-blue-600 text-white' : 'hover:bg-slate-700 text-slate-300 hover:text-white' ?> transition-all">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="font-medium">Data Musnah</span>
                </a>
                
                <div class="pt-4 border-t border-slate-700 mt-4">
                    <a href="?page=form" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white transition-all hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="font-medium">Tambah Data</span>
                    </a>
                </div>
            </nav>
            
            <div class="p-4 border-t border-slate-700">
                <div class="text-sm text-slate-400 mb-3">
                    <?= $_SESSION['nama_lengkap'] ?? 'Petugas' ?> 
                    <span class="block text-xs"><?= $_SESSION['role'] ?? 'petugas' ?></span>
                </div>
                <a href="?page=logout" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-500/20 text-slate-300 hover:text-red-400 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="font-medium">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <div class="p-8">
                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            <?= isset($_GET['filter']) ? 'Data ' . $_GET['filter'] : 'Dashboard' ?>
                        </h1>
                        <p class="text-gray-500 mt-1">
                            Selamat datang, <?= $_SESSION['nama_lengkap'] ?? 'Petugas' ?>
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-white px-4 py-2 rounded-xl shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-600 font-medium"><?= date('l, d F Y') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards (hanya tampil di dashboard utama) -->
                <?php if (!isset($_GET['filter'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <a href="?page=dashboard&filter=Aktif" class="card-stat bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Total Aktif</p>
                                <p class="text-4xl font-bold mt-2"><?= $stats['Aktif'] ?? 0 ?></p>
                            </div>
                            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    
                    <a href="?page=dashboard&filter=Inaktif" class="card-stat bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-100 text-sm font-medium">Total Inaktif</p>
                                <p class="text-4xl font-bold mt-2"><?= $stats['Inaktif'] ?? 0 ?></p>
                            </div>
                            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    
                    <a href="?page=dashboard&filter=Musnah" class="card-stat bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl p-6 text-white shadow-lg cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-medium">Total Musnah</p>
                                <p class="text-4xl font-bold mt-2"><?= $stats['Musnah'] ?? 0 ?></p>
                            </div>
                            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <!-- Data Table -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <h2 class="text-xl font-bold text-gray-800">Data Rekam Medis</h2>
                            <?php if (isset($_GET['filter'])): ?>
                                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-medium">
                                    Filter: <?= $_GET['filter'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="?page=dashboard" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-medium transition-all">
                                Semua Data
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No RM</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Lahir</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Retensi</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (empty($berkas)): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="font-medium">Belum ada data</p>
                                            <p class="text-sm mt-1">Klik "Tambah Data" untuk menambahkan rekam medis</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($berkas as $row): ?>
                                    <tr class="table-row transition-all">
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= $no++ ?></td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-800"><?= htmlspecialchars($row['no_rm']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= $row['tgl_lahir'] ? date('d/m/Y', strtotime($row['tgl_lahir'])) : '-' ?></td>
                                        <td class="px-6 py-4">
                                            <?php
                                            $statusColors = [
                                                'Aktif' => 'bg-green-100 text-green-700',
                                                'Inaktif' => 'bg-amber-100 text-amber-700',
                                                'Musnah' => 'bg-red-100 text-red-700'
                                            ];
                                            $color = $statusColors[$row['status']] ?? 'bg-gray-100 text-gray-700';
                                            ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $color ?>">
                                                <?= $row['status'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <?= $row['tgl_retensi'] ? date('d/m/Y', strtotime($row['tgl_retensi'])) : '-' ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($row['file_pdf']): ?>
                                                <a href="uploads/berkas/<?= $row['file_pdf'] ?>" target="_blank" 
                                                   class="flex items-center gap-2 px-3 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002
                                                        2zM12 3v6h6"/>
                                                    </svg>
                                                    Lihat File
                                                </a>
                                            <?php else: ?>
                                                <span class="text-sm text-gray-400">Tidak ada file</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="?page=form&id=<?= $row['id'] ?>"   
                                               class="text-blue-600 hover:text-blue-800 font-medium text-sm mr-4">
                                                Edit
                                            </a>
                                            <a href="?page=delete&id=<?= $row['id'] ?>" 
                                               class="text-red-600 hover:text-red-800 font-medium text-sm"
                                               onclick="return confirm('Yakin ingin menghapus data ini?');">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>