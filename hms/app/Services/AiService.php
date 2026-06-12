<?php

namespace App\Services;

class AiService
{
    protected $baseUrl;
    protected $timeout;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:8000';
        $this->timeout = 30;
        $this->apiKey  = '';
    }

    public function analyzeECG(array $ecgData): array
    {
        return $this->sendRequest('/analyze/ecg', $ecgData);
    }

    public function analyzeXRay(string $imagePath, array $metadata = []): array
    {
        return $this->sendRequest('/analyze/xray', ['image_path' => $imagePath, 'metadata' => $metadata], true);
    }

    public function analyzeCT(string $imagePath, array $metadata = []): array
    {
        return $this->sendRequest('/analyze/ct', ['image_path' => $imagePath, 'metadata' => $metadata], true);
    }

    public function analyzeMRI(string $imagePath, array $metadata = []): array
    {
        return $this->sendRequest('/analyze/mri', ['image_path' => $imagePath, 'metadata' => $metadata], true);
    }

    public function analyzeLabResults(array $labResults): array
    {
        return $this->sendRequest('/analyze/lab', $labResults);
    }

    protected function sendRequest(string $endpoint, array $data, bool $isFileUpload = false): array
    {
        try {
            $client = \Config\Services::curlrequest(['base_uri' => $this->baseUrl, 'timeout' => $this->timeout]);
            $headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
            if ($this->apiKey) { $headers['Authorization'] = 'Bearer ' . $this->apiKey; }
            $response = $client->request('POST', $endpoint, ['headers' => $headers, 'json' => !$isFileUpload ? $data : null]);
            $body = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === 200 && isset($body['success']) && $body['success']) {
                return ['success' => true, 'data' => $body['data'] ?? [], 'message' => $body['message'] ?? 'Analysis completed'];
            }
            return ['success' => false, 'error' => $body['error'] ?? 'Unknown error'];
        } catch (\Exception $e) {
            log_message('error', 'AI Service Error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'AI service unavailable: ' . $e->getMessage()];
        }
    }

    public function healthCheck(): bool
    {
        try {
            $client = \Config\Services::curlrequest(['base_uri' => $this->baseUrl, 'timeout' => 5]);
            $response = $client->get('/health');
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) { return false; }
    }
}
