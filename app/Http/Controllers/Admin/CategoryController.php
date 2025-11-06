<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Muestra la lista de categorías y el formulario para crear.
     */
    public function index()
    {
        $categories = $this->repository->getAll();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Guarda la nueva categoría en la BD.
     */
    public function store(Request $request)
    {
        // Validación simple
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        $this->repository->create($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Borra una categoría.
     */
    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría eliminada exitosamente.');
    }
}
