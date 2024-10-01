<?php
$csvFile = dirname(__FILE__) . '/us-airports.csv';

// Check if the file exists
if (!file_exists($csvFile)) {
    die("CSV file not found.");
}
?>


<?php if ($_SERVER['REQUEST_METHOD'] === 'GET') : ?>
<?php
// $city = isset($_POST['city']) ? $_POST['city'] : '';
// $state = isset($_POST['state']) ? $_POST['state'] : '';
// echo "<pre>";
// print_r($dataArray);
// echo "</pre>";


        $userDistance = intval($_POST['distance']);
        // Validate the user-provided distance and update the default value
        if ($userDistance > 0) {
            $defaultDistance = $userDistance;
        } 

 $city = $dataArray['fromCity'];
 $state = $dataArray['fromState'];

// Read CSV file
$csvData = array_map('str_getcsv', file($csvFile));

$filteredData = array();

// Geocoding API
$apiKey = '';
 $cityState = urlencode($city . ',' . $state);
 $geocodeApiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$cityState}&key={$apiKey}";

$response = file_get_contents($geocodeApiUrl);
$data = json_decode($response, true);

if ($data['status'] === 'OK') {
    $userCityCenterLatitude = $data['results'][0]['geometry']['location']['lat'];
    $userCityCenterLongitude = $data['results'][0]['geometry']['location']['lng'];

    // Calculate Distance in Miles
    function haversineDistanceInMiless($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 3959; // Earth's radius in miles (approximately)
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        return $distance;
    }

    // Filter data based on user input city name and distance
    foreach ($csvData as $index => $row) {
        if ($index === 0) {
            continue; // Skip header row
        }

        $airportLatitude = $row[4]; // Latitude column index
        $airportLongitude = $row[5]; // Longitude column index

        $distanceToCityCenter = haversineDistanceInMiless($userCityCenterLatitude, $userCityCenterLongitude, $airportLatitude, $airportLongitude);

        if ($distanceToCityCenter <= $defaultDistance) {
            $filteredData[] = array(
                'municipality' => $row[2],
                'type' => $row[12],
                'name' => $row[3],
                'latitude' => $airportLatitude,
                'longitude' => $airportLongitude,
               // 'country_name' => $row[8],
                'region_name' => $row[10],
                'iata_code' => $row[16],
                //'local_code' => $row[17],
                'distance_to_city_center' => $distanceToCityCenter
            );
        }
    }
}
?>
<?php endif; ?>