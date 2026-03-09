<?php
/**
 * TSILIZY LLC — 404 Error Page
 */

Core\View::layout('public', ['page_title' => 'Page non trouvée']);
?>

<section class="min-h-screen flex items-center justify-center py-24 px-4">
    <div class="text-center max-w-lg">
        <div class="text-9xl font-serif font-bold text-gold-gradient mb-4">404</div>
        <h1 class="text-3xl font-serif font-bold text-white mb-4">Page non trouvée</h1>
        <p class="text-dark-400 mb-8">
            Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= SITE_URL ?>" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                <i class="fas fa-home mr-2"></i> Retour à l'accueil
            </a>
            <a href="<?= SITE_URL ?>/contact" class="px-6 py-3 border border-dark-600 text-white font-medium rounded-lg hover:border-gold-500 hover:text-gold-500 transition-all duration-300">
                <i class="fas fa-envelope mr-2"></i> Nous contacter
            </a>
        </div>
    </div>
</section>
