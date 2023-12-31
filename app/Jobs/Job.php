<?php


namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    abstract public function send($user, $data, $partViewWeb, $subjectEmail, $viewEmail);
    abstract public function getView($data, $partView);
    abstract public function getSubject($type);
    abstract public function getContent($type);
}
