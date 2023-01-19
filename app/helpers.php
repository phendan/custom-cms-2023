<?php

// Dump and die
function dd(...$values) {
    echo '<pre>', var_dump(...$values), '</pre>';
    die();
}

function d(...$values) {
    echo '<pre>', var_dump(...$values), '</pre>';
}
