<?php namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider {

    /**
     * Specify that the 'ApplicationComposer' should be called when
     * ANY view is rendered (denoted by the '*').
     *
     * @return void
     */
    public function boot()
    {
        // Bind 
        View::composer('*', 'App\Http\ViewComposers\ApplicationComposer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}