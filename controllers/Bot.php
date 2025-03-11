<?php
declare(strict_types=1);

namespace Bot;

use GuzzleHttp\Client;
use Exception;
use QR_code_;
use Web\Converter;
use Interfaces\BotInterface; 

class Bot implements BotInterface{ 
  
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

    $called_query=(new QR_code_())->getQuery();

    match($this->text){
      '/start' => $this->handleStartCommand(),
      '/Text -> QR' => $this->prepareTextToQr(),
      '/QR -> Text' => $this->prepareQrToText(),
      default => $this->handleDefaultCommand($this->text, $called_query),
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
    } 
    catch(Exception $e){
        return $e->getMessage();
    }
  }
  public function handleStartCommand(){

    $text = "Assalomu alaykum $this->firstName";
    $text .= "\n\nBotimizga xush kelibsiz!";
      
    $this->http->post('sendMessage', [
      'form_params' => [
        'chat_id' => $this->chatId,
        'text'    => $text,
        'reply_markup' => json_encode([
          'keyboard' => [
            [['text' => '/Text -> QR'], 
            ['text' => '/QR -> Text']]
          ],
          'resize_keyboard' => true
        ]),
      ]
    ]); 
  }

  public function prepareTextToQr(){

    (new QR_code_())->setQuery('text2qr');

    $this->http->post('sendMessage', [
      'form_params' => [
        'chat_id' => $this->chatId,
        'text'    => 'Matn kiriting :',
      ]
    ]); 
  }

  public function prepareQrToText(){

    (new QR_code_())->setQuery('qr2text');

    $this->http->post('sendMessage', [
      'form_params' => [
        'chat_id' => $this->chatId,
        'text'    => 'QR rasmini yuklang :',
      ]
    ]); 
  }

  public function handleDefaultCommand($text, string $called_query){

    if ($called_query == 'text2qr') {
      $this->http->post('sendPhoto', [
        'multipart' => [
          [
            'name'=>'chat_id',
            'contents' => $this->chatId
          ],
          [
            'name'=>'photo',
            'contents' => fopen((new Converter())->text2qr($text), 'r')
          ],
          [
            'name'=>'reply_markup',
            'contents' => json_encode([
              'keyboard' => [
                [['text' => '/Text -> QR'], 
                ['text' => '/QR -> Text']]
              ],
              'resize_keyboard' => true
            ])
          ]
        ]
      ]); 
    } 
    else if($called_query == 'qr2text') {
      $this->http->post('sendMessage', [
        'form_params' => [
          'chat_id' => $this->chatId,
          'text'    => (new Converter())->qr2txt($text),
          'reply_markup' => json_encode([
            'keyboard' => [
              [['text' => '/Text -> QR'], 
              ['text' => '/QR -> Text']]
            ],
            'resize_keyboard' => true
          ]),
        ]
      ]); 
    }
    else {
      $this->http->post('sendMessage', [
        'form_params' => [
          'chat_id' => $this->chatId,
          'text'    => 'Noto\'g\'ri buyruq! Oldin amalni tanlang !:',
          'reply_markup' => json_encode([
            'keyboard' => [
              [['text' => '/Text -> QR'], 
              ['text' => '/QR -> Text']]
            ],
            'resize_keyboard' => true
          ]),
        ]
      ]); 
    } 
  } 
}

?>
