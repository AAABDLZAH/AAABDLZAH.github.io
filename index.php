<?php
$apikey = 'e5768785c04b5a39955fc76bcf0ba78f';
$weather = null;
$forecast = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['city'])) {
    $city = htmlspecialchars($_POST['city']);
    $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apikey}&units=metric";
    $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$apikey}&units=metric";

    $weatherResponse = @file_get_contents($weatherUrl);
    $forecastResponse = @file_get_contents($forecastUrl);

    if ($weatherResponse !== FALSE && $forecastResponse !== FALSE) {
        $weather = json_decode($weatherResponse, true);
        $forecast = json_decode($forecastResponse, true);
    } else {
        $error = "City not found or API error.";
    }
}

function getWeatherIcon($id) {
    if ($id <= 232) return 'thunderstorm.svg';
    if ($id <= 321) return 'drizzle.svg';
    if ($id <= 531) return 'rain.svg';
    if ($id <= 622) return 'snow.svg';
    if ($id <= 781) return 'atmosphere.svg';
    if ($id == 800) return 'clear.svg';
    return 'clouds.svg';
}
function formatDate($date) {
    return date("d M", strtotime($date));
}
function currentDate() {
    return date("D, d M");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weather App</title>
    <link rel="stylesheet" href="cc.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

</head>
<body>
    <main class="main-container">
        <header class="input-container">
            <form method="POST" action="">
                <input type="text" name="city" class="city-input" placeholder="Search city" required>
                <button type="submit" class="search-btn"><span class="material-symbols-outlined">
            search
            </span></button>
            </form>
        </header>

        <?php if ($error): ?>
            <section class="not-found section-message">
                <img src="assets/message/not-found.png">
                <div>
                    <h1>City Not Found</h1>
                    <h4 class="regular-txt"><?= $error ?></h4>
                </div>
            </section>
<!-- affich -->
        <?php elseif ($weather): ?>
            <section class="weather-info">
                <div class="location-date-container">
                    <div class="location">
                    <span class="material-symbols-outlined">
                location_on
                </span><h4 class="country-txt"><?= $weather['name'] ?></h4>
                    </div>
                    <h5 class="current-date-txt regular-txt"><?= currentDate() ?></h5>
                </div>
                <div class="weather-summmary-container">
                    <img src="assets/weather/<?= getWeatherIcon($weather['weather'][0]['id']) ?>" class="weather-summary-img">
                    <div class="weather-summary-info">
                        <h1 class="temp-txt"><?= round($weather['main']['temp']) ?>°C</h1>
                        <h3 class="condition-txt regular-txt"><?= $weather['weather'][0]['main'] ?></h3>
                    </div>
                </div>
                <div class="weather-conditions-container ">
                    <div class="condition-item">
                    <span class="material-symbols-outlined">
                    water_drop
                    </span>
                        <div class="condition-info">
                            <h5 class="regular-txt">Humidity</h5>
                            <h5 class="humidity-value-txt"><?= $weather['main']['humidity'] ?>%</h5>
                        </div>
                    </div>
                    <div class="condition-item">
                    <span class="material-symbols-outlined">
                air
                </span>
                        <div class="condition-info">
                            <h5 class="regular-txt">Wind</h5>
                            <h5 class="wind-value-txt"><?= $weather['wind']['speed'] ?> m/s</h5>
                        </div>
                    </div>
                </div>

                <div class="forecast-items-container">
                    <?php
                  $shownDates = [];
$today = date('Y-m-d'); 

foreach ($forecast['list'] as $item) {
    $dateKey = substr($item['dt_txt'], 0, 10);
    
    if ($dateKey == $today) continue;

    if (strpos($item['dt_txt'], '12:00:00') !== false && !in_array($dateKey, $shownDates)) {
        $shownDates[] = $dateKey;
        $icon = getWeatherIcon($item['weather'][0]['id']);
        $temp = round($item['main']['temp']);
        $dateFormatted = formatDate($item['dt_txt']);
        echo "<div class='forecast-item'>
                <h5 class='forecast-item-date regular-txt'>{$dateFormatted}</h5>
                <img src='assets/weather/{$icon}' class='forecast-item-img'>
                <h5 class='forecast-item-temp'>{$temp} °C</h5>
            </div>";
    }
}

                    ?>
                </div>
            </section>

        <?php else: ?>
            <section class="search-city section-message">
                <img src="assets/message/search-city.png">
                <div>
                    <h1>Search City</h1>
                    <h4 class="regular-txt">Find out the weather conditions of the city</h4>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
