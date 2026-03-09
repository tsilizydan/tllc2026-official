<?php
/**
 * TSILIZY LLC — Admin Client Show View
 */

Core\View::layout('admin', ['page_title' => ($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? '')]);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/clients" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= Core\View::e($client['first_name'] . ' ' . $client['last_name']) ?></h2>
    </div>
    <a href="<?= SITE_URL ?>/admin/clients/<?= $client['id'] ?>/modifier" class="px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-edit mr-2"></i> Modifier</a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Client Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-16 h-16 bg-gold-500/20 rounded-full flex items-center justify-center">
                    <span class="text-gold-500 font-bold text-2xl"><?= strtoupper(substr($client['first_name'], 0, 1)) ?></span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white"><?= Core\View::e($client['first_name'] . ' ' . $client['last_name']) ?></h3>
                    <?php if ($client['company']): ?>
                    <p class="text-dark-400"><?= Core\View::e($client['company']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center text-sm">
                    <i class="fas fa-envelope text-dark-500 w-5"></i>
                    <span class="text-dark-300 ml-3"><?= Core\View::e($client['email']) ?></span>
                </div>
                <?php if ($client['phone']): ?>
                <div class="flex items-center text-sm">
                    <i class="fas fa-phone text-dark-500 w-5"></i>
                    <span class="text-dark-300 ml-3"><?= Core\View::e($client['phone']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($client['address']): ?>
                <div class="flex items-start text-sm">
                    <i class="fas fa-map-marker-alt text-dark-500 w-5 mt-1"></i>
                    <span class="text-dark-300 ml-3"><?= Core\View::e($client['address']) ?><br><?= Core\View::e($client['postal_code'] . ' ' . $client['city']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($client['vat_number']): ?>
                <div class="flex items-center text-sm">
                    <i class="fas fa-file-alt text-dark-500 w-5"></i>
                    <span class="text-dark-300 ml-3">TVA: <?= Core\View::e($client['vat_number']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($client['notes']): ?>
            <div class="mt-6 pt-4 border-t border-dark-700">
                <p class="text-xs text-dark-500 uppercase mb-2">Notes</p>
                <p class="text-dark-400 text-sm"><?= nl2br(Core\View::e($client['notes'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-white mb-4">Actions rapides</h3>
            <div class="space-y-2">
                <a href="<?= SITE_URL ?>/admin/factures/creer?client=<?= $client['id'] ?>" class="flex items-center px-4 py-3 bg-dark-700 rounded-lg hover:bg-dark-600 transition-colors">
                    <i class="fas fa-file-invoice-dollar text-gold-500 w-5"></i>
                    <span class="ml-3 text-sm">Créer une facture</span>
                </a>
                <a href="<?= SITE_URL ?>/admin/contrats/creer?client=<?= $client['id'] ?>" class="flex items-center px-4 py-3 bg-dark-700 rounded-lg hover:bg-dark-600 transition-colors">
                    <i class="fas fa-file-contract text-blue-400 w-5"></i>
                    <span class="ml-3 text-sm">Créer un contrat</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Invoices & Contracts -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Invoices -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Factures récentes</h3>
                <a href="<?= SITE_URL ?>/admin/factures?client=<?= $client['id'] ?>" class="text-sm text-gold-500 hover:text-gold-400">Voir tout</a>
            </div>
            <?php if (!empty($invoices)): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-dark-500">
                            <th class="pb-3">Référence</th>
                            <th class="pb-3">Total</th>
                            <th class="pb-3">Statut</th>
                            <th class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-700">
                        <?php foreach ($invoices as $inv): ?>
                        <tr class="hover:bg-dark-700/50">
                            <td class="py-3"><a href="<?= SITE_URL ?>/admin/factures/<?= $inv['id'] ?>" class="text-gold-500 font-mono"><?= $inv['reference'] ?></a></td>
                            <td class="py-3 text-white"><?= Core\View::currency($inv['total']) ?></td>
                            <td class="py-3"><span class="px-2 py-0.5 text-xs rounded-full <?= $inv['status'] === 'paid' ? 'bg-green-500/20 text-green-400' : ($inv['status'] === 'overdue' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') ?>"><?= ucfirst($inv['status']) ?></span></td>
                            <td class="py-3 text-dark-400"><?= Core\View::date($inv['created_at']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-dark-400 text-center py-4">Aucune facture</p>
            <?php endif; ?>
        </div>
        
        <!-- Contracts -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Contrats</h3>
            </div>
            <?php if (!empty($contracts)): ?>
            <div class="space-y-3">
                <?php foreach ($contracts as $contract): ?>
                <a href="<?= SITE_URL ?>/admin/contrats/<?= $contract['id'] ?>" class="block p-4 bg-dark-700 rounded-lg hover:bg-dark-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-white font-medium"><?= Core\View::e($contract['title']) ?></span>
                        <span class="text-gold-500"><?= $contract['value'] ? Core\View::currency($contract['value']) : '-' ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-dark-400 text-center py-4">Aucun contrat</p>
            <?php endif; ?>
        </div>
    </div>
</div>
