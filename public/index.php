<?php
include_once 'site/header.php';

require_once __DIR__ . "/../vendor/autoload.php";
define('STORAGE_DIR', __DIR__ . '/../datas');


if (isset($_GET['submit'])) {

    $username = $_GET['username'] ?? null;

    if(!empty($username)) {


        $client = new GuzzleHttp\Client();
        $fullUrl = "https://www.instagram.com/$username/?__a=1";
        $response = $client->get($fullUrl);
        $contents = $response->getBody()->getContents();
        $data = json_decode($contents);



        function saveUserData($username, $data)
        {

            if (!is_dir(STORAGE_DIR)) {
                mkdir(STORAGE_DIR, 0775, true);
            }

            $realFullFilePath = getFullFilePath($username);

            $encodedData = json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            file_put_contents($realFullFilePath, $encodedData);

            return $realFullFilePath;
        }

        saveUserData($username, $data);

        function getUserData($username)
        {
            $fullFilePath = getFullFilePath($username);

            if (!is_file($fullFilePath)) {
                return 0;
            }

            $encodedData = file_get_contents($fullFilePath);

            return json_decode($encodedData);
        }


        function getFullFilePath(string $username): string
        {
            $fullFilePath = STORAGE_DIR . "/$username.json";
            return realpath($fullFilePath);
        }
    }
}


?>
<div class="jumbotron jumbotron-fluid">
  <div class="container">
  <form method="get">
  <div class="form-group">
    <label for="username">Instagram username</label>
    <input id="username" type="text" class="form-control" aria-describedby="username" name="username">

  </div>

  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>
  </div>
</div>


<?php
    include_once 'site/footer.php';
