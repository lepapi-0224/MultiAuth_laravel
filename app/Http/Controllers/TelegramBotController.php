<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\OTPSendMessage;
use Illuminate\Http\Request;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Str;
use Tzsk\Otp\Facades\Otp;

class TelegramBotController extends Controller
{

    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
        $numbers = [];
        $number = auth()->user()->telephone_number;
        
        foreach ($activity as $act) {
            //echo $act->getMessage()->getText()." "."<br>";
            if ($act->getMessage()->getText() == $number) {
                $numbers[] = [
                    'number' => $act->getMessage()->getText(),
                    'id'     => $act->getMessage()->getChat()->getId()
                ];
            }
        }
        //dd($numbers);
        $lastData = end($numbers);

        $user = User::where('telephone_number', $lastData['number'])->value('telephone_number');
        if (empty($user)) {

            return back()->with("danger", "Votre numero ne correspond pas !!");
        } else {

            User::where('telephone_number', $lastData['number'])
                ->update(['user_id' => $lastData['id']
            ]);
            
            $unique_secret = md5('12345678');
            $otp = Otp::digits(8)->expiry(30)->generate($unique_secret); // 8 digits, 30 min expiry
            
            //send OTP to phone number client
            event(new OTPSendMessage($lastData['number'], $otp));
   
            Telegram::sendMessage([
                'chat_id' => $lastData['id'],
                'parse_mode' => 'HTML',
                'text' => "Entrez exactement ce code pour finalisez la liaison avec votre compte Netnoh !\n"
                ."<b>$otp</b>"
            ]);

            return back()->with("success", "Veuillez renseigner par telegram le code reçu par sms pour finaliser le processus !");
        }
        
    }


    //verification du numero de telephone par telegram bot 
    public function verifyOTP(){
        $activity = Telegram::getUpdates();
        //dd($activity);

        $numbers = [];
        //$number = auth()->user()->telephone_number;
        $userid = auth()->user()->user_id;
        
        foreach ($activity as $act) {
            if ($act->getMessage()->getChat()->getId() == $userid) {
                $numbers[] = [
                    'otp' => (int)$act->getMessage()->getText(),
                    'id'  => $act->getMessage()->getChat()->getId()
                ];
            }
        }

        $lastData = end($numbers);
        //dd($lastData);
        $unique_secret = md5('12345678');
        $result = Otp::digits(8)->expiry(30)->check($lastData['otp'], $unique_secret); // -> true

        //dd($result);
        if ($result == true) {
            User::where('user_id', $lastData['id'])
                    ->update(['is_verified' => 1
            ]);

            Telegram::sendMessage([
            'chat_id' => $userid,
            'parse_mode' => 'HTML',
            'text' => "<b>Votre numero de telephone est correct !</b>"
            ]);

            Telegram::sendMessage([
                'chat_id' => $userid,
                'parse_mode' => 'HTML',
                'text' => "<b>Votre compte telegram est lie a Netnoh </b>\n"
                        ."<b>Desormais vous receverez vos notifications via ce canal</b>\n"
                        ."Nous restons a votre disposition en cas d'un soucis !"
                ]);

            return back()->with("success", "Numero de telephone verifie \n Désormais vous receverez vos notifications via telegram");

        } else {

            Telegram::sendMessage([
                'chat_id' => $userid,
                'parse_mode' => 'HTML',
                'text' => "<b>Verifiez et recopiez exactement le code recu !</b>"
                ]);

            return back()->with("success", "Verifiez et recopiez exactement le code recu par telegram !");
        }
    }


    //verification par l'application web
    public function verifyOTPweb(Request $request){
        $number = $request->user()->telephone_number;
        $request->validate(
            [
                'code' => 'required'
            ]);
        //dd($request->code);
        $unique_secret = md5('12345678');
        $result = Otp::digits(8)->expiry(30)->check($request->code, $unique_secret); // -> true
        //dd($result);
        
        if($result == true)
            { 
                //throw new \Exception("Error Processing Request ". $result);
                User::where('telephone_number', $number)
                ->update(['is_verified' => 1
                ]);

                return back()->with("success", "Numero de telephone verifie !!");
            }
            else{
                return back()->with("danger", "Verifiez et recopiez exactement le code recu !!");
            }
        
    }


    public function sendMessage()
    {
        return view('message');
    }

    public function storeMessage(Request $request)
    {
        //$user_id = $request->user()->user_id;
        //dd(env('TELEGRAM_CHANNEL_ID', ''));
        $request->validate([
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $text = "A new contact\n"
            . "<b>Email Address: </b>\n"
            . "$request->email\n"
            . "<b>Message: </b>\n"
            . $request->message;

        Telegram::sendMessage([
            'chat_id' => $request->user()->user_id,
            'parse_mode' => 'HTML',
            'text' => $text
        ]);

        return redirect()->back()->with('success', 'Message envoye avec success !!');
    }

    public function sendPhoto()
    {
        return view('photo');
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'file' => 'file|mimes:jpeg,png,gif'
        ]);

        $photo = $request->file('file');

        Telegram::sendPhoto([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
            'photo' => InputFile::createFromContents(file_get_contents($photo->getRealPath()), str::random(10) . '.' . $photo->getClientOriginalExtension())
        ]);

        return redirect()->back();
    }
}
