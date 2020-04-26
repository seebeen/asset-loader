<?php

namespace SGI\WP\Asset;

class Loader implements Loadable
{

    private $name;

    private $version;

    private $priority;

    private $base_uri;

    private $path;

    private $assets;

    public function __construct(array $args)
    {

        foreach ($args as $var => $val)
            $this->$var = $val;

        if (!isset($this->priority))
            $this->priority = 50;

        $action = (!is_admin())  ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';

        add_action($action, [&$this, 'load_styles'], $this->priority);
        add_action($action, [&$this, 'load_scripts'], $this->priority);

    }

    private function is_external($asset)
    {


    }

    public function asset_uri(string $file) : string
    {

        return (filter_var($file, FILTER_VALIDATE_URL)) ?
            $file :
            "{$this->base_uri}/assets/{$file}";


    }

    public function asset_path(string $file) : string
    {

        return (filter_var($file, FILTER_VALIDATE_URL)) ?
            $file :
            "{$this->base_uri}/assets/{$file}";

    }

    public function load_styles() : void
    {

        $load_styles = apply_filters("sgi/loader/{$this->name}/load_styles", true);

        if (!$load_styles)
            return;

        foreach ($this->assets['css'] as $name => $file) :

            $handler = $this->name . '-' . $name;

            $load_style = apply_filters("sgi/loader/{$this->name}/enqueue_{$name}", true);

            if (!$load_style)
                continue;

            wp_register_style(
                $handler,
                $this->asset_uri($file),
                null,
                $this->version
            );
            wp_enqueue_style($handler);

        endforeach;

    }

    public function load_scripts() : void
    {

        $load_scripts = apply_filters("sgi/loader{$this->name}/load_scripts", true);

        if (!$load_scripts)
            return;

        foreach ($this->assets['js'] as $name => $data) :

            $handler = $this->name . '-' . $name;

            $load_script = apply_filters("sgi/loader/{$this->name}/enqueue_{$name}", true);

            if (!$load_script)
                continue;

            wp_register_script($handler,
                $this->asset_uri($data['file']),
                $data['deps'],
                $this->version,
                $data['footer'])
            ;
            wp_enqueue_script($handler);

        endforeach;

    }

}