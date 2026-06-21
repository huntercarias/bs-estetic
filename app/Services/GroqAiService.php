<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqAiService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.groq.com/openai/v1';
    private string $model   = 'llama-3.3-70b-versatile';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key', '');
    }

    public function generateWellnessPlan(array $patientData, string $type = 'both'): array
    {
        $prompt = $this->buildPrompt($patientData, $type);

        if (empty($this->apiKey)) {
            return $this->demoResponse($patientData, $type);
        }

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->model,
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'Eres un experto en salud, fitness y nutrición de una clínica estética. Responde siempre en español, con planes claros, seguros y personalizados. Devuelve únicamente JSON válido sin texto adicional.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature'     => 0.7,
                'max_tokens'      => 2048,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            return ['error' => 'No se pudo conectar con la IA. Verifica tu API key de Groq.'];
        }

        $content = $response->json('choices.0.message.content');

        return json_decode($content, true) ?? ['error' => 'Respuesta inválida de la IA.'];
    }

    private function buildPrompt(array $p, string $type): string
    {
        $typeText = match($type) {
            'exercise'  => 'una rutina de ejercicios semanal',
            'nutrition' => 'un plan de alimentación semanal',
            default     => 'una rutina de ejercicios y un plan de alimentación semanal',
        };

        $location = match($p['trains_at'] ?? 'none') {
            'gym'  => 'gimnasio',
            'home' => 'casa (sin equipamiento especial)',
            'both' => 'gimnasio y casa',
            default => 'sin entrenamiento previo',
        };

        $goal = match($p['goal'] ?? 'wellness') {
            'weight_loss'    => 'pérdida de peso',
            'toning'         => 'tonificación muscular',
            'wellness'       => 'bienestar general',
            'rehabilitation' => 'rehabilitación física',
            default          => 'bienestar general',
        };

        return "Genera {$typeText} personalizado para un paciente con los siguientes datos:
- Nombre: {$p['name']}
- Edad: {$p['age']} años
- Género: {$p['gender']}
- Peso: {$p['weight']} kg
- Altura: {$p['height']} cm
- Lugar de entrenamiento: {$location}
- Objetivo principal: {$goal}
- Alergias o restricciones: {$p['allergies']}
- Notas médicas: {$p['medical_notes']}

Devuelve un JSON con esta estructura exacta:
{
  \"resumen\": \"Breve descripción del plan personalizado\",
  \"ejercicios\": [
    {\"dia\": \"Lunes\", \"grupo_muscular\": \"...\", \"ejercicios\": [{\"nombre\": \"...\", \"series\": 3, \"repeticiones\": \"12\", \"descanso\": \"60s\", \"nota\": \"...\"}]}
  ],
  \"alimentacion\": {
    \"calorias_diarias\": 0,
    \"macros\": {\"proteinas_g\": 0, \"carbohidratos_g\": 0, \"grasas_g\": 0},
    \"plan_semanal\": [
      {\"dia\": \"Lunes\", \"desayuno\": \"...\", \"almuerzo\": \"...\", \"cena\": \"...\", \"snacks\": \"...\"}
    ],
    \"recomendaciones\": [\"...\"]
  },
  \"consejos_generales\": [\"...\"]
}";
    }

    private function demoResponse(array $p, string $type): array
    {
        return [
            'resumen' => "Plan de demostración para {$p['name']}. Configura tu API Key de Groq en .env para generar planes reales con IA.",
            'ejercicios' => [
                [
                    'dia' => 'Lunes',
                    'grupo_muscular' => 'Pecho y Tríceps',
                    'ejercicios' => [
                        ['nombre' => 'Flexiones', 'series' => 3, 'repeticiones' => '12-15', 'descanso' => '60s', 'nota' => 'Espalda recta'],
                        ['nombre' => 'Dips', 'series' => 3, 'repeticiones' => '10', 'descanso' => '60s', 'nota' => ''],
                    ],
                ],
                [
                    'dia' => 'Miércoles',
                    'grupo_muscular' => 'Espalda y Bíceps',
                    'ejercicios' => [
                        ['nombre' => 'Dominadas asistidas', 'series' => 3, 'repeticiones' => '8', 'descanso' => '90s', 'nota' => ''],
                        ['nombre' => 'Curl de bíceps', 'series' => 3, 'repeticiones' => '12', 'descanso' => '60s', 'nota' => ''],
                    ],
                ],
            ],
            'alimentacion' => [
                'calorias_diarias' => 1800,
                'macros' => ['proteinas_g' => 120, 'carbohidratos_g' => 200, 'grasas_g' => 60],
                'plan_semanal' => [
                    ['dia' => 'Lunes', 'desayuno' => 'Avena con frutas', 'almuerzo' => 'Pollo a la plancha con ensalada', 'cena' => 'Sopa de verduras', 'snacks' => 'Yogur natural'],
                ],
                'recomendaciones' => ['Tomar 2L de agua al día', 'Evitar azúcares procesados', 'Dormir 7-8 horas'],
            ],
            'consejos_generales' => [
                'Este es un plan de ejemplo. Agrega tu API Key de Groq para planes personalizados con IA real.',
                'Consulta siempre con tu médico antes de iniciar un plan de ejercicio.',
            ],
        ];
    }
}
