<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Chat;

class ChatGptDestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Chat $chat): RedirectResponse
    {
        $chat->delete();
        return to_route('chat.show');
    }
}
