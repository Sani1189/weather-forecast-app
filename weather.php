<?php
date_default_timezone_set('UTC');
$api_key = "e6bab8c1acba11abbf5d2ad889b8c087";
$units = "metric"; 


if(isset($_GET['new_location'])) {
    if($_GET['new_location'] == ""){
        $data = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/forecast?q=Dhaka&appid=e6bab8c1acba11abbf5d2ad889b8c087&units=metric"), true);

    }
    else{
        $new_location = urlencode($_GET['new_location']);
        $url = "https://api.openweathermap.org/data/2.5/forecast?q=$new_location&appid=$api_key&units=$units";
        if (file_get_contents($url) == false){
            echo "<script>alert('Please enter a valid city name')</script>";
            $data = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/forecast?q=Dhaka&appid=e6bab8c1acba11abbf5d2ad889b8c087&units=metric"), true);
        }
        else{
            $data = json_decode(file_get_contents($url), true);
        }   
    }
}
else{
    $data = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/forecast?q=Dhaka&appid=e6bab8c1acba11abbf5d2ad889b8c087&units=metric"), true);
}

$cities = array("Paris,fr", "New York,us", "London,uk", "Rome,it", "Dhaka,bd");
$other_data = array();
foreach ($cities as $city) {
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$api_key&units=$units";
    $other_data[$city] = json_decode(file_get_contents($url), true);
}

function display_icon($icon) {
    return "<img src='https://openweathermap.org/img/w/$icon.png' alt='Weather icon'>";
}

function display_current_weather($data) {
    if ($data['city']['name'] == "Sāmāir"){
        $data['city']['name'] = "Dhaka";
    }
    echo "<div class='current-weather'>";
    echo "<div class='search-box'>
    <form action='' method='GET'>
        <input type='text' name='new_location' placeholder='Enter a city'>
        <button type='submit'>Search</button>
    </form>
</div>";
    
    $current_weather = $data['list'][0];
    echo "<img src='https://openweathermap.org/img/w/{$current_weather['weather'][0]['icon']}.png' alt='Weather icon' class='large-icon'>";
    echo "<h3 class='temperature'>" . round($current_weather['main']['temp']) . "°C</h3>";
    echo "<p class='feels-like muted'>Feels like " . round($current_weather['main']['feels_like']) . "°C</p>";
    echo "<p class='day-name'>" . date("l", $current_weather['dt']) . "</p>";
    echo "<p class='weather-description'>" . $current_weather['weather'][0]['description'] . "</p>";
    echo "<p class='chance-of-rain'>Chance of rain: " . round($current_weather['pop'] * 100) . "%</p>";

    echo "<h2 class='location-title'>" . $data['city']['name'] . ", " . $data['city']['country'] . "</h2>";
    
    echo "</div>";
}

function display_hourly_weather($data) {
    echo "<div class='hourly-weather'>";
    $hourly_data = $data['list'];

    for ($i = 0; $i < 5; $i++) {
        $hourly_item = $hourly_data[$i];
        $time = date("H:i", $hourly_item['dt']);
        $icon = $hourly_item['weather'][0]['icon'];
        $temp = round($hourly_item['main']['temp']);
        
        echo "<div class='hourly-item'>";
        echo "<img src='https://openweathermap.org/img/w/{$icon}.png' alt='Weather icon' class='hourly-icon'>";
        echo "<p class='hourly-temp'>{$temp}°C</p>";
        echo "<p class='hourly-time'>$time</p>";
        echo "</div>";
    }
    echo "</div>";
}


function display_windStatus($data) {
    $wind_speed = $data['list'][0]['wind']['speed'];

    $wind_speed = (int)$wind_speed *3.6;


    $wind_direction = $data['list'][0]['wind']['deg'];
    
    echo "<div class='highlight-item'>";
    echo "<h4 class='highlight-item-title'>Wind Status</h4>";
    echo "<p><span class='highlight-label'>Speed:</span> {$wind_speed} km/h</p>";
    echo "<p><span class='highlight-label'>Direction:</span> {$wind_direction}°</p>";
    echo "</div>";
}

function display_humidity($data) {
    $humidity = $data['list'][0]['main']['humidity'];



    if ($humidity > 60){
        echo "<div class='highlight-item'>";
        echo "<h4 class='highlight-item-title'>Humidity</h4>";
        echo "<p class='highlight-number'>{$humidity}%</p>";
        echo "<p>Stay hydrated</p>";
        echo "</div>";
    }
    else if ($humidity < 30){
        echo "<div class='highlight-item'>";
        echo "<h4 class='highlight-item-title'>Humidity</h4>";
        echo "<p class='highlight-number'>{$humidity}%</p>";
        echo "<p>Stay hydrated</p>";
        echo "</div>";
    }
    else{
        echo "<div class='highlight-item'>";
        echo "<h4 class='highlight-item-title'>Humidity</h4>";
        echo "<p class='highlight-number'>{$humidity}%</p>";
        echo "<p>Humidity level is good</p>";
        echo "</div>";
    }


}

