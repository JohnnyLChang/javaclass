<?php
    include 'bot_reply.php';

    $channel_id = "1533630624";
	$channel_secret = "c2bbb12db947ce68ccda8bddf3d38535";
	$channel_access_token = "A4JjzafM/45VLpK4QgEFqWkCuT34Uj4FT2isLC5pPeR+DD1u1gaWAqtdGg9hivP3ndkN37GhwqRwHlZJLdwQaQLrnWihQamZXGFrMZPtdEyifQGo/EJRq+V2SlRnqeaGbF/D7OW4nqdX1LXGJ7lG3QdB04t89/1O/w1cDnyilFU=";

	$myURL = "https://javaclass.herokuapp.com/linebot/classbot.php";

	// 將收到的資料整理至變數
	$receive = json_decode(file_get_contents("php://input"));
	
	// 讀取收到的訊息內容
	$text = $receive->events[0]->message->text;
	
	// 讀取訊息來源的類型 	[user, group, room]
	$type = $receive->events[0]->source->type;
	
	if ($type == "room")
	{
		// 多人聊天 讀取房間id
		$from = $receive->events[0]->source->roomId;
	} 
	else if ($type == "group")
	{
		// 群組 讀取群組id
		$from = $receive->events[0]->source->groupId;
	}
	else
	{
		// 一對一聊天 讀取使用者id
		$from = $receive->events[0]->source->userId;
	}
	
	// 讀取訊息的型態 [Text, Image, Video, Audio, Location, Sticker]
	$content_type = $receive->events[0]->message->type;
	
	// 準備Post回Line伺服器的資料 
	$header = ["Content-Type: application/json", "Authorization: Bearer {" . $channel_access_token . "}"];
	
	// 回覆訊息
    reply($content_type, $text);
?>