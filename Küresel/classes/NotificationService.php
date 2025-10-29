<?php
/**
 * Notification Service
 * 
 * Handles sending email and SMS notifications to users
 */

class NotificationService {
    
    /**
     * Send email notification
     */
    public static function sendEmail($to, $subject, $message) {
        // In a real implementation, you would use a proper email service
        // For now, we'll just log the email
        error_log("EMAIL SENT - To: {$to}, Subject: {$subject}, Message: {$message}");
        
        // For demonstration purposes, let's simulate sending an email
        // In a real application, you would use PHPMailer or similar
        return true;
    }
    
    /**
     * Send SMS notification
     */
    public static function sendSMS($to, $message) {
        // In a real implementation, you would use a proper SMS service
        // For now, we'll just log the SMS
        error_log("SMS SENT - To: {$to}, Message: {$message}");
        
        // For demonstration purposes, let's simulate sending an SMS
        // In a real application, you would use a service like Twilio
        return true;
    }
    
    /**
     * Send notification based on user preferences
     */
    public static function sendNotificationToUser($user, $type, $subject, $message) {
        // Get user preferences
        $preferences = new UserPreferences();
        $userPrefs = $preferences->getPreferences($user->getId());
        
        // Merge with default preferences
        $defaultPrefs = UserPreferences::getDefaultPreferences();
        $userPrefs = array_merge($defaultPrefs, $userPrefs);
        
        // Send email notification if enabled
        if ($userPrefs['email_notifications'] && $type !== 'sms_only') {
            self::sendEmail($user->getEmail(), $subject, $message);
        }
        
        // Send SMS notification if enabled and user has a phone number
        if ($userPrefs['sms_notifications'] && $user->getPhone() && $type !== 'email_only') {
            self::sendSMS($user->getPhone(), $message);
        }
        
        return true;
    }
}
?>