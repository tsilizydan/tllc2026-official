<?php
/**
 * TSILIZY LLC — Admin Agenda View (Enhanced Interactive Calendar)
 */

Core\View::layout('admin', ['page_title' => 'Agenda']);
$months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
?>

<!-- Print Header -->
<div class="print-header">
    <h1><?= SITE_NAME ?> — Agenda</h1>
    <p><?= $months[$month - 1] ?> <?= $year ?></p>
    <p>Généré le <?= date('d/m/Y H:i') ?></p>
</div>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 no-print">
    <h2 class="text-2xl font-bold text-white">Agenda</h2>
    <div class="flex flex-wrap gap-2">
        <button onclick="window.print()" class="px-3 py-1.5 bg-dark-700 text-dark-300 rounded-lg text-sm hover:bg-dark-600"><i class="fas fa-print mr-1"></i> Imprimer</button>
        <a href="<?= SITE_URL ?>/admin/agenda/creer" class="px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-plus mr-2"></i> Nouveau RDV</a>
    </div>
</div>

<div class="grid lg:grid-cols-4 gap-6">
    <!-- Sidebar: Today + Upcoming -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Today's Appointments -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-5">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <span class="w-8 h-8 bg-gold-500/20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-day text-gold-500"></i>
                </span>
                Aujourd'hui
            </h3>
            <?php if (!empty($today)): ?>
            <div class="space-y-3">
                <?php foreach ($today as $apt): ?>
                <a href="<?= SITE_URL ?>/admin/agenda/<?= $apt['id'] ?>/modifier" 
                   class="block p-3 bg-dark-700 rounded-lg hover:bg-dark-600 transition-all hover:scale-[1.02] border-l-4 border-gold-500">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-white"><?= Core\View::e($apt['title']) ?></span>
                        <span class="text-gold-500 text-sm font-mono"><?= date('H:i', strtotime($apt['start_date'])) ?></span>
                    </div>
                    <?php if (!empty($apt['client_name'])): ?>
                    <p class="text-dark-400 text-sm mt-1"><i class="fas fa-user mr-1"></i> <?= Core\View::e($apt['client_name']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($apt['location'])): ?>
                    <p class="text-dark-500 text-xs mt-1"><i class="fas fa-map-marker-alt mr-1"></i> <?= Core\View::e($apt['location']) ?></p>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-6">
                <i class="fas fa-calendar-check text-3xl text-dark-600 mb-2"></i>
                <p class="text-dark-500 text-sm">Aucun rendez-vous</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Stats -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-dark-400 uppercase mb-4">Ce mois</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-dark-400">Total RDV</span>
                    <span class="text-white font-semibold"><?= count($appointments ?? []) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-dark-400">Aujourd'hui</span>
                    <span class="text-gold-500 font-semibold"><?= count($today ?? []) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-dark-400">À venir</span>
                    <span class="text-blue-400 font-semibold"><?= count(array_filter($appointments ?? [], fn($a) => strtotime($a['start_date']) > time())) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-dark-400 uppercase mb-3">Légende</h3>
            <div class="space-y-2 text-sm">
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-gold-500 mr-2"></span> Confirmé</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> En attente</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span> Annulé</div>
            </div>
        </div>
    </div>
    
    <!-- Main Calendar -->
    <div class="lg:col-span-3">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <!-- Calendar Navigation -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="?month=<?= $month == 1 ? 12 : $month - 1 ?>&year=<?= $month == 1 ? $year - 1 : $year ?>" 
                       class="w-10 h-10 flex items-center justify-center bg-dark-700 rounded-lg text-dark-400 hover:text-white hover:bg-dark-600 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <h3 class="text-xl font-bold text-white"><?= $months[$month - 1] ?> <?= $year ?></h3>
                    <a href="?month=<?= $month == 12 ? 1 : $month + 1 ?>&year=<?= $month == 12 ? $year + 1 : $year ?>" 
                       class="w-10 h-10 flex items-center justify-center bg-dark-700 rounded-lg text-dark-400 hover:text-white hover:bg-dark-600 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <a href="?month=<?= date('n') ?>&year=<?= date('Y') ?>" class="px-4 py-2 bg-dark-700 text-dark-300 rounded-lg text-sm hover:bg-dark-600">
                    Aujourd'hui
                </a>
            </div>
            
            <!-- Weekday Headers -->
            <div class="grid grid-cols-7 gap-2 text-center text-sm mb-3">
                <?php foreach (['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $i => $dayName): ?>
                <div class="py-3 font-semibold <?= $i >= 5 ? 'text-dark-500' : 'text-dark-400' ?>"><?= $dayName ?></div>
                <?php endforeach; ?>
            </div>
            
            <?php
            $firstDay = date('N', strtotime("$year-$month-01"));
            $daysInMonth = date('t', strtotime("$year-$month-01"));
            $appointmentsByDate = [];
            foreach ($appointments as $apt) {
                $date = date('j', strtotime($apt['start_date']));
                $appointmentsByDate[$date][] = $apt;
            }
            ?>
            
            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2">
                <!-- Empty cells for days before first of month -->
                <?php for ($i = 1; $i < $firstDay; $i++): ?>
                <div class="min-h-[100px] bg-dark-900 rounded-lg opacity-50"></div>
                <?php endfor; ?>
                
                <!-- Days of the month -->
                <?php for ($d = 1; $d <= $daysInMonth; $d++): ?>
                <?php 
                $isToday = $d == date('j') && $month == date('m') && $year == date('Y');
                $hasApts = isset($appointmentsByDate[$d]);
                $dayApts = $appointmentsByDate[$d] ?? [];
                ?>
                <div class="min-h-[100px] bg-dark-700 rounded-lg p-2 <?= $isToday ? 'ring-2 ring-gold-500' : '' ?> hover:bg-dark-600 transition-colors"
                     x-data="{ showList: false }" @click="showList = !showList">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium <?= $isToday ? 'w-6 h-6 bg-gold-500 text-dark-950 rounded-full flex items-center justify-center' : 'text-dark-300' ?>">
                            <?= $d ?>
                        </span>
                        <?php if ($hasApts): ?>
                        <span class="text-xs bg-gold-500/20 text-gold-500 px-2 py-0.5 rounded-full"><?= count($dayApts) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($hasApts): ?>
                    <div class="space-y-1 mt-2">
                        <?php foreach (array_slice($dayApts, 0, 2) as $apt): ?>
                        <div class="text-xs p-1.5 rounded bg-gold-500/20 text-gold-500 truncate">
                            <?= date('H:i', strtotime($apt['start_date'])) ?> <?= Core\View::e(Core\View::truncate($apt['title'], 15)) ?>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($dayApts) > 2): ?>
                        <div class="text-xs text-dark-500">+<?= count($dayApts) - 2 ?> autres</div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Month Appointments List -->
        <?php if (!empty($appointments)): ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 mt-6">
            <h3 class="text-lg font-semibold text-white mb-4">Tous les rendez-vous de <?= $months[$month - 1] ?></h3>
            <div class="space-y-3">
                <?php foreach ($appointments as $apt): ?>
                <div class="flex items-center justify-between p-4 bg-dark-700 rounded-lg hover:bg-dark-600 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gold-500/20 rounded-lg flex flex-col items-center justify-center">
                            <span class="text-gold-500 text-xs font-semibold"><?= date('d', strtotime($apt['start_date'])) ?></span>
                            <span class="text-gold-500/60 text-[10px]"><?= substr($months[date('n', strtotime($apt['start_date'])) - 1], 0, 3) ?></span>
                        </div>
                        <div>
                            <p class="font-medium text-white"><?= Core\View::e($apt['title']) ?></p>
                            <p class="text-dark-400 text-sm">
                                <i class="fas fa-clock mr-1"></i> <?= date('H:i', strtotime($apt['start_date'])) ?>
                                <?php if (!empty($apt['end_date'])): ?>
                                - <?= date('H:i', strtotime($apt['end_date'])) ?>
                                <?php endif; ?>
                                <?php if (!empty($apt['location'])): ?>
                                <span class="ml-3"><i class="fas fa-map-marker-alt mr-1"></i> <?= Core\View::e($apt['location']) ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="<?= SITE_URL ?>/admin/agenda/<?= $apt['id'] ?>/modifier" class="w-8 h-8 flex items-center justify-center rounded-lg text-dark-400 hover:text-gold-500 hover:bg-dark-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?= SITE_URL ?>/admin/agenda/<?= $apt['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ce rendez-vous ?')">
                            <?= Core\CSRF::field() ?>
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-dark-400 hover:text-red-400 hover:bg-dark-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
