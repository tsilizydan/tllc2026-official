<?php
/**
 * TSILIZY LLC — Admin Activity Logs View
 */

Core\View::layout('admin', ['page_title' => 'Journaux d\'activité']);
?>

<!-- Print Header -->
<div class="print-header">
    <h1><?= SITE_NAME ?> — Journaux d'Activité</h1>
    <p>Généré le <?= date('d/m/Y H:i') ?></p>
</div>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 no-print">
    <h2 class="text-2xl font-bold text-white">Journaux d'activité</h2>
    
    <div class="flex flex-wrap gap-2">
        <a href="<?= SITE_URL ?>/admin/journaux/exporter?<?= http_build_query($filters) ?>" class="px-3 py-1.5 bg-dark-700 text-dark-300 rounded-lg text-sm hover:bg-dark-600"><i class="fas fa-download mr-1"></i> Exporter CSV</a>
        <button onclick="window.print()" class="px-3 py-1.5 bg-dark-700 text-dark-300 rounded-lg text-sm hover:bg-dark-600"><i class="fas fa-print mr-1"></i> Imprimer</button>
        <form action="<?= SITE_URL ?>/admin/journaux/nettoyer" method="POST" onsubmit="return confirm('Supprimer les journaux de plus de 30 jours ?')">
            <?= Core\CSRF::field() ?>
            <button type="submit" class="px-3 py-1.5 bg-red-500/20 text-red-400 rounded-lg text-sm hover:bg-red-500/30"><i class="fas fa-trash mr-1"></i> Nettoyer</button>
        </form>
    </div>
</div>

<!-- Filters -->
<form action="" method="GET" class="bg-dark-800 border border-dark-700 rounded-xl p-4 mb-6 no-print">
    <div class="grid md:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs text-dark-400 mb-1">Utilisateur</label>
            <select name="user_id" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-sm text-white">
                <option value="">Tous</option>
                <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>" <?= $filters['user_id'] == $u['id'] ? 'selected' : '' ?>><?= Core\View::e($u['first_name'] . ' ' . $u['last_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs text-dark-400 mb-1">Action</label>
            <select name="action" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-sm text-white">
                <option value="">Toutes</option>
                <?php foreach ($actions as $a): ?>
                <option value="<?= Core\View::e($a) ?>" <?= $filters['action'] === $a ? 'selected' : '' ?>><?= Core\View::e(ucfirst(str_replace('_', ' ', $a))) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs text-dark-400 mb-1">Du</label>
            <input type="date" name="start_date" value="<?= $filters['start_date'] ?>" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-sm text-white">
        </div>
        <div>
            <label class="block text-xs text-dark-400 mb-1">Au</label>
            <input type="date" name="end_date" value="<?= $filters['end_date'] ?>" class="w-full px-3 py-2 bg-dark-900 border border-dark-600 rounded-lg text-sm text-white">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-gold-500 text-dark-950 font-medium rounded-lg">Filtrer</button>
            <a href="<?= SITE_URL ?>/admin/journaux" class="px-4 py-2 bg-dark-700 text-dark-300 rounded-lg hover:bg-dark-600"><i class="fas fa-times"></i></a>
        </div>
    </div>
</form>

<?php if (!empty($logs)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead class="bg-dark-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-dark-400 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-dark-400 uppercase">Utilisateur</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-dark-400 uppercase">Action</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-dark-400 uppercase">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-dark-400 uppercase hidden lg:table-cell">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                <?php foreach ($logs as $log): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-4 py-3 text-sm text-dark-300 whitespace-nowrap"><?= Core\View::datetime($log['created_at']) ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gold-500/20 rounded-full flex items-center justify-center">
                                <span class="text-gold-500 text-xs font-semibold"><?= strtoupper(substr($log['user_name'] ?? 'S', 0, 1)) ?></span>
                            </div>
                            <span class="text-sm text-white"><?= Core\View::e($log['user_name'] ?? 'Système') ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full <?php
                            if (strpos($log['action'], 'create') !== false || strpos($log['action'], 'add') !== false) echo 'bg-green-500/20 text-green-400';
                            elseif (strpos($log['action'], 'update') !== false || strpos($log['action'], 'edit') !== false) echo 'bg-blue-500/20 text-blue-400';
                            elseif (strpos($log['action'], 'delete') !== false || strpos($log['action'], 'remove') !== false) echo 'bg-red-500/20 text-red-400';
                            elseif (strpos($log['action'], 'login') !== false) echo 'bg-gold-500/20 text-gold-500';
                            else echo 'bg-dark-600 text-dark-300';
                        ?>"><?= Core\View::e(ucfirst(str_replace('_', ' ', $log['action']))) ?></span>
                    </td>
                    <td class="px-4 py-3 text-sm text-dark-400 max-w-xs truncate"><?= Core\View::e($log['description']) ?></td>
                    <td class="px-4 py-3 text-sm text-dark-500 font-mono hidden lg:table-cell"><?= Core\View::e($log['ip_address']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($last_page > 1): ?>
<div class="flex justify-center mt-6 no-print">
    <nav class="flex flex-wrap justify-center gap-1">
        <?php 
        $queryParams = $filters;
        for ($i = 1; $i <= min($last_page, 10); $i++): 
            $queryParams['page'] = $i;
        ?>
        <a href="?<?= http_build_query($queryParams) ?>" class="px-3 py-2 rounded <?= $i === $current_page ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-400 hover:bg-dark-700' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($last_page > 10): ?>
        <span class="px-3 py-2 text-dark-500">...</span>
        <?php endif; ?>
    </nav>
</div>
<?php endif; ?>

<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-history text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun journal trouvé.</p>
</div>
<?php endif; ?>

<p class="text-dark-500 text-sm mt-4 text-center"><?= Core\View::number($total) ?> entrée<?= $total > 1 ? 's' : '' ?> au total</p>
