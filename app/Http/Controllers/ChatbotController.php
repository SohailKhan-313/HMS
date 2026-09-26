<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatbotRequest;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ChatbotService $chatbotService
    ) {}

    /**
     * Handle incoming chatbot message from the user.
     */
    public function handle(ChatbotRequest $request): JsonResponse
    {
        $userMessage = $request->validated('message');

        $result = $this->chatbotService->respond($userMessage);

        return response()->json([
            'status' => 'success',
            'reply' => $result['reply'],
            'suggestions' => $result['suggestions'] ?? [],
        ]);
    }
}
