<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected array $settings;

    public function __construct()
    {
        $this->settings = SiteSetting::getAiSettings();
    }

    /**
     * Check if AI is configured and ready to use
     */
    public function isConfigured(): bool
    {
        return !empty($this->settings['api_key']);
    }

    /**
     * Generate an answer for a question
     */
    public function generateAnswer(string $question, array $context = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'AI is not configured. Please add your API key in settings.',
            ];
        }

        $prompt = $this->buildPrompt($question, $context);

        try {
            if ($this->settings['provider'] === 'anthropic') {
                return $this->callAnthropic($prompt);
            }

            if ($this->settings['provider'] === 'google') {
                return $this->callGemini($prompt);
            }

            return $this->callOpenAI($prompt);
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to generate answer: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test the AI connection
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'AI is not configured. Please add your API key first.',
            ];
        }

        try {
            $testPrompt = 'Say "Connection successful!" in exactly those words.';

            if ($this->settings['provider'] === 'anthropic') {
                $result = $this->callAnthropic($testPrompt, true);
            } elseif ($this->settings['provider'] === 'google') {
                $result = $this->callGemini($testPrompt, true);
            } else {
                $result = $this->callOpenAI($testPrompt, true);
            }

            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'Successfully connected to ' . ucfirst($this->settings['provider']) . ' API using model: ' . $this->settings['model'],
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build the prompt for the AI
     */
    protected function buildPrompt(string $question, array $context = []): string
    {
        $prompt = "Question: {$question}\n\n";

        if (!empty($context['unit'])) {
            $prompt = "Unit/Topic: {$context['unit']}\n\n" . $prompt;
        }

        if (!empty($context['course'])) {
            $prompt = "Course: {$context['course']}\n\n" . $prompt;
        }

        $prompt .= "Please provide a comprehensive, educational answer suitable for TVET students in Kenya. ";
        $prompt .= "Format the answer clearly with proper structure. ";
        $prompt .= "If applicable, include practical examples relevant to the Kenyan context.";

        return $prompt;
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(string $prompt, bool $isTest = false): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->settings['api_key'],
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->settings['model'],
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->settings['system_prompt'] ?: 'You are an expert TVET educator in Kenya. Provide clear, accurate, and educational answers to questions. Format your answers in a way that helps students understand the concepts.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens' => $isTest ? 50 : (int) $this->settings['max_tokens'],
            'temperature' => (float) $this->settings['temperature'],
        ]);

        if ($response->failed()) {
            $error = $response->json('error.message') ?? 'Unknown error occurred';
            throw new \Exception($error);
        }

        $content = $response->json('choices.0.message.content');

        return [
            'success' => true,
            'answer' => $content,
            'usage' => $response->json('usage'),
        ];
    }

    /**
     * Call Anthropic API
     */
    protected function callAnthropic(string $prompt, bool $isTest = false): array
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->settings['api_key'],
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->settings['model'],
            'max_tokens' => $isTest ? 50 : (int) $this->settings['max_tokens'],
            'system' => $this->settings['system_prompt'] ?: 'You are an expert TVET educator in Kenya. Provide clear, accurate, and educational answers to questions. Format your answers in a way that helps students understand the concepts.',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        if ($response->failed()) {
            $error = $response->json('error.message') ?? $response->json('error.type') ?? 'Unknown error occurred';
            throw new \Exception($error);
        }

        $content = $response->json('content.0.text');

        return [
            'success' => true,
            'answer' => $content,
            'usage' => [
                'input_tokens' => $response->json('usage.input_tokens'),
                'output_tokens' => $response->json('usage.output_tokens'),
            ],
        ];
    }

    /**
     * Call Google Gemini API
     */
    protected function callGemini(string $prompt, bool $isTest = false): array
    {
        $model = $this->settings['model'] ?: 'gemini-1.5-flash';
        $apiKey = $this->settings['api_key'];

        $systemPrompt = $this->settings['system_prompt'] ?: 'You are an expert TVET educator in Kenya. Provide clear, accurate, and educational answers to questions. Format your answers in a way that helps students understand the concepts.';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $systemPrompt . "\n\n" . $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => $isTest ? 50 : (int) $this->settings['max_tokens'],
                'temperature' => (float) $this->settings['temperature'],
            ],
        ]);

        if ($response->failed()) {
            $error = $response->json('error.message') ?? 'Unknown error occurred';
            throw new \Exception($error);
        }

        $content = $response->json('candidates.0.content.parts.0.text');

        if (empty($content)) {
            throw new \Exception('No response content received from Gemini');
        }

        return [
            'success' => true,
            'answer' => $content,
            'usage' => [
                'input_tokens' => $response->json('usageMetadata.promptTokenCount') ?? 0,
                'output_tokens' => $response->json('usageMetadata.candidatesTokenCount') ?? 0,
            ],
        ];
    }
}
