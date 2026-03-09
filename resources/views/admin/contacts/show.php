<?php
/**
 * TSILIZY LLC — Admin View Contact Message
 */

Core\View::layout('admin', ['page_title' => 'Message de ' . $contact['name']]);
?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/contacts" class="text-dark-400 hover:text-white text-sm mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux messages
        </a>
        <h2 class="text-2xl font-bold text-white"><?= Core\View::e($contact['subject']) ?></h2>
    </div>
    <div class="flex items-center space-x-2">
        <?php if ($contact['status'] !== 'replied'): ?>
        <form action="<?= SITE_URL ?>/admin/contacts/<?= $contact['id'] ?>/repondre" method="POST" class="inline">
            <?= Core\CSRF::field() ?>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-reply mr-2"></i> Marquer comme répondu
            </button>
        </form>
        <?php endif; ?>
        <form action="<?= SITE_URL ?>/admin/contacts/<?= $contact['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ce message ?')">
            <?= Core\CSRF::field() ?>
            <button type="submit" class="px-4 py-2 border border-red-500 text-red-500 font-medium rounded-lg hover:bg-red-500/10 transition-colors">
                <i class="fas fa-trash mr-2"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Message -->
    <div class="lg:col-span-2">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="prose prose-invert max-w-none">
                <?= nl2br(Core\View::e($contact['message'])) ?>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Sender Info -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Expéditeur</h3>
            
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <span class="text-2xl text-blue-400 font-bold"><?= strtoupper(substr($contact['name'], 0, 1)) ?></span>
                </div>
                <div>
                    <p class="text-white font-semibold"><?= Core\View::e($contact['name']) ?></p>
                    <p class="text-dark-400 text-sm"><?= Core\View::e($contact['email']) ?></p>
                </div>
            </div>
            
            <?php if ($contact['phone']): ?>
            <div class="flex items-center space-x-3 py-2 border-t border-dark-700">
                <i class="fas fa-phone text-dark-500 w-4"></i>
                <span class="text-dark-300"><?= Core\View::e($contact['phone']) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="pt-4 space-y-2">
                <a href="mailto:<?= Core\View::e($contact['email']) ?>" class="flex items-center justify-center w-full px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                    <i class="fas fa-envelope mr-2"></i> Envoyer un email
                </a>
            </div>
        </div>
        
        <!-- Details -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Détails</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-dark-400">Statut</span>
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
                    <span class="px-2 py-1 text-xs font-medium rounded-full <?= $statusColors[$contact['status']] ?? '' ?>">
                        <?= $statusLabels[$contact['status']] ?? $contact['status'] ?>
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-dark-400">Reçu le</span>
                    <span class="text-white"><?= Core\View::datetime($contact['created_at']) ?></span>
                </div>
                
                <?php if ($contact['replied_at']): ?>
                <div class="flex justify-between">
                    <span class="text-dark-400">Répondu le</span>
                    <span class="text-white"><?= Core\View::datetime($contact['replied_at']) ?></span>
                </div>
                <?php endif; ?>
                
                <div class="flex justify-between">
                    <span class="text-dark-400">Adresse IP</span>
                    <span class="text-white text-sm"><?= Core\View::e($contact['ip_address'] ?? 'N/A') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
