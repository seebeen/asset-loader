<?php

namespace SGI\WP\Asset;

interface Loadable
{

    public function asset_uri(string $file) : string;

    public function asset_path(string $file) : string;

    public function load_styles() : void;

    public function load_scripts() : void;

}