<?php
    // Load web push library
    require '../vendor/autoload.php';
    use Minishlink\WebPush\Subscription;
    use Minishlink\WebPush\WebPush;

    $web_push = new WebPush(['VAPID' => [
        'subject' => 'https://softworld.selfip.com/lms_pwa/',
        'publicKey' => 'BLTcBSpIX05ltriAEXaYqP07pV0pXdRhWB-n0CkQeMZuLeKpnPWWlvNlZLQe37u3WtpcspkRsKp6al0yMcXir_k',
        'privateKey' => 'HNyr9fGraUGIPBx9sd-vs4rH_nChiO4Njsx4bnfY9pk'
    ]]);

    if (isset($_POST)) {
        $sender_name = $_POST['sender_name'];
        $subscriptions = explode('|', $_POST['subscriptions']);

        foreach ($subscriptions as $subscription) {
            // Get subscription
            $sub = Subscription::create(json_decode($subscription, TRUE));
    
            $message = $sender_name . ' requests to login.';
    
            // Send notification
            $result = $web_push->sendOneNotification(
                $sub,
                $message
            );
    
            echo 'Notification sent.';
        }
    }
?>