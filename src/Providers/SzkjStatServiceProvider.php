<?php
/**
 * Creator htm
 * Created by 2020/11/17 16:47
 **/

namespace Szkj\Stat\Providers;


use Illuminate\Support\ServiceProvider;
use Szkj\Stat\Console\Commands\InstallCommand;
use Szkj\Stat\ItemStat;

class SzkjStatServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * @var array
     */
    protected $commands = [
        InstallCommand::class,
    ];

    public function register()
    {
        $this->app->singleton(ItemStat::class);

        $this->app->alias(ItemStat::class, 'ItemsStat');
    }

    public function provides()
    {
        return [ItemStat::class, 'ItemsStat'];
    }
}