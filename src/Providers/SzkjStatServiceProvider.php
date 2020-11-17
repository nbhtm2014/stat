<?php
/**
 * Creator htm
 * Created by 2020/11/17 16:47
 **/

namespace Szkj\Stat\Providers;


use Illuminate\Support\ServiceProvider;
use Szkj\Stat\ItemsStat;

class SzkjStatServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(ItemsStat::class);

        $this->app->alias(ItemsStat::class, 'ItemsStat');
    }

    public function provides()
    {
        return [ItemsStat::class, 'ItemsStat'];
    }
}