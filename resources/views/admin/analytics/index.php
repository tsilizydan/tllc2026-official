<?php
/**
 * TSILIZY LLC — Admin Analytics Index View
 */

Core\View::layout('admin', ['page_title' => 'Analytiques']);
?>

<!-- Print Header -->
<div class="print-header">
    <h1><?= SITE_NAME ?> — Rapport d'Analytiques</h1>
    <p>Période: <?= Core\View::date($date_from) ?> au <?= Core\View::date($date_to) ?></p>
    <p>Généré le <?= date('d/m/Y H:i') ?></p>
</div>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 no-print">
    <h2 class="text-2xl font-bold text-white">Analytiques</h2>
    
    <div class="flex flex-wrap items-center gap-3">
        <!-- Period Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="?period=7days" class="px-3 py-1.5 rounded-lg text-sm <?= $period === '7days' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">7 jours</a>
            <a href="?period=30days" class="px-3 py-1.5 rounded-lg text-sm <?= $period === '30days' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">30 jours</a>
            <a href="?period=90days" class="px-3 py-1.5 rounded-lg text-sm <?= $period === '90days' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">90 jours</a>
            <a href="?period=year" class="px-3 py-1.5 rounded-lg text-sm <?= $period === 'year' ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>">1 an</a>
        </div>
        
        <!-- Custom Date Range -->
        <form action="" method="GET" class="flex items-center gap-2">
            <input type="date" name="start_date" value="<?= $date_from ?>" class="px-3 py-1.5 bg-dark-800 border border-dark-600 rounded-lg text-sm text-white">
            <span class="text-dark-500">—</span>
            <input type="date" name="end_date" value="<?= $date_to ?>" class="px-3 py-1.5 bg-dark-800 border border-dark-600 rounded-lg text-sm text-white">
            <button type="submit" class="px-3 py-1.5 bg-gold-500 text-dark-950 rounded-lg text-sm font-medium">Filtrer</button>
        </form>
        
        <!-- Actions -->
        <div class="flex gap-2">
            <a href="<?= SITE_URL ?>/admin/analytiques/exporter?start_date=<?= $date_from ?>&end_date=<?= $date_to ?>" class="px-3 py-1.5 bg-dark-700 text-dark-300 rounded-lg text-sm hover:bg-dark-600"><i class="fas fa-download mr-1"></i> CSV</a>
            <button onclick="window.print()" class="px-3 py-1.5 bg-dark-700 text-dark-300 rounded-lg text-sm hover:bg-dark-600"><i class="fas fa-print mr-1"></i> Imprimer</button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">Pages vues</p>
        <p class="text-2xl font-bold text-white"><?= Core\View::number($total_views) ?></p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">Visiteurs uniques</p>
        <p class="text-2xl font-bold text-white"><?= Core\View::number($unique_visitors) ?></p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">Aujourd'hui</p>
        <p class="text-2xl font-bold text-gold-500"><?= Core\View::number($today_views) ?></p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">Taux de rebond</p>
        <p class="text-2xl font-bold text-white"><?= $bounce_rate ?>%</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <!-- Traffic Chart -->
    <div class="lg:col-span-2 bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Trafic</h3>
        <canvas id="trafficChart" height="200"></canvas>
    </div>
    
    <!-- Devices -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Appareils</h3>
        <canvas id="devicesChart" height="200"></canvas>
        <div class="space-y-2 mt-4">
            <div class="flex justify-between text-sm"><span class="text-dark-400">Desktop</span><span class="text-white"><?= Core\View::number($devices['desktop'] ?? 0) ?></span></div>
            <div class="flex justify-between text-sm"><span class="text-dark-400">Mobile</span><span class="text-white"><?= Core\View::number($devices['mobile'] ?? 0) ?></span></div>
            <div class="flex justify-between text-sm"><span class="text-dark-400">Tablet</span><span class="text-white"><?= Core\View::number($devices['tablet'] ?? 0) ?></span></div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Top Pages -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Pages les plus visitées</h3>
        <?php if (!empty($top_pages)): ?>
        <div class="space-y-3">
            <?php foreach ($top_pages as $page): ?>
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-white text-sm truncate"><?= Core\View::e($page['page_title'] ?: $page['page_url']) ?></p>
                    <p class="text-dark-500 text-xs truncate"><?= Core\View::e($page['page_url']) ?></p>
                </div>
                <span class="text-gold-500 font-medium ml-4"><?= Core\View::number($page['views']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-dark-500 text-center py-4">Aucune donnée</p>
        <?php endif; ?>
    </div>
    
    <!-- Traffic Sources -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Sources de trafic</h3>
        <?php if (!empty($sources)): ?>
        <div class="space-y-3">
            <?php foreach ($sources as $source): ?>
            <div class="flex items-center justify-between">
                <span class="text-white"><?= Core\View::e($source['source']) ?></span>
                <span class="text-gold-500 font-medium"><?= Core\View::number($source['visits']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-dark-500 text-center py-4">Aucune donnée</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Traffic Chart
const trafficData = <?= json_encode($page_views) ?>;
new Chart(document.getElementById('trafficChart'), {
    type: 'line',
    data: {
        labels: trafficData.map(d => d.date),
        datasets: [{
            label: 'Pages vues',
            data: trafficData.map(d => d.views),
            borderColor: '#C9A227',
            backgroundColor: 'rgba(201, 162, 39, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: '#374151' }, ticks: { color: '#9CA3AF' } },
            y: { grid: { color: '#374151' }, ticks: { color: '#9CA3AF' } }
        }
    }
});

// Devices Chart
new Chart(document.getElementById('devicesChart'), {
    type: 'doughnut',
    data: {
        labels: ['Desktop', 'Mobile', 'Tablet'],
        datasets: [{
            data: [<?= $devices['desktop'] ?? 0 ?>, <?= $devices['mobile'] ?? 0 ?>, <?= $devices['tablet'] ?? 0 ?>],
            backgroundColor: ['#C9A227', '#10B981', '#3B82F6']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { color: '#9CA3AF' } } }
    }
});
</script>
