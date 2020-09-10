<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Department;
use App\Division;
use App\Country;
use App\State;
use App\City;
use App\Employee;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        if (!Auth::check() && $request->path() == 'login') {
            return view('welcome');
        }
        if ($request->path() == 'login') {
            return redirect('/');
        }
        return view('welcome');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //                                                         |
    //                           USER                          |
    //                                                         |
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * Add user
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required|unique:users|max:20',
            'firstname' => 'required|max:60',
            'lastname'  => 'required|max:60',
            'email'     => 'required|unique:users|email',
            'password'  => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors()
            ], 401);
        }

        return $create = User::create([
            'username'  => $request->username,
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
        ]);
    }

    /**
     * Edit user
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required|max:20|unique:users,username,'.$request->id,
            'firstname' => 'required|max:60',
            'lastname'  => 'required|max:60',
            'email'     => 'required|email|unique:users,email,'.$request->id
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors()
            ], 401);
        }

        return $edit = User::where('id', $request->id)->update([
            'username'  => $request->username,
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
        ]);
    }

    /**
     * Delete user
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request)
    {
        return $delete = User::where('id', $request->id)->delete();
    }

    /**
     * Get users
     * @param int $page
     * @param int $pageSize
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers($page = 0, $pageSize = 0)
    {
        $total = User::count();
        $offset = ($page - 1) * $pageSize;
        $responseData = [
            'total'     => $total,
            'users' => $pageSize == 0? User::orderBy('id', 'desc')->get() : User::offset($offset)->limit($pageSize)->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Search users
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsers(Request $request)
    {
        $query = User::query();
        $username  = $request->input('username');
        $firstname = $request->input('firstname');
        $lastname  = $request->input('lastname');

        $query->when($username, function($q, $username) {
            return $q->where('username', 'like', '%'.$username.'%');
        });
        $query->when($firstname, function($q, $firstname) {
            return $q->where('firstname', 'like', '%'.$firstname.'%');
        });
        $query->when($lastname, function($q, $lastname) {
            return $q->where('lastname', 'like', '%'.$lastname.'%');
        });

        $responseData = [
            'total' => $query->count(),
            'users' => $query->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //                                                         |
    //                        Department                       |
    //                                                         |
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * Add department
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|unique:departments|max:60',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors()
            ], 401);
        }

        return $create = Department::create([
            'name'  => $request->name,
        ]);
    }

    /**
     * Edit department
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:60|unique:departments,name,'.$request->id,
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors()
            ], 401);
        }

        return $edit = Department::where('id', $request->id)->update([
            'name'  => $request->name,
        ]);
    }

    /**
     * Delete department
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDepartment(Request $request)
    {
        return $delete = Department::where('id', $request->id)->delete();
    }

    /**
     * Get departments
     * @param int $page
     * @param int $pageSize
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartments($page = 0, $pageSize = 0)
    {
        $total = Department::count();
        $offset = ($page - 1) * $pageSize;
        $responseData = [
            'total'     => $total,
            'departments' => $pageSize == 0? Department::orderBy('id', 'desc')->get() : Department::offset($offset)->limit($pageSize)->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Search departments
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDepartments(Request $request)
    {
        $query = Department::query();
        $name  = $request->input('name');

        $query->when($name, function($q, $name) {
            return $q->where('name', 'like', '%'.$name.'%');
        });

        $responseData = [
            'total' => $query->count(),
            'departments' => $query->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //                                                         |
    //                         Division                        |
    //                                                         |
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * Add division
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDivision(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|unique:divisions|max:60',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors()
            ], 401);
        }

        return $create = Division::create([
            'name'  => $request->name,
        ]);
    }

    /**
     * Edit division
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editDivision(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:60|unique:divisions,name,'.$request->id,
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors()
            ], 401);
        }

        return $edit = Division::where('id', $request->id)->update([
            'name'  => $request->name,
        ]);
    }

    /**
     * Delete division
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDivision(Request $request)
    {
        return $delete = Division::where('id', $request->id)->delete();
    }

    /**
     * Get divisions
     * @param int $page
     * @param int $pageSize
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDivisions($page = 0, $pageSize = 0)
    {
        $total = Division::count();
        $offset = ($page - 1) * $pageSize;
        $responseData = [
            'total'     => $total,
            'divisions' => $pageSize == 0? Division::orderBy('id', 'desc')->get() : Division::offset($offset)->limit($pageSize)->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Search divisions
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDivisions(Request $request)
    {
        $query = Division::query();
        $name  = $request->input('name');

        $query->when($name, function($q, $name) {
            return $q->where('name', 'like', '%'.$name.'%');
        });

        $responseData = [
            'total'     => $query->count(),
            'divisions' => $query->orderBy('id', 'desc')->get()
        ];

        return response()->json($responseData);
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //                                                         |
    //                         Country                         |
    //                                                         |
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * Add country
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|unique:countries|max:3',
            'name'         => 'required|unique:countries|max:60',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }

        return $create = Country::create([
            'country_code' => $request->country_code,
            'name'         => $request->name,
        ]);
    }

    /**
     * Edit country
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|max:3|unique:countries,country_code,'.$request->id,
            'name'         => 'required|max:60|unique:countries,name,'.$request->id,
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors()
            ], 401);
        }

        return $edit = Country::where('id', $request->id)->update([
            'country_code' => $request->country_code,
            'name'         => $request->name,
        ]);
    }

    /**
     * Delete country
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCountry(Request $request)
    {
        return $delete = Country::where('id', $request->id)->delete();
    }

    /**
     * Get countries
     * @param int $page
     * @param int $pageSize
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountries($page = 0, $pageSize = 0)
    {
        $total = Country::count();
        $offset = ($page - 1) * $pageSize;
        $responseData = [
            'total'     => $total,
            'countries' => $pageSize == 0? Country::with('states')->orderBy('id', 'desc')->get() : Country::with('states')->offset($offset)->limit($pageSize)->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Search countries
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCountries(Request $request)
    {
        $query = Country::query();
        $country_code = $request->input('country_code');
        $name         = $request->input('name');

        $query->when($country_code, function($q, $country_code) {
            return $q->where('country_code', 'like', '%'.$country_code.'%');
        });
        $query->when($name, function($q, $name) {
            return $q->where('name', 'like', '%'.$name.'%');
        });

        $responseData = [
            'total' => $query->count(),
            'countries' => $query->with('states')->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Get country by id
     * 
     * @param \Illuminate\Http\Request
     * @return String
     */
    public function getCountryById(Request $request)
    {
        return Country::with('states')->find($request->input('country_id'));
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //                                                         |
    //                          State                          |
    //                                                         |
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * Add state
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'name'       => 'required|unique:states|max:60',
        ], [
            'country_id.required' => 'The country field is required',
            'name.required' => 'The state field is required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }

        $create = State::create([
            'country_id' => $request->country_id,
            'name'       => $request->name,
        ]);

        $result = State::with('country')->with('cities')->find($create->id);
        return $result;
    }

    /**
     * Edit state
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'name'       => 'required|max:60|unique:states,name,'.$request->id,
        ], [
            'country_id.required' => 'The country field is required',
            'name.required' => 'The state field is required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }

        return $edit = State::where('id', $request->id)->update([
            'country_id' => $request->country_id,
            'name'       => $request->name,
        ]);
    }

    /**
     * Delete state
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteState(Request $request)
    {
        return $delete = State::where('id', $request->id)->delete();
    }

    /**
     * Get states
     * @param int $page
     * @param int $pageSize
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStates($page = 0, $pageSize = 0)
    {
        $total  = State::count();
        $offset = ($page - 1) * $pageSize;
        $responseData = [
            'total'  => $total,
            'states' => $pageSize == 0? State::with('country')->with('cities')->orderBy('id', 'desc')->get() : State::with('country')->with('cities')->offset($offset)->limit($pageSize)->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Search states
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchStates(Request $request)
    {
        $query = State::query();
        $country_id = $request->input('country_id');
        $name       = $request->input('name');

        $query->when($country_id, function($q, $country_id) {
            return $q->where('country_id', $country_id);
        });
        $query->when($name, function($q, $name) {
            return $q->where('name', 'like', '%'.$name.'%');
        });

        $responseData = [
            'total' => $query->count(),
            'states' => $query->with('country')->with('cities')->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Get state by id
     * 
     * @param \Illuminate\Http\Request
     * @return String
     */
    public function getStateById(Request $request)
    {
        return State::with('country')->with('cities')->find($request->input('state_id'));
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //                                                         |
    //                          City                           |
    //                                                         |
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * Add city
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'name'     => 'required|unique:cities|max:60',
        ], [
            'state_id.required' => 'The state field is required',
            'name.required' => 'The city field is required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }

        $create = City::create([
            'state_id' => $request->state_id,
            'name'     => $request->name,
        ]);

        $result = City::with('state')->find($create->id);
        return $result;
    }

    /**
     * Edit city
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'name'     => 'required|max:60|unique:cities,name,'.$request->id,
        ], [
            'state_id.required' => 'The state field is required',
            'name.required' => 'The city field is required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }

        return $edit = City::where('id', $request->id)->update([
            'state_id' => $request->state_id,
            'name'     => $request->name,
        ]);
    }

    /**
     * Delete city
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCity(Request $request)
    {
        return $delete = City::where('id', $request->id)->delete();
    }

    /**
     * Get cities
     * @param int $page
     * @param int $pageSize
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities($page = 0, $pageSize = 0)
    {
        $total  = City::count();
        $offset = ($page - 1) * $pageSize;
        $responseData = [
            'total'  => $total,
            'cities' => $pageSize == 0? City::with('state')->orderBy('id', 'desc')->get() : City::with('state')->offset($offset)->limit($pageSize)->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Search cities
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCities(Request $request)
    {
        $query = City::query();
        $state_id = $request->input('state_id');
        $name     = $request->input('name');

        $query->when($state_id, function($q, $state_id) {
            return $q->where('state_id', $state_id);
        });
        $query->when($name, function($q, $name) {
            return $q->where('name', 'like', '%'.$name.'%');
        });

        $responseData = [
            'total' => $query->count(),
            'cities' => $query->with('state')->orderBy('id', 'desc')->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Get city by id
     * 
     * @param \Illuminate\Http\Request
     * @return String
     */
    public function getCityById(Request $request)
    {
        return City::with('state')->find($request->input('city_id'));
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //                                                         |
    //                         Employee                        |
    //                                                         |
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * Add employee
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'     => 'required|max:60',
            'lastname'      => 'required|max:60',
            'address'       => 'required|max:120',
            'city_id'       => 'required',
            'state_id'      => 'required',
            'country_id'    => 'required',
            'zip'           => 'required|max:10',
            'age'           => 'required',
            'birthdate'     => 'required',
            'date_hired'    => 'required',
            'department_id' => 'required',
            'division_id'   => 'required',
        ], [
            'city_id.required'       => 'The city field is required',
            'state_id.required'      => 'The state field is required',
            'country_id.required'    => 'The country field is required',
            'department_id.required' => 'The department field is required',
            'division_id.required'   => 'The division field is required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }

        $picture_name = 'empty';
        $picture      = NULL;
        if (!empty($_FILES['picture']['tmp_name'])) {

            if (is_uploaded_file($_FILES['picture']['tmp_name'])) {

                $picture_name  = md5(time() . rand());
                $extension     = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $original_name = $request->files->get('picture')->getClientOriginalName();
                $sTempFileName = public_path() . '/images/employee/' . $picture_name . '.' . $extension;

                if (move_uploaded_file($_FILES['picture']['tmp_name'], $sTempFileName)) {
                    $picture = $picture_name . '.' . $extension;
                }
            }
        }

        $create = Employee::create([
            'firstname'     => $request->firstname,
            'middlename'    => $request->middlename,
            'lastname'      => $request->lastname,
            'address'       => $request->address,
            'city_id'       => $request->city_id,
            'state_id'      => $request->state_id,
            'country_id'    => $request->country_id,
            'zip'           => $request->zip,
            'age'           => $request->age,
            'birthdate'     => date('Y-m-d', strtotime($request->birthdate)),
            'date_hired'    => date('Y-m-d', strtotime($request->date_hired)),
            'department_id' => $request->department_id,
            'division_id'   => $request->division_id,
            'picture'       => $picture,
        ]);

        $result = Employee::with('country')
                    ->with('state')
                    ->with('city')
                    ->with('department')
                    ->with('division')
                    ->find($create->id);
        return $result;
    }

    /**
     * Edit employee
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'     => 'required|max:60',
            'lastname'      => 'required|max:60',
            'address'       => 'required|max:120',
            'city_id'       => 'required',
            'state_id'      => 'required',
            'country_id'    => 'required',
            'zip'           => 'required|max:10',
            'age'           => 'required',
            'birthdate'     => 'required',
            'date_hired'    => 'required',
            'department_id' => 'required',
            'division_id'   => 'required',
        ], [
            'city_id.required'       => 'The city field is required',
            'state_id.required'      => 'The state field is required',
            'country_id.required'    => 'The country field is required',
            'department_id.required' => 'The department field is required',
            'division_id.required'   => 'The division field is required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }        

        $picture_name = 'empty';
        $picture      = NULL;
        if (!empty($_FILES['picture']['tmp_name'])) {

            if (is_uploaded_file($_FILES['picture']['tmp_name'])) {

                $picture_name  = md5(time() . rand());
                $extension     = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $original_name = $request->files->get('picture')->getClientOriginalName();
                $sTempFileName = public_path() . '/images/employee/' . $picture_name . '.' . $extension;

                if (move_uploaded_file($_FILES['picture']['tmp_name'], $sTempFileName)) {
                    $picture = $picture_name . '.' . $extension;
                }
            }
        }

        $updateData = [];
        if ($picture == NULL) {
            $updateData = [
                'firstname'     => $request->firstname,
                'middlename'    => $request->middlename,
                'lastname'      => $request->lastname,
                'address'       => $request->address,
                'city_id'       => $request->city_id,
                'state_id'      => $request->state_id,
                'country_id'    => $request->country_id,
                'zip'           => $request->zip,
                'age'           => $request->age,
                'birthdate'     => date('Y-m-d', strtotime($request->birthdate)),
                'date_hired'    => date('Y-m-d', strtotime($request->date_hired)),
                'department_id' => $request->department_id,
                'division_id'   => $request->division_id,
            ];
        } else {
            $updateData = [
                'firstname'     => $request->firstname,
                'middlename'    => $request->middlename,
                'lastname'      => $request->lastname,
                'address'       => $request->address,
                'city_id'       => $request->city_id,
                'state_id'      => $request->state_id,
                'country_id'    => $request->country_id,
                'zip'           => $request->zip,
                'age'           => $request->age,
                'birthdate'     => date('Y-m-d', strtotime($request->birthdate)),
                'date_hired'    => date('Y-m-d', strtotime($request->date_hired)),
                'department_id' => $request->department_id,
                'division_id'   => $request->division_id,
                'picture'       => $picture,
            ];
        }

        return $edit = Employee::where('id', $request->id)->update($updateData);
    }

    /**
     * Delete employee
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEmployee(Request $request)
    {
        return $delete = Employee::where('id', $request->id)->update([
            'deleted_at'   => date('Y-m-d'),
        ]);
    }

    /**
     * Get employees
     * @param int $page
     * @param int $pageSize
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployees($page = 0, $pageSize = 0)
    {
        $total  = Employee::count();
        $offset = ($page - 1) * $pageSize;
        $responseData = [
            'total'     => $total,
            'employees' => $pageSize == 0? 
                                Employee::with('country')
                                    ->with('state')
                                    ->with('city')
                                    ->with('department')
                                    ->with('division')
                                    ->where('deleted_at', NULL)
                                    ->orderBy('id', 'desc')
                                    ->get() : 
                                Employee::with('country')
                                    ->with('state')
                                    ->with('city')
                                    ->with('department')
                                    ->with('division')
                                    ->where('deleted_at', NULL)
                                    ->offset($offset)
                                    ->limit($pageSize)
                                    ->orderBy('id', 'desc')
                                    ->get()
        ];
        return response()->json($responseData);
    }

    /**
     * Search employees
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchEmployees(Request $request)
    {
        $query = Employee::query();

        $department_id = $request->input('department_id');
        $name          = $request->input('name');

        $query->when($department_id, function($q, $department_id) {
            return $q->where('department_id', $department_id);
        });
        $query->when($name, function($q, $name) {
            return $q->where(function($qu) use($name) {
                $qu->where('firstname', 'like', '%'.$name.'%')
                    ->orWhere('middlename', 'like', '%'.$name.'%')
                    ->orWhere('lastname', 'like', '%'.$name.'%');
            });
        });

        $responseData = [
            'total' => $query->count(),
            'employees' => $query->with('country')
                                ->with('state')
                                ->with('city')
                                ->with('department')
                                ->with('division')
                                ->orderBy('id', 'desc')
                                ->get()
        ];
        return response()->json($responseData);
    }

    /**
     * User login
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters.',
                'errors'  => $validator->errors()
            ], 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'msg' => 'You are logged in'
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect login details'
            ], 401);
        }
    }
}
