<?php

namespace MrVokia\MailHub\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailSender extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Send type
     */
    private $type;

    /**
     * Send params
     */
    private $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $params)
    {
        $this->type = $type;
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $params = $this->params;
        if( $type == 'Normal' ) {

            // Text send
            Mail::raw($params['html'], function ($message) use ($params) {
                if( $params['attach'] ) {
                    $message->attach($params['attach']);
                }
                $message->to($params['to'])
                        ->from(env('MAIL_USERNAME'), $params['fromName'])
                        ->subject($params['subject']);
            });
        } elseif ( $type == 'Template' ) {

            // Template send
            Mail::send($params['templateInvokeName'], $params['xsmtpapi'], function ($message) use ($params) {
                if( $params['attach'] ) {
                    $message->attach($params['attach']);
                }
                $message->to($params['to'])
                        ->from(env('MAIL_USERNAME'), $params['fromName'])
                        ->subject($params['subject']);
            });
        }
    }
}