<?php

namespace Fluent\Legacy;

use Illuminate\Support\ServiceProvider;

class LegacyServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->app->view->share('ci', get_instance());
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
    }
}
