<?php

namespace Modules\Applications\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Modules\Applications\Entities\Application;
use Modules\Applications\Entities\ApplicationType;
use Modules\Employees\Entities\Department;
use Modules\Employees\Entities\Employee;
use Modules\Employees\Entities\EmployeeType;
use Yajra\Datatables\Datatables;

class ApplicationsController extends Controller
{
    protected $data = [];

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $employee = Auth::user();
        $this->data['access_level'] = EmployeeType::find($employee->type_id)->access_level;
        return view('applications::index', $this->data);
    }

    public function getAllApplications()
    {
        $applications = Application::select([
                'applications.id',
                'applications.employee_id',
                DB::raw('CONCAT(employee.first_name, " ", employee.middle_name, " ", employee.last_name) as employee_name'),
                'application_type.name as application_type',
                'applications.date_from as date_from',
                'applications.date_to as date_to',
                'applications.overtime_hours as overtime_hours',
                'applications.status as status',
                DB::raw('CONCAT(supervisor.first_name, " ", supervisor.middle_name, " ", supervisor.last_name) as supervisor_name'),
            ])
            ->leftJoin('employees as employee', 'applications.employee_id', '=', 'employee.id')
            ->leftJoin('application_type', 'applications.application_type_id', '=', 'application_type.id')
            ->leftJoin('employees as supervisor', 'applications.supervisor_id', '=', 'supervisor.id');

        return Datatables::of($applications)
            ->editColumn('status', function($application){
                $status_content = '';
                switch($application->status){
                    case 'pending':
                        $status_content = '<div class="chip"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Pending</div>';
                        break;
                    case 'approved':
                        $status_content = '<div class="chip green darken-1 white-text"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Approved</div>';
                        break;
                    case 'denied':
                        $status_content = '<div class="chip red darken-1 white-text"><i class="fa fa-ban" aria-hidden="true"></i> Denied</div>';
                }
                return $status_content;
            })
            ->addColumn('action', function($application){
                $is_owner = $application->employee_id == Auth::user()->id;
                $actions = '';
                // view
                $actions .= '<button class="btn btn-small action-btn waves-effect waves-light blue darken-1 btn-view_application" type="button" title="View" data-id="'.$application->id.'">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>';

                // edit
                // can edit only pending applications
                $actions .= $is_owner && $application->status == Application::PENDING ? '<a href="'.url('/applications/form/edit/'.$application->id).'" class="btn btn-small action-btn waves-effect waves-light yellow darken-3 btn-edit_application" title="Edit" data-id="'.$application->id.'">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>' : '';

                // start - for "can approve" employee types
                // can only approve pending applications
                // once approved or denied, it will be final and irrevocable
                // approve
                $actions .= Employee::canApprove(Auth::user()->id) && $application->status == Application::PENDING && !$is_owner ? '<button class="btn btn-small action-btn waves-effect waves-light green darken-1 btn-approve_application" type="button" title="Approve" data-id="'.$application->id.'">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        </button>' : '';

                // disapprove
                $actions .= Employee::canApprove(Auth::user()->id) && $application->status == Application::PENDING && !$is_owner ? '<button class="btn btn-small action-btn waves-effect waves-light red darken-1 btn-deny_application" type="button" title="Deny" data-id="'.$application->id.'">
                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                        </button>' : '';
                // end - for "can approve" employee types

                // delete
                // can delete only pending applications
                $actions .= $is_owner && $application->status == Application::PENDING ? '<button class="btn btn-small action-btn waves-effect waves-light red darken-3 btn-delete_application" type="button" title="Delete" data-id="'.$application->id.'">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>' : '';

                return $actions;
            })
            ->make(true);
    }

    public function getSupervisedApplications(){
        $supervisor_id = Auth::user()->id;
        $applications = Application::select([
                'applications.id',
                'applications.employee_id',
                DB::raw('CONCAT(employee.first_name, " ", employee.middle_name, " ", employee.last_name) as employee_name'),
                'application_type.name as application_type',
                'applications.date_from as date_from',
                'applications.date_to as date_to',
                'applications.overtime_hours as overtime_hours',
                'applications.status as status',
                DB::raw('CONCAT(supervisor.first_name, " ", supervisor.middle_name, " ", supervisor.last_name) as supervisor_name'),
            ])
            ->leftJoin('employees as employee', 'applications.employee_id', '=', 'employee.id')
            ->leftJoin('application_type', 'applications.application_type_id', '=', 'application_type.id')
            ->leftJoin('employees as supervisor', 'applications.supervisor_id', '=', 'supervisor.id')
            ->where('employee_id', $supervisor_id)
            ->orWhere('supervisor_id', $supervisor_id);

        return Datatables::of($applications)
            ->editColumn('status', function($application){
                $status_content = '';
                switch($application->status){
                    case 'pending':
                        $status_content = '<div class="chip"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Pending</div>';
                        break;
                    case 'approved':
                        $status_content = '<div class="chip green darken-1 white-text"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Approved</div>';
                        break;
                    case 'denied':
                        $status_content = '<div class="chip red darken-1 white-text"><i class="fa fa-ban" aria-hidden="true"></i> Denied</div>';
                }
                return $status_content;
            })
            ->addColumn('action', function($application){
                $is_owner = $application->employee_id == Auth::user()->id;
                $actions = '';
                // view
                $actions .= '<button class="btn btn-small action-btn waves-effect waves-light blue darken-1 btn-view_application" type="button" title="View" data-id="'.$application->id.'">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>';

                // edit
                // can edit only pending applications
                $actions .= $is_owner && $application->status == Application::PENDING ? '<a href="'.url('/applications/form/edit/'.$application->id).'" class="btn btn-small action-btn waves-effect waves-light yellow darken-3 btn-edit_application" title="Edit" data-id="'.$application->id.'">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>' : '';

                // start - for "can approve" employee types
                // approve
                $actions .= Employee::canApprove(Auth::user()->id) && $application->status == Application::PENDING && !$is_owner ? '<button class="btn btn-small action-btn waves-effect waves-light green darken-1 btn-approve_application" type="button" title="Approve" data-id="'.$application->id.'">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        </button>' : '';

                // disapprove
                $actions .= Employee::canApprove(Auth::user()->id) && $application->status == Application::PENDING && !$is_owner ? '<button class="btn btn-small action-btn waves-effect waves-light red darken-1 btn-deny_application" type="button" title="Deny" data-id="'.$application->id.'">
                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                        </button>' : '';
                // end - for "can approve" employee types

                // delete
                // can delete only pending applications
                $actions .= $is_owner && $application->status == Application::PENDING ? '<button class="btn btn-small action-btn waves-effect waves-light red darken-3 btn-delete_application" type="button" title="Delete" data-id="'.$application->id.'">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>' : '';

                return $actions;
            })
            ->make(true);
    }

    public function getEmployeeApplications($employee_id)
    {
        $employee_id = $employee_id ? $employee_id : Auth::user()->id;
        $applications = Application::select([
                'applications.id',
                'applications.employee_id',
                DB::raw('CONCAT(employee.first_name, " ", employee.middle_name, " ", employee.last_name) as employee_name'),
                'application_type.name as application_type',
                'applications.date_from as date_from',
                'applications.date_to as date_to',
                'applications.overtime_hours as overtime_hours',
                'applications.status as status',
                DB::raw('CONCAT(supervisor.first_name, " ", supervisor.middle_name, " ", supervisor.last_name) as supervisor_name'),
            ])
            ->leftJoin('employees as employee', 'applications.employee_id', '=', 'employee.id')
            ->leftJoin('application_type', 'applications.application_type_id', '=', 'application_type.id')
            ->leftJoin('employees as supervisor', 'applications.supervisor_id', '=', 'supervisor.id')
            ->where('employee_id', $employee_id);

        return Datatables::of($applications)
            ->editColumn('status', function($application){
                $status_content = '';
                switch($application->status){
                    case 'pending':
                        $status_content = '<div class="chip"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Pending</div>';
                        break;
                    case 'approved':
                        $status_content = '<div class="chip green darken-1 white-text"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Approved</div>';
                        break;
                    case 'denied':
                        $status_content = '<div class="chip red darken-1 white-text"><i class="fa fa-ban" aria-hidden="true"></i> Denied</div>';
                }
                return $status_content;
            })
            ->addColumn('action', function($application){
                $is_owner = $application->employee_id == Auth::user()->id;
                $actions = '';
                // view
                $actions .= '<button class="btn btn-small action-btn waves-effect waves-light blue darken-1 btn-view_application" type="button" title="View" data-id="'.$application->id.'">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>';

                // edit
                // can edit only pending applications
                $actions .= $is_owner && $application->status == Application::PENDING ? '<a href="'.url('/applications/form/edit/'.$application->id).'" class="btn btn-small action-btn waves-effect waves-light yellow darken-3 btn-edit_application" title="Edit" data-id="'.$application->id.'">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>' : '';

                // start - for "can approve" employee types
                // approve
                $actions .= Employee::canApprove(Auth::user()->id) && $application->status == Application::PENDING && !$is_owner ? '<button class="btn btn-small action-btn waves-effect waves-light green darken-1 btn-approve_application" type="button" title="Approve" data-id="'.$application->id.'">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        </button>' : '';

                // disapprove
                $actions .= Employee::canApprove(Auth::user()->id) && $application->status == Application::PENDING && !$is_owner ? '<button class="btn btn-small action-btn waves-effect waves-light red darken-1 btn-deny_application" type="button" title="Deny" data-id="'.$application->id.'">
                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                        </button>' : '';
                // end - for "can approve" employee types

                // delete
                // can delete only pending applications
                $actions .= $is_owner && $application->status == Application::PENDING ? '<button class="btn btn-small action-btn waves-effect waves-light red darken-3 btn-delete_application" type="button" title="Delete" data-id="'.$application->id.'">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>' : '';

                return $actions;
            })
            ->make(true);
    }

    public function getForm()
    {
        $this->data['employee'] = $employee = Employee::find(Auth::user()->id);
        $this->data['application_types'] = ApplicationType::all();
        $this->data['department'] = Department::find($employee->department_id);
        $this->data['supervisors'] = Employee::supervisors($employee);
        return view('applications::form', $this->data);
    }

    public function getEdit($id)
    {
        if($id){
            $this->data['application'] = Application::find($id);
            if($this->data['application']){
                $this->data['employee'] = $employee = Employee::find(Auth::user()->id);
                $this->data['application_types'] = ApplicationType::all();
                $this->data['department'] = Department::find($employee->department_id);
                $this->data['supervisors'] = Employee::supervisors($employee);
                return view('applications::form', $this->data);
            }else{
                return redirect('applications')->with('error_message', 'Unable to load Application Details. Application not found.');
            }
        }else{
            return redirect('applications')->with('error_message', 'Unable to load Application Details. No Application ID provided.');
        }
    }

    public function save(Request $request)
    {
        if($request->get('id')){
            return $this->update();
        }else{
            return $this->add();
        }
    }

    public function add()
    {
        $inputs = Input::all();
        $rules = [
            'application_type_id' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'number_of_days' => 'numeric',    // decimal values
            'overtime_hours' => 'numeric',    // decimal values,
            'supervisor_id' => 'required',
            'reason' => 'required'
        ];

        $validator = Validator::make($inputs, $rules);

        if($validator->fails()){
            return redirect('applications/form')
                ->withErrors($validator)
                ->withInput($inputs);
        }else{
            $inputs = Input::all();
            $application = new Application();
            $application->employee_id = $inputs['employee_id'];
            $application->application_type_id = $inputs['application_type_id'];
            $application->date_from = strftime('%Y-%m-%d', strtotime($inputs['date_from']));
            $application->date_to = strftime('%Y-%m-%d', strtotime($inputs['date_to']));
            $application->number_of_days = $inputs['number_of_days'];
            $application->overtime_hours = $inputs['overtime_hours'];
            $application->supervisor_id = $inputs['supervisor_id'];
            $application->reason = $inputs['reason'];
            $application->status = 'pending';
            if($application->save()){
                return redirect('applications')->with('success_message', 'Application successfully submitted.');
            }else{
                return redirect('applications/forms')
                    ->with('error_message', 'Something went wrong upon submitting your application. Please double check your inputs.')
                    ->withInput($inputs);
            }
        }
    }

    public function update()
    {
        $inputs = Input::all();
        $rules = [
            'id' => 'required',
            'application_type_id' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'number_of_days' => 'numeric',    // decimal values
            'overtime_hours' => 'numeric',    // decimal values,
            'supervisor_id' => 'required',
            'reason' => 'required'
        ];

        $validator = Validator::make($inputs, $rules);

        if($validator->fails()){
            return redirect('applications/form')
                ->withErrors($validator)
                ->withInput($inputs);
        }else{
            $inputs = Input::all();
            $application = Application::find($inputs['id']);
            $application->employee_id = $inputs['employee_id'];
            $application->application_type_id = $inputs['application_type_id'];
            $application->date_from = strftime('%Y-%m-%d', strtotime($inputs['date_from']));
            $application->date_to = strftime('%Y-%m-%d', strtotime($inputs['date_to']));
            $application->number_of_days = $inputs['number_of_days'];
            $application->overtime_hours = $inputs['overtime_hours'];
            $application->supervisor_id = $inputs['supervisor_id'];
            $application->reason = $inputs['reason'];
            $application->status = 'pending';
            if($application->save()){
                return redirect('applications')->with('success_message', 'Application successfully updated.');
            }else{
                return redirect('applications/forms')
                    ->with('error_message', 'Something went wrong upon updating your application. Please double check your inputs.')
                    ->withInput($inputs);
            }
        }
    }

    public function viewApplication($id)
    {
        $application = Application::select([
                'applications.id as application_id',
                'applications.employee_id',
                'employee.first_name as first_name',
                'employee.middle_name as middle_name',
                'employee.last_name as last_name',
                'application_type.name as application_type',
                'applications.date_from as date_from',
                'applications.date_to as date_to',
                'applications.number_of_days as number_of_days',
                'applications.overtime_hours as overtime_hours',
                'applications.status as status',
                DB::raw('CONCAT(supervisor.first_name, " ", supervisor.middle_name, " ", supervisor.last_name) as supervisor_name'),
                'applications.reason as reason',
            ])
            ->leftJoin('employees as employee', 'applications.employee_id', '=', 'employee.id')
            ->leftJoin('application_type', 'applications.application_type_id', '=', 'application_type.id')
            ->leftJoin('employees as supervisor', 'applications.supervisor_id', '=', 'supervisor.id')
            ->where('applications.id', $id)
            ->first();

        if($application){
            $application_arr = $application->toArray();
            $department = Employee::belongsToDepartment($application->employee_id);
            $application_arr['department'] = ucwords($department->name);
            return json_encode(['application' => $application_arr]);
        }else{
            return json_encode(['application' => false]);
        }
    }

    public function approve(Request $request)
    {
        if(Employee::canApprove(Auth::user()->id)){
            $application_id = $request->get('id');
            try{
                $approved = Application::approve($application_id);
                return response()
                    ->json(['errors' => false, 'approved' => $approved])
                    ->setStatusCode(200);
            }catch (Exception $e){
                return json_encode(['errors' => $e]);
            }
        }else{
            return response()
                ->json(['errors' => 'You are not allowed to Approve or Deny a request'])
                ->setStatusCode(403);
        }
    }

    public function deny(Request $request)
    {
        if(Employee::canApprove(Auth::user()->id)) {
            $application_id = $request->get('id');
            try {
                $denied = Application::deny($application_id);
                return response()
                    ->json(['errors' => false, 'denied' => $denied])
                    ->setStatusCode(200);
            } catch (Exception $e) {
                return json_encode(['errors' => $e, 'status']);
            }
        }else{
            return response()
                ->json(['errors' => 'You are not allowed to Approve or Deny a request'])
                ->setStatusCode(403);
        }
    }

    public function delete(Request $request)
    {
        $application = Application::find($request->get('id'));
        if($application){
            if($application->status == Application::PENDING){
                $deleted = $application->delete();
                return response()->json(['errors' => false, 'deleted' => $deleted]);
            }else{
                return response()
                    ->json(['errors' => 'You are not allowed to delete an approved or denied application'])
                    ->setStatusCode(403);
            }
        }else{
            return response()
                ->json(['errors' => 'You are not allowed to delete an approved or denied application'])
                ->setStatusCode(403);
        }
    }
}
