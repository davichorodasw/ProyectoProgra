<?php
function getBasePath()
{
    return '/php/Proyecto1puro/ProyectoProgra/';
}

function asset($path)
{
    return getBasePath() . ltrim($path, '/');
}

function url($path)
{
    return getBasePath() . ltrim($path, '/');
}
