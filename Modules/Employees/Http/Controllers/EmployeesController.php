<?php

namespace Modules\Employees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Modules\Applications\Entities\Application;
use Modules\Applications\Entities\ApplicationType;
use Modules\Employees\Entities\Department;
use Modules\Employees\Entities\Employee;
use Modules\Employees\Entities\EmployeeType;
use Yajra\Datatables\Datatables;

class EmployeesController extends Controller
{
    protected $data = [];

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->data['employee'] = Employee::find(Auth::user()->id);
        return view('employees::index', $this->data);
    }

    public function getLogin()
    {
        if(Auth::check()){
            return redirect('applications');
        }else{
            return view('employees::login');
        }
    }

    public function login()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email', // make sure the email is an actual email
            'password' => 'required|min:8' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = \Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return redirect('employees/login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'email'     => Input::get('email'),
                'password'  => Input::get('password')
            );

            $remember = Input::get('remember') ? true : false;
            // attempt to do the login
            if (Auth::attempt($userdata, $remember)) {

                // validation successful!
                // redirect them to the secure section or whatever
                // return Redirect::to('secure');
                // for now we'll just echo success (even though echoing in a controller is bad)
                return redirect('applications');

            } else {
                // validation not successful, send back to form
                Session::flash('login_error', 'Incorrect email or password!');
                return redirect('employees/login')->withInput(Input::all());
            }

        }
    }

    public function logout()
    {
        Auth::logout(); // log the user out of our application
        return redirect('/'); // redirect the user to the login screen
    }

    public function getProfile($id = null)
    {
        $employee_id = $id ? $id : Auth::user()->id;
        $this->data['employee'] = $employee = Employee::find($employee_id);
        if($employee){
            $this->data['avatar'] = $employee->profile_pic ?
                $employee->profile_pic :
                    $employee->gender == Employee::MALE ?
                        asset('public/images/avatars/male1.png') :
                        asset('public/images/avatars/female1.png');
            $this->data['employee_type'] = ucwords(EmployeeType::find($employee->type_id)->name);
            $this->data['department'] = ucwords(Department::find($employee->department_id)->name);
            $this->data['access_level'] = EmployeeType::find($employee->type_id)->access_level;
            return view('employees::profile', $this->data);
        }else{
            return redirect('/')->with('error_message', 'Employee Not Found!');
        }
    }

    public function getCompactApplicationList($employee_id)
    {
        if($employee_id){
            $applications = Application::select([
                'applications.id',
                'applications.employee_id',
                'application_type.name as application_type',
                'applications.status as status',
                'applications.created_at as date_filed',
            ])
                ->leftJoin('application_type', 'applications.application_type_id', '=', 'application_type.id')
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
                ->editColumn('date_filed', function($application){
                    return date('Y-m-d', strtotime($application->date_filed));
                })
                ->addColumn('action', function($application){
                    $is_owner = $application->employee_id == Auth::user()->id;
                    session()->put('redirect_url', '/employees/profile/'.$application->employee_id);
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
        }else{
            return response()->setStatusCode(500)->json(['errors' => 'No Employee ID provided.']);
        }
    }

    public function getTotalPaidLeaveDetails($id)
    {
        if($id){
            $employee = Employee::find($id);

            $used_paid = Application::leftJoin('application_type', 'applications.application_type_id', '=', 'application_type.id')
                ->where('employee_id', $id)
                ->where('status', Application::APPROVED)
                ->where('applications.created_at', '>=', date('Y-m-d', strtotime(date('Y-01-01'))))
                ->where('applications.created_at', '<=', date('Y-m-d', strtotime(date('Y-12-31'))))
                ->where('application_type.unit', 'days')
                ->where('application_type.paid', ApplicationType::PAID)
                ->sum('number_of_days');

            $used_unpaid = Application::leftJoin('application_type', 'applications.application_type_id', '=', 'application_type.id')
                ->where('employee_id', $id)
                ->where('status', Application::APPROVED)
                ->where('applications.created_at', '>=', date('Y-m-d', strtotime(date('Y-01-01'))))
                ->where('applications.created_at', '<=', date('Y-m-d', strtotime(date('Y-12-31'))))
                ->where('application_type.unit', 'days')
                ->where('application_type.paid', ApplicationType::UNPAID)
                ->sum('number_of_days');

            $paid_leave_credits = EmployeeType::find($employee->type_id)->paid_leave_credits;
            $unpaid_leave_credits = EmployeeType::find($employee->type_id)->unpaid_leave_credits;

            return response()->json([
                'errors' => false,
                'used_paid' => $used_paid === null ? 0 : $used_paid,
                'used_unpaid' => $used_unpaid === null ? 0 : $used_unpaid,
                'paid_leave_credits' => $paid_leave_credits,
                'unpaid_leave_credits' => $unpaid_leave_credits
            ]);
        }else{
            return response()->setStatusCode(500)->json(['errors' => 'No Employee ID provided.']);
        }
    }
}
