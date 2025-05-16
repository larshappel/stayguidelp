<?php
// Configure your Subject Prefix and Recipient here
$subjectPrefix = 'STAYGUIDE問い合わせ';
$emailTo       = 'developer@k-cs.co.jp';
$errors = array(); // array to hold validation errors
$data   = array(); // array to pass back data
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = stripslashes(trim($_POST['name']));
    $email   = stripslashes(trim($_POST['email']));
    $subject = stripslashes(trim($_POST['subject']));
    $message = stripslashes(trim($_POST['message']));
    if (empty($name)) {
        $errors['name'] = 'お名前は必須です';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'メールアドレスの形式が正しくありません';
    }
    if (empty($subject)) {
        $errors['subject'] = '要件は必須です';
    }
    if (empty($message)) {
        $errors['message'] = 'メッセージ内容は必須です';
    }
    // if there are any errors in our errors array, return a success boolean or false
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        $subject = "$subjectPrefix $subject";
        $body    = '
            <strong>Name: </strong>'.$name.'<br />
            <strong>Email: </strong>'.$email.'<br />
            <strong>Message: </strong>'.nl2br($message).'<br />
        ';
        $headers  = "MIME-Version: 1.1" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
        $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
        $headers .= "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>' . PHP_EOL;
        $headers .= "From: " . "=?UTF-8?B?".base64_encode($name)."?=" . "<$email>" . PHP_EOL;
        $headers .= "Return-Path: $emailTo" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
        $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;
        mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", $body, $headers);
                // --- ▼送信者への控えメール処理を追加 ---
        $autoReplySubject = "【STAYGUIDE】お問い合わせありがとうございます";
        $autoReplyBody = '
            '.$name.' 様<br><br>
            このたびはSTAYGUIDEへお問い合わせいただき、誠にありがとうございます。<br>
            以下の内容で受け付けました。担当者より1営業日以内にご連絡差し上げます。<br><br>
            <strong>お名前:</strong> '.$name.'<br>
            <strong>メールアドレス:</strong> '.$email.'<br>
            <strong>件名:</strong> '.$subject.'<br>
            <strong>メッセージ:</strong><br>'.nl2br($message).'<br><br>
            どうぞよろしくお願いいたします。<br>
            STAYGUIDE サポート
        ';

        $autoReplyHeaders  = "MIME-Version: 1.1" . PHP_EOL;
        $autoReplyHeaders .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $autoReplyHeaders .= "From: STAYGUIDE <no-reply@stayguide.jp>" . PHP_EOL;
        $autoReplyHeaders .= "Reply-To: $emailTo" . PHP_EOL;

        mail($email, "=?utf-8?B?" . base64_encode($autoReplySubject) . "?=", $autoReplyBody, $autoReplyHeaders);
        // --- ▲ここまで追加 ---
        $data['success'] = true;
        $data['message'] = 'このたびはお問合せいただき、誠にありがとうございます。<br>担当者より1営業日以内にご連絡差し上げますので、今しばらくお待ちください。';
    }
    // return all our data to an AJAX call
    echo json_encode($data);
}
