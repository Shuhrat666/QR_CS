<?php
declare(strict_types=1);

namespace Bot;

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Exception;

class Bot {
  public  string $text;
  public  int    $chatId;
  public  string $firstName;

  private string $api;
  private        $http;

  public function __construct(string $token){
    $this->api  = "https://api.telegram.org/bot$token/"; 
    $this->http = new Client(['base_uri' => $this->api]);
  }

  public function handle(string $update){
    $update = json_decode($update);

    $this->text      = $update->message->text;
    $this->chatId    = $update->message->chat->id;
    $this->firstName = $update->message->chat->first_name;

    match($this->text){
      '/start' => $this->handleStartCommand(),
        
    };

  }

  public function setWebhook(string $url): string {
    try{
      $response = $this->http->post('setWebhook', [
        'form_params' => [
          'url'                  => $url,
          'drop_pending_updates' => true,
        ]
      ]);
    
      $response = json_decode($response->getBody()->getContents());
        
      return $response->description;
      } catch(Exception $e){
        return $e->getMessage();
      }
    }
  public function handleStartCommand(){

    $text = "Assalomu alaykum $this->firstName";
    $text .= "\n\nBotimizga xush kelibsiz!";
      
    $this->http->post('sendMessage', [
      'form_params' => [
        'chat_id' => $this->chatId,
        'text'    => $text
      ]
    ]); 
  }
    
}

?>