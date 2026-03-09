<?php
/**
 * TSILIZY LLC — Contact Page
 */

Core\View::layout('public', [
    'page_title' => 'Contact',
    'seo_title' => $seo_title ?? 'Contactez-nous | ' . SITE_NAME,
    'seo_description' => $seo_description ?? ''
]);

$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-b from-dark-900 to-dark-950">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23C9A227\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
            Contact
        </span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
            Parlons de votre <span class="text-gold-gradient">projet</span>
        </h1>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">
            Notre équipe est à votre disposition pour répondre à toutes vos questions et vous accompagner dans vos projets.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Address -->
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6">
                    <div class="w-14 h-14 bg-gold-500/20 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-gold-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Adresse</h3>
                    <p class="text-dark-400"><?= SITE_ADDRESS ?></p>
                </div>
                
                <!-- Phone -->
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6">
                    <div class="w-14 h-14 bg-gold-500/20 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-phone text-2xl text-gold-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Téléphone</h3>
                    <a href="tel:<?= SITE_PHONE ?>" class="text-dark-400 hover:text-gold-500 transition-colors"><?= SITE_PHONE ?></a>
                </div>
                
                <!-- Email -->
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6">
                    <div class="w-14 h-14 bg-gold-500/20 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-envelope text-2xl text-gold-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Email</h3>
                    <a href="mailto:<?= SITE_EMAIL ?>" class="text-dark-400 hover:text-gold-500 transition-colors"><?= SITE_EMAIL ?></a>
                </div>
                
                <!-- Social -->
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Suivez-nous</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-8">
                    <h2 class="text-2xl font-serif font-bold text-white mb-6">Envoyez-nous un message</h2>
                    
                    <form action="<?= SITE_URL ?>/contact" method="POST" class="space-y-6">
                        <?= Core\CSRF::field() ?>
                        
                        <!-- Honeypot -->
                        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-dark-300 mb-2">
                                    Nom complet <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="name" name="name" required
                                       value="<?= Core\View::e(Core\Session::old('name')) ?>"
                                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['name']) ? 'border-red-500' : '' ?>"
                                       placeholder="Jean Dupont">
                                <?php if (isset($errors['name'])): ?>
                                <p class="text-red-400 text-xs mt-1"><?= $errors['name'] ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-dark-300 mb-2">
                                    Email <span class="text-red-400">*</span>
                                </label>
                                <input type="email" id="email" name="email" required
                                       value="<?= Core\View::e(Core\Session::old('email')) ?>"
                                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['email']) ? 'border-red-500' : '' ?>"
                                       placeholder="vous@exemple.com">
                                <?php if (isset($errors['email'])): ?>
                                <p class="text-red-400 text-xs mt-1"><?= $errors['email'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-dark-300 mb-2">
                                    Téléphone
                                </label>
                                <input type="tel" id="phone" name="phone"
                                       value="<?= Core\View::e(Core\Session::old('phone')) ?>"
                                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                                       placeholder="+33 6 12 34 56 78">
                            </div>
                            
                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-dark-300 mb-2">
                                    Sujet <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="subject" name="subject" required
                                       value="<?= Core\View::e(Core\Session::old('subject')) ?>"
                                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['subject']) ? 'border-red-500' : '' ?>"
                                       placeholder="Demande de devis">
                                <?php if (isset($errors['subject'])): ?>
                                <p class="text-red-400 text-xs mt-1"><?= $errors['subject'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-dark-300 mb-2">
                                Message <span class="text-red-400">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors resize-none <?= isset($errors['message']) ? 'border-red-500' : '' ?>"
                                      placeholder="Décrivez votre projet..."><?= Core\View::e(Core\Session::old('message')) ?></textarea>
                            <?php if (isset($errors['message'])): ?>
                            <p class="text-red-400 text-xs mt-1"><?= $errors['message'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Privacy -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="privacy" name="privacy" required
                                   class="w-4 h-4 mt-1 rounded border-dark-600 bg-dark-800 text-gold-500 focus:ring-gold-500 focus:ring-offset-0">
                            <label for="privacy" class="text-sm text-dark-400">
                                J'accepte la <a href="<?= SITE_URL ?>/page/politique-confidentialite" class="text-gold-500 hover:text-gold-400">politique de confidentialité</a> et le traitement de mes données.
                            </label>
                        </div>
                        
                        <!-- Submit -->
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                            <i class="fas fa-paper-plane mr-2"></i> Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Map Section (Placeholder) -->
<section class="py-16 bg-dark-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden h-96 flex items-center justify-center">
            <div class="text-center text-dark-500">
                <i class="fas fa-map-marked-alt text-5xl mb-4"></i>
                <p>Carte interactive</p>
                <p class="text-sm">(Google Maps à intégrer)</p>
            </div>
        </div>
    </div>
</section>
