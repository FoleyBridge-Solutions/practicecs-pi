<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Exceptions\ConnectionException;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException as HttpConnectionException;

/**
 * HTTP client for the PracticeCS API microservice.
 *
 * Handles authentication via API key, retry logic with exponential backoff,
 * and rate limiting. This is a thin HTTP client — no caching of domain data.
 */
class ApiClient
{
    /**
     * The base URL of the PracticeCS API microservice.
     */
    protected string $baseUrl;

    /**
     * The API key for authentication.
     */
    protected string $apiKey;

    /**
     * Create a new API client instance.
     *
     * @param string $baseUrl Base URL of the PracticeCS API
     * @param string $apiKey API key for authentication
     * @throws PracticeCsException If required credentials are missing when enabled
     */
    public function __construct(string $baseUrl, string $apiKey)
    {
        if (config('practicecs.enabled', false)) {
            if (empty($apiKey)) {
                throw new PracticeCsException(
                    'PracticeCS API key is required. Set PRACTICECS_API_KEY in your .env file.'
                );
            }

            if (empty($baseUrl)) {
                throw new PracticeCsException(
                    'PracticeCS API base URL is required. Set PRACTICECS_API_BASE_URL in your .env file.'
                );
            }
        }

        $this->baseUrl = rtrim($baseUrl ?: 'http://localhost:8001', '/');
        $this->apiKey = $apiKey;
    }

    /**
     * Make a GET request to the API.
     *
     * @param string $endpoint API endpoint path
     * @param array $query Query string parameters
     * @return array Decoded JSON response
     * @throws PracticeCsException
     */
    public function get(string $endpoint, array $query = []): array
    {
        return $this->requestWithRetry('GET', $endpoint, [], $query);
    }

    /**
     * Make a POST request to the API.
     *
     * @param string $endpoint API endpoint path
     * @param array $data Request body data
     * @return array Decoded JSON response
     * @throws PracticeCsException
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->requestWithRetry('POST', $endpoint, $data);
    }

    /**
     * Make a PUT request to the API.
     *
     * @param string $endpoint API endpoint path
     * @param array $data Request body data
     * @return array Decoded JSON response
     * @throws PracticeCsException
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->requestWithRetry('PUT', $endpoint, $data);
    }

    /**
     * Make a PATCH request to the API.
     *
     * @param string $endpoint API endpoint path
     * @param array $data Request body data
     * @return array Decoded JSON response
     * @throws PracticeCsException
     */
    public function patch(string $endpoint, array $data = []): array
    {
        return $this->requestWithRetry('PATCH', $endpoint, $data);
    }

    /**
     * Make a DELETE request to the API.
     *
     * @param string $endpoint API endpoint path
     * @return array Decoded JSON response
     * @throws PracticeCsException
     */
    public function delete(string $endpoint): array
    {
        return $this->requestWithRetry('DELETE', $endpoint);
    }

