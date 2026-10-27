<?php
/**
 * inquiry-submit.php
 * Receives the home page enquiry form (AJAX), validates it and saves it into `inquiries`.
 */
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

function out(bool $ok, string $msg, int $code = 200): void {
    http_response_code($code);
    echo json_encode(['ok' => $ok, 'message' => $msg]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(false, 'Invalid request.', 405);
if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) out(false, 'Your session expired. Please refresh the page and try again.', 419);
if (!empty($_POST['website'])) out(true, 'Thank you. We will be in touch shortly.');   // honeypot: bots fill this field
if (time() - (int)($_SESSION['last_inquiry'] ?? 0) < 20) out(false, 'Please wait a few seconds before sending another enquiry.', 429);

$clean = function (string $k, int $max) { return mb_substr(trim(strip_tags((string)($_POST[$k] ?? ''))), 0, $max); };
$name    = $clean('name', 120);
$email   = $clean('email', 160);
$phone   = $clean('phone', 30);
$country = $clean('country', 80);
$product = $clean('product', 160);
$message = mb_substr(trim(strip_tags((string)($_POST['message'] ?? ''))), 0, 2000);

if (mb_strlen($name) < 2)                              out(false, 'Please enter your name.', 422);
if (!filter_var($email, FILTER_VALIDATE_EMAIL))        out(false, 'Please enter a valid email address.', 422);
if ($phone !== '' && !preg_match('/^[0-9+\-\s()]{6,30}$/', $phone)) out(false, 'Please enter a valid phone number.', 422);
if (mb_strlen($message) < 10)                          out(false, 'Please describe your requirement in a few words.', 422);

$subject = 'Website enquiry' . ($product !== '' ? ': ' . $product : '');
$lines = [];
if ($product !== '') $lines[] = 'Product: ' . $product;
if ($country !== '') $lines[] = 'Country: ' . $country;
if ($phone   !== '') $lines[] = 'Phone/WhatsApp: ' . $phone;
$lines[] = '';
$lines[] = $message;
$body = implode("\n", $lines);
$phone_col = mb_substr(preg_replace('/[^0-9+]/', '', $phone), 0, 11);   // inquiries.phone is varchar(11); full number is kept in the message

try {
    $st = $conn->prepare('INSERT INTO inquiries (name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, 0)');
    $st->bind_param('sssss', $name, $email, $phone_col, $subject, $body);
    $st->execute();
    $st->close();
} catch (Throwable $ex) {
    error_log('[inquiry] ' . $ex->getMessage());
    out(false, 'We could not save your enquiry right now. Please call or email us directly.', 500);
}

// Best-effort email alert to the contact address (silently ignored if the server has no mail setup)
$contact = get_contact();
$host = parse_url($site, PHP_URL_HOST) ?: 'localhost';
@mail($contact['email'], $subject, "Name: $name\nEmail: $email\n\n$body",
      "From: Website <noreply@$host>\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8");

$_SESSION['last_inquiry'] = time();
out(true, 'Thank you, ' . $name . '. Your enquiry has been received and we will get back to you soon.');
