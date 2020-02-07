<?php

namespace App\Providers;

use App\Http\Requests\FormRequest;
use Laravel\Lumen\Http\Redirector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
	public function boot () {
		$this->app->resolving(FormRequest::class, function ($request, $app) {
			$this->initializeRequest($request, $app['request']);
		});

		$this->app->afterResolving(FormRequest::class, function ($form) {
			$form->validate();
		});
	}

	protected function initializeRequest (FormRequest $form, \Illuminate\Http\Request $current) {
		$files = $current->files->all();
		$files = is_array($files) ? array_filter($files) : $files;
		$form->initialize($current->query->all(), $current->request->all(), $current->attributes->all(), $current->cookies->all(), $files, $current->server->all(), $current->getContent());
		$form->setJson($current->json());
	}

}
