<?php

namespace App\Services;

use App\Models\MedicalSession;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
class ChatService
{
    private string $baseUrl = "https://ollama.com/api/"; 
    private string $model = "gemma3:27b-cloud";

public function chat($messages,string $input,$session_id){
    $messages[] = [
        'role' => 'user',
        'content' => $input,
    ];
    $response=Http::withHeaders(["Authorization" => "Bearer ".config('services.ollama.key'),"Content-Type" => "application/json"])->post($this->baseUrl."chat",[
        "model" => $this->model,
        "messages" => $messages,
        "stream" => false
    ]);
    $res=json_decode($response,true);
    Message::create(["medical_session_id" => $session_id,"role" => "user","content" => $input]);
    Message::create(["medical_session_id" => $session_id,"role" =>  $res["message"]["role"],"content" =>  $res["message"]["content"]]);

    return $res["message"];
}   
 
} 
