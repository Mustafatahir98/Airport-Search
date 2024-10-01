<?php /* Template Name: Nearby Airports Search */ ?>

<?php 
  
if (!is_user_logged_in()) {
    // Check if dealId is set in the URL
    $deal_id = isset($_GET['dealId']) ? $_GET['dealId'] : '';

    // Ensure that dealId is appended to the current page URL
    if (!empty($deal_id)) {
        $current_page_with_deal_id = add_query_arg('dealId', $deal_id, get_permalink());
    } else {
        // If no dealId, just use the current page URL
        $current_page_with_deal_id = get_permalink();
    }

    // Pass the current page URL (with dealId if present) as the redirect_to parameter in the login URL
    $login_url = wp_login_url($current_page_with_deal_id);

    // Redirect to the login URL
    wp_redirect($login_url);
    exit;
}
    
    

?>
 
 <?php
// Default distance value
$defaultDistance = 100;
 ?>
<!DOCTYPE html>
<html>

<head>
    <title>Airport Search</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <style>
      .top-barr {
            background-color: #027CBA; /* Background color */
            color: #fff; /* Text color */
            padding: 5px 0; 
            text-align: center; 
            margin-bottom: 40px;
        }
        .top-barr h1 {
            font-size: 24px;
            margin: 0;
            padding: 5px;
        }
    </style>

</head>

<body>

    <div style="padding:20px 0px 20px 20px; display:none">
    <h3>Search HubSpot Deal:</h3>
    <form method="POST">
        Enter Hubspot Deal ID: <input type="text" name="dealId" placeholder="Enter HubSpot ID">
        <!-- <input type="number" id="distance" name="distance" value="" min="1"> -->
       Enter radius Value: <input type="number" id="distance" name="distance" value="" placeholder="<?php echo $defaultDistance; ?>">
        <button type="submit">Fetch Deal</button>
    </form>


</div>
</body>

</html>

<?php


global $wpdb;

$result = $wpdb->get_results("SELECT token FROM hubspot_access_tokens LIMIT 1");

if (!empty($result)) {
    $accessToken = $result[0]->token;
}



// Function to fetch deals from HubSpot API
function fetchDeals($url, $headers)
{
    $response = file_get_contents(
        $url,
        false,
        stream_context_create(
            array(
                'http' => array(
                    'method' => 'GET',
                    'header' => implode("\r\n", $headers)
                )
            )
        )
    );

    return array(
        'response' => $response,
        'http_code' => $http_response_header[0]
    );
}

    if (isset($_GET['dealId'])) {
        $dealId = intval($_GET['dealId']);

        // API endpoint to retrieve a specific deal
        $dealUrl = "https://api.hubapi.com/crm/v3/objects/deals/$dealId?properties=amount,closedate,dealname,dealstage,amd,from_city,from_state,to_city,to_state";

        $headers = array(
            "Authorization: Bearer $accessToken"
        );

        $dealResponse = fetchDeals($dealUrl, $headers);

       echo '<div class="top-barr">';
        echo "<h2>Hubspot Deal ID: <span>$dealId</span></h2>"; 
       echo "</div>";

        if ($dealResponse === false) {
            echo "API request failed: " . print_r(error_get_last(), true);

        } else {
            // Check if the HTTP response code is 200 (OK)
            if ($dealResponse['http_code'] == 'HTTP/1.1 200 OK') {
                $dealData = json_decode($dealResponse['response'], true);

                // Display the deal data if found
                if (isset($dealData['properties'])) {
                    echo "<table border='1'>";
                    echo "<tr><th>Deal Name</th><th>From City</th><th>From State</th><th>To City</th><th>To State</th><th>Amount</th><th>Hubspot ID</th><th>Close Date</th></tr>";

                    $deal = $dealData['properties'];
                    $dealName = $deal['dealname'];
                    $amount = $deal['amount'];
                    $closeDate = $deal['closedate'];

                    $Hubspot_ID = isset($deal['hs_object_id']) ? $deal['hs_object_id'] : 'N/A';
                    $fromCity = isset($deal['from_city']) ? $deal['from_city'] : 'N/A';
                    $fromState = isset($deal['from_state']) ? $deal['from_state'] : 'N/A';
                    $toCity = isset($deal['to_city']) ? $deal['to_city'] : 'N/A';
                    $toState = isset($deal['to_state']) ? $deal['to_state'] : 'N/A';


                    $dataArray = array(
                        'fromCity' => $fromCity,
                        'fromState' => $fromState,
                        'toCity' => $toCity,
                        'toState' => $toState
                    );
   
                    

                    echo "<tr><td>$dealName</td><td>$fromCity</td><td>$fromState</td><td>$toCity</td><td>$toState</td><td>$amount</td><td>$Hubspot_ID</td><td>$closeDate</td></tr>";

                    echo "</table>";
                    
                } else {
                    echo '<p class="not_found">';
                    echo "No deal found with the provided HubSpot ID: $dealId";
                }
            } else if ($dealResponse['http_code'] == 'HTTP/1.1 404 Not Found') {
                echo '<p class="not_found">';
                echo "No deal found with the provided HubSpot ID: $dealId";
            } else {
                echo '<p class="not_found">';
                echo "An error occurred while fetching the deal data.";
            }
        }
    } else {
        echo '<p class="not_found">';
        echo "No hubSpot ID available in the url requested";
    }

 include 'airport_results.php'
?>