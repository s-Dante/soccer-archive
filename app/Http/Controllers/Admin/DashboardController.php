<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Muestra el dashboard con estadísticas reales.
     */
    public function index()
    {
        $stats = $this->repository->getStats();
        return view('admin.dashboard', compact('stats'));
    }
}
