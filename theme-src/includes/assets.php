<?php
/**
 * Enqueue theme assets from Vite manifests
 */

function fnesl_get_manifest_entry($entry) {
    static $manifest = null;

    if ($manifest === null) {
        $manifest_path = get_stylesheet_directory() . '/manifest.json';
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = [];
        }
    }

    return $manifest[$entry] ?? null;
}

function fnesl_enqueue_assets() {
    $dist_uri = get_stylesheet_directory_uri();

    // Front-end assets
    $theme_entry = fnesl_get_manifest_entry('js/theme.entry.js');
    if ($theme_entry) {
        if (!empty($theme_entry['css'])) {
            foreach ($theme_entry['css'] as $css_file) {
                wp_enqueue_style('theme-style', $dist_uri . '/' . $css_file, [], null);
            }
        }
        wp_enqueue_script('theme-main', $dist_uri . '/' . $theme_entry['file'], [], null, true);
    }
}
add_action('wp_enqueue_scripts', 'fnesl_enqueue_assets');

function fnesl_editor_assets() {
    $dist_uri = get_stylesheet_directory_uri();

    // Editor assets
    $editor_entry = fnesl_get_manifest_entry('js/editor.entry.js');
    if ($editor_entry) {
        if (!empty($editor_entry['css'])) {
            foreach ($editor_entry['css'] as $css_file) {
                wp_enqueue_style('editor-style', $dist_uri . '/' . $css_file, [], null);
            }
        }
        wp_enqueue_script('editor-script', $dist_uri . '/' . $editor_entry['file'], [], null, true);
    }
}
add_action('enqueue_block_editor_assets', 'fnesl_editor_assets');