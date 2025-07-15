<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $message, $isHTML = true) {
    $siteSettings = getSiteSettings();
    
    if (!$siteSettings['enable_email_module'] || empty($siteSettings['email_smtp_host'])) {
        return ['success' => false, 'message' => 'Email module not configured'];
    }
    
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $siteSettings['email_smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $siteSettings['email_smtp_user'];
        $mail->Password   = $siteSettings['email_smtp_pass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $siteSettings['email_smtp_port'];
        
        // Recipients
        $mail->setFrom($siteSettings['email_smtp_user'], $siteSettings['site_name']);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        
        $mail->send();
        
        // Log email
        logCommunication('email', $to, $subject, $message, 'sent');
        
        return ['success' => true, 'message' => 'Email sent successfully'];
        
    } catch (Exception $e) {
        // Log failed email
        logCommunication('email', $to, $subject, $message, 'failed');
        
        return ['success' => false, 'message' => 'Email could not be sent. Error: ' . $mail->ErrorInfo];
    }
}

function sendSMS($to, $message) {
    $siteSettings = getSiteSettings();
    
    if (!$siteSettings['enable_sms_module'] || empty($siteSettings['sms_api_key'])) {
        return ['success' => false, 'message' => 'SMS module not configured'];
    }
    
    try {
        // Generic SMS API implementation
        // You can customize this for your specific SMS provider
        
        $postData = [
            'apikey' => $siteSettings['sms_api_key'],
            'numbers' => $to,
            'message' => $message,
            'sender' => $siteSettings['site_name']
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $siteSettings['sms_api_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            logCommunication('sms', $to, null, $message, 'sent');
            return ['success' => true, 'message' => 'SMS sent successfully'];
        } else {
            logCommunication('sms', $to, null, $message, 'failed');
            return ['success' => false, 'message' => 'Failed to send SMS'];
        }
        
    } catch (Exception $e) {
        logCommunication('sms', $to, null, $message, 'failed');
        return ['success' => false, 'message' => 'SMS error: ' . $e->getMessage()];
    }
}

function logCommunication($type, $recipient, $subject, $message, $status) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO communication_logs (type, recipient, subject, message, status, sent_at, created_by) 
                  VALUES (:type, :recipient, :subject, :message, :status, :sent_at, :created_by)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':recipient', $recipient);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':sent_at', date('Y-m-d H:i:s'));
        $stmt->bindParam(':created_by', $_SESSION['user_id']);
        
        $stmt->execute();
    } catch(PDOException $e) {
        // Silent fail for logging
    }
}

// Email Templates
function getEmailTemplate($type, $data = []) {
    $siteSettings = getSiteSettings();
    $siteName = $siteSettings['site_name'];
    $primaryColor = $siteSettings['primary_color'];
    
    $templates = [
        'appointment_confirmation' => [
            'subject' => 'Appointment Confirmation - ' . $siteName,
            'body' => "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: {$primaryColor}; color: white; padding: 20px; text-align: center;'>
                    <h1>{$siteName}</h1>
                    <h2>Appointment Confirmation</h2>
                </div>
                <div style='padding: 20px; background: #f9f9f9;'>
                    <p>Dear {$data['patient_name']},</p>
                    <p>Your appointment has been confirmed with the following details:</p>
                    <ul>
                        <li><strong>Doctor:</strong> {$data['doctor_name']}</li>
                        <li><strong>Date:</strong> {$data['appointment_date']}</li>
                        <li><strong>Time:</strong> {$data['appointment_time']}</li>
                        <li><strong>Appointment ID:</strong> {$data['appointment_id']}</li>
                    </ul>
                    <p>Please arrive 15 minutes before your scheduled time.</p>
                    <p>If you need to reschedule, please contact us as soon as possible.</p>
                    <p>Thank you for choosing {$siteName}.</p>
                </div>
            </div>"
        ],
        
        'appointment_reminder' => [
            'subject' => 'Appointment Reminder - ' . $siteName,
            'body' => "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: {$primaryColor}; color: white; padding: 20px; text-align: center;'>
                    <h1>{$siteName}</h1>
                    <h2>Appointment Reminder</h2>
                </div>
                <div style='padding: 20px; background: #f9f9f9;'>
                    <p>Dear {$data['patient_name']},</p>
                    <p>This is a reminder for your upcoming appointment:</p>
                    <ul>
                        <li><strong>Doctor:</strong> {$data['doctor_name']}</li>
                        <li><strong>Date:</strong> {$data['appointment_date']}</li>
                        <li><strong>Time:</strong> {$data['appointment_time']}</li>
                    </ul>
                    <p>Please arrive 15 minutes before your scheduled time.</p>
                    <p>Thank you!</p>
                </div>
            </div>"
        ],
        
        'bill_receipt' => [
            'subject' => 'Payment Receipt - ' . $siteName,
            'body' => "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: {$primaryColor}; color: white; padding: 20px; text-align: center;'>
                    <h1>{$siteName}</h1>
                    <h2>Payment Receipt</h2>
                </div>
                <div style='padding: 20px; background: #f9f9f9;'>
                    <p>Dear {$data['patient_name']},</p>
                    <p>Thank you for your payment. Here are the details:</p>
                    <ul>
                        <li><strong>Bill Number:</strong> {$data['bill_number']}</li>
                        <li><strong>Amount Paid:</strong> {$data['currency']} {$data['paid_amount']}</li>
                        <li><strong>Payment Method:</strong> {$data['payment_method']}</li>
                        <li><strong>Date:</strong> {$data['payment_date']}</li>
                    </ul>
                    <p>This receipt serves as proof of payment.</p>
                    <p>Thank you for choosing {$siteName}.</p>
                </div>
            </div>"
        ]
    ];
    
    return $templates[$type] ?? null;
}

// SMS Templates
function getSMSTemplate($type, $data = []) {
    $siteSettings = getSiteSettings();
    $siteName = $siteSettings['site_name'];
    
    $templates = [
        'appointment_confirmation' => "Your appointment with Dr. {$data['doctor_name']} on {$data['appointment_date']} at {$data['appointment_time']} has been confirmed. ID: {$data['appointment_id']}. - {$siteName}",
        
        'appointment_reminder' => "Reminder: You have an appointment with Dr. {$data['doctor_name']} tomorrow at {$data['appointment_time']}. Please arrive 15 minutes early. - {$siteName}",
        
        'bill_payment' => "Payment of {$data['currency']} {$data['paid_amount']} received for Bill #{$data['bill_number']}. Thank you! - {$siteName}"
    ];
    
    return $templates[$type] ?? null;
}
?>