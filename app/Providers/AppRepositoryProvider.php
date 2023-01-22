<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{


    public function boot() {}


    public function register() {
        $this->app->bind("App\Repositories\BaseRepositoryInterface", "App\Repositories\BaseRepository");
        
        $models = array(
            'Admin',
            'Item',
            'User',
            'Storage'
        );

        foreach ($models as $model) {
            $pluralModel = str_plural($model);
            $this->app->bind("App\Models\\{$pluralModel}\Repositories\\{$model}RepositoryInterface", "App\Models\\{$pluralModel}\Repositories\\{$model}Repository");
        }
    }

}
