<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\EmailList;
use Illuminate\Http\Request;
use App\Services\MoosendApiService;

class EmailListController extends Controller
{
    protected $moosendApi;

    public function __construct(MoosendApiService $moosendApi)
    {
        $this->moosendApi = $moosendApi;
    }

    public function index()
    {
        return view('email_lists.index', get_defined_vars());
    }

    public function add()
    {
        return view('email_lists.add', get_defined_vars());
    }

    public function fetch()
    {
        $list = EmailList::withCount('activeSubscribers', 'inActiveSubscribers')->orderBy('id', 'DESC');

        return Datatables::of($list)
            ->editColumn('created_at', function($row) {
                return $row->created_at->format('Y-m-d h:i A');
            })
            ->addColumn('action', function($row){
                $html = '';
                $html .= '
                    <a href="'.route('email.lists.delete', $row->id).'" class="me-2 delete-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Record">
                        <i class="bi bi-trash fs-4 cursor-pointer text-danger"></i>
                    </a>
                ';
                return $html;
            })
            ->rawColumns(['user', 'email', 'action'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $response = $this->moosendApi->post('lists/create.json', [
            'Name' => $validated['name'],
        ]);

        if ($response->successful()) {
            $moosendId = $response->json()['Context'];
            EmailList::create([
                'name' => $validated['name'],
                'moosend_id' => $moosendId,
            ]);
            return redirect()->route('email.lists.index')->with('success', 'Email list created successfully.');
        }

        return back()->with('error', 'Failed to create email list.');
    }

    public function delete($id)
    {
        $emailList = EmailList::find($id);
        $response = $this->moosendApi->delete("lists/{$emailList->moosend_id}/delete.json");

        if ($response->successful()) {
            $emailList->delete();
            return redirect()->route('email.lists.index')->with('success', 'Email list deleted successfully.');
        }

        return back()->with('error', 'Failed to delete email list.');
    }
}
