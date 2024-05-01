<?php

namespace App\Http\Controllers\API;

use App\Services\UserTransactionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController
{
    public function __construct(private UserTransactionsService $transactionsService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->transactionsService->getAllTransactions($request->all());

            return response()->json([
                'data' => $data
            ]);

        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
        catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json([
                'message' => 'something went wrong'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
