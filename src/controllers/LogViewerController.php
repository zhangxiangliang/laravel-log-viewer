<?php
namespace Rap2hpoutre\LaravelLogViewer;
use Illuminate\Support\Facades\Crypt;

if (class_exists("\\Illuminate\\Routing\\Controller")) {
    class BaseController extends \Illuminate\Routing\Controller {}
} else if (class_exists("Laravel\\Lumen\\Routing\\Controller")) {
    class BaseController extends \Laravel\Lumen\Routing\Controller {}
}

class LogViewerController extends BaseController
{
    protected $request;

    public function __construct ()
    {
        $this->request = app('request');
    }

    public function index()
    {
        if ($this->request->input('l')) {
            LaravelLogViewer::setFile(Crypt::decrypt($this->request->input('l')));
        }

        $data = [
            'logs' => LaravelLogViewer::all(),
            'files' => LaravelLogViewer::getFiles(true),
            'current_file' => LaravelLogViewer::getFileName()
        ];

        if ($this->request->wantsJson()) {
            return $data;
        }

        return app('view')->make('laravel-log-viewer::log', $data);
    }
}