    /**
     * Test the API connection by calling the health check endpoint.
     *
     * @return bool True if the API is reachable
     */
    public function testConnection(): bool
    {
        try {
            $this->get('/api/health');
            return true;
        } catch (\Exception $e) {
            Log::error('PracticeCS API connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Make a request with retry logic for transient failures.
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint path
     * @param array $data Request body data
     * @param array $query Query string parameters
     * @return array Decoded JSON response
     * @throws PracticeCsException
     */
    protected function requestWithRetry(string $method, string $endpoint, array $data = [], array $query = []): array
    {
        if (!config('practicecs.enabled', false)) {
            throw new ConnectionException(
                'PracticeCS API integration is disabled. Set PRACTICECS_ENABLED=true in your .env file.'
            );
        }

        $maxAttempts = (int) config('practicecs.retry.max_attempts', 3);
        $baseDelayMs = (int) config('practicecs.retry.base_delay_ms', 100);
        $multiplier = (float) config('practicecs.retry.multiplier', 2);

        $attempts = 0;
        $lastException = null;

        while ($attempts < $maxAttempts) {
            try {
                $this->checkRateLimit();
                return $this->request($method, $endpoint, $data, $query);
            } catch (HttpConnectionException $e) {
                $attempts++;

                Log::warning('PracticeCS API connection error', [
                    'attempt' => $attempts,
                    'max_attempts' => $maxAttempts,
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage(),
                ]);

                if ($attempts >= $maxAttempts) {
                    throw ConnectionException::failed(
                        $this->baseUrl . $endpoint,
                        $e->getMessage()
                    );
                }

                $sleepMs = $baseDelayMs * pow($multiplier, $attempts - 1);
                usleep((int) ($sleepMs * 1000));
            } catch (PracticeCsException $e) {
                $lastException = $e;
                $attempts++;

                if (!$this->isRetryable($e) || $attempts >= $maxAttempts) {
                    throw $e;
                }

                $sleepMs = $baseDelayMs * pow($multiplier, $attempts - 1);
                usleep((int) ($sleepMs * 1000));

                Log::warning('PracticeCS API retry', [
                    'attempt' => $attempts,
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        throw $lastException ?? new PracticeCsException('Request failed after retries');
    }

    /**
     * Check if the exception is retryable (server errors, rate limits).
     *
     * @param PracticeCsException $e The exception to check
     * @return bool True if the request should be retried
     */
    protected function isRetryable(PracticeCsException $e): bool
    {
        $statusCode = $e->getStatusCode();

        return $statusCode !== null && ($statusCode >= 500 || $statusCode === 429);
    }

    /**
     * Check rate limit before making a request.
     *
     * @return void
     * @throws PracticeCsException If rate limit is exceeded
     */
    protected function checkRateLimit(): void
    {
        if (!config('practicecs.rate_limit.enabled', true)) {
            return;
        }

        $maxRequests = (int) config('practicecs.rate_limit.max_requests', 1000);
        $perSeconds = (int) config('practicecs.rate_limit.per_seconds', 3600);
        $timeBucket = (int) floor(time() / $perSeconds);
        $cacheKey = 'practicecs_rate_limit_' . $perSeconds . '_' . $timeBucket;

        Cache::add($cacheKey, 0, $perSeconds);
        $currentCount = Cache::increment($cacheKey);

        if ($currentCount > $maxRequests) {
            throw new PracticeCsException(
                "PracticeCS API rate limit exceeded. Maximum {$maxRequests} requests per {$perSeconds} seconds.",
                0,
                null,
                429
            );
        }
    }

    /**
     * Make a single HTTP request to the API.
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint path
     * @param array $data Request body data
     * @param array $query Query string parameters
     * @return array Decoded JSON response
     * @throws PracticeCsException
     */
    protected function request(string $method, string $endpoint, array $data = [], array $query = []): array
    {
        $connectTimeout = (int) config('practicecs.timeout.connect', 5);
        $requestTimeout = (int) config('practicecs.timeout.request', 30);

        $request = Http::connectTimeout($connectTimeout)
            ->timeout($requestTimeout)
            ->withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
            ]);

        if (!empty($query)) {
            $request = $request->withQueryParameters($query);
        }

        $url = "{$this->baseUrl}{$endpoint}";

        $response = match ($method) {
            'GET' => $request->get($url),
            'POST' => $request->post($url, $data),
            'PUT' => $request->put($url, $data),
            'PATCH' => $request->patch($url, $data),
            'DELETE' => $request->delete($url),
            default => throw new PracticeCsException("Unsupported HTTP method: {$method}"),
        };

        $result = $response->json() ?? [];

        if (!$response->successful()) {
            $message = $result['message'] ?? $result['error'] ?? $response->body();
            Log::error('PracticeCS API request failed', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'message' => $message,
            ]);
            throw new PracticeCsException(
                "PracticeCS API error: {$message}",
                0,
                null,
                $response->status(),
                $result
            );
        }

        return $result;
    }
}
