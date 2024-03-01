<?php
// Load required libraries
require_once 'vendor/autoload.php';

// Set authentication credentials
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/credentials.json');

// Import Dialogflow libraries
use Google\Cloud\Dialogflow\V2\{
    SessionsClient,
    TextInput,
    QueryInput
};
use Twilio\Rest\Client;

// Define REST API endpoints
add_action('rest_api_init', function () {
    // Endpoint to get products by ID
    register_rest_route('chatbot/v1', 'get-products/(?P<product_id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'get_products_by_id'
    ));

    // Endpoint to handle WhatsApp messages
    register_rest_route('chatbot/v1', 'whatsapp', array(
        'methods' => 'POST',
        'callback' => 'whatsappDialogFlow'
    ));
});

// Function to fetch products by ID ------- Example of retriving a product from a text input of dialogFlow
function get_products_by_id($request)
{
    // Retrieve product ID from request parameters
    $request_params = $request->get_params();
    $product_id = $request_params['queryResult']['parameters']['product'];

    // Retrieve product object by ID
    $product = wc_get_product($product_id);

    // Check if product exists
    if (!$product) {
        return 'Product not found';
    }

    // Get product name
    $product_name = $product->get_name();

    // Prepare response payload
    $response_payload = array(
        'fulfillmentText' => 'The product you are looking for is: ' . $product_name,
        // Additional platform-specific responses can be included here
    );

    // Send response
    $response = new WP_REST_Response($response_payload);
    $response->set_status(200);

    return $response;
}

// Function to handle WhatsApp messages and interact with Dialogflow
function whatsappDialogFlow($request)
{
    // Retrieve request parameters
    $request_params = $request->get_params();
    $from = $request_params['From'];
    $to = $request_params['To'];
    $body = $request_params['Body'];

    // Send message to Dialogflow and retrieve response
    $fulfilmentText = sendMessage($body);

    // Send Dialogflow response via WhatsApp using Twilio
    sendWhatsAppMessage($from, $to, $fulfilmentText);
}

// Function to send message to Dialogflow and get response
function sendMessage($body)
{
    $sessionClient = new SessionsClient();
    $projectId = 'YOUR-PROJECT-ID';
    $languageCode = 'en'; // Language code (e.g., 'en' for English)

    // Create session
    $sessionPath = $sessionClient->sessionName($projectId, uniqid());

    // Set text input and query input
    $textInput = new TextInput();
    $textInput->setText($body);
    $textInput->setLanguageCode($languageCode);
    $queryInput = new QueryInput();
    $queryInput->setText($textInput);

    // Detect intent
    $response = $sessionClient->detectIntent($sessionPath, $queryInput);
    $fulfilmentText = $response->getQueryResult()->getFulfillmentText();

    return $fulfilmentText;
}

// Function to send WhatsApp message using Twilio
function sendWhatsAppMessage($from, $to, $body)
{
    $sid = 'YOUR-TWILIO-SID';
    $token = 'YOUR-TWILIO-TOKEN';
    $twilio = new Client($sid, $token);

    // Send message
    $message = $twilio->messages->create($from, array(
        'from' => $to,
        'body' => $body
    ));

    // Print message SID for reference
    print ($message->sid);
}
