<?php
/**
 * TSILIZY LLC — Admin Invoices List
 */

Core\View::layout('admin', ['page_title' => 'Factures']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Factures</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> facture(s)</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/factures/creer" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg">
        <i class="fas fa-plus mr-2"></i> Nouvelle facture
    </a>
</div>

<!-- Stats -->
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">En attente</p>
        <p class="text-2xl font-bold text-yellow-400"><?= Core\View::currency($stats['pending']) ?></p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">Payées</p>
        <p class="text-2xl font-bold text-green-400"><?= Core\View::currency($stats['paid']) ?></p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <p class="text-dark-400 text-sm">En retard</p>
        <p class="text-2xl font-bold text-red-400"><?= $stats['overdue'] ?></p>
    </div>
</div>

<!-- Filters -->
<div class="flex gap-2 mb-6">
    <a href="<?= SITE_URL ?>/admin/factures" class="px-4 py-2 rounded-lg text-sm <?= !$status ? 'bg-gold-500 text-dark-950' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Toutes</a>
    <a href="?status=pending" class="px-4 py-2 rounded-lg text-sm <?= $status === 'pending' ? 'bg-yellow-500 text-dark-950' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">En attente</a>
    <a href="?status=sent" class="px-4 py-2 rounded-lg text-sm <?= $status === 'sent' ? 'bg-blue-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Envoyées</a>
    <a href="?status=paid" class="px-4 py-2 rounded-lg text-sm <?= $status === 'paid' ? 'bg-green-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Payées</a>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Facture</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Client</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Montant</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Échéance</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php if (!empty($invoices)): ?>
                <?php foreach ($invoices as $invoice): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-6 py-4">
                        <p class="text-white font-mono"><?= $invoice['reference'] ?></p>
                        <p class="text-dark-500 text-sm"><?= Core\View::date($invoice['created_at']) ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-white"><?= Core\View::e($invoice['user_name'] ?? 'N/A') ?></p>
                        <p class="text-dark-500 text-sm"><?= Core\View::e($invoice['user_email'] ?? '') ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gold-500 font-semibold"><?= Core\View::currency($invoice['total']) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <?php 
                        $isOverdue = $invoice['due_date'] && strtotime($invoice['due_date']) < time() && $invoice['status'] !== 'paid';
                        ?>
                        <span class="<?= $isOverdue ? 'text-red-400' : 'text-dark-400' ?>">
                            <?= $invoice['due_date'] ? Core\View::date($invoice['due_date']) : '-' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo match($invoice['status']) { 'paid' => 'bg-green-500/20 text-green-400', 'sent' => 'bg-blue-500/20 text-blue-400', 'pending' => 'bg-yellow-500/20 text-yellow-400', default => 'bg-dark-600 text-dark-400' }; ?>">
                            <?php echo match($invoice['status']) { 'paid' => 'Payée', 'sent' => 'Envoyée', 'pending' => 'En attente', default => $invoice['status'] }; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= SITE_URL ?>/admin/factures/<?= $invoice['id'] ?>" class="p-2 text-dark-400 hover:text-white"><i class="fas fa-eye"></i></a>
                            <?php if ($invoice['status'] !== 'paid'): ?>
                            <form action="<?= SITE_URL ?>/admin/factures/<?= $invoice['id'] ?>/payer" method="POST" class="inline">
                                <?= Core\CSRF::field() ?>
                                <button type="submit" class="p-2 text-dark-400 hover:text-green-400" title="Marquer payée"><i class="fas fa-check"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-dark-400"><i class="fas fa-file-invoice-dollar text-4xl mb-4 block"></i>Aucune facture</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
