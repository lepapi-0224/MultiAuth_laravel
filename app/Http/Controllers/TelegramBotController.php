<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Str;
use DB;


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
                    'id' => $act->getMessage()->getChat()->getId()
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
                ->update(['user_id' => $lastData['id']]);

            return back()->with("success", "Votre compte est connecte a telegram !!!");
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
