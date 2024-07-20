<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Services\MoosendApiService;

class EmailTemplateController extends Controller
{
    protected $moosendApi;

    public function __construct(MoosendApiService $moosendApi)
    {
        $this->moosendApi = $moosendApi;
    }

    public function index()
    {
        return view('email_templates.index', get_defined_vars());
    }

    public function add()
    {
        return view('email_templates.add', get_defined_vars());
    }

    public function fetch()
    {
        $list = EmailTemplate::orderBy('id', 'DESC');

        return Datatables::of($list)
            ->addColumn('action', function($row){
                $html = '';
                $html .= '
                    <a href="'.route('email.templates.delete', $row->id).'" class="me-2 delete-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Record">
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
            'content' => 'required|string',
        ]);

        EmailTemplate::create([
            'name' => $validated['name'],
            'content' => $validated['content'],
        ]);
        return redirect()->route('email.templates.index')->with('success', 'Email template created successfully.');
    }

    public function delete($id)
    {
        $emailTemplate = EmailTemplate::find($id);
        $emailTemplate->delete();
        return redirect()->route('email.templates.index')->with('success', 'Email template deleted successfully.');
    }
}
