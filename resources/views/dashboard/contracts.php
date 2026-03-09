<?php
/**
 * TSILIZY LLC — User Contracts View
 */

Core\View::layout('dashboard', ['page_title' => 'Mes Contrats']);
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Mes Contrats</h1>
    <p class="text-dark-400 mt-1">Consultez et téléchargez vos contrats</p>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <?php if (!empty($contracts)): ?>
    <div class="divide-y divide-dark-700">
        <?php foreach ($contracts as $contract): ?>
        <div class="p-6 hover:bg-dark-700/50 transition-colors">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-contract text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-medium"><?= Core\View::e($contract['title']) ?></h3>
                        <span class="text-dark-500 text-sm"><?= Core\View::e($contract['reference']) ?></span>
                    </div>
                </div>
                <span class="px-2 py-1 text-xs rounded-full <?php
                    echo match($contract['status']) {
                        'active' => 'bg-green-500/20 text-green-400',
                        'pending' => 'bg-yellow-500/20 text-yellow-400',
                        'expired' => 'bg-dark-600 text-dark-400',
                        'terminated' => 'bg-red-500/20 text-red-400',
                        default => 'bg-dark-600 text-dark-300'
                    };
                ?>">
                    <?php
                    echo match($contract['status']) {
                        'active' => 'Actif',
                        'pending' => 'En attente',
                        'expired' => 'Expiré',
                        'terminated' => 'Résilié',
                        default => $contract['status']
                    };
                    ?>
                </span>
            </div>
            
            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                <div>
                    <span class="text-dark-500">Date de début</span>
                    <p class="text-white"><?= Core\View::date($contract['start_date']) ?></p>
                </div>
                <div>
                    <span class="text-dark-500">Date de fin</span>
                    <p class="text-white"><?= $contract['end_date'] ? Core\View::date($contract['end_date']) : 'Indéterminée' ?></p>
                </div>
                <div class="text-right">
                    <a href="<?= SITE_URL ?>/mon-compte/contrats/<?= $contract['id'] ?>/pdf" target="_blank" class="inline-flex items-center text-gold-500 hover:text-gold-400 transition-colors">
                        <i class="fas fa-download mr-2"></i> Télécharger
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <i class="fas fa-file-contract text-5xl text-dark-600 mb-4"></i>
        <p class="text-dark-400">Aucun contrat pour le moment</p>
    </div>
    <?php endif; ?>
</div>
