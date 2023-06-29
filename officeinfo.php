<?php
//YOU MPESA API KEYS
$consumerKey = "pGE8GYBTPAPlj3v7xO44TeruIEAGJBle";
$consumerSecret = "3U9MTfhCMELAv0MO";
//ACCESS TOKEN URL
$access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$headers = ['Content-Type:application/json; charset=utf8'];
$curl = curl_init($access_token_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HEADER, FALSE);
curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
$result = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$result = json_decode($result);
// ASSIGN ACCESS TOKEN TO A VARIABLE
$access_token = $result->access_token;
curl_close($curl);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve user input from the form
  $phone = $_POST["phone"];
  $money = $_POST["money"];
  // Validate the input fields (you can add more validation if needed)
  if (empty($phone) || empty($money)) {
    $error = "Please fill in all the required fields.";
  } else {
    // Process the transaction
    $BusinessShortCode = '174379';
    $Timestamp = date('YmdHis');
    $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
    $processrequestUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $callbackurl = 'https://tukit-class.co.ke/destinySafaris/callback.php';
    // ENCRYPT DATA TO GET PASSWORD
    $Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);
    $PartyA = $phone;
    $PartyB = '254708601348';
    $AccountReference = 'DESTINY SAFARIS';
    $TransactionDesc = 'stkpush test';
    $Amount = $money;
    $stkpushheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

    //INITIATE CURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $processrequestUrl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkpushheader); //setting custom header
    $curl_post_data = array(
      //Fill in the request parameters with valid values
      'BusinessShortCode' => $BusinessShortCode,
      'Password' => $Password,
      'Timestamp' => $Timestamp,
      'TransactionType' => 'CustomerPayBillOnline',
      'Amount' => $Amount,
      'PartyA' => $PartyA,
      'PartyB' => $BusinessShortCode,
      'PhoneNumber' => $PartyA,
      'CallBackURL' => $callbackurl,
      'AccountReference' => $AccountReference,
      'TransactionDesc' => $TransactionDesc
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);

    // CHECK IF CURL REQUEST WAS SUCCESSFUL
    if ($curl_response === false) {
      $error_message = curl_error($curl);
      // Handle the error
      // ...
      exit("cURL Error: " . $error_message);
    }

    //ECHO  RESPONSE
    $data = json_decode($curl_response);

    //CHECK IF RESPONSE IS VALID
    if ($data === null) {
      exit("Invalid response from API.");
    }

    // CHECK IF THE RESPONSE CONTAINS THE EXPECTED PROPERTIES
    if (property_exists($data, 'CheckoutRequestID') && property_exists($data, 'ResponseCode')) {
      $CheckoutRequestID = $data->CheckoutRequestID;
      $ResponseCode = $data->ResponseCode;
      if ($ResponseCode == "0") {
        $success = "The CheckoutRequestID for this transaction is: " . $CheckoutRequestID;
      }
    }

    //CLOSE CURL
    curl_close($curl);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>M-PESA Payment</title>
</head>
<body>
  <h2>M-PESA Payment</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" required><br><br>

    <label for="money">Amount:</label>
    <input type="text" id="money" name="money" required><br><br>

    <input type="submit" value="Submit">
  </form>
</body>
</html>
