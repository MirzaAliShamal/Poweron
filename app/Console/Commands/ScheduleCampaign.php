<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\EmailList;
use App\Models\EmailCampaign;
use Illuminate\Console\Command;
use App\Enums\EmailCampaignType;
use App\Enums\EmailCampaignStatus;
use App\Services\MoosendApiService;

class ScheduleCampaign extends Command
{
    protected $moosendApi;

    public function __construct(MoosendApiService $moosendApi)
    {
        parent::__construct();
        $this->moosendApi = $moosendApi;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule-campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule Campaign';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $campaigns = EmailCampaign::where('type', EmailCampaignType::SCHEDULED)
            ->where('moosend_id', null)
            ->get();

        foreach ($campaigns as $camp) {
            $diff = Carbon::now()->diffInSeconds(Carbon::parse($camp->scheduled_at), false);

            if ($diff <= 0) {
                $emailLists = EmailList::whereIn('id', $camp->emailLists()->pluck('email_list_id')->toArray())
                    ->selectRaw('moosend_id as MailingListID')
                    ->get()->toArray();

                $response = $this->moosendApi->post('/campaigns/create.json', [
                    'Name' => $camp->name,
                    'Subject' => $camp->subject,
                    'SenderEmail' => $camp->sender_email,
                    'HTMLContent' => $camp->emailTemplate->content,
                    'MailingLists' => $emailLists,
                ]);

                if ($response->successful()) {
                    $moosendId = $response->json()['Context'];
                    $emailCampaign = EmailCampaign::updateOrCreate(['id' => $camp->id], [
                        'status' => EmailCampaignStatus::SENTTOAPI,
                        'moosend_id' => $moosendId,
                    ]);

                    $this->moosendApi->post('/campaigns/'.$moosendId.'/send.json');
                }
            }
        }
    }
}
