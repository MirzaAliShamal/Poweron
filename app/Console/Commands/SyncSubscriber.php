<?php

namespace App\Console\Commands;

use App\Models\EmailList;
use App\Models\Subscriber;
use Illuminate\Console\Command;
use App\Services\MoosendApiService;

class SyncSubscriber extends Command
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
    protected $signature = 'sync-subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Subscriber';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emailLists = EmailList::all();

        foreach ($emailLists as $list) {
            $response = $this->moosendApi->get('/lists/'.$list->moosend_id.'/subscribers/Unsubscribed.json', ['PageSize' => '1000000']);
            $subscribers = $response->json()['Context']['Subscribers'];

            foreach ($subscribers as $sub) {
                if ($sub['SubscribeType'] == '2') {
                    Subscriber::updateOrCreate(['email' => $sub['Email']],[
                        'subscribed' => 0,
                    ]);
                }
            }
        }
    }
}
