<?php
include 'class.php';

function reply($content_type, $message) {
    
    $msgtokens = array(
        "今天課表" => 0,
        "明天課表" => 1,
        "後天課表" => 2
    );

    global $header, $from, $receive;
        
       $url = "https://api.line.me/v2/bot/message/push";
       
       $data = ["to" => $from, "messages" => array(["type" => "text", "text" => $message])];

        if (!array_key_exists($message, $msgtokens)) {
            error_log("[classbot][NC] " . $message);
            return;
        }

       switch($content_type) {
       
           case "text" :
            error_log("[classbot][TEXT] command: " . $message);
               $content_type = "文字訊息";
               $today = new Datetime();
               $diff = $msgtokens[$message];
               if ( $diff > 0 )
                    $today = $today->modify('+' . $diff .' day');
               if (is_holiday($today)) {
                    $replymsg = mb_substr($message, 0, 2, "UTF-8") . "不用上課喔！！";
                    $data = [
                       "to" => $from, 
                       "messages" => array(
                           ["type" => "text", "text" => $replymsg]
                           )
                        ];
               }
               else {
                    list($l1, $l2) = generate_class($today);
                    $data = [
                       "to" => $from, 
                       "messages" => array(
                           ["type" => "image", "originalContentUrl" => $l1, "previewImageUrl" => $l1],
                           ["type" => "image", "originalContentUrl" => $l2, "previewImageUrl" => $l2]
                           )
                        ];
               }
               break;
           
               /*
           case "image" :
               $content_type = "圖片訊息";
               $message = getObjContent("jpeg");   // 讀取圖片內容
               $data = ["to" => $from, "messages" => array(["type" => "image", "originalContentUrl" => $message, "previewImageUrl" => $message])];
               break;
               
           case "video" :
               $content_type = "影片訊息";
               $message = getObjContent("mp4");   // 讀取影片內容
               $data = ["to" => $from, "messages" => array(["type" => "video", "originalContentUrl" => $message, "previewImageUrl" => $message])];
               break;
               
           case "audio" :
               $content_type = "語音訊息";
               $message = getObjContent("mp3");   // 讀取聲音內容
               $data = ["to" => $from, "messages" => array(["type" => "audio", "originalContentUrl" => $message[0], "duration" => $message[1]])];
               break;
               
           case "location" :
               $content_type = "位置訊息";
               $title = $receive->events[0]->message->title;
               $address = $receive->events[0]->message->address;
               $latitude = $receive->events[0]->message->latitude;
               $longitude = $receive->events[0]->message->longitude;
               $data = ["to" => $from, "messages" => array(["type" => "location", "title" => $title, "address" => $address, "latitude" => $latitude, "longitude" => $longitude])];
               break;
               
           case "sticker" :
               $content_type = "貼圖訊息";
               $packageId = $receive->events[0]->message->packageId;
               $stickerId = $receive->events[0]->message->stickerId;
               $data = ["to" => $from, "messages" => array(["type" => "sticker", "packageId" => $packageId, "stickerId" => $stickerId])];
               break;
               */
               
           default:
               $content_type = "未知訊息";
               break;
          }
       
       $context = stream_context_create(array(
       "http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
       ));
       file_get_contents($url, false, $context);
   }
?>