<?php

namespace App\Console\Commands;

use App\Models\EmailCampaign;
use Illuminate\Console\Command;
use App\Services\MoosendApiService;

class SyncCampaign extends Command
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
    protected $signature = 'sync-campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Campaign';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = $this->moosendApi->get('campaigns.json', ['PageSize' => '1000000']);
        $campaigns = $response->json()['Context']['Campaigns'];

        foreach ($campaigns as $camp) {
            EmailCampaign::updateOrCreate(['moosend_id' => $camp['ID']], [
                'status' => $camp['Status'],
                'recipients_count' => $camp['RecipientsCount'],
                'total_sent' => $camp['TotalSent'],
                'total_opens' => $camp['TotalOpens'],
                'total_bounces' => $camp['TotalBounces'],
            ]);
        }
    }
}
