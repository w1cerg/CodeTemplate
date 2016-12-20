<?

/*
 * Добавляем версию файла
 */
function my_wp_default_styles($styles) {
    $styles->default_version = filemtime(get_stylesheet_directory() . '/style.css' );
    // $styles->default_version = hash_file('crc32', get_stylesheet_directory() . '/style.css');
}
add_action("wp_default_styles", "my_wp_default_styles");