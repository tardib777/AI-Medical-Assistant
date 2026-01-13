<?php

namespace App\Http\Controllers;

use App\Models\MedicalSession;
use AppendIterator;
use Illuminate\Http\Request;
use App\Services\ChatService;
use App\Services\TextCleanService;
use Illuminate\Support\Facades\Http;
class ChatController extends Controller
{
    protected $chatService;
    protected $textCleanService;
    public function __construct(ChatService $chatService,TextCleanService $textCleanService){
        $this->chatService=$chatService;
        $this->textCleanService=$textCleanService;
    }
    public function chat(Request $request){
        $request->validate([
            'prompt' => 'required|string|min:1'
        ]);
        $session=$request->user()->Sessions()->where('status','active')->first();
        $response=$this->chatService->chat($session->messages()->select('role', 'content')->get()->toArray(),$request->prompt,$session->id);
        $response=$this->textCleanService->clean($response["content"]);
        return $response;
    }

    
}
