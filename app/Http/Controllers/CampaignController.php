<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\EmailList;
use Illuminate\Http\Request;
use App\Models\EmailCampaign;
use App\Models\EmailTemplate;
use App\Enums\EmailCampaignStatus;
use App\Services\MoosendApiService;
use App\Models\EmailCampaignEmailLists;

class CampaignController extends Controller
{
    protected $moosendApi;

    public function __construct(MoosendApiService $moosendApi)
    {
        $this->moosendApi = $moosendApi;
    }

    public function index()
    {
        return view('campaigns.index', get_defined_vars());
    }

    public function add()
    {
        $emailLists = EmailList::orderBy('name', 'ASC')->get();
        $emailTemplates = EmailTemplate::orderBy('name', 'ASC')->get();
        $senders = [];
        $response = $this->moosendApi->get('/senders/find_all.json');
        if ($response->successful()) {
            $senders = $response['Context'];
        }
        return view('campaigns.add', get_defined_vars());
    }

    public function fetch()
    {
        $list = EmailCampaign::with('emailTemplate', 'emailLists')->orderBy('id', 'DESC');

        return Datatables::of($list)
            ->editColumn('name', function($row) {
                $emailLists = '';
                foreach ($row->emailLists as $lists) {
                    $emailLists .= $lists->emailList->name.', ';
                }

                $html = '';
                $html .= '
                    <div class="d-flex align-items-center">
				    	<div class="d-flex justify-content-start flex-column">
				    		<span class="text-dark fw-bolder fs-6">'.$row->name.'</span>
				    		<span class="text-muted fw-bold text-muted d-block fs-7">'.$emailLists.'</span>
				    	</div>
				    </div>
                ';
                return $html;
            })
            ->addColumn('emailTemplate', function($row) {
                return $row->emailTemplate->name;
            })
            ->editColumn('status', function($row) {
                $html = '';
                if ($row->status == EmailCampaignStatus::DRAFT) {
                    $html .= '<span class="badge badge-light-primary">draft</span>';
                } else if ($row->status == EmailCampaignStatus::QUEUEDFORSENDING) {
                    $html .= '<span class="badge badge-light-success">Queued for sending</span>';
                } else if ($row->status == EmailCampaignStatus::SENT) {
                    $html .= '<span class="badge badge-light-success">Sent</span>';
                } else if ($row->status == EmailCampaignStatus::NOTENOUGHCREDITS) {
                    $html .= '<span class="badge badge-light-success">Not enough credits</span>';
                } else if ($row->status == EmailCampaignStatus::AWAITINGDELIVERY) {
                    $html .= '<span class="badge badge-light-success">Awaiting Delivery</span>';
                } else if ($row->status == EmailCampaignStatus::SENDING) {
                    $html .= '<span class="badge badge-light-success">Sending</span>';
                } else if ($row->status == EmailCampaignStatus::DELETED) {
                    $html .= '<span class="badge badge-light-danger">Deleted</span>';
                }
                return $html;
            })
            ->addColumn('action', function($row){
                $html = '';
                $html .= '
                    <a href="'.route('campaigns.send', $row->id).'" class="me-2 send-now" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Now">
                        <i class="bi bi-alarm fs-4 cursor-pointer text-primary"></i>
                    </a>
                    <a href="'.route('campaigns.delete', $row->id).'" class="me-2 delete-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Record">
                        <i class="bi bi-trash fs-4 cursor-pointer text-danger"></i>
                    </a>
                ';
                return $html;
            })
            ->rawColumns(['name', 'emailTemplate', 'status', 'action'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'subject' => 'required|string',
            'sender_email' => 'required|string',
            'email_list_id' => 'required|array',
            'email_list_id.*' => 'exists:email_lists,id',
            'email_template_id' => 'required|exists:email_templates,id',
            // 'scheduled_at' => 'nullable'
        ]);

        $emailLists = EmailList::whereIn('id', $validated['email_list_id'])->selectRaw('moosend_id as MailingListID')->get()->toArray();
        $emailTemplate = EmailTemplate::find($validated['email_template_id']);
        $response = $this->moosendApi->post('/campaigns/create.json', [
            'Name' => $validated['name'],
            'Subject' => $validated['subject'],
            'SenderEmail' => $validated['sender_email'],
            'HTMLContent' => $emailTemplate->content,
            'MailingLists' => $emailLists,
        ]);

        if ($response->successful()) {
            $moosendId = $response->json()['Context'];
            $emailCampaign = EmailCampaign::create([
                'name' => $validated['name'],
                'subject' => $validated['subject'],
                'sender_email' => $validated['sender_email'],
                'email_template_id' => $validated['email_template_id'],
                'status' => EmailCampaignStatus::DRAFT,
                'moosend_id' => $moosendId,
            ]);

            if (count($validated['email_list_id']) > 0) {
                foreach ($validated['email_list_id'] as $list) {
                    EmailCampaignEmailLists::create([
                        'email_list_id' => $list,
                        'email_campaign_id' => $emailCampaign->id,
                    ]);
                }
            }

            return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
        }

        return back()->with('error', 'Failed to create campaign.');
    }

    public function delete($id)
    {
        $emailCampaign = EmailCampaign::find($id);
        $response = $this->moosendApi->delete("campaigns/{$emailCampaign->moosend_id}/delete.json");

        if ($response->successful()) {
            $emailCampaign->delete();
            return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
        }

        return back()->with('error', 'Failed to delete campaign.');
    }

    public function send($id)
    {
        $emailCampaign = EmailCampaign::find($id);
        $response = $this->moosendApi->post('/campaigns/'.$emailCampaign->moosend_id.'/send.json');

        if ($response->successful()) {
            return redirect()->route('campaigns.index')->with('success', 'Campaign sent successfully.');
        }

        return back()->with('error', 'Failed to send campaign.');
    }

    public function schedule(Request $request, $id)
    {
        $emailCampaign = EmailCampaign::find($id);
        $response = $this->moosendApi->post('/campaigns/'.$emailCampaign->moosend_id.'/schedule.json', [
            'DateTime' => $request->schedule_at,
            'Timezone' => $request->timezone
        ]);


        if ($response->successful()) {
            $emailCampaign->scheduled_at = Carbon::parse($request->schedule_at)->format('Y-m-d H:i');
            $emailCampaign->status = EmailCampaignStatus::SCHEDULED;
            $emailCampaign->save();

            return redirect()->route('campaigns.index')->with('success', 'Campaign scheduled successfully.');
        }

        return back()->with('error', 'Failed to schedule campaign.');
    }
}
