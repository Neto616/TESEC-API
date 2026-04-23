<?php

namespace Database\Seeders;

use App\Models\Perfil;
use App\Models\Permiso;
use App\Models\Seccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Perfil::create(['perfil' => 'Administrador']);
        $empleado = Perfil::create(['perfil' => 'Empleado']);

        $nombresSecciones = ['Productos', 'Variantes', 'Inventario', 'Proveedores', 'Atributos', 'Usuarios', 'Clientes', 'Catalogos'];
        $seccionesIds = [];
        foreach ($nombresSecciones as $nombre) {
            $s = Seccion::create(['nombre_seccion' => $nombre]);
            $seccionesIds[] = $s->id;
        }

        $nombresPermisos = ['Crear', 'Actualizar', 'Eliminar', 'Visualizar'];
        $permisosIds = [];
        foreach ($nombresPermisos as $nombre) {
            $p = Permiso::create(['permiso' => $nombre]);
            $permisosIds[] = $p->id;
        }

        foreach ($seccionesIds as $seccionId) {
            foreach ($permisosIds as $permisoId) {
                DB::table('perfil_permisos')->insert([
                    'perfil_id'  => $admin->id,
                    'permiso_id' => $permisoId,
                    'seccion_id' => $seccionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}