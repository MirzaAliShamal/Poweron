<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\EmailList;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Imports\SubscriberImport;
use App\Services\MoosendApiService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SubscriberCountImport;
use Illuminate\Validation\ValidationException;

class SubscriberController extends Controller
{
    protected $moosendApi;

    public function __construct(MoosendApiService $moosendApi)
    {
        $this->moosendApi = $moosendApi;
    }

    public function index()
    {
        $emailLists = EmailList::orderBy('name', 'ASC')->get();
        return view('subscribers.index', get_defined_vars());
    }

    public function add()
    {
        $emailLists = EmailList::orderBy('name', 'ASC')->get();
        return view('subscribers.add', get_defined_vars());
    }

    public function fetch()
    {
        $list = Subscriber::with('emailList')->orderBy('id', 'DESC');

        return Datatables::of($list)
            ->addColumn('emailList', function($row) {
                return $row->emailList->name;
            })
            ->editColumn('subscribed', function($row) {
                $html = '';
                if ($row->subscribed) {
                    $html .= 'Subscribed';
                } else {
                    $html .= 'Un Subscribed';
                }

                return $html;
            })
            ->addColumn('action', function($row){
                $html = '';
                $html .= '
                    <a href="'.route('subscribers.delete', $row->id).'" class="me-2 delete-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Record">
                        <i class="bi bi-trash fs-4 cursor-pointer text-danger"></i>
                    </a>
                ';
                return $html;
            })
            ->rawColumns(['emailList', 'subscribed', 'action'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:subscribers,email',
            'email_list_id' => 'required|exists:email_lists,id'
        ]);

        $emailList = EmailList::find($request->email_list_id);

        $response = $this->moosendApi->post('/subscribers/'.$emailList->moosend_id.'/subscribe.json', [
            'Name' => $validated['name'],
            'Email' => $validated['email'],
        ]);

        if ($response->successful()) {
            Subscriber::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'email_list_id' => $validated['email_list_id'],
            ]);
            return redirect()->route('subscribers.index')->with('success', 'Subscriber created successfully.');
        }

        return back()->with('error', 'Failed to create subscriber.');
    }

    public function delete($id)
    {
        $subscriber = Subscriber::find($id);
        $response = $this->moosendApi->post("subscribers/{$subscriber->emailList->moosend_id}/remove.json", [
            'Email' => $subscriber->email,
        ]);

        if ($response->successful()) {
            $subscriber->delete();
            return redirect()->route('subscribers.index')->with('success', 'Subscriber deleted successfully.');
        }

        return back()->with('error', 'Failed to delete subscriber.');
    }

    public function bulk(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,csv',
            ]);

            $file = $request->file;
            $rowCount = new SubscriberCountImport();
            Excel::import($rowCount, $file);

            $dataRowCount = $rowCount->rowCount - 1;

            if ($dataRowCount > 1000) {
                return redirect()->back()->with('error', 'File contains more than 1000 rows. Please upload a smaller file.');
            }

            Excel::import(new SubscriberImport($request->email_list_id), $request->file('file'));

            $emailList = EmailList::find($request->email_list_id);
            $response = $this->moosendApi->post('/subscribers/'.$emailList->moosend_id.'/subscribe_many.json', [
                'Subscribers' => Subscriber::where('sync', false)->select('name', 'email')->get()->toArray(),
            ]);

            return redirect()->back()->with('success', "Successfully imported $dataRowCount users.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing users: ' . $e->getMessage());
        } catch (ValidationException $ve) {
            return redirect()->back()->with('error', 'Please fill all the fields while importing');
        }
    }
}
