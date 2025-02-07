<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use OpenAI\Laravel\Facades\OpenAI;
use Inertia\Inertia;
use App\Models\Chat;
use Auth;

class ChatGptStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreChatRequest $request, string $id = null)
    {

        $messages = [];
        if($id){
            $chat = Chat::findOrFail($id);
            $messages = $chat->context;
        }
        $messages[] = ['role' => 'user', 'content' => $request->input('promt')];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo', 
            'messages' => $messages
        ]);

        $messages[] = ['role' => 'assistant', 'content' => $response->choices[0]->message->content];
        $chat = Chat::updateOrCreate([
            'id' => $id,
            'user_id' => Auth::id()
        ],
        [
            'context' => $messages
        ]);
        
        return redirect()->route('chat.show', [$chat->id]);
    }
}
