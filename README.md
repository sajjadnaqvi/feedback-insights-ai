# AI-Driven Sentiment Analysis for Laravel Feedback

This project demonstrates a real-time sentiment analysis workflow for user feedback in a Laravel application, using **Make.com** and the **Hugging Face Inference API**.

-----

## Project Overview

When a user submits feedback through the Laravel app, the feedback is stored and a queued job sends the comment to Make.com via webhook. Make.com uses a Hugging Face sentiment analysis model to classify the sentiment (positive/negative/neutral) and calls back a secure Laravel webhook to update the feedback record. Admins can view all feedback and sentiment in a dashboard.

-----

## Key Technologies

- **Laravel:** PHP framework for feedback forms, authentication, and database management.
- **Make.com:** No-code automation platform for workflow orchestration.
- **Hugging Face Inference API:** Provides pre-trained AI models for sentiment analysis.

-----

## Features

- **User Authentication:** Register/login system for users.
- **Feedback Submission:** Authenticated users can submit feedback.
- **Queued Sentiment Analysis:** Feedback is analyzed asynchronously for scalability.
- **Webhook Integration:** Secure, token-protected webhook for Make.com to update sentiment.
- **Admin Dashboard:** Admins can view all feedbacks and their sentiment.
- **Extensible:** Easily swap AI models or add analytics.

-----

## Setup and Installation

### 1. Laravel Application Setup

**Prerequisites:** PHP, Composer, Laravel, and a database (MySQL recommended).

1. **Clone the repository:**
    ```bash
    git clone [your-repo-link]
    cd [your-repo-name]
    ```
2. **Install dependencies:**
    ```bash
    composer install
    ```
3. **Copy `.env.example` to `.env` and configure:**
    ```bash
    cp .env.example .env
    ```
    - Set your database credentials.
    - Add your Make.com webhook URL:
      ```
      MAKEAI_URL="https://hook.make.com/your-make-webhook"
      ```
    - Add a secret for webhook security:
      ```
      WEBHOOK_SECRET=your_secure_token
      ```
4. **Generate application key:**
    ```bash
    php artisan key:generate
    ```
5. **Run migrations:**
    ```bash
    php artisan migrate
    ```
6. **Start the Laravel server:**
    ```bash
    php artisan serve
    ```

-----

## Make.com and Hugging Face Integration

### Prerequisites

- **Make.com Account:** Sign up at [Make.com](https://www.make.com/).
- **Hugging Face Account:** Sign up at [Hugging Face](https://huggingface.co/).
- **Hugging Face API Token (Recommended):** Generate a token at [Hugging Face Tokens](https://huggingface.co/settings/tokens) for higher rate limits.

### Make.com Scenario Setup

1. **Create a New Scenario:**
   - In your Make.com dashboard, click "Create a new scenario".

2. **Add Your Trigger Module:**
   - Add the **Webhook** module as the trigger.
   - Configure the webhook to receive data from your Laravel application (the feedback comment).

3. **Add the HTTP Module for Sentiment Analysis:**
   - Click the "+" button next to your trigger module.
   - Search for and select the **HTTP** app.
   - Choose the **Make a request** module.

4. **Configure the HTTP Request to Hugging Face:**
   - **URL:**  
     ```
     https://api-inference.huggingface.co/models/bert-base-uncased-finetuned-sst-2-english
     ```
     (You can use other models, e.g., `distilbert-base-uncased-finetuned-sst-2-english`.)
   - **Method:** `POST`
   - **Headers:**
     - `Content-Type: application/json`
     - `Authorization: Bearer YOUR_HUGGINGFACE_API_TOKEN` (replace with your token, optional but recommended)
   - **Body type:** Raw
   - **Request content:**  
     ```json
     {
       "inputs": "{{1.body.comment}}"
     }
     ```
     *(Assuming your webhook module is the first module in the scenario. Adjust the `1` if it's a different number.)*

5. **Send Sentiment Data Back to Laravel:**
   - Add another **HTTP** module.
   - Configure it to POST the sentiment result to your Laravel webhook endpoint:
     - **URL:**  
       ```
       http://127.0.0.1:8000/api/webhook/sentiment
       ```
     - **Headers:**
       - `X-Webhook-Token: your_secure_token`
       - `Content-Type: application/json`
     - **Body:**  
       ```json
       {
            "data":{
                "feedback_id": "{{1.body.feedback_id}}",
                "sentiments": {{2.data}}
            }
       }
       ```
       *(Adjust the mapping as needed based on your scenario's module order.)*

6. **Test the Scenario:**
   - Submit feedback in your Laravel app.
   - Confirm that sentiment is analyzed and updated in your database.

-----

## Usage

1. Users register/login and submit feedback.
2. Feedback is stored and a job sends it to Make.com for sentiment analysis.
3. Make.com analyzes the sentiment using Hugging Face and calls back your Laravel webhook.
4. The feedback record is updated with the sentiment.
5. Admins can view all feedback and sentiment in the dashboard.

-----

## Security

- Webhook endpoints are protected with a secret token (`X-Webhook-Token`).
- Only authenticated users can submit feedback.
- Admin dashboard is protected by middleware.

-----

## Contributing

Feel free to fork this project, open issues, or submit pull requests.

-----

## License

This project is open-sourced under the MIT License.
