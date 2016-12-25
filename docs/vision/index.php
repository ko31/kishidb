<?php
    // APIキー
    $api_key = "AIzaSyAtslO_IFvo3f6CEZktmoEuBjhlC67FQ1g";

    // 画像パス
    $image_path = "./235.jpg";

    // リクエスト用のJSONを作成
    $json = json_encode( array(
        "requests" => array(
            array(
                "image" => array(
                    "content" => base64_encode(file_get_contents($image_path)),
                ) ,
                "features" => array(
                    array(
                        "type" => "FACE_DETECTION",
                        "maxResults" => 1,
                    ) ,
                ) ,
            ) ,
        ) ,
    ) ) ;

    // APIリクエスト
    $curl = curl_init() ;
    curl_setopt($curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=" . $api_key ) ;
    curl_setopt($curl, CURLOPT_HEADER, true); 
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
    $res1 = curl_exec($curl);
    $res2 = curl_getinfo($curl);
    curl_close($curl);

    // レスポンス取得
    $header = substr($res1, 0, $res2["header_size"]);
    if (strpos($header, "200 OK") === false) {
        exit("Request failed.");
    }
    $json = substr($res1, $res2["header_size"]);
    $result = json_decode($json);

    // 顔座標取得
    $vertices = $result->responses[0]->faceAnnotations[0]->boundingPoly->vertices;
    $left = $vertices[0]->x;
    $top = $vertices[0]->y;
    $width = $vertices[1]->x - $vertices[0]->x;
    $height = $vertices[2]->y - $vertices[0]->y;

    // 感情取得
    $joy = $result->responses[0]->faceAnnotations[0]->joyLikelihood;
    $sorrow = $result->responses[0]->faceAnnotations[0]->sorrowLikelihood;
    $anger = $result->responses[0]->faceAnnotations[0]->angerLikelihood;
    $surprise = $result->responses[0]->faceAnnotations[0]->surpriseLikelihood;
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Cloud Vision APIテスト</title>
<style>
.facial-image-wrapper {
    position:relative;
    margin:0 auto;
}
.face-image-border {
    position: absolute;
    border: 3px solid #2196F3;
}
</style>
</head>
<body>
<figure class="facial-image-wrapper">
<img src="<?php echo $image_path;?>">
<div class="face-image-border" style="left: <?php echo $left;?>px; top:<?php echo $top;?>px; width:<?php echo $width;?>px; height:<?php echo $height;?>px;"></div>
</figure>
<table>
<tbody>
<tr>
<th></th>
<th></th>
</tr>
<tr>
<td>楽しさ</td>
<td><?php echo $joy;?></td>
</tr>
<tr>
<td>悲しさ</td>
<td><?php echo $sorrow;?></td>
</tr>
<tr>
<td>怒り</td>
<td><?php echo $anger;?></td>
</tr>
<tr>
<td>驚き</td>
<td><?php echo $surprise;?></td>
</tr>
</tbody>
</table>
</body>
</html>
