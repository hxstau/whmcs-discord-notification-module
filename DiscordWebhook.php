<?php

namespace WHMCS\Module\Notification\Yourmodulename;

use WHMCS\Module\Contracts\NotificationModuleInterface;

/**
 * Discord Webhook
 *
 * @copyright No Copyright. Use freely.
 * @license https://example.com/
 */
class Yourmodulename implements NotificationModuleInterface
{
    public function __construct()
    {
        $this->setDisplayName("Discord Webhook Notification")
            ->setLogoFileName('logo.png');
    }

    /*
        The below are the required classes as per the developers documentation
        https://developers.whmcs.com/notification-providers/
    */

    public function settings()
    {
        return [
            'dwh_url' => [
                'FriendlyName' => 'Webhook URL',
                'Type' => 'text',
                'Description' => 'The webhook URL to use for sending notifications to',
            ],
            'dwh_botname' => [
                'FriendlyName' => 'Bot Name',
                'Type' => 'text',
                'Description' => 'This is the name that comes up for the bot',
            ]
        ]
    }

    public function notificationSettings()
    {
        // This is retained as documentation doesn't clearly state
        // how to not use this.
        return [
            'priority' => [
                'FriendlyName' => 'Notification Priority',
                'Type' => 'dropdown',
                'Options' => [
                    'Low',
                    'Medium',
                    'High',
                ],
                'Description' => 'Choose the notification priority for the alert.',
            ],
        ];
    }

    public function testConnection($settings)
    {
        $dwh_url = $settings['dwh_url'];
        $dwh_botname = $settings['dwh_botname'];
        $dwh_message = "This is a test message. Your notifications are working!"

        throw new \Exception('Submitting webhook failed, sadly');
    }

    public function sendNotification(NotificationInterface $notification, $moduleSettings, $notificationSettings)
    {
        $api_username = $moduleSettings['api_username'];
        $api_password = $moduleSettings['api_password'];

        $priority = $notificationSettings['priority'];
        $channel = $notificationSettings['channel'];

        // Build API Request to remote service
        /* Commented out but retained for future dev
        $postData = [
            'username' => $api_username,
            'password' => $api_password,
            'priority' => $priority,
            'channel' => $channel,
            'title' => $notification->getTitle(),
            'message' => $notification->getMessage(),
            'url' => $notification->getUrl(),
            'attributes' => [],
        ];

        // Attributes vary depending on the event trigger. Loop through as necessary.
        foreach ($notification->getAttributes() as $attribute) {
            $postData['attributes'][] = [
                'label' => $attribute->getLabel(),
                'value' => $attribute->getValue(),
                'url' => $attribute->getUrl(),
                'style' => $attribute->getStyle(),
                'icon' => $attribute->getIcon(),
            ];
        }
        

        // Call the remote API
        $response = file_get_contents('https://www.example.com/?' . http_build_query($postData));
        */

        $response = $this->postToDiscord($moduleSettings);

        if (!$response) {
            // Throw a human friendly exception on error
            throw new Exception('No response received from API');
        }
    }

    /*
        That ends the required stuff. The below is the custom stuff the above stuff leverages
    */

    public function postToDiscord($settings)
    {

        /*
            This code largely based on Foghladha's work
            https://www.gaisciochmagazine.com/articles/posting_to_discord_using_php_and_webhooks.html
        */
        $msg = json_decode('
        {
            "username":"'.$settings['dwh_botname'].'",
            "content":"Some content",
            "embeds": [{
                "title":"Some title",
                "description":"Some description",
                "url":"https://example.com",
                "color":"'.$settings['dwh_color'].'",
                "author":{
                    "name":"'.$settings['dwh_botname'].'",
                    "url":"https://example.com",
                    "icon_url":"https://discord.com/assets/2d20a45d79110dc5bf947137e9d99b66.svg"
                }
            }]
        }', true);

        $ch = curl_init($settings['dwh_url']);
        $msg = "payload_json=" . urlencode(json_encode($msg))."";
        if(isset($ch)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    }
}