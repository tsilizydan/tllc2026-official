<?php
/**
 * TSILIZY LLC — Admin Invoice Form (Complete)
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouvelle facture' : 'Modifier la facture']);
$inv = $invoice ?? [];
$items = json_decode($inv['items'] ?? '[]', true) ?: [];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/factures" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouvelle facture' : 'Modifier la facture' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/factures' : SITE_URL . '/admin/factures/' . ($inv['id'] ?? '') ?>" method="POST" id="invoiceForm">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Selection or Manual Entry -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Informations client</h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Client existant</label>
                        <select name="user_id" id="clientSelect" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                            <option value="">-- Sélectionner ou saisir manuellement --</option>
                            <?php foreach ($users ?? [] as $user): ?>
                            <option value="<?= $user['id'] ?>" 
                                    data-name="<?= Core\View::e(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>"
                                    data-email="<?= Core\View::e($user['email'] ?? '') ?>"
                                    <?= ($inv['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                <?= Core\View::e(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?> (<?= $user['email'] ?? '' ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Nom du client <span class="text-red-400">*</span></label>
                        <input type="text" name="client_name" id="clientName" required value="<?= Core\View::e($inv['client_name'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Email</label>
                        <input type="email" name="client_email" id="clientEmail" value="<?= Core\View::e($inv['client_email'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">N° TVA</label>
                        <input type="text" name="client_vat" value="<?= Core\View::e($inv['client_vat'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Adresse</label>
                    <textarea name="client_address" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($inv['client_address'] ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Invoice Items -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Articles</h3>
                    <button type="button" onclick="addItem()" class="text-gold-500 hover:text-gold-400 text-sm"><i class="fas fa-plus mr-1"></i> Ajouter</button>
                </div>
                
                <div id="itemsContainer" class="space-y-3">
                    <?php if (empty($items)): ?>
                    <div class="item-row grid grid-cols-12 gap-2 items-end" data-index="0">
                        <div class="col-span-5">
                            <label class="block text-xs text-dark-400 mb-1">Description</label>
                            <input type="text" name="items[0][description]" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm" placeholder="Description...">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-dark-400 mb-1">Qté</label>
                            <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm item-qty" onchange="calculateTotals()">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-dark-400 mb-1">Prix unit.</label>
                            <input type="number" name="items[0][unit_price]" step="0.01" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm item-price" onchange="calculateTotals()">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-dark-400 mb-1">Total</label>
                            <input type="text" class="w-full px-3 py-2 bg-dark-700 border border-dark-600 rounded-lg text-gold-500 text-sm item-total" readonly>
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="removeItem(this)" class="w-full py-2 text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <?php else: ?>
                    <?php foreach ($items as $i => $item): ?>
                    <div class="item-row grid grid-cols-12 gap-2 items-end" data-index="<?= $i ?>">
                        <div class="col-span-5">
                            <label class="block text-xs text-dark-400 mb-1">Description</label>
                            <input type="text" name="items[<?= $i ?>][description]" value="<?= Core\View::e($item['description'] ?? '') ?>" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-dark-400 mb-1">Qté</label>
                            <input type="number" name="items[<?= $i ?>][quantity]" value="<?= $item['quantity'] ?? 1 ?>" min="1" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm item-qty" onchange="calculateTotals()">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-dark-400 mb-1">Prix unit.</label>
                            <input type="number" name="items[<?= $i ?>][unit_price]" step="0.01" value="<?= $item['unit_price'] ?? 0 ?>" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm item-price" onchange="calculateTotals()">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-dark-400 mb-1">Total</label>
                            <input type="text" class="w-full px-3 py-2 bg-dark-700 border border-dark-600 rounded-lg text-gold-500 text-sm item-total" readonly>
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="removeItem(this)" class="w-full py-2 text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Totals -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Montants</h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Sous-total (€)</label>
                        <input type="number" name="subtotal" id="subtotal" step="0.01" readonly value="<?= $inv['subtotal'] ?? 0 ?>" class="w-full px-4 py-3 bg-dark-700 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">TVA (%)</label>
                        <input type="number" name="tax_rate" id="taxRate" step="0.01" value="<?= $inv['tax_rate'] ?? 20 ?>" onchange="calculateTotals()" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Remise (€)</label>
                        <input type="number" name="discount" id="discount" step="0.01" value="<?= $inv['discount'] ?? 0 ?>" onchange="calculateTotals()" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Total (€)</label>
                        <input type="number" name="total" id="total" step="0.01" readonly value="<?= $inv['total'] ?? 0 ?>" class="w-full px-4 py-3 bg-gold-500/20 border border-gold-500/50 rounded-lg text-gold-500 font-bold">
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <label class="block text-sm font-medium text-dark-300 mb-2">Notes / Conditions</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($inv['notes'] ?? '') ?></textarea>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                        <option value="draft" <?= ($inv['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="sent" <?= ($inv['status'] ?? '') === 'sent' ? 'selected' : '' ?>>Envoyée</option>
                        <option value="paid" <?= ($inv['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Payée</option>
                        <option value="partial" <?= ($inv['status'] ?? '') === 'partial' ? 'selected' : '' ?>>Paiement partiel</option>
                        <option value="overdue" <?= ($inv['status'] ?? '') === 'overdue' ? 'selected' : '' ?>>En retard</option>
                        <option value="cancelled" <?= ($inv['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Date d'émission</label>
                        <input type="date" name="issue_date" required value="<?= !empty($inv['issue_date']) ? date('Y-m-d', strtotime($inv['issue_date'])) : date('Y-m-d') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Échéance</label>
                        <input type="date" name="due_date" required value="<?= !empty($inv['due_date']) ? date('Y-m-d', strtotime($inv['due_date'])) : date('Y-m-d', strtotime('+30 days')) ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white">
                    </div>
                </div>
                
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg hover:bg-gold-400 transition-colors"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
            
            <?php if ($mode === 'edit' && !empty($inv['reference'])): ?>
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Référence</h3>
                <p class="font-mono text-gold-500 text-xl"><?= Core\View::e($inv['reference']) ?></p>
                <p class="text-dark-400 text-sm mt-2">Créée le <?= Core\View::date($inv['created_at'] ?? '') ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
let itemIndex = <?= count($items) ?: 1 ?>;

document.getElementById('clientSelect')?.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (option.value) {
        document.getElementById('clientName').value = option.dataset.name || '';
        document.getElementById('clientEmail').value = option.dataset.email || '';
    }
});

function addItem() {
    const container = document.getElementById('itemsContainer');
    const html = `<div class="item-row grid grid-cols-12 gap-2 items-end" data-index="${itemIndex}">
        <div class="col-span-5"><label class="block text-xs text-dark-400 mb-1">Description</label><input type="text" name="items[${itemIndex}][description]" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm" placeholder="Description..."></div>
        <div class="col-span-2"><label class="block text-xs text-dark-400 mb-1">Qté</label><input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm item-qty" onchange="calculateTotals()"></div>
        <div class="col-span-2"><label class="block text-xs text-dark-400 mb-1">Prix unit.</label><input type="number" name="items[${itemIndex}][unit_price]" step="0.01" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-white text-sm item-price" onchange="calculateTotals()"></div>
        <div class="col-span-2"><label class="block text-xs text-dark-400 mb-1">Total</label><input type="text" class="w-full px-3 py-2 bg-dark-700 border border-dark-600 rounded-lg text-gold-500 text-sm item-total" readonly></div>
        <div class="col-span-1"><button type="button" onclick="removeItem(this)" class="w-full py-2 text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button></div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
        calculateTotals();
    }
}

function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
        const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
        const total = qty * price;
        row.querySelector('.item-total').value = total.toFixed(2) + ' €';
        subtotal += total;
    });
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    
    const taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const taxAmount = subtotal * (taxRate / 100);
    const total = subtotal + taxAmount - discount;
    
    document.getElementById('total').value = total.toFixed(2);
}

// Calculate on load
calculateTotals();
</script>
