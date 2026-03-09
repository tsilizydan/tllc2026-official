<?php
/**
 * TSILIZY LLC — Admin Dashboard View
 */

Core\View::layout('admin', ['page_title' => 'Tableau de bord']);
?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Users -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Utilisateurs</p>
                <p class="text-3xl font-bold text-white mt-1"><?= number_format($stats['users'] ?? 0) ?></p>
                <p class="text-green-400 text-xs mt-1">
                    <i class="fas fa-arrow-up"></i> +<?= $stats['new_users'] ?? 0 ?> ce mois
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-2xl text-blue-400"></i>
            </div>
        </div>
    </div>
    
    <!-- Page Views -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Vues ce mois</p>
                <p class="text-3xl font-bold text-white mt-1"><?= number_format($stats['page_views'] ?? 0) ?></p>
                <p class="text-dark-500 text-xs mt-1">
                    <?= number_format($stats['unique_visitors'] ?? 0) ?> visiteurs uniques
                </p>
            </div>
            <div class="w-14 h-14 bg-green-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-2xl text-green-400"></i>
            </div>
        </div>
    </div>
    
    <!-- Messages -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Nouveaux messages</p>
                <p class="text-3xl font-bold text-white mt-1"><?= number_format($stats['new_contacts'] ?? 0) ?></p>
                <p class="text-orange-400 text-xs mt-1">
                    <?= $stats['open_tickets'] ?? 0 ?> tickets ouverts
                </p>
            </div>
            <div class="w-14 h-14 bg-orange-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-envelope text-2xl text-orange-400"></i>
            </div>
        </div>
    </div>
    
    <!-- Revenue -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Chiffre d'affaires</p>
                <p class="text-3xl font-bold text-white mt-1"><?= Core\View::currency($stats['revenue'] ?? 0) ?></p>
                <p class="text-gold-500 text-xs mt-1">
                    Factures payées
                </p>
            </div>
            <div class="w-14 h-14 bg-gold-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-euro-sign text-2xl text-gold-400"></i>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-dark-800/50 border border-dark-700 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-white"><?= $stats['services'] ?? 0 ?></p>
        <p class="text-dark-400 text-xs mt-1">Services</p>
    </div>
    <div class="bg-dark-800/50 border border-dark-700 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-white"><?= $stats['products'] ?? 0 ?></p>
        <p class="text-dark-400 text-xs mt-1">Produits</p>
    </div>
    <div class="bg-dark-800/50 border border-dark-700 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-white"><?= $stats['pages'] ?? 0 ?></p>
        <p class="text-dark-400 text-xs mt-1">Pages</p>
    </div>
    <div class="bg-dark-800/50 border border-dark-700 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-white"><?= $stats['subscribers'] ?? 0 ?></p>
        <p class="text-dark-400 text-xs mt-1">Abonnés</p>
    </div>
    <div class="bg-dark-800/50 border border-dark-700 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-gold-500"><?= $stats['pending_reviews'] ?? 0 ?></p>
        <p class="text-dark-400 text-xs mt-1">Avis en attente</p>
    </div>
    <div class="bg-dark-800/50 border border-dark-700 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-gold-500"><?= $stats['pending_applications'] ?? 0 ?></p>
        <p class="text-dark-400 text-xs mt-1">Candidatures</p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid lg:grid-cols-3 gap-6">
    
    <!-- Left Column - 2/3 width -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Traffic Chart -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Trafic des 30 derniers jours</h3>
                <a href="<?= SITE_URL ?>/admin/analytiques" class="text-sm text-gold-500 hover:text-gold-400">
                    Voir plus <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <canvas id="trafficChart" height="120"></canvas>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trafficData = <?= json_encode($traffic_data ?? []) ?>;
            new Chart(document.getElementById('trafficChart'), {
                type: 'line',
                data: {
                    labels: trafficData.map(d => d.date),
                    datasets: [{
                        label: 'Vues',
                        data: trafficData.map(d => d.views),
                        borderColor: '#C9A227',
                        backgroundColor: 'rgba(201, 162, 39, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: '#334155' }, ticks: { color: '#94A3B8', maxTicksLimit: 7 } },
                        y: { grid: { color: '#334155' }, ticks: { color: '#94A3B8' }, beginAtZero: true }
                    }
                }
            });
        });
        </script>
        
        <!-- Recent Contacts -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Messages récents</h3>
                <a href="<?= SITE_URL ?>/admin/contacts" class="text-sm text-gold-500 hover:text-gold-400">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <?php if (!empty($recent_contacts)): ?>
            <div class="space-y-4">
                <?php foreach ($recent_contacts as $contact): ?>
                <div class="flex items-start justify-between p-4 bg-dark-900/50 rounded-lg hover:bg-dark-700/50 transition-colors">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-400 font-semibold"><?= strtoupper(substr($contact['name'], 0, 1)) ?></span>
                        </div>
                        <div>
                            <h4 class="text-white font-medium"><?= Core\View::e($contact['name']) ?></h4>
                            <p class="text-dark-400 text-sm truncate max-w-md"><?= Core\View::truncate(Core\View::e($contact['message']), 80) ?></p>
                            <p class="text-dark-500 text-xs mt-1"><?= Core\View::datetime($contact['created_at']) ?></p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full <?= $contact['status'] === 'new' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-300' ?>">
                        <?= $contact['status'] === 'new' ? 'Nouveau' : 'Lu' ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-dark-400 text-center py-8">Aucun message récent</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Right Column - 1/3 width -->
    <div class="space-y-6">
        
        <!-- Quick Actions -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Actions rapides</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="<?= SITE_URL ?>/admin/pages/creer" class="flex flex-col items-center p-4 bg-dark-900/50 rounded-lg hover:bg-dark-700/50 transition-colors text-center">
                    <i class="fas fa-file-alt text-xl text-blue-400 mb-2"></i>
                    <span class="text-sm text-dark-300">Nouvelle page</span>
                </a>
                <a href="<?= SITE_URL ?>/admin/services/creer" class="flex flex-col items-center p-4 bg-dark-900/50 rounded-lg hover:bg-dark-700/50 transition-colors text-center">
                    <i class="fas fa-concierge-bell text-xl text-green-400 mb-2"></i>
                    <span class="text-sm text-dark-300">Nouveau service</span>
                </a>
                <a href="<?= SITE_URL ?>/admin/annonces/creer" class="flex flex-col items-center p-4 bg-dark-900/50 rounded-lg hover:bg-dark-700/50 transition-colors text-center">
                    <i class="fas fa-bullhorn text-xl text-orange-400 mb-2"></i>
                    <span class="text-sm text-dark-300">Annonce</span>
                </a>
                <a href="<?= SITE_URL ?>/admin/factures/creer" class="flex flex-col items-center p-4 bg-dark-900/50 rounded-lg hover:bg-dark-700/50 transition-colors text-center">
                    <i class="fas fa-file-invoice-dollar text-xl text-gold-400 mb-2"></i>
                    <span class="text-sm text-dark-300">Facture</span>
                </a>
            </div>
        </div>
        
        <!-- Pending Reviews -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Avis en attente</h3>
                <a href="<?= SITE_URL ?>/admin/avis" class="text-sm text-gold-500 hover:text-gold-400">
                    Voir tout
                </a>
            </div>
            
            <?php if (!empty($pending_reviews)): ?>
            <div class="space-y-3">
                <?php foreach ($pending_reviews as $review): ?>
                <div class="p-3 bg-dark-900/50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-white"><?= Core\View::e($review['user_name'] ?? 'Anonyme') ?></span>
                        <div class="flex space-x-1">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                            <i class="fas fa-star text-xs <?= $i < $review['rating'] ? 'text-gold-500' : 'text-dark-600' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="text-dark-400 text-xs truncate"><?= Core\View::e($review['comment']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-dark-400 text-center py-4 text-sm">Aucun avis en attente</p>
            <?php endif; ?>
        </div>
        
        <!-- Open Tickets -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Tickets ouverts</h3>
                <a href="<?= SITE_URL ?>/admin/tickets" class="text-sm text-gold-500 hover:text-gold-400">
                    Voir tout
                </a>
            </div>
            
            <?php if (!empty($open_tickets)): ?>
            <div class="space-y-3">
                <?php foreach ($open_tickets as $ticket): ?>
                <div class="p-3 bg-dark-900/50 rounded-lg">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-white font-medium">#<?= $ticket['reference'] ?></span>
                        <span class="px-2 py-0.5 text-xs rounded-full <?php
                            echo match($ticket['priority']) {
                                'urgent' => 'bg-red-500/20 text-red-400',
                                'high' => 'bg-orange-500/20 text-orange-400',
                                'medium' => 'bg-yellow-500/20 text-yellow-400',
                                default => 'bg-dark-600 text-dark-300'
                            };
                        ?>">
                            <?= ucfirst($ticket['priority']) ?>
                        </span>
                    </div>
                    <p class="text-dark-400 text-xs truncate"><?= Core\View::e($ticket['subject']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-dark-400 text-center py-4 text-sm">Aucun ticket ouvert</p>
            <?php endif; ?>
        </div>
        
    </div>
</div>
