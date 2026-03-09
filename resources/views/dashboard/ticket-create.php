<?php
/**
 * TSILIZY LLC — Create Ticket Form
 */

Core\View::layout('dashboard', ['page_title' => 'Nouveau Ticket']);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
?>

<div class="mb-6">
    <a href="<?= SITE_URL ?>/mon-compte/tickets" class="text-dark-400 hover:text-white text-sm inline-block mb-2">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux tickets
    </a>
    <h1 class="text-2xl font-bold text-white">Nouveau Ticket</h1>
    <p class="text-dark-400 mt-1">Décrivez votre problème ou question</p>
</div>

<form action="<?= SITE_URL ?>/mon-compte/tickets" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="subject" class="block text-sm font-medium text-dark-300 mb-2">
                    Sujet <span class="text-red-400">*</span>
                </label>
                <input type="text" id="subject" name="subject" required
                       value="<?= Core\View::e(Core\Session::old('subject')) ?>"
                       class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 <?= isset($errors['subject']) ? 'border-red-500' : '' ?>"
                       placeholder="Résumé de votre demande">
                <?php if (isset($errors['subject'])): ?>
                <p class="text-red-400 text-xs mt-1"><?= $errors['subject'] ?></p>
                <?php endif; ?>
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-dark-300 mb-2">
                    Catégorie
                </label>
                <select id="category" name="category" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    <option value="general">Question générale</option>
                    <option value="technical">Support technique</option>
                    <option value="billing">Facturation</option>
                    <option value="other">Autre</option>
                </select>
            </div>
        </div>
        
        <div>
            <label for="message" class="block text-sm font-medium text-dark-300 mb-2">
                Message <span class="text-red-400">*</span>
            </label>
            <textarea id="message" name="message" rows="8" required
                      class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 resize-none <?= isset($errors['message']) ? 'border-red-500' : '' ?>"
                      placeholder="Décrivez votre demande en détail..."><?= Core\View::e(Core\Session::old('message')) ?></textarea>
            <?php if (isset($errors['message'])): ?>
            <p class="text-red-400 text-xs mt-1"><?= $errors['message'] ?></p>
            <?php endif; ?>
            <p class="text-dark-500 text-xs mt-1">Minimum 20 caractères</p>
        </div>
        
        <div class="pt-6 border-t border-dark-600">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-paper-plane mr-2"></i> Envoyer le ticket
            </button>
        </div>
    </div>
</form>
