**Chatbot Integration with Dialogflow and Twilio**

This repository contains code for integrating a chatbot with Dialogflow and Twilio, allowing for conversational interactions via WhatsApp. The chatbot leverages Dialogflow for natural language understanding and Twilio for communication via WhatsApp.

### Setup Instructions:

1. **Authentication Credentials:**
   - Ensure you have authentication credentials for both Dialogflow and Twilio.
   - Set up a service account key for Dialogflow and place the JSON file in a directory named `credentials.json`.
   - Obtain your Twilio Account SID and Auth Token.

2. **Dependencies:**
   - Install dependencies using Composer. Run `composer install` in the terminal to install the required PHP packages.

3. **Configuration:**
   - Set your Dialogflow project ID and language code in the `sendMessage` function.
   - Replace `'YOUR-TWILIO-SID'` and `'YOUR-TWILIO-TOKEN'` with your Twilio SID and token respectively.

4. **Endpoints Setup:**
   - If you're using WordPress with WooCommerce, the code includes endpoint setup for fetching products by ID and receiving WhatsApp messages. If not using WordPress, you can set up your own endpoints according to your system.

### Usage:

- **Fetching Products by ID:**
  - Send a POST request to `/chatbot/v1/get-products/{product_id}` to retrieve details about a specific product.
  - Replace `{product_id}` with the ID of the product you want to retrieve.

- **WhatsApp Integration with Dialogflow:**
  - Send WhatsApp messages to your Twilio number connected to this integration.
  - The message content will be processed by Dialogflow for natural language understanding.
  - Dialogflow will respond based on the configured intents and entities, and the response will be sent back to the sender via WhatsApp.


### Example:

![Chatbot Functionality](chatbot_example.png)

### Notes:

- This integration is a demonstration of how to connect Dialogflow with Twilio for WhatsApp communication.
- Further customization and enhancements can be made based on specific requirements and use cases.

For any issues or inquiries, please contact [Me :)](hector_ugarter@hotmail.com).
