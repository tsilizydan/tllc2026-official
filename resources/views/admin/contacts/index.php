<?php
/**
 * TSILIZY LLC — Admin Contacts List
 */

Core\View::layout('admin', ['page_title' => 'Messages']);
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Messages de contact</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> message(s) au total</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-dark-800 border border-dark-700 rounded-xl p-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="<?= SITE_URL ?>/admin/contacts" class="px-4 py-2 rounded-lg text-sm font-medium <?= !$status ? 'bg-gold-500 text-dark-950' : 'bg-dark-700 text-dark-300 hover:text-white' ?> transition-colors">
            Tous
        </a>
        <a href="<?= SITE_URL ?>/admin/contacts?status=new" class="px-4 py-2 rounded-lg text-sm font-medium <?= $status === 'new' ? 'bg-green-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?> transition-colors">
            Nouveaux
        </a>
        <a href="<?= SITE_URL ?>/admin/contacts?status=read" class="px-4 py-2 rounded-lg text-sm font-medium <?= $status === 'read' ? 'bg-blue-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?> transition-colors">
            Lus
        </a>
        <a href="<?= SITE_URL ?>/admin/contacts?status=replied" class="px-4 py-2 rounded-lg text-sm font-medium <?= $status === 'replied' ? 'bg-purple-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?> transition-colors">
            Répondus
        </a>
    </div>
</div>

<!-- Messages Table -->
<form action="<?= SITE_URL ?>/admin/contacts/bulk" method="POST" id="bulkForm">
    <?= Core\CSRF::field() ?>
    
    <div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
        <!-- Bulk Actions -->
        <div class="px-6 py-3 bg-dark-900/50 border-b border-dark-700 flex items-center gap-4">
            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-gold-500">
            <select name="action" class="px-3 py-1 bg-dark-800 border border-dark-600 rounded-lg text-sm text-white">
                <option value="">Action groupée</option>
                <option value="mark_read">Marquer comme lu</option>
                <option value="delete">Supprimer</option>
            </select>
            <button type="submit" class="px-3 py-1 bg-dark-700 text-white text-sm rounded-lg hover:bg-dark-600 transition-colors">
                Appliquer
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-dark-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left w-10"></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Expéditeur</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Sujet</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700">
                    <?php if (!empty($contacts)): ?>
                        <?php foreach ($contacts as $contact): ?>
                        <tr class="hover:bg-dark-700/50 transition-colors <?= $contact['status'] === 'new' ? 'bg-dark-800/50' : '' ?>">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="<?= $contact['id'] ?>" class="item-checkbox w-4 h-4 rounded border-dark-600 bg-dark-800 text-gold-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-blue-400 font-semibold"><?= strtoupper(substr($contact['name'], 0, 1)) ?></span>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium <?= $contact['status'] === 'new' ? 'font-semibold' : '' ?>">
                                            <?= Core\View::e($contact['name']) ?>
                                        </p>
                                        <p class="text-dark-400 text-sm"><?= Core\View::e($contact['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white truncate max-w-xs <?= $contact['status'] === 'new' ? 'font-semibold' : '' ?>">
                                    <?= Core\View::e($contact['subject']) ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $statusColors = [
                                    'new' => 'bg-green-500/20 text-green-400',
                                    'read' => 'bg-blue-500/20 text-blue-400',
                                    'replied' => 'bg-purple-500/20 text-purple-400'
                                ];
                                $statusLabels = [
                                    'new' => 'Nouveau',
                                    'read' => 'Lu',
                                    'replied' => 'Répondu'
                                ];
                                ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?= $statusColors[$contact['status']] ?? 'bg-dark-600 text-dark-300' ?>">
                                    <?= $statusLabels[$contact['status']] ?? $contact['status'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-dark-400 text-sm">
                                <?= Core\View::datetime($contact['created_at']) ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="<?= SITE_URL ?>/admin/contacts/<?= $contact['id'] ?>" class="p-2 text-dark-400 hover:text-white transition-colors" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="<?= SITE_URL ?>/admin/contacts/<?= $contact['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ce message ?')">
                                        <?= Core\CSRF::field() ?>
                                        <button type="submit" class="p-2 text-dark-400 hover:text-red-400 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-dark-400">
                                <i class="fas fa-envelope text-4xl mb-4 block"></i>
                                Aucun message
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($last_page > 1): ?>
        <div class="px-6 py-4 border-t border-dark-700 flex items-center justify-between">
            <p class="text-dark-400 text-sm">Page <?= $current_page ?> sur <?= $last_page ?></p>
            <div class="flex space-x-2">
                <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?><?= $status ? '&status=' . $status : '' ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <?php endif; ?>
                <?php if ($current_page < $last_page): ?>
                <a href="?page=<?= $current_page + 1 ?><?= $status ? '&status=' . $status : '' ?>" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</form>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>
