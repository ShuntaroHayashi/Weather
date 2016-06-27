<?php
$result;
if (isset($_GET["citycode"])) {
  $citycode = $_GET["citycode"];
  if ($citycode==="") {
    header("Location: index.php");
  } else {
    $base_url = 'http://weather.livedoor.com/forecast/webservice/json/v1';
    $query = ['city'=>$citycode];
    $proxy = array(
      "http" => array(
       "proxy" => "tcp://proxy.kmt.neec.ac.jp:8080",
       'request_fulluri' => true,
      ),
    );
    $proxy_context = stream_context_create($proxy);
    $response = file_get_contents($base_url.'?'.http_build_query($query),false,$proxy_context);
    $result = json_decode($response,true);
  }
}
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>天気予報</title>
</head>
<body>
  <a href="http://weather.livedoor.com/forecast/rss/primary_area.xml">地域とIDの定義表</a>
  <form action="index.php" method="get">
    ID:<input type="text" name="citycode"></input>
    <button type="submit">検索</button>
  </form>
  <hr>
  <?php if( isset($result)){?>
    <?= $result["location"]["city"] ?>
    <br>
    <?php
      for($i=0;$i < count($result["forecasts"]);$i++){
    ?>
        <?= $result["forecasts"][$i]["date"]?>
        <img src=<?=$result["forecasts"][$i]["image"]["url"]?>>
        <?=$result["forecasts"][$i]["telop"]?>
    <?php
      echo '<br>';
      }
      ?>
      <a href=<?=$result["link"]?>>詳細</a>
    <?php
    }
    ?>

  </body>
</html>
