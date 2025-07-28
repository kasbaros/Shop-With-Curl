<?php

    namespace App\Http\Controllers;

    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller as BaseController;

    abstract class Controller extends BaseController
    {
        use AuthorizesRequests, ValidatesRequests;

        /**
         * Return a success JSON response.
         */
        protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
        {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $code);
        }

        /**
         * Return an error JSON response.
         */
        protected function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
        {
            $response = [
                'success' => false,
                'message' => $message,
            ];

            if ($errors) {
                $response['errors'] = $errors;
            }

            return response()->json($response, $code);
        }

        /**
         * Return a validation error response.
         */
        protected function validationError($errors, string $message = 'Validation failed'): JsonResponse
        {
            return $this->error($message, 422, $errors);
        }

        /**
         * Return a not found response.
         */
        protected function notFound(string $message = 'Resource not found'): JsonResponse
        {
            return $this->error($message, 404);
        }

        /**
         * Return an unauthorized response.
         */
        protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
        {
            return $this->error($message, 401);
        }

        /**
         * Return a forbidden response.
         */
        protected function forbidden(string $message = 'Forbidden'): JsonResponse
        {
            return $this->error($message, 403);
        }

        /**
         * Return a paginated JSON response.
         */
        protected function paginated($data, string $message = 'Data retrieved successfully'): JsonResponse
        {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                    'has_more_pages' => $data->hasMorePages(),
                ],
            ]);
        }

        /**
         * Get pagination parameters from request.
         */
        protected function getPaginationParams(Request $request, int $defaultLimit = 15, int $maxLimit = 100): array
        {
            $limit = min(
                $request->get('limit', $defaultLimit),
                $maxLimit
            );

            return [
                'page' => $request->get('page', 1),
                'limit' => max($limit, 1),
            ];
        }

        /**
         * Get search and sorting parameters from request.
         */
        protected function getSearchParams(Request $request): array
        {
            return [
                'search' => $request->get('search'),
                'sort_by' => $request->get('sort_by', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];
        }

        /**
         * Check if request expects JSON response.
         */
        protected function expectsJson(Request $request): bool
        {
            return $request->expectsJson() || $request->is('api/*');
        }

        /**
         * Get the authenticated user.
         */
        protected function user()
        {
            return auth()->user();
        }

        /**
         * Check if user is authenticated.
         */
        protected function isAuthenticated(): bool
        {
            return auth()->check();
        }
    }
