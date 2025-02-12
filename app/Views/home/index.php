<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(180deg, #4a90e2, #1451a2);
            color: white;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .weather-card {
            width: 350px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
        }

        .temperature {
            font-size: 60px;
            font-weight: bold;
        }

        .weather-desc {
            font-size: 20px;
            font-weight: 500;
        }

        .weather-icon {
            width: 80px;
        }

        .map-container {
            margin-top: 10px;
            border-radius: 10px;
            overflow: hidden;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="weather-card">
        <h3>Bangkok</h3>
        <p class="weather-desc">Partly Cloudy</p>
        <img src="https://openweathermap.org/img/wn/02d@2x.png" alt="weather icon" class="weather-icon">
        <p class="temperature">33°C</p>
        <p>Feels like <strong>34°C</strong></p>

        <div class="info-box">
            <p>🌡 High: 35°C | Low: 28°C</p>
            <p>💨 Wind: 3 km/h</p>
        </div>

        <div class="map-container">
            <img src="https://via.placeholder.com/300x150" alt="Weather Radar Map" class="w-100">
        </div>
    </div>

</body>
</html>
