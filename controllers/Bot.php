<?php
declare(strict_types=1);

namespace Bot;

use GuzzleHttp\Client;
use Exception;
use Interfaces\BotInterface;
use QR_code\QR_code;
use Web\Converter;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Bot implements BotInterface{ 
  
  public  string $text;
  public  int    $chatId;
  public  string $firstName;
  public  array  $photo;

  private string $api;
  private        $http;

  public function __construct(string $token){
    $this->api  = "https://api.telegram.org/bot$token/"; 
    $this->http = new Client(['base_uri' => $this->api]);
  }

  public function handle(string $update){
    $update = json_decode($update);

    $this->text = $update->message->text ?? 'No text entered !';
    $this->photo = $update->message->photo ?? [];
    $this->chatId    = $update->message->chat->id;
    $this->firstName = $update->message->chat->first_name;
  

    $called_query=(new QR_code())->getQuery();

    match($this->text){
      '/start' => $this->handleStartCommand(),
      '/Text -> QR' => $this->prepareTextToQr(),
      '/QR -> Text' => $this->prepareQrToText(),
      default => $this->handleDefaultCommand($this->text, $this->photo, $called_query),
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

    (new QR_code())->setQuery('text2qr');

    $this->http->post('sendMessage', [
      'form_params' => [
        'chat_id' => $this->chatId,
        'text'    => 'Matn kiriting :',
      ]
    ]); 
  }

  public function prepareQrToText(){

    (new QR_code())->setQuery('qr2text');

    $this->http->post('sendMessage', [
      'form_params' => [
        'chat_id' => $this->chatId,
        'text'    => 'QR rasmini yuklang :',
      ]
    ]); 
  }

  public function resolveTelegramFilePath($fileId, $token){

    if (!is_writable(__DIR__ . '/../qr_codes/')) {
        mkdir(__DIR__ . '/../qr_codes/', 0777, true);
    }

    $response = $this->http->get("getFile", [
        'query' => ['file_id' => $fileId]
    ]);
    $data = json_decode($response->getBody(), true);

    if (!isset($data['result']['file_path'])) {
        throw new Exception("Unable to resolve file path from Telegram API.");
    }

    $fileUrl = "https://api.telegram.org/file/bot" .$token. "/" . $data['result']['file_path'];

    $localPath = __DIR__ . '/../qr_codes/' . basename($data['result']['file_path']);
    file_put_contents($localPath, file_get_contents($fileUrl));
    return $localPath;
  }


  public function handleDefaultCommand(string|int $text, array $photo, string $called_query){

    if ($called_query == 'text2qr') {
      $this->http->post('sendPhoto', [
        'multipart' => [
          [
            'name'=>'chat_id',
            'contents' => $this->chatId
          ],
          [
            'name'=>'photo',
            'contents' => fopen((new Converter())->text2qr($text), 'r'),
            'caption' => 'QR matni: ' . $text
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

      if (!empty($this->photo) && isset($this->photo[0]->file_id)) {

        $fileId = $this->photo[0]->file_id;
        $filePath = $this->resolveTelegramFilePath($fileId, $_ENV["TOKEN"]);
        $decodedText = (new Converter())->qr2txt($filePath);
      } 
      else {
        $decodedText = "No QR uploaded!";
      }

    $this->http->post('sendMessage', [
        'multipart' => [
            [
                'name' => 'chat_id',
                'contents' => $this->chatId,
            ],
            [
                'name' => 'text',
                'contents' => $decodedText,
            ],
            [
                'name' => 'reply_markup',
                'contents' => json_encode([
                    'keyboard' => [
                        [['text' => '/Text -> QR'], ['text' => '/QR -> Text']],
                    ],
                    'resize_keyboard' => true,
                ]),
            ],
        ],
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
