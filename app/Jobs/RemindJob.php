<?php

namespace App\Jobs;

use App\Notifications\DistributionNotification;
use App\Notifications\EntryConfirmNotification;
use App\Notifications\EntryDeclineNotification;
use App\Notifications\EntryRejectionNotification;
use App\Notifications\EntryRequestingNotification;
use App\Notifications\OfferConfirmNotification;
use App\Notifications\OfferDeclineNotification;
use App\Notifications\RemindAccountNotification;
use App\Notifications\RemindOnJobNotification;
use App\Notifications\ResultExpirationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RemindJob extends Job
{
    const TYPE_NOTIFY_REMIND_ACCOUNT = 1;
    const TYPE_NOTIFY_DISTRIBUTION = 2;
    const TYPE_NOTIFY_ENTRY_REQUESTING = 3;
    const TYPE_NOTIFY_ENTRY_DECLINE = 4;
    const TYPE_NOTIFY_ENTRY_REJECTION = 5;
    const TYPE_NOTIFY_ENTRY_CONFIRM = 6;

    const TYPE_NOTIFY_OFFER_DECLINE = 7;
    const TYPE_NOTIFY_OFFER_CONFIRM = 8;

    const TYPE_NOTIFY_RESULT_EXPIRATION = 9;


    public function send($user, $data, $partViewWeb, $subjectEmail, $viewEmail, $typeNotify = self::TYPE_NOTIFY_REMIND_ACCOUNT)
    {
        try {
            $notify = [
                'contentHTML' => $this->getView($data, $partViewWeb),
                'email' => $user->mail_address,
                'date' => Carbon::now()->format('Y/m/d'),
                'subject' => $subjectEmail,
                'view' => $viewEmail,
            ];
            $data = array_merge($data, $notify);
            switch ($typeNotify) {
                case self::TYPE_NOTIFY_REMIND_ACCOUNT :
                    Notification::send($user, new RemindAccountNotification($data));
                    break;
                case self::TYPE_NOTIFY_DISTRIBUTION :
                    Notification::send($user, new DistributionNotification($data));
                    break;
                case self::TYPE_NOTIFY_ENTRY_DECLINE :
                    Notification::send($user, new EntryDeclineNotification($data));
                    break;
                case self::TYPE_NOTIFY_ENTRY_REJECTION :
                    Notification::send($user, new EntryRejectionNotification($data));
                    break;
                case self::TYPE_NOTIFY_ENTRY_CONFIRM :
                    Notification::send($user, new EntryConfirmNotification($data));
                    break;
                case self::TYPE_NOTIFY_ENTRY_REQUESTING :
                    Notification::send($user, new EntryRequestingNotification($data));
                    break;
                case self::TYPE_NOTIFY_OFFER_DECLINE :
                    Notification::send($user, new OfferDeclineNotification($data));
                    break;
                case self::TYPE_NOTIFY_OFFER_CONFIRM :
                    Notification::send($user, new OfferConfirmNotification($data));
                    break;
                case self::TYPE_NOTIFY_RESULT_EXPIRATION :
                    Notification::send($user, new ResultExpirationNotification($data));
                    break;
                default:
                    Notification::send($user, new RemindOnJobNotification($data));
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    public function getView($data, $partView)
    {
        $data = view($partView, compact('data'));
        return $data->render();
    }

    /**
     * Type 1: Remind Type 2: Notification
     */
    public function getSubject($type): string
    {
        if ($type == self::TYPE_NOTIFY_REMIND_ACCOUNT) {
            return "海外人材マッチングシステム(仮)";
        } else {
            return "";
        }
    }

    public function partViewRemind($type): array
    {
        $messages = [
            1 => ['web' => 'messages.remind.format_a', 'email' => 'email.remind.format_a'],
            2 => ['web' => 'messages.remind.format_b', 'email' => 'email.remind.format_b'],
            3 => ['web' => 'messages.remind.format_b', 'email' => 'email.remind.format_b'],
            4 => ['web' => 'messages.remind.format_b', 'email' => 'email.remind.format_b'],
            5 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
            6 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
            7 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
            8 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
            9 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],
            10 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],
            11 => ['web' => 'messages.remind.format_e', 'email' => 'email.remind.format_e'],
            12 => ['web' => 'messages.remind.format_e', 'email' => 'email.remind.format_e'],
            13 => ['web' => 'messages.remind.format_f', 'email' => 'email.remind.format_f'],
            14 => ['web' => 'messages.remind.format_b', 'email' => 'email.remind.format_b'],
            15 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
            16 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
            17 => ['web' => 'messages.remind.format_b', 'email' => 'email.remind.format_b'],
            18 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
            19 => ['web' => 'messages.remind.format_c', 'email' => 'email.remind.format_c'],
        ];

        if (array_key_exists($type, $messages)) {
            return $messages[$type];
        }

        return [];
    }


    public function getContent($type)
    {
        $messages = [
            1 => '申請された企業アカウントを審査するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            2 => '申請された人材団体アカウントを審査するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            3 => '申請されたエントリーを審査するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            4 => '申請されたオファーを審査するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            5 => '認されたオファーに対して、面接候補日を設定するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            6 => '承認したエントリーに対して、面接候補日を設定するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            7 => '設定された面接に対して、調整不可否を回答するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            8 => '面接候補日を再設定するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            9 => '設定された面接に対して、面接URLを発行するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            10 => '面接に対して合否を判定する。のタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            11 => '申請された内定に対して、承諾か辞退かを判断する。のタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            12 => '内定に対して、入社日を設定するのタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。',
            13 => "エントリーの辞退が設定されました。<br><a href=" . url('/matching-management') . "> こちら</a>よりエントリー辞退の内容を確認してください。",
            14 => "エントリーの承認可否が設定されました。承認可否は以下の通りです。",
            15 => 'エントリーの承認可否が設定されました。承認可否は以下の通りです。<br><a href="/matching-management">こちら</a>より詳細を確認してください。',
            16 => '求人にエントリーが実行されました。<br><a href="/matching-management">こちら</a>よりエントリーの内容を確認してください。',
            17 => 'オファーの承認可否が設定されました。承認可否は以下の通りです。',
            18 => 'オファーの承認可否が設定されました。承認可否は以下の通りです。',
            19 => '以下の内定の承諾が期限切れになりました。<br><a href="/matching-management">こちら</a>より確認してください。'

        ];

        if (array_key_exists($type, $messages)) {
            return $messages[$type];
        }

        return 'のタスクが停滞しています。<br><a href="/matching-management"> こちら</a>より確認してください。';
    }
}
