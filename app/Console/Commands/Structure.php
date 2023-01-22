<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class Structure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:structure {model} {module?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generate model and a base repository out of the box.';

    /**
     * Filesystem instance
     *
     * @var string
     */
    protected $filesystem;

    /**
     * Default laracom folder
     *
     * @var string
     */
    protected $folder;

    protected $controllerPath = 'app/Http/Controllers/Backend';

    protected $modelPath = 'app/Models';

    protected $baseRepository = 'app';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */


    public function handle()
    {

        $this->createFiles();

        $this->info('File structure for ' . $this->model . ' created.');

    }

    public function askForField ($fields = [])
    {

        $fields = array_merge([], $fields);

        foreach ($this->fieldQuestions() as $key => $question) {

            $result = $this->ask($question);

            if (!trim($result)) {
                $result = $this->askAgain($question);
            }

            $field[$key] = $result;

        }

        $fields[] = $field;

        if ('yes' == $this->ask('Need another field? [yes]')) {
            return $this->askForField($fields);
        }

        return $fields;

    }

    protected function fieldQuestions ()
    {

        return [
            'name' => 'Field name ?',
            'type' => 'Field type ?',
            'rules' => 'Field rules?'
        ];

    }

    protected function askAgain ($question)
    {

        $this->error('This question is required!');

        $result = $this->ask($question);

        if (!trim($result)) {
            return $this->askAgain($question);
        }

        return $result;

    }

    protected function createFiles ()
    {

        $this->model = ucfirst($this->argument('model'));
        $this->module = $this->argument('module');

        $this->pluralModel = $pluralModel = str_plural($this->model);
        $this->pluralModelModel = $pluralSmallModel = strtolower($pluralModel);

//        dd($this->model, $pluralModel, $pluralSmallModel);

        if ($this->filesystem->exists("{$this->modelPath}/{$pluralModel}/{$this->model}.php")) {
            return $this->error("The given model already exists!");
        }

        $this->createFolders($this->modelPath);

        $this->createFile(
            app_path('Console/Stubs/DummyModel.stub'),
            "{$this->modelPath}/{$pluralModel}/{$this->model}.php"
        );

        $this->createFile(
            app_path('Console/Stubs/DummyRepository.stub'),
            "{$this->modelPath}/{$pluralModel}/Repositories/{$this->model}Repository.php"
        );

        $this->createFile(
            app_path('Console/Stubs/DummyRepositoryInterface.stub'),
            "{$this->modelPath}/{$pluralModel}/Repositories/{$this->model}RepositoryInterface.php"
        );

        $this->createFile(
            app_path('Console/Stubs/DummyViewData.stub'),
            "{$this->modelPath}/{$pluralModel}/Repositories/ViewData.php"
        );

        $this->createFile(
            app_path('Console/Stubs/DummyController.stub'),
            "{$this->controllerPath}/{$this->model}/{$this->model}Controller.php"
        );



        $this->createFile(
            app_path('Console/Stubs/DummyRequest.stub'),
            "{$this->modelPath}/{$pluralModel}/Requests/Store{$this->model}Request.php",
            'Store'
        );

        $this->createFile(
            app_path('Console/Stubs/DummyRequest.stub'),
            "{$this->modelPath}/{$pluralModel}/Requests/Update{$this->model}Request.php",
            'Update'
        );

        $this->createFile(
            app_path('Console/Stubs/DummyIndex.stub'),
            "resources/views/backend/{$pluralSmallModel}/index.blade.php"
        );

        $this->createFile(
            app_path('Console/Stubs/DummyCreate.stub'),
            "resources/views/backend/{$pluralSmallModel}/create.blade.php"
        );

        $this->createFile(
            app_path('Console/Stubs/DummyEdit.stub'),
            "resources/views/backend/{$pluralSmallModel}/edit.blade.php"
        );

    }

    protected function createFile($dummySource, $destinationPath, $type = null)
    {
        $pluralModel = str_plural($this->model);
        $modelNameSpace = str_replace('app', 'App', $this->modelPath) . '\\' . $pluralModel;
        $dummyRepository = $this->filesystem->get($dummySource);
        $repositoryContent = str_replace(
            [
                'Dummy',
                'Dummies',
                'type_request',
                'models',
                'base_path',
                'model_name',
                'controllers',
                'plural_lower_case',
                'single_lower_case',
            ],

            [
                $this->model,
                $pluralModel,
                $type,
                str_replace("/", '\\', $modelNameSpace),
                $this->baseRepository,
                $this->model,
                str_replace("/", '\\', str_replace('app','App', $this->controllerPath)),
                str_plural(strtolower($this->model)),
                strtolower($this->model)
            ],
            $dummyRepository
        );
        $this->filesystem->put($dummySource, $repositoryContent);
        $this->filesystem->copy($dummySource, $destinationPath);
        $this->filesystem->put($dummySource, $dummyRepository);
    }

    protected function createFolders($baseFolder)
    {
        $pluralModel = str_plural($this->model);
        $pluralSmallModel = strtolower($pluralModel);

        $this->makeDirectory($this->controllerPath . "/{$this->model}/.");
        $this->makeDirectory($baseFolder . "/{$pluralModel}");
        $this->makeDirectory($baseFolder . "/{$pluralModel}/Requests/.");
        $this->makeDirectory($baseFolder . "/{$pluralModel}/Repositories/.");

        $this->makeDirectory("resources/views/backend" . "/{$pluralSmallModel}/.");
    }

    protected function makeDirectory($path)
    {
        if (!$this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0777, true, true);
        }
        return $path;
    }

    protected function createControllerFolders($baseFolder)
    {
        $pluralModel = str_plural($this->model);
        $this->filesystem->makeDirectory(app_path($baseFolder . "/{$pluralModel}"));
    }


}
