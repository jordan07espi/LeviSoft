<?php
// Archivo: view/partials/breadcrumb.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$breadcrumb = $_SESSION['breadcrumb'] ?? [['nombre' => 'Inicio', 'url' => 'dashboard.php']];
?>

<nav class="bg-white p-3 rounded-md shadow-sm border-b mb-6">
    <ol class="list-none p-0 inline-flex items-center text-sm">
        <?php foreach ($breadcrumb as $index => $crumb): ?>
            <li class="flex items-center">
                <?php if ($index < count($breadcrumb) - 1): ?>
                    
                    <a href="<?php echo htmlspecialchars($crumb['url']); ?>?bc_index=<?php echo $index; ?>" class="text-blue-600 hover:underline">
                        <?php echo htmlspecialchars($crumb['nombre']); ?>
                    </a>
                    
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <?php else: ?>
                    
                    <span class="text-gray-500 font-semibold"><?php echo htmlspecialchars($crumb['nombre']); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>