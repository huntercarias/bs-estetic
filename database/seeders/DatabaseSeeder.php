<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PatientProfile;
use App\Models\Product;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole   = Role::firstOrCreate(['name' => 'admin']);
        $staffRole   = Role::firstOrCreate(['name' => 'staff']);
        $patientRole = Role::firstOrCreate(['name' => 'patient']);

        // Tenant de prueba
        $tenant = Tenant::create([
            'name' => 'Estética Salud y Bienestar',
            'slug' => 'estetica-salud-bienestar',
            'description' => 'Tu bienestar es nuestra prioridad. Ofrecemos tratamientos estéticos y de salud integral con tecnología de vanguardia.',
            'phone' => '+1 (809) 555-0100',
            'email' => 'info@esteticasaludbienestar.com',
            'address' => 'Av. Principal 123, Santo Domingo',
            'active' => true,
        ]);

        // Admin user
        $admin = User::create([
            'tenant_id'         => $tenant->id,
            'name'              => 'Administrador',
            'email'             => 'admin@clinica.com',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        // Paciente de prueba
        $patient = User::create([
            'tenant_id'         => $tenant->id,
            'name'              => 'María García',
            'email'             => 'paciente@clinica.com',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $patient->assignRole($patientRole);
        PatientProfile::create([
            'tenant_id'    => $tenant->id,
            'user_id'      => $patient->id,
            'birth_date'   => '1990-05-15',
            'gender'       => 'female',
            'weight_kg'    => 65.0,
            'height_cm'    => 162.0,
            'trains_at'    => 'home',
            'goal'         => 'toning',
            'allergies'    => 'ninguna',
            'medical_notes'=> 'sin condiciones previas',
        ]);

        // Categorías de servicios
        $catConsultas = Category::create(['tenant_id' => $tenant->id, 'name' => 'Consultas Médicas', 'type' => 'service', 'active' => true]);
        $catEstetica = Category::create(['tenant_id' => $tenant->id, 'name' => 'Estética y Bienestar', 'type' => 'service', 'active' => true]);
        $catLaboratorio = Category::create(['tenant_id' => $tenant->id, 'name' => 'Laboratorio', 'type' => 'service', 'active' => true]);

        // Servicios
        Service::create(['tenant_id' => $tenant->id, 'category_id' => $catConsultas->id, 'name' => 'Consulta General', 'description' => 'Evaluación médica completa con médico general certificado.', 'price' => 1500.00, 'duration_minutes' => 30, 'active' => true, 'order' => 1]);
        Service::create(['tenant_id' => $tenant->id, 'category_id' => $catConsultas->id, 'name' => 'Consulta Especialista', 'description' => 'Atención con especialistas en diversas áreas de la medicina.', 'price' => 2500.00, 'duration_minutes' => 45, 'active' => true, 'order' => 2]);
        Service::create(['tenant_id' => $tenant->id, 'category_id' => $catConsultas->id, 'name' => 'Pediatría', 'description' => 'Atención médica especializada para niños y adolescentes.', 'price' => 1800.00, 'duration_minutes' => 30, 'active' => true, 'order' => 3]);
        Service::create(['tenant_id' => $tenant->id, 'category_id' => $catEstetica->id, 'name' => 'Limpieza Facial', 'description' => 'Limpieza profunda y rejuvenecimiento del rostro.', 'price' => 1200.00, 'duration_minutes' => 60, 'active' => true, 'order' => 1]);
        Service::create(['tenant_id' => $tenant->id, 'category_id' => $catEstetica->id, 'name' => 'Masaje Terapéutico', 'description' => 'Masaje relajante y terapéutico para aliviar tensiones.', 'price' => 1500.00, 'duration_minutes' => 60, 'active' => true, 'order' => 2]);
        Service::create(['tenant_id' => $tenant->id, 'category_id' => $catLaboratorio->id, 'name' => 'Hemograma Completo', 'description' => 'Análisis completo de sangre con resultados en 24 horas.', 'price' => 800.00, 'duration_minutes' => 15, 'active' => true, 'order' => 1]);

        // Categorías de productos
        $catMedicamentos = Category::create(['tenant_id' => $tenant->id, 'name' => 'Medicamentos', 'type' => 'product', 'active' => true]);
        $catSuplemetos = Category::create(['tenant_id' => $tenant->id, 'name' => 'Suplementos', 'type' => 'product', 'active' => true]);

        // Productos
        Product::create(['tenant_id' => $tenant->id, 'category_id' => $catMedicamentos->id, 'name' => 'Vitamina C 1000mg', 'description' => 'Suplemento de vitamina C de alta potencia. Caja x 30 tabletas.', 'price' => 350.00, 'stock' => 50, 'sku' => 'VIT-C-1000', 'active' => true, 'order' => 1]);
        Product::create(['tenant_id' => $tenant->id, 'category_id' => $catMedicamentos->id, 'name' => 'Paracetamol 500mg', 'description' => 'Analgésico y antipirético. Caja x 20 tabletas.', 'price' => 120.00, 'stock' => 100, 'sku' => 'PARA-500', 'active' => true, 'order' => 2]);
        Product::create(['tenant_id' => $tenant->id, 'category_id' => $catSuplemetos->id, 'name' => 'Omega 3', 'description' => 'Ácidos grasos esenciales para la salud cardiovascular. Caja x 60 cápsulas.', 'price' => 650.00, 'stock' => 30, 'sku' => 'OMG-3-60', 'active' => true, 'order' => 1]);
        Product::create(['tenant_id' => $tenant->id, 'category_id' => $catSuplemetos->id, 'name' => 'Multivitamínico Diario', 'description' => 'Complejo vitamínico completo para adultos. Caja x 30 tabletas.', 'price' => 450.00, 'stock' => 40, 'sku' => 'MULTI-30', 'active' => true, 'order' => 2]);
    }
}
