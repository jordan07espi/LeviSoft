<?php
// Archivo: helpers/breadcrumb_helper.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Gestiona la ruta de navegación (breadcrumb).
 *
 * @param string $nombre El nombre de la página actual que se mostrará.
 * @param string $url La URL de la página actual.
 */
function gestionar_breadcrumb($nombre = null, $url = null) {
    // 1. Inicializa el breadcrumb si no existe
    if (!isset($_SESSION['breadcrumb'])) {
        $_SESSION['breadcrumb'] = [
            ['nombre' => 'Inicio', 'url' => 'dashboard.php']
        ];
    }

    // 2. Si se hace clic en un enlace del breadcrumb, recorta la ruta
    if (isset($_GET['bc_index'])) {
        $index = (int)$_GET['bc_index'];
        // Cortamos el array hasta el índice en el que se hizo clic
        $_SESSION['breadcrumb'] = array_slice($_SESSION['breadcrumb'], 0, $index + 1);
        // Redirigimos para limpiar la URL del parámetro 'bc_index'
        header('Location: ' . $_SESSION['breadcrumb'][$index]['url']);
        exit();
    }

    // 3. Si se proporciona un nombre y URL (es una nueva página), lo añade
    if ($nombre && $url) {
        // Evita añadir la misma página dos veces seguidas (al recargar)
        $ultima_pagina = end($_SESSION['breadcrumb']);
        if ($ultima_pagina['url'] !== $url) {
            $_SESSION['breadcrumb'][] = ['nombre' => $nombre, 'url' => $url];
        }
    }
}