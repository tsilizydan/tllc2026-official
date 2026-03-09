<?php
/**
 * TSILIZY LLC — Admin Invoice View/Print
 */

Core\View::layout('admin', ['page_title' => 'Facture ' . $invoice['reference']]);
$items = json_decode($invoice['items'] ?? '[]', true) ?: [];
?>

<!-- Print Header -->
<div class="print-header">
    <h1><?= SITE_NAME ?></h1>
    <p>Facture N° <?= $invoice['reference'] ?></p>
</div>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 no-print">
    <div>
        <a href="<?= SITE_URL ?>/admin/factures" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $invoice['reference'] ?></h2>
    </div>
    <div class="flex flex-wrap gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600"><i class="fas fa-print mr-2"></i> Imprimer</button>
        <?php if ($invoice['status'] !== 'paid'): ?>
        <form action="<?= SITE_URL ?>/admin/factures/<?= $invoice['id'] ?>/payer" method="POST" class="inline">
            <?= Core\CSRF::field() ?>
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-400"><i class="fas fa-check mr-2"></i> Marquer payée</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <!-- Invoice Document -->
    <div class="bg-white text-dark-950 rounded-xl p-8 shadow-xl print:shadow-none print:p-0">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8 border-b border-gray-200 pb-6">
            <div>
                <div class="w-16 h-16 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-dark-950 font-bold text-2xl">T</span>
                </div>
                <h1 class="text-2xl font-bold text-dark-950"><?= SITE_NAME ?></h1>
                <p class="text-gray-500 text-sm mt-1">Agence Digitale</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-dark-950">FACTURE</p>
                <p class="text-gold-600 font-mono text-lg mt-1"><?= $invoice['reference'] ?></p>
                <p class="mt-4 text-gray-600">
                    <span class="block">Émise le: <?= Core\View::date($invoice['issue_date'] ?? $invoice['created_at']) ?></span>
                    <?php if ($invoice['due_date']): ?>
                    <span class="block">Échéance: <?= Core\View::date($invoice['due_date']) ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <!-- Client Info -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Facturer à</p>
                <p class="font-semibold text-lg"><?= Core\View::e($invoice['client_name']) ?></p>
                <?php if ($invoice['client_email']): ?>
                <p class="text-gray-600"><?= Core\View::e($invoice['client_email']) ?></p>
                <?php endif; ?>
                <?php if (!empty($invoice['client_address'])): ?>
                <p class="text-gray-600 whitespace-pre-line"><?= Core\View::e($invoice['client_address']) ?></p>
                <?php endif; ?>
                <?php if (!empty($invoice['client_vat'])): ?>
                <p class="text-gray-500 text-sm mt-2">TVA: <?= Core\View::e($invoice['client_vat']) ?></p>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Statut</p>
                <span class="inline-flex px-4 py-2 text-sm rounded-full font-semibold <?php 
                    echo match($invoice['status']) {
                        'paid' => 'bg-green-100 text-green-700',
                        'sent' => 'bg-blue-100 text-blue-700',
                        default => 'bg-yellow-100 text-yellow-700'
                    };
                ?>">
                    <?php echo match($invoice['status']) { 'paid' => 'PAYÉE', 'sent' => 'ENVOYÉE', default => 'EN ATTENTE' }; ?>
                </span>
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                    <th class="py-3 text-center text-xs font-semibold text-gray-500 uppercase w-20">Qté</th>
                    <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase w-32">Prix Unit.</th>
                    <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase w-32">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($items as $item): ?>
                <tr>
                    <td class="py-4"><?= Core\View::e($item['description'] ?? '') ?></td>
                    <td class="py-4 text-center"><?= $item['quantity'] ?? 1 ?></td>
                    <td class="py-4 text-right"><?= Core\View::currency($item['unit_price'] ?? 0) ?></td>
                    <td class="py-4 text-right font-medium"><?= Core\View::currency(($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0)) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-64">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Sous-total</span>
                    <span><?= Core\View::currency($invoice['subtotal'] ?? 0) ?></span>
                </div>
                <?php if (($invoice['tax_rate'] ?? 0) > 0): ?>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">TVA (<?= $invoice['tax_rate'] ?>%)</span>
                    <span><?= Core\View::currency($invoice['tax_amount'] ?? 0) ?></span>
                </div>
                <?php endif; ?>
                <?php if (($invoice['discount'] ?? 0) > 0): ?>
                <div class="flex justify-between py-2 border-b border-gray-100 text-green-600">
                    <span>Remise</span>
                    <span>-<?= Core\View::currency($invoice['discount']) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between py-3 text-lg font-bold">
                    <span>Total</span>
                    <span class="text-gold-600"><?= Core\View::currency($invoice['total']) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <?php if (!empty($invoice['notes'])): ?>
        <div class="bg-gray-50 rounded-lg p-4 mb-8">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Notes</p>
            <p class="text-gray-700 text-sm"><?= nl2br(Core\View::e($invoice['notes'])) ?></p>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="text-center text-gray-500 text-xs border-t border-gray-200 pt-6">
            <p><?= SITE_NAME ?> — Agence Digitale</p>
            <p class="mt-1">Merci pour votre confiance !</p>
        </div>
    </div>
</div>