function display_visibility($data) {
    $visibility = $data['list'][0]['visibility'];
    $visibility = (int)$visibility / 1000;


    if ($visibility < 5){
        echo "<div class='highlight-item'>";
        echo "<h4 class='highlight-item-title'>Visibility</h4>";
        echo "<p class='highlight-number'>{$visibility} km</p>";
        echo "<p>low</p>";
        echo "</div>";
    }
    else if ($visibility > 10){
        echo "<div class='highlight-item'>";
        echo "<h4 class='highlight-item-title'>Visibility</h4>";
        echo "<p class='highlight-number'>{$visibility} km</p>";
        echo "<p>Good</p>";
        echo "</div>";
    }
    else{
        echo "<div class='highlight-item'>";
        echo "<h4 class='highlight-item-title'>Visibility</h4>";
        echo "<p class='highlight-number'>{$visibility} km</p>";
        echo "<p>Normal</p>";
        echo "</div>";
    }

}

function display_pressure($data) {
    $pressure = $data['list'][0]['main']['pressure'];

    echo "<div class='highlight-item'>";
    echo "<h4 class='highlight-item-title'>Pressure</h4>";
    echo "<p class='highlight-number'> {$pressure}<span><p>hPa</p></span> </p>";
    echo "</div>";

}

function display_sunriseSunset($data) {
    $timezone = $data['city']['timezone'];

    
    $sunrise = date("H:i", $data['city']['sunrise'] + $timezone);
    $sunset = date("H:i", $data['city']['sunset'] + $timezone);


    echo "<div class='highlight-item'>";
    echo "<h4 class='highlight-item-title'>Sunrise & Sunset</h4>";
    echo "<p><span class='highlight-label'>Sunrise:</span> {$sunrise}</p>";
    echo "<p><span class='highlight-label'>Sunset:</span> {$sunset}</p>";
    echo "</div>";
}


function display_Temp($data) {
    $max_temp = round($data['list'][0]['main']['temp_max']);
    $min_temp = round($data['list'][0]['main']['temp_min']);
    $totalRain = 0;
    if (isset($item['rain']) && isset($item['rain']['3h'])) {
        $totalRain += $item['rain']['3h'];
    }

    echo "<div class='highlight-item'>";
    echo "<h4 class='highlight-item-title'>Temperature</h4>";
    echo "<p><span class='highlight-label'>Max:</span> {$max_temp}°C</p>";
    echo "<p><span class='highlight-label'>Min:</span> {$min_temp}°C</p>";
    echo "</div>";

}

function display_next_5_days_weather($data){
    echo "<div class='next-5-days'>";
    echo "<h2>5-Day-Forecast</h2>";
    echo "<div class='next-5-days-container'>";
    
    $previous_day = ""; 
    foreach ($data['list'] as $item) {
        $day = date("l", $item['dt']);
        
        if ($day == date("l")) {
            continue;
        }

        if ($day != $previous_day) {
            echo "<div class='next-5-days-item'>";
            echo "<p class='next-5-days-day'>$day</p>";
            echo "<img src='https://openweathermap.org/img/w/{$item['weather'][0]['icon']}.png' alt='Weather icon' class='next-5-days-icon'>";
            echo "<p class='next-5-days-temp'>{$item['main']['temp']}°C</p>";
            echo "</div>";
            $previous_day = $day;
        }
    }
    
    echo "</div>";
    echo "</div>";
}

function display_other_cities_weather($other_data,$data) {
    echo "<div class='other-cities-container'>";
    
    foreach ($other_data as $city_data) {
        if($city_data['city']['name'] == $data['city']['name']){
            continue;
        }
        echo "<div class='other-city-item'>";
        echo "<h3 class='other-city-name'>{$city_data['city']['name']}, {$city_data['city']['country']}</h3>";
        echo "<img src='https://openweathermap.org/img/w/{$city_data['list'][0]['weather'][0]['icon']}.png' alt='Weather icon' class='other-city-icon'>";
        echo "<p class='other-city-temp'>{$city_data['list'][0]['main']['temp']}°C</p>";
        echo "</div>";

    }
    echo "</div>";
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Weather Showing Site</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

</head>

<body>
    <h1 class="text-center">Weather Forecast</h1>
    <div class="container-fluid mt-4">
        <div class="row mylocation">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-md-4">
                        <div class="weather">
                            
                            <?php 

                        display_current_weather($data);
                        
                        ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="details-today">
                            <div class="row">
                                <!-- Hourly -->
                                <div class="col-sm-12">
                                    <h2 class="section-title">Hourly</h2>

                                    <?php // Display hourly weather info
                                        display_hourly_weather($data);
                                        ?>


                                </div>
                                <div class="row mt-4">
                                    <!-- Today's Highlight -->
                                    <div class="col-md-12">
                                        <h2 class="section-title">Today's Highlight</h2>
                                        <div class="row">
                                            <div class="today-highlight">
                                                <div class="col-md-4">
                                                    <?php // Display today's highlight info 
                                                
                                                display_pressure($data);
                                                ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php // Display today's highlight info 
                                                
                                                display_visibility($data);
                                                ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php // Display today's highlight info 
                                                display_humidity($data);
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="today-highlight">
                                                <div class="col-md-4">
                                                    <?php // Display today's highlight info                                         
                                                display_windStatus($data);
                                                ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php // Display today's highlight info 
                                            
                                                display_Temp($data);
                                                ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php // Display today's highlight info                                         
                                                
                                                display_sunriseSunset($data);
                                                ?>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class='row'>
                    <div class='col-lg-12'>
                        <div class='other-cities'>
                            <?php 
                                display_other_cities_weather($other_data,$data);
                            ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="next-days">
                    <?php 
                    display_next_5_days_weather($data);
                    ?>

                </div>

            </div>
        </div>

    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
