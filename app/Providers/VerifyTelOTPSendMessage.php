<?php

namespace App\Providers;

use App\Providers\OTPSendMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerifyTelOTPSendMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OTPSendMessage  $event
     * @return void
     */
    public function handle(OTPSendMessage $event)
    {
        $infoTel = $event->tel;
        $infoMessage = $event->message;

        $args = "api_key=JcRcnFsYNMpttCb&password=Ne!n0H@24&sender=Netnoh&message=".$infoMessage."&phone=237".$infoTel."&flag=long_sms";

        $url = "https://app.lmtgroup.com/bulksms/api/v1/push";

        $header = array("Content-Type: application/x-www-form-urlencoded;charset=utf-8" );

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Response
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }
}
