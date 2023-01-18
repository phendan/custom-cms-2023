<?php

// Dump and die
function dd(...$values) {
    echo '<pre>', var_dump(...$values), '</pre>';
    die();
}
