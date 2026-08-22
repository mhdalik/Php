<?php

namespace App\Services;

use Config\Services;

class EmailService
{
    /**
     * Send welcome email to a customer.
     *
     * @param array $customer
     * @return bool
     */
    public function sendWelcomeEmail(array $customer): bool
    {
        $email = Services::email();

        $email->setTo($customer['email']);
        $email->setSubject('Welcome to Our CRM!');

        // render the email view template
        $body = view('emails/welcome', [
            'name' => $customer['name'],
            'email' => $customer['email']
        ]);

        $email->setMessage($body);

        try {
            if ($email->send()) {
                return true;
            }
            
            // log details of smtp send failure
            log_message('error', 'Failed to send welcome email to ' . $customer['email'] . '. Debug: ' . $email->printDebugger(['headers', 'subject', 'body']));
            return false;
        } catch (\Throwable $e) {
            // log exception and continue
            log_message('error', 'Exception when sending welcome email to ' . $customer['email'] . ': ' . $e->getMessage());
            return false;
        }
    }
}
