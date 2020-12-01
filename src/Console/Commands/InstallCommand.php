<?php
/**
 * Creator htm
 * Created by 2020/12/1 10:48
 **/

namespace Szkj\Stat\Console\Commands;


use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'szkj:stat-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the stat package';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    public function getConnection(): string
    {
        return config('database.default');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->createModels();
    }

    public  function createModels(){
        $files = [];
        $this->listDir(__DIR__ . '/../../Stubs/Models', $files);
        foreach ($files as $file) {
            $dir = basename(dirname($file));

            $this->makeDir($dir);

            $filename = pathinfo($file, PATHINFO_FILENAME);

            $model = app_path("Models/{$filename}.php");

            if ($filename === 'User' && file_exists($model)){
                unlink($model);
            }

            $stub_model = $this->laravel['files']->get($file);

            $this->laravel['files']->put(
                $model,
                str_replace(
                    'DummyNamespace',
                    'App\\Models',
                    $stub_model
                )
            );
            $this->line('<info>' . $filename . ' file was created:</info> ' . str_replace(base_path(), '', $model));
        }
    }


    /**
     * @param $directory
     * @param array &$file
     */
    protected function listDir($directory, array &$file)
    {
        $temp = scandir($directory);
        foreach ($temp as $k => $v) {
            if ('.' == $v || '..' == $v) {
                continue;
            }
            $a = $directory . '/' . $v;
            if (is_dir($a)) {
                $this->listDir($a, $file);
            } else {
                $file[] = $a;
            }
        }
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__ . "/../../Stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory(app_path() . '/' . $path, 0755, true, true);
    }
}