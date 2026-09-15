<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiReceiptService
{
    public function analyzeReceipt($imagePath, $categories)
    {
       

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}";
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath);

        
        $categoryList = $categories->pluck('name', 'id')->toJson();

        $prompt = "Actúa como un asistente financiero experto. Analiza el siguiente recibo o ticket de compra.
        Extrae la información y devuélvela ESTRICTAMENTE en formato JSON. No incluyas markdown, ni comillas invertidas, ni texto extra.
        Estructura requerida:
        {
            \"amount\": (número decimal, el total final de la compra),
            \"date\": (fecha de la compra en formato YYYY-MM-DD),
            \"description\": (nombre del comercio o resumen corto),
            \"category_id\": (elige el ID que mejor encaje de esta lista de categorías del usuario: {$categoryList})
        }";

        $response = Http::post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
            ]
        ]);

        if ($response->successful()) {
            $content = $response->json('candidates.0.content.parts.0.text');
            return json_decode($content, true);
        }

        throw new \Exception('Error Gemini HTTP ' . $response->status() . ': ' . $response->body());
    }
}