<?php
/**
 * TSILIZY LLC — Admin Tickets List
 */

Core\View::layout('admin', ['page_title' => 'Tickets']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Tickets de support</h2>
        <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> ticket(s)</p>
    </div>
</div>

<!-- Stats -->
<div class="grid md:grid-cols-4 gap-4 mb-6">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Ouverts</p>
                <p class="text-2xl font-bold text-green-400"><?= $stats['open'] ?></p>
            </div>
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center"><i class="fas fa-inbox text-green-400"></i></div>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">En cours</p>
                <p class="text-2xl font-bold text-blue-400"><?= $stats['in_progress'] ?></p>
            </div>
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center"><i class="fas fa-spinner text-blue-400"></i></div>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">En attente</p>
                <p class="text-2xl font-bold text-yellow-400"><?= $stats['waiting'] ?></p>
            </div>
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center"><i class="fas fa-clock text-yellow-400"></i></div>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-dark-400 text-sm">Fermés</p>
                <p class="text-2xl font-bold text-dark-400"><?= $stats['closed'] ?></p>
            </div>
            <div class="w-10 h-10 bg-dark-700 rounded-lg flex items-center justify-center"><i class="fas fa-check text-dark-400"></i></div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="flex gap-2 mb-6">
    <a href="<?= SITE_URL ?>/admin/tickets" class="px-4 py-2 rounded-lg text-sm <?= !$status ? 'bg-gold-500 text-dark-950' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Tous</a>
    <a href="?status=open" class="px-4 py-2 rounded-lg text-sm <?= $status === 'open' ? 'bg-green-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Ouverts</a>
    <a href="?status=in_progress" class="px-4 py-2 rounded-lg text-sm <?= $status === 'in_progress' ? 'bg-blue-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">En cours</a>
    <a href="?status=waiting" class="px-4 py-2 rounded-lg text-sm <?= $status === 'waiting' ? 'bg-yellow-500 text-dark-950' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">En attente</a>
    <a href="?status=closed" class="px-4 py-2 rounded-lg text-sm <?= $status === 'closed' ? 'bg-dark-600 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Fermés</a>
</div>

<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Ticket</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Client</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Priorité</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-dark-400 uppercase">Date</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $ticket): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-6 py-4">
                        <p class="text-gold-500 font-mono text-sm">#<?= $ticket['reference'] ?></p>
                        <p class="text-white font-medium truncate max-w-xs"><?= Core\View::e($ticket['subject']) ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-white"><?= Core\View::e($ticket['user_name'] ?? 'N/A') ?></p>
                        <p class="text-dark-500 text-sm"><?= Core\View::e($ticket['user_email'] ?? '') ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php
                            echo match($ticket['priority']) {
                                'high' => 'bg-red-500/20 text-red-400',
                                'medium' => 'bg-yellow-500/20 text-yellow-400',
                                default => 'bg-dark-600 text-dark-400'
                            };
                        ?>"><?= ucfirst($ticket['priority']) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php
                            echo match($ticket['status']) {
                                'open' => 'bg-green-500/20 text-green-400',
                                'in_progress' => 'bg-blue-500/20 text-blue-400',
                                'waiting' => 'bg-yellow-500/20 text-yellow-400',
                                default => 'bg-dark-600 text-dark-400'
                            };
                        ?>"><?php echo match($ticket['status']) { 'open' => 'Ouvert', 'in_progress' => 'En cours', 'waiting' => 'En attente', 'closed' => 'Fermé', default => $ticket['status'] }; ?></span>
                    </td>
                    <td class="px-6 py-4 text-dark-400 text-sm"><?= Core\View::datetime($ticket['created_at']) ?></td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= SITE_URL ?>/admin/tickets/<?= $ticket['id'] ?>" class="p-2 text-dark-400 hover:text-white"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-dark-400"><i class="fas fa-ticket-alt text-4xl mb-4 block"></i>Aucun ticket</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
