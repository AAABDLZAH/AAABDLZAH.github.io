<?php
$apiKey = 'e5768785c04b5a39955fc76bcf0ba78f';

function getWeatherData($city, $endpoint) {
    global $apiKey;
    $apiUrl = "http://api.openweathermap.org/data/2.5/{$endpoint}?q={$city}&appid={$apiKey}&units=metric";
    $response = file_get_contents($apiUrl);
    return json_decode($response, true);
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

function getCurrentDate() {
    return date('D, d M');
}

// Traitement du formulaire
$weatherInfo = null;
$forecastInfo = [];
$error = null;
$showSearch = true;
$showNotFound = false;
$showWeather = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['city'])) {
    $city = trim($_POST['city']);
    
    // Récupérer les données météo actuelles
    $weatherData = getWeatherData($city, 'weather');
    
    if ($weatherData['cod'] != 200) {
        $showNotFound = true;
        $showSearch = false;
        $error = "Ville non trouvée";
    } else {
        $showWeather = true;
        $showSearch = false;
        
        // Formater les données météo actuelles
        $weatherInfo = [
            'country' => $weatherData['name'],
            'temp' => round($weatherData['main']['temp']),
            'condition' => $weatherData['weather'][0]['main'],
            'humidity' => $weatherData['main']['humidity'],
            'wind' => $weatherData['wind']['speed'],
            'date' => getCurrentDate(),
            'icon' => getWeatherIcon($weatherData['weather'][0]['id'])
        ];
        
        // Récupérer les prévisions
        $forecastData = getWeatherData($city, 'forecast');
        $timeTaken = '12:00:00';
        $todayDate = date('Y-m-d');
        
        foreach ($forecastData['list'] as $forecast) {
            if (strpos($forecast['dt_txt'], $timeTaken) !== false && 
                strpos($forecast['dt_txt'], $todayDate) === false) {
                $date = new DateTime($forecast['dt_txt']);
                $formattedDate = $date->format('d M');
                
                $forecastInfo[] = [
                    'date' => $formattedDate,
                    'temp' => round($forecast['main']['temp']),
                    'icon' => getWeatherIcon($forecast['weather'][0]['id'])
                ];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather app</title>
    <link rel="stylesheet" href="cc.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body>
    <main class="main-container">
        <form method="POST" class="input-container">
            <input type="text" name="city" class="city-input" placeholder="search city">
            <button type="submit" class="search-btn">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        
        <?php if ($showWeather && $weatherInfo): ?>
        <section class="weather-info">
            <div class="location-date-container">
                <div class="location">
                    <span class="material-symbols-outlined">location_on</span>
                    <h4 class="country-txt"><?= htmlspecialchars($weatherInfo['country']) ?></h4>
                </div>
                <h5 class="current-date-txt regular-txt"><?= $weatherInfo['date'] ?></h5>
            </div>
            <div class="weather-summmary-container">
                <img src="assets/weather/<?= $weatherInfo['icon'] ?>" class="weather-summary-img">
                <div class="weather-summary-info">
                    <h1 class="temp-txt"><?= $weatherInfo['temp'] ?> °C</h1>
                    <h3 class="condition-txt regular-txt"><?= $weatherInfo['condition'] ?></h3>
                </div>
            </div>
            <div class="weather-conditions-container">
                <div class="condition-item">
                    <span class="material-symbols-outlined">water_drop</span>
                    <div class="condition-info">
                        <h5 class="regular-txt">Humidity</h5>
                        <h5 class="humidity-value-txt"><?= $weatherInfo['humidity'] ?>%</h5>
                    </div>
                </div>
                <div class="condition-item">
                    <span class="material-symbols-outlined">air</span>
                    <div class="condition-info">
                        <h5 class="regular-txt">wind speed</h5>
                        <h5 class="wind-value-txt"><?= $weatherInfo['wind'] ?> m/s</h5>
                    </div>
                </div>
            </div>
            <div class="forecast-items-container">
                <?php foreach ($forecastInfo as $forecast): ?>
                <div class="forecast-item">
                    <h5 class="forecast-item-date regular-txt"><?= $forecast['date'] ?></h5>
                    <img src="assets/weather/<?= $forecast['icon'] ?>" class="forecast-item-img">
                    <h5 class="forecast-item-temp"><?= $forecast['temp'] ?> °C</h5>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <?php if ($showSearch): ?>
        <section class="search-city section-message">
            <img src="assets/message/search-city.png">
            <div>
                <h1>Search City</h1>
                <h4 class="regular-txt">find out the weather conditions of the city</h4>
            </div>
        </section>
        <?php endif; ?>
        
        <?php if ($showNotFound): ?>
        <section class="not-found section-message">
            <img src="assets/message/not-found.png">
            <div>
                <h1>City Not Found</h1>
                <h4 class="regular-txt">Please try another city name</h4>
            </div>
        </section>
        <?php endif; ?>
    </main>
</body>
</html>
