<?php
// Array de idiomas ya existente
$GLOBALS['languages'] = [
    'lang1' => 'DE 🇩🇪',
    'lang2' => 'EN 🇬🇧',
    'lang3' => 'ES 🇪🇸'
];

$GLOBALS['cpt_existing'] = ['referenzen', 'stellen'];

// Array de CPTs a crear
$GLOBALS['cpt_creator'] = array(
    [ 'lang2' => array('posts_en', 'post', 'Posts'), 'lang3' => array('entradas', 'entrada', 'Entradas')],
    [ 'lang2' => array('pages_en', 'page', 'Pages'), 'lang3' => array('paginas', 'pagina', 'Páginas')],
    //Extra CPTs
    [ 'lang2' => array('projects_en', 'project', 'Projects'), 'lang3' => array('referencias', 'referencia', 'Referencias') ],
    [ 'lang2' => array('jobs_en', 'job', 'Jobs'), 'lang3' => array('empleos', 'empleo', 'Empleos') ],
    // [ 'lang1' => array('autos', 'auto', 'Autos'), 'lang2' => array('cars', 'car', 'Cars'), 'lang3' => array('coches', 'coche', 'Coches') ],
);

$GLOBALS['all_cpt'] = array(
    [ 'lang1' => 'posts', 'lang2' => 'posts_en', 'lang3' => 'entradas'],
    [ 'lang1' => 'pages', 'lang2' => 'pages_en', 'lang3' => 'paginas'],
    //Extra CPTs
    [ 'lang1' => 'projects', 'lang2' => 'projects_en', 'lang3' => 'referencias'],
    [ 'lang1' => 'jobs', 'lang2' => 'jobs_en', 'lang3' => 'empleos'],
);
?>

