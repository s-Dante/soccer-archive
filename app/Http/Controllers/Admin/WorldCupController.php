<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\WorldCupRepository;
use App\Http\Requests\Admin\StoreWorldCupRequest; // <-- 1. IMPORTAMOS el nuevo Request
use App\Http\Requests\Admin\UpdateWorldCupRequest; // <-- 1. IMPORTAMOS el nuevo Request
use App\Services\CountryService; // <-- Paises



class WorldCupController extends Controller
{
    protected $repository;
    protected $countryService;

    // Inyección de dependencias: Laravel nos da el repositorio automáticamente
    public function __construct(WorldCupRepository $repository, CountryService $countryService)
    {
        $this->repository = $repository;
        // --- CORRECCIÓN AQUÍ ---
        $this->countryService = $countryService;
    }

    public function index()
    {
        // --- 2. CAMBIO DE MÉTODO ---
        // Llama al método explícito de admin
        $worldCups = $this->repository->getForAdminIndex();
        // --------------------------

        return view('admin.worldcups.index', compact('worldCups'));
    }

    /**
     * Muestra el formulario para crear un nuevo mundial.
     * (Lo añadimos para que la ruta 'admin.worldcups.create' funcione)
     */
    public function create()
    {
        // --- ¡ESTA ES LA LÍNEA QUE FALTABA! ---
        // --- Y CORRECCIÓN AQUÍ ---
        $countries = $this->countryService->getCountryList();
        
        // --- Y AQUÍ LA PASAMOS A LA VISTA ---
        return view('admin.worldcups.create', compact('countries'));
    }

    /**
     * Guarda el nuevo mundial en la BD.
     */
    // --- 3. CAMBIAMOS Request POR StoreWorldCupRequest ---
    public function store(StoreWorldCupRequest $request)
    {
        // 4. ¡La validación se fue! Ahora solo obtenemos los datos validados.
        $data = $request->validated();

        // 5. Preparamos los datos de las imágenes (esta lógica se queda aquí)
        $data['cover_image'] = $request->hasFile('cover_image') 
                             ? file_get_contents($request->file('cover_image')->getRealPath()) 
                             : null;
        
        $data['ball_image'] = $request->hasFile('ball_image') 
                            ? file_get_contents($request->file('ball_image')->getRealPath()) 
                            : null;

        $this->repository->create($data);

        return redirect()->route('admin.worldcups.index')
                         ->with('success', 'Mundial creado exitosamente.');
    }

    public function edit(int $id)
    {
        $worldCup = $this->repository->getById($id);
        if (!$worldCup) {
            return redirect()->route('admin.worldcups.index')->withErrors('Mundial no encontrado.');
        }
        
        $countries = $this->countryService->getCountryList();
        
        return view('admin.worldcups.edit', compact('worldCup', 'countries'));
    }


    // --- 3. MÉTODO NUEVO PARA ACTUALIZAR EN LA BD ---
    public function update(UpdateWorldCupRequest $request, int $id)
    {
        // 1. Validamos y obtenemos los datos de texto (y opcionalmente de imagen)
        $data = $request->validated(); 

        // 2. Actualizamos los datos de texto (año, sede, descripción)
        $this->repository->update($id, $data);

        // 3. Verificamos si se subió una nueva imagen de portada
        if ($request->hasFile('cover_image')) {
            $coverImageData = file_get_contents($request->file('cover_image')->getRealPath());
            $this->repository->updateCoverImage($id, $coverImageData);
        }
        
        // 4. Verificamos si se subió una nueva imagen de balón
        if ($request->hasFile('ball_image')) {
            $ballImageData = file_get_contents($request->file('ball_image')->getRealPath());
            $this->repository->updateBallImage($id, $ballImageData);
        }

        return redirect()->route('admin.worldcups.index')
                         ->with('success', 'Mundial actualizado exitosamente.');
    }

    // --- 4. MÉTODO NUEVO PARA BORRAR (BAJA LÓGICA) ---
    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.worldcups.index')
                         ->with('success', 'Mundial dado de baja exitosamente (junto con sus publicaciones).');
    }

    public function restore(int $id)
    {
        $this->repository->restore($id);

        return redirect()->route('admin.worldcups.index')
                         ->with('success', 'Mundial reactivado exitosamente.');
    }
}

