<?php
function getWeather($city) {
    $apiKey = '6d460dd47b1c65b844a1905314f5d493';
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid={$apiKey}&units=metric&lang=ru";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$weather = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city = $_POST['city'];
    $weatherData = getWeather($city);

    if ($weatherData['cod'] == 200) {
        $weather = $weatherData;
    } else {
        $error = "Город не найден. Пожалуйста, проверьте правильность ввода.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Погода в городе</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Узнать погоду</h1>
                        <form action="" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="city" placeholder="Введите город" required>
                                <button class="btn btn-primary" type="submit">Узнать погоду</button>
                            </div>
                        </form>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php elseif ($weather): ?>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h2 class="card-title">Погода в городе <?php echo htmlspecialchars($weather['name']); ?></h2>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">Температура: <?php echo $weather['main']['temp']; ?>°C</li>
                                        <li class="list-group-item">Ощущается как: <?php echo $weather['main']['feels_like']; ?>°C</li>
                                        <li class="list-group-item">Влажность: <?php echo $weather['main']['humidity']; ?>%</li>
                                        <li class="list-group-item">Описание: <?php echo $weather['weather'][0]['description']; ?></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
