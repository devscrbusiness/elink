@props(['name'])

@php
$path = resource_path('svg/' . $name . '.svg');

if (file_exists($path)) {
    // Obtenemos el contenido del SVG
    $svg = file_get_contents($path);

    // Eliminamos los atributos de ancho y alto para que se pueda controlar con clases de CSS
    $svg = preg_replace('/(width|height)="[^"]*"/i', '', $svg);

    // Añadimos los atributos pasados al componente (como `class`) a la etiqueta <svg>
    $svg = str_replace('<svg', '<svg ' . $attributes, $svg);
}
@endphp

{!! $svg ?? '' !!}
