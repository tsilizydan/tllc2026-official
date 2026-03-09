<?php
/**
 * TSILIZY LLC — Admin Settings Page
 */

Core\View::layout('admin', ['page_title' => 'Paramètres']);
$s = $settings ?? [];
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-white">Paramètres</h2>
    <p class="text-dark-400 text-sm mt-1">Configurez votre site web</p>
</div>

<!-- Tabs -->
<div x-data="{ activeTab: window.location.hash.slice(1) || 'general' }">
    <div class="flex flex-wrap border-b border-dark-700 mb-6">
        <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'border-gold-500 text-gold-500' : 'border-transparent text-dark-400 hover:text-white'" class="px-6 py-3 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-cog mr-2"></i> Général
        </button>
        <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'border-gold-500 text-gold-500' : 'border-transparent text-dark-400 hover:text-white'" class="px-6 py-3 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-search mr-2"></i> SEO
        </button>
        <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'border-gold-500 text-gold-500' : 'border-transparent text-dark-400 hover:text-white'" class="px-6 py-3 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-envelope mr-2"></i> Email
        </button>
        <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'border-gold-500 text-gold-500' : 'border-transparent text-dark-400 hover:text-white'" class="px-6 py-3 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-share-alt mr-2"></i> Réseaux sociaux
        </button>
    </div>
    
    <!-- General Settings -->
    <div x-show="activeTab === 'general'" x-cloak>
        <form action="<?= SITE_URL ?>/admin/parametres" method="POST">
            <?= Core\CSRF::field() ?>
            <input type="hidden" name="group" value="general">
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Paramètres généraux</h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Nom du site</label>
                        <input type="text" name="site_name" value="<?= Core\View::e($s['general']['site_name'] ?? SITE_NAME) ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Slogan</label>
                        <input type="text" name="site_tagline" value="<?= Core\View::e($s['general']['site_tagline'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Email de contact</label>
                        <input type="email" name="site_email" value="<?= Core\View::e($s['general']['site_email'] ?? SITE_EMAIL) ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Téléphone</label>
                        <input type="text" name="site_phone" value="<?= Core\View::e($s['general']['site_phone'] ?? SITE_PHONE) ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Adresse</label>
                    <textarea name="site_address" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 resize-none"><?= Core\View::e($s['general']['site_address'] ?? SITE_ADDRESS) ?></textarea>
                </div>
                
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" <?= ($s['general']['maintenance_mode'] ?? false) ? 'checked' : '' ?> class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-gold-500 focus:ring-gold-500">
                    <label for="maintenance_mode" class="text-sm text-dark-300">Mode maintenance</label>
                </div>
                
                <div class="pt-6 border-t border-dark-600">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- SEO Settings -->
    <div x-show="activeTab === 'seo'" x-cloak>
        <form action="<?= SITE_URL ?>/admin/parametres" method="POST">
            <?= Core\CSRF::field() ?>
            <input type="hidden" name="group" value="seo">
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Référencement (SEO)</h3>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Titre par défaut</label>
                    <input type="text" name="meta_title" value="<?= Core\View::e($s['seo']['meta_title'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description par défaut</label>
                    <textarea name="meta_description" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 resize-none"><?= Core\View::e($s['seo']['meta_description'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Google Analytics ID</label>
                    <input type="text" name="google_analytics" value="<?= Core\View::e($s['seo']['google_analytics'] ?? '') ?>" placeholder="G-XXXXXXXXXX" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                </div>
                
                <div class="pt-6 border-t border-dark-600">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Email Settings -->
    <div x-show="activeTab === 'email'" x-cloak>
        <form action="<?= SITE_URL ?>/admin/parametres" method="POST">
            <?= Core\CSRF::field() ?>
            <input type="hidden" name="group" value="email">
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Configuration Email (SMTP)</h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Serveur SMTP</label>
                        <input type="text" name="smtp_host" value="<?= Core\View::e($s['email']['smtp_host'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Port</label>
                        <input type="number" name="smtp_port" value="<?= Core\View::e($s['email']['smtp_port'] ?? 587) ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Nom d'utilisateur</label>
                        <input type="text" name="smtp_username" value="<?= Core\View::e($s['email']['smtp_username'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Mot de passe</label>
                        <input type="password" name="smtp_password" value="<?= Core\View::e($s['email']['smtp_password'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Nom de l'expéditeur</label>
                        <input type="text" name="mail_from_name" value="<?= Core\View::e($s['email']['mail_from_name'] ?? SITE_NAME) ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Email de l'expéditeur</label>
                        <input type="email" name="mail_from_address" value="<?= Core\View::e($s['email']['mail_from_address'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                </div>
                
                <div class="pt-6 border-t border-dark-600">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Social Settings -->
    <div x-show="activeTab === 'social'" x-cloak>
        <form action="<?= SITE_URL ?>/admin/parametres" method="POST">
            <?= Core\CSRF::field() ?>
            <input type="hidden" name="group" value="social">
            
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Réseaux sociaux</h3>
                
                <div class="space-y-4">
                    <?php 
                    $socials = [
                        ['key' => 'facebook_url', 'icon' => 'fab fa-facebook', 'label' => 'Facebook'],
                        ['key' => 'twitter_url', 'icon' => 'fab fa-twitter', 'label' => 'Twitter'],
                        ['key' => 'linkedin_url', 'icon' => 'fab fa-linkedin', 'label' => 'LinkedIn'],
                        ['key' => 'instagram_url', 'icon' => 'fab fa-instagram', 'label' => 'Instagram'],
                        ['key' => 'youtube_url', 'icon' => 'fab fa-youtube', 'label' => 'YouTube']
                    ];
                    foreach ($socials as $social):
                    ?>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-dark-700 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="<?= $social['icon'] ?> text-dark-400"></i>
                        </div>
                        <input type="url" name="<?= $social['key'] ?>" value="<?= Core\View::e($s['social'][$social['key']] ?? '') ?>" placeholder="<?= $social['label'] ?> URL" class="flex-1 px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="pt-6 border-t border-dark-600">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
