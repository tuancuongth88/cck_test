<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\DistributionNotification;
use App\Notifications\EntryConfirmNotification;
use App\Notifications\EntryDeclineNotification;
use App\Notifications\EntryRejectionNotification;
use App\Notifications\EntryRequestingNotification;
use App\Notifications\InterviewAdjustmentAdjustedNotification;
use App\Notifications\InterviewAdjustmentAdjustingNotification;
use App\Notifications\InterviewCancelNotification;
use App\Notifications\InterviewDeclineNotification;
use App\Notifications\InterviewHrRejectTimeNotification;
use App\Notifications\InterviewNoPassNotification;
use App\Notifications\InterviewOfferNotification;
use App\Notifications\InterviewPassNotification;
use App\Notifications\InterviewURLSettingCompanyNotification;
use App\Notifications\InterviewURLSettingManagerNotification;
use App\Notifications\OfferConfirmNotification;
use App\Notifications\OfferDeclineNotification;
use App\Notifications\RemindAccountNotification;
use App\Notifications\RemindOnJobNotification;
use App\Notifications\ResultConfirmNotification;
use App\Notifications\ResultDeclineNotification;
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
    const TYPE_NOTIFY_OFFER_REQUESTING = 40;
    const TYPE_NOTIFY_OFFER_CONFIRM = 8;
    const TYPE_NOTIFY_RESULT_EXPIRATION = 9;
    const TYPE_NOTIFY_RESULT_CONFIRM = 10;
    const TYPE_NOTIFY_RESULT_DECLINE = 12;


    const TYPE_NOTIFY_INTERVIEW_ADJUSTMENT_ADJUSTING = 11;
    const TYPE_NOTIFY_INTERVIEW_CANCEL = 20;
    const TYPE_NOTIFY_INTERVIEW_DECLINE = 13;
    const TYPE_NOTIFY_INTERVIEW_URL_SETTING_MANAGER = 14;
    const TYPE_NOTIFY_INTERVIEW_URL_SETTING_COMPANY = 15;
    const TYPE_NOTIFY_INTERVIEW_PASS = 16;
    const TYPE_NOTIFY_INTERVIEW_OFFER = 17;
    const TYPE_NOTIFY_INTERVIEW_NO_PASS = 30;
    const TYPE_NOTIFY_INTERVIEW_ADJUSTMENT_ADJUSTED = 18;
    const TYPE_NOTIFY_INTERVIEW_HR_REJECT = 19;


    public function send($user, $data, $partViewWeb = null, $subjectEmail = null, $viewEmail = null, $typeNotify = self::TYPE_NOTIFY_REMIND_ACCOUNT)
    {
        try {
            $notify = [
                'permission' => User::getPermissionName($user),
//                'contentHTML' => $this->getView($data, $partViewWeb),
                'email' => $user->mail_address,
                'date' => Carbon::now()->format('Y/m/d'),
//                'subject' => $subjectEmail,
//                'view' => $viewEmail,
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
                case self::TYPE_NOTIFY_OFFER_REQUESTING:
                    Notification::send($user, new OfferConfirmNotification($data));
                    break;
                case self::TYPE_NOTIFY_RESULT_EXPIRATION :
                    Notification::send($user, new ResultExpirationNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_ADJUSTMENT_ADJUSTING :
                    Notification::send($user, new InterviewAdjustmentAdjustingNotification($data));
                    break;
                case self::TYPE_NOTIFY_RESULT_DECLINE:
                    Notification::send($user, new ResultDeclineNotification($data));
                    break;
                case self::TYPE_NOTIFY_RESULT_CONFIRM:
                    Notification::send($user, new ResultConfirmNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_CANCEL :
                    Notification::send($user, new InterviewCancelNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_DECLINE :
                    Notification::send($user, new InterviewDeclineNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_URL_SETTING_MANAGER :
                    Notification::send($user, new InterviewURLSettingManagerNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_URL_SETTING_COMPANY :
                    Notification::send($user, new InterviewURLSettingCompanyNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_PASS :
                    Notification::send($user, new InterviewPassNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_OFFER :
                    Notification::send($user, new InterviewOfferNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_NO_PASS :
                    Notification::send($user, new InterviewNoPassNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_ADJUSTMENT_ADJUSTED :
                    Notification::send($user, new InterviewAdjustmentAdjustedNotification($data));
                    break;
                case self::TYPE_NOTIFY_INTERVIEW_HR_REJECT :
                    Notification::send($user, new InterviewHrRejectTimeNotification($data));
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
            return "リマインドのお知らせ";
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
            20 => ['web' => 'messages.remind.format_b', 'email' => 'email.remind.format_b'],
            21 => ['web' => 'messages.remind.format_e', 'email' => 'email.remind.format_e'],
            22 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],
            23 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],
            24 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],
            25 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],
            26 => ['web' => 'messages.remind.format_e', 'email' => 'email.remind.format_e'],
            27 => ['web' => 'messages.remind.format_e', 'email' => 'email.remind.format_e'],
            28 => ['web' => 'messages.remind.format_e', 'email' => 'email.remind.format_e'],
            29 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],
            30 => ['web' => 'messages.remind.format_d', 'email' => 'email.remind.format_d'],

        ];

        if (array_key_exists($type, $messages)) {
            return $messages[$type];
        }

        return [];
    }


    public function getContent($type)
    {
        $messages = [
            1 => '「申請された企業アカウントを審査する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            2 => '「申請された人材団体アカウントを審査する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            3 => '「申請されたエントリーを審査する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            4 => '「申請されたオファーを審査する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            5 => '認されたオファーに対して、「面接候補日を設定する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            6 => '承認したエントリーに対して、「面接候補日を設定する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            7 => '設定された面接に対して、「調整不可否を回答する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            8 => '「面接候補日を再設定する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            9 => '設定された面接に対して、「面接URLを発行する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            10 => '「面接に対して合否を判定する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            11 => '申請された内定に対して、「承諾か辞退かを判断する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            12 => '内定に対して、「入社日を設定する」のタスクが停滞しています。<br>マッチング管理より確認してください。',
            13 => "エントリーの辞退が設定されましたマッチング管理よりエントリー辞退の内容を確認してください。",
            14 => "エントリーの承認可否が設定されました承認可否は以下の通りです。",
            15 => 'エントリーの承認可否が設定されました。承認可否は以下の通りです。マッチング管理より詳細を確認してください。',
            16 => '求人にエントリーが実行されました。<br>マッチング管理よりエントリーの内容を確認してください。',
            17 => 'オファーの承認可否が設定されました。承認可否は以下の通りです。',
            18 => 'オファーの承認可否が設定されました。承認可否は以下の通りです。',
            19 => '以下の内定の承諾が期限切れになりました。<br>マッチング管理より確認してください。', // table:Result Official offer h as expired
            20 => '以下の面接候補日が設定されました。<br>マッチング管理より詳細を確認してください。',
            21 => '以下の内定の承諾可否が設定されました。<br>マッチングより確認してください。', // table:Result Official offer approval msg
            22 => '面接結果は以下の通りです。<br>マッチング管理より確認してください。',
            23 => '以下の面接日が決定しましたZoomのURL発行を行い、<br>マッチング管理より設定してください。',
            24 => 'Zoom URLの発行が完了しました。<br>マッチング管理より確認してください。',
            25 => '以下の面接日が決定しました。<br>マッチング管理より詳細を 確認してください。',
            26 => '面接結果は以下の通りです。<br>マッチング管理より確認してください。',
            27 => '面接結果は以下の通りです。<br>マッチング管理より確認してください。',
            28 => '面接結果は以下の通りです。<br>マッチング管理より確認してください。',
            29 => '以下の面接候補日の再調整依頼が実行されました。<br>マッチング管理より詳細を確認してください。',
            30 => '以下の内定の承諾期限の変更が設定されました。<br>マッチング管理より確認してください。', // table:Result Change of deadline for official offer acceptance has been set
            //result
            31 => '以下の内定の辞退が設定されました。<br>マッチング管理より確認してください。', // table:Result  official offer decline msg
            32 => '以下の内定の入社日が設定されました。<br>マッチング管理より確認してください。', // table:Result  The following official hire dates have been set.
        ];

        if (array_key_exists($type, $messages)) {
            return $messages[$type];
        }

        return 'のタスクが停滞しています。<br>マッチング管理より確認してください。';
    }
}
