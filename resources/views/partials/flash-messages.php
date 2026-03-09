<?php
/**
 * TSILIZY LLC — Flash Messages Partial
 */

$success = Core\Session::flash('success');
$error = Core\Session::flash('error');
$warning = Core\Session::flash('warning');
$info = Core\Session::flash('info');
?>

<?php if ($success): ?>
<div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-transition>
    <i class="fas fa-check-circle text-green-400 mr-3"></i>
    <p class="text-green-400 flex-1"><?= Core\View::e($success) ?></p>
    <button @click="show = false" class="text-green-400 hover:text-green-300"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="mb-4 p-4 bg-red-500/20 border border-red-500/30 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-transition>
    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
    <p class="text-red-400 flex-1"><?= Core\View::e($error) ?></p>
    <button @click="show = false" class="text-red-400 hover:text-red-300"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if ($warning): ?>
<div class="mb-4 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-transition>
    <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
    <p class="text-yellow-400 flex-1"><?= Core\View::e($warning) ?></p>
    <button @click="show = false" class="text-yellow-400 hover:text-yellow-300"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if ($info): ?>
<div class="mb-4 p-4 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-transition>
    <i class="fas fa-info-circle text-blue-400 mr-3"></i>
    <p class="text-blue-400 flex-1"><?= Core\View::e($info) ?></p>
    <button @click="show = false" class="text-blue-400 hover:text-blue-300"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>
