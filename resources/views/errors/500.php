<?php
/**
 * TSILIZY LLC — 500 Error Page
 */

Core\View::layout('public', ['page_title' => 'Erreur serveur']);
?>

<section class="min-h-screen flex items-center justify-center py-24 px-4">
    <div class="text-center max-w-lg">
        <div class="text-9xl font-serif font-bold text-gold-gradient mb-4">500</div>
        <h1 class="text-3xl font-serif font-bold text-white mb-4">Erreur serveur</h1>
        <p class="text-dark-400 mb-8">
            Une erreur inattendue s'est produite. Veuillez réessayer plus tard.
        </p>
        <a href="<?= SITE_URL ?>" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
            <i class="fas fa-home mr-2"></i> Retour à l'accueil
        </a>
    </div>
</section>
