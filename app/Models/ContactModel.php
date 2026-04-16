<?php

namespace App\Models;

use App\Core\Model;

class ContactModel {

    use Model;

    protected $table = 'contact_messages';

    /**
     * Validate contact form data
     */
    public function validate($data)
    {
        $errors = [];

        if (empty($data['full_name'])) {
            $errors['full_name'] = "Full name is required";
        }

        if (empty($data['email'])) {
            $errors['email'] = "Email address is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }

        if (empty($data['message'])) {
            $errors['message'] = "Message content is required";
        }

        return $errors;
    }

    /**
     * Insert a new contact message
     */
    public function insertMessage($data)
    {
        $query = "INSERT INTO contact_messages (full_name, email, phone, subject, message) 
                  VALUES (:full_name, :email, :phone, :subject, :message)";
                  
        return $this->query($query, [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? 'General Inquiry',
            'message' => $data['message']
        ]);
    }
}
