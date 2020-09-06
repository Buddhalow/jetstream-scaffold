<?php
namespace App\Http\Controllers;
use App\Models\Coverage;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use ReflectionMethod;

class ObjectsController extends Controller
{
    private function resolveClass($model) {
        die($model)
        if ($model === 'coverage') {
            return 'Coverage';
        }
    }
    public function index($model)
    {
        $Class = $this->resolveClass($model);
        if (!$Class) {
            return;
        }
        $allMethod =  new ReflectionMethod("App\Models\\${$Class}", "all");
        $getFieldsMethod =  new ReflectionMethod("App\Models\\${$Class}", "getFields");
        return Inertia::render('Model/Index', [
            'objects' =>$allMethod->invoke(),
            'fields' => $getFieldsMethod->invoke(),
            'model' => $model
        ]);
    }
    public function store($model)
    {
        $Class = $this->resolveClass($model);
        $createMethod =  new ReflectionMethod("App\Models\\${$Class}", "create");
        $getFieldsMethod =  new ReflectionMethod("App\Models\\${$Class}", "getFields");
        $result = $createMethod->invoke(
            Request::validate(
                $getFieldsMethod->invoke()
            )
        );
        return Redirect::route('objects');
    }
    public function update($model, $id)
    {
        $Class = $this->resolveClass($model);
        $createMethod =  new ReflectionMethod("App\Models\\${$Class}", "update");
        $getFieldsMethod =  new ReflectionMethod("App\Models\\${$Class}", "getFields");
        $result = $createMethod->invoke(
            $id,
            Request::validate(
                $getFieldsMethod->invoke()
            )
        );
        return Redirect::route('objects', array('model' => $model, 'id' => $id));
    }
    public function delete($model) {
        $Class = $this->resolveClass($model);
        $deleteMethod =  new ReflectionMethod("App\Models\\${$Class}", "delete");
        return Redirect::route('objects', ['model' => $model]);
    }
}
