<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Mon Compte' ?> — <?= SITE_NAME ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { 500: '#C9A227', 600: '#B89020', 700: '#A07E1B' },
                        dark: { 50: '#F8FAFC', 100: '#F1F5F9', 200: '#E2E8F0', 300: '#CBD5E1', 400: '#94A3B8', 500: '#64748B', 600: '#475569', 700: '#334155', 800: '#1E293B', 900: '#0F172A', 950: '#020617' }
                    }
                }
            }
        }
    </script>
    
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-dark-950 text-white font-sans antialiased" x-data="{ mobileMenuOpen: false }">
    
    <!-- Header -->
    <header class="bg-dark-900 border-b border-dark-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="<?= SITE_URL ?>" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center">
                    <span class="text-dark-950 font-bold">T</span>
                </div>
                <span class="text-lg font-semibold text-gold-500"><?= SITE_NAME ?></span>
            </a>
            
            <nav class="hidden md:flex items-center space-x-6">
                <a href="<?= SITE_URL ?>/mon-compte" class="text-dark-400 hover:text-white">Tableau de bord</a>
                <a href="<?= SITE_URL ?>/mon-compte/profil" class="text-dark-400 hover:text-white">Profil</a>
                <a href="<?= SITE_URL ?>/mon-compte/factures" class="text-dark-400 hover:text-white">Factures</a>
                <a href="<?= SITE_URL ?>/mon-compte/messages" class="text-dark-400 hover:text-white">Messages</a>
            </nav>
            
            <div class="flex items-center space-x-4">
                <span class="text-dark-400 hidden md:block"><?= Core\View::e(Core\Auth::user()['first_name'] ?? 'Utilisateur') ?></span>
                <form action="<?= SITE_URL ?>/deconnexion" method="POST">
                    <?= Core\CSRF::field() ?>
                    <button type="submit" class="text-dark-400 hover:text-white"><i class="fas fa-sign-out-alt"></i></button>
                </form>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-dark-400">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-dark-900 border-t border-dark-800 p-4 space-y-2">
            <a href="<?= SITE_URL ?>/mon-compte" class="block py-2 text-dark-300">Tableau de bord</a>
            <a href="<?= SITE_URL ?>/mon-compte/profil" class="block py-2 text-dark-300">Profil</a>
            <a href="<?= SITE_URL ?>/mon-compte/factures" class="block py-2 text-dark-300">Factures</a>
            <a href="<?= SITE_URL ?>/mon-compte/messages" class="block py-2 text-dark-300">Messages</a>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <?php Core\View::partial('flash-messages'); ?>
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark-900 border-t border-dark-800 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-dark-500 text-sm">
            &copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
