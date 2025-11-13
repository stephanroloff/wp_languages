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
    [ 'lang2' => array('posts', 'post'), 'lang3' => array('entradas', 'entrada')],
    [ 'lang2' => array('pages', 'page'), 'lang3' => array('paginas', 'pagina')],
    //Extra CPTs
    [ 'lang2' => array('projects', 'project'), 'lang3' => array('referencias', 'referencia') ],
    [ 'lang2' => array('jobs', 'job'), 'lang3' => array('empleos', 'empleo') ],
    [ 'lang1' => array('autos', 'auto'), 'lang2' => array('cars', 'car'), 'lang3' => array('coches', 'coche') ],
);
?>

