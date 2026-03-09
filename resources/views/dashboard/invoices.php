<?php
/**
 * TSILIZY LLC — User Invoices View
 */

Core\View::layout('dashboard', ['page_title' => 'Mes Factures']);
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Mes Factures</h1>
    <p class="text-dark-400 mt-1">Consultez et téléchargez vos factures</p>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <?php if (!empty($invoices)): ?>
    <table class="w-full">
        <thead class="bg-dark-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Référence</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Date</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Montant</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php foreach ($invoices as $invoice): ?>
            <tr class="hover:bg-dark-700/50 transition-colors">
                <td class="px-6 py-4">
                    <span class="text-white font-medium"><?= Core\View::e($invoice['reference']) ?></span>
                </td>
                <td class="px-6 py-4 text-dark-400">
                    <?= Core\View::date($invoice['created_at']) ?>
                </td>
                <td class="px-6 py-4">
                    <span class="text-gold-500 font-semibold"><?= Core\View::currency($invoice['total']) ?></span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full <?php
                        echo match($invoice['status']) {
                            'paid' => 'bg-green-500/20 text-green-400',
                            'pending', 'sent' => 'bg-yellow-500/20 text-yellow-400',
                            'overdue' => 'bg-red-500/20 text-red-400',
                            default => 'bg-dark-600 text-dark-300'
                        };
                    ?>">
                        <?php
                        echo match($invoice['status']) {
                            'paid' => 'Payée',
                            'pending' => 'En attente',
                            'sent' => 'Envoyée',
                            'overdue' => 'En retard',
                            default => $invoice['status']
                        };
                        ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="<?= SITE_URL ?>/mon-compte/factures/<?= $invoice['id'] ?>/pdf" target="_blank" class="inline-flex items-center text-sm text-dark-400 hover:text-gold-500 transition-colors">
                        <i class="fas fa-download mr-2"></i> PDF
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="text-center py-16">
        <i class="fas fa-file-invoice-dollar text-5xl text-dark-600 mb-4"></i>
        <p class="text-dark-400">Aucune facture pour le moment</p>
    </div>
    <?php endif; ?>
</div>
