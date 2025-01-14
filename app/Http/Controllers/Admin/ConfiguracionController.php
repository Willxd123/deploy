<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Color;
use App\Models\Configuracion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configuracions = Configuracion::with('colors')->get();
        $colors = Color::all();
        return view('admin.configuracions.index', compact('configuracions', 'colors'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.configuracions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'color' => 'nullable|string|max:250',
            'estado' => 'boolean',
            'logotipo' => 'nullable|image|mimes:png|max:1024', // Validar solo imágenes PNG
        ]);

        $aws_ruta = 'https://tienda-m.s3.amazonaws.com/';
        $image_url = null;
        // Almacenar el logotipo
        if ($request->hasFile('logotipo')) {
            $image_ruta = $request->file('logotipo')->storePublicly('configuracions');
            $image_url = $aws_ruta . $image_ruta;
        }
        // Crear el registro en la base de datos para Configuracion
        /* $configuracion = Configuracion::create([
            'logotipo' => $image_url,

        ]); */
        // Asignar el ID de la configuración manualmente
        $configuracion_id = 1;
        // Obtener la configuración
        $configuracion = Configuracion::findOrFail($configuracion_id);

        // Crear el registro en la base de datos para Color con el configuracion_id
        if ($request->estado) {
            // Desactivar otros colores activos
            $configuracion->colors()->update(['estado' => false]);
        }
        $configuracion->colors()->create([
            'color' => $request->color,
            'estado' => $request->estado ?? false,
            'configuracion_id' => $configuracion_id,
        ]);

        // Register the action in the Bitacora
        $bitacora = new Bitacora();
        $bitacora->descripcion = "Creación de una Configuración";
        $bitacora->usuario = auth()->user()->name;
        $bitacora->usuario_id = auth()->user()->id;
        $bitacora->direccion_ip = $request->ip();
        $bitacora->navegador = $request->header('user-agent');
        $bitacora->tabla = "Configuracion";
        $bitacora->registro_id = $configuracion->id;
        $bitacora->fecha_hora = Carbon::now();
        $bitacora->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Bien Hecho',
            'text' => 'Configuración agregada correctamente.'
        ]);

        return redirect()->route('admin.configuracions.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(configuracion $configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(configuracion $configuracion)
    {
        $colors = Color::all();
        return view('admin.configuracions.edit', compact('configuracion', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        $request->validate([
            'logotipo' => 'nullable|image|max:5024',
            'colors' => 'required|array',
        ], [
            'logotipo.image' => 'El logotipo debe ser una imagen.',
            'logotipo.max' => 'El logotipo no debe superar los 5mb.',
            'colors.required' => 'Debe seleccionar al menos un color.',
            'colors.array' => 'Los colores seleccionados deben ser un arreglo válido.'
        ]);
        $aws_ruta = 'https://tienda-m.s3.amazonaws.com/';
        $image_url = $configuracion->logotipo;

        if ($request->hasFile('logotipo')) {
            // Eliminar la imagen anterior del almacenamiento
            if ($configuracion->logotipo) {
                $oldImagePath = str_replace($aws_ruta, '', $configuracion->logotipo);
                Storage::delete($oldImagePath);
            }

            // Almacenar la nueva imagen
            $image_ruta = $request->file('logotipo')->storePublicly('configuracions');
            $image_url = $aws_ruta . $image_ruta;
        }

        // Actualizar los campos en la base de datos
        $configuracion->update([
            'logotipo' => $image_url,
        ]);

        // Sincronizar colores seleccionados
        $configuracion->colors()->sync($request->input('colors'));
        // Register the action in the Bitacora
        $bitacora = new Bitacora();
        $bitacora->descripcion = "Actualización de una Configuración";
        $bitacora->usuario = auth()->user()->name;
        $bitacora->usuario_id = auth()->user()->id;
        $bitacora->direccion_ip = $request->ip();
        $bitacora->navegador = $request->header('user-agent');
        $bitacora->tabla = "Configuracion";
        $bitacora->registro_id = $configuracion->id;
        $bitacora->fecha_hora = Carbon::now();
        $bitacora->save();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'Configuración actualizada correctamente.'
        ]);

        return redirect()->route('admin.configuracions.index');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(configuracion $configuracion)
    {
        //
    }
}
