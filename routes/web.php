<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    $response = Http::get('https://catfact.ninja/fact');
    $data = $response->json();

    // Get the first three words of the fact
    $fact = $data['fact'];
    $words = explode(' ', $fact);
    $firstThreeWords = array_slice($words, 0, 3);
    $result = implode(' ', $firstThreeWords);

    $apiKey = env('GIPHY_API_KEY');
    $response = Http::get("https://api.giphy.com/v1/gifs/search", [
        'api_key' => $apiKey,
        'q' => $result,
        'limit' => 1
    ]);
    $data2 = $response->json();
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .gif-container {
            margin-top: 20px;
        }
        .fact {
            font-size: 20px;
            color: #333;
            margin-top: 20px;
        }
    </style>
    <?php
    if (!empty($data2['data'])) {
        $gifUrl = $data2['data'][0]['images']['original']['url'];
        echo "<div class='gif-container'><img src='{$gifUrl}' alt='GIF'></div>";
        echo "<div class='fact'>{$data['fact']}</div>";
    } else {
        echo "<div class='fact'>No GIF found.</div>";
    }
});
?>