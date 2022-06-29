<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\History;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function getIndex()
    {
        return view('template');
    }

    public function getDashboard($id)
    {

        $link = Link::where('link', $id)->first();
        if (!Auth::check()) {
            Auth::loginUsingId($link->user_id);
        }
        if ($link == null) {
            return view('error')->with('error', 'Error link not found');
        }
        if (Carbon::parse($link->date_end) < Carbon::now()) {
            return view('error')->with('error', 'The link is no longer valid');
        }
        if ($link->active == 2) {
            return view('error')->with('error', 'The link is no longer valid');
        }

        return view('dashboard')->with('link', $link);
    }

    public function getAddHistory(Request $r)
    {
        $win = 0;
        $rand = rand(1, 1000);
        if ($rand % 2 === 0) {
            $result = 'Win';
        } else {
            $result = 'Lose';
        }

        if ($result == 'Win') {
            if ($rand > 900) {
                $win = ceil($rand * 0.7);
            }
            if ($rand > 600 && $rand <= 900) {
                $win = ceil($rand * 0.6);
            }
            if ($rand > 300 && $rand <= 600) {
                $win = ceil($rand * 0.3);
            }
            if ($rand <= 300) {
                $win = ceil($rand * 0.1);
            }
        }

        $History = new History();
        $History->user_id = Auth::user()->id;
        $History->link_id = $r->l;
        $History->win = $win;
        $History->save();

        return response(array('success' => "true", 'win' => $win, 'result' => $result), 200);
    }

    public function getHistory()
    {
        $histories = History::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->limit(3)->get();
        return response(array('success' => "true", 'histories' => $histories), 200);
    }


    public function getUsers()
    {
        return view('users.index');
    }

    public function getJson()
    {

        $draw = request()->get('draw');
        $start = request()->get("start");
        $rowperpage = request()->get("length"); // Rows display per page

        //ordering
        $order_col = 'id';
        $order_direction = 'desc';
        $cols = request('columns');
        $order = request('order');

        if (isset($order[0]['dir'])) {
            $order_direction = $order[0]['dir'];
        }
        if (isset($order[0]['column']) && isset($cols)) {
            $col_number = $order[0]['column'];
            if (isset($cols[$col_number]) && isset($cols[$col_number]['data'])) {
                $data = $cols[$col_number]['data'];
                if ($data == 0) {
                    $order_col = 'id';
                    $order_direction = 'desc';
                }

            }
        }
        // search
        $filter__status = request('status');
        $search = request('search');
        $group = request('group');


        if ($filter__status == '') {
            $users = User::whereIn('activation', [1]);
        } else {
            $users = User::where('activation', $filter__status);
        }

        if ($search != '') {
            $users = $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%')
                    ->orWhere('number', 'LIKE', '%' . $search . '%');
            });
        }
        $users = $users->orderBy($order_col, $order_direction);


        $users = $users
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data = [];


        foreach ($users as $u) {

            if ($u->activation == 1) {
                $select_active = '<select onchange="changeActivation(' . $u->id . ')"
                                    class="form-select form-select-sm form-select-solid changeActivation' . $u->id . '">
                                <option selected value="1">Active</option>
                                <option value="2">Deactivate</option>
                            </select>';
            } else {
                $select_active = '<select onchange="changeActivation(' . $u->id . ')"
                                    class="form-select form-select-sm form-select-solid changeActivation' . $u->id . '">
                                <option value="1">Active</option>
                                <option selected value="2">Deactivate</option>
                            </select>';
            }


            $temp_arr = [
                //  $checkbox,
                '<a href="javascript:;" onclick="editUser(' . $u->id . ')">' . $u->id . '</a>',
                $u->name,
                $u->phone,
                $u->number,
                $select_active

            ];
            $data[] = $temp_arr;
        }


        return Response::json(array('data' => $data,
            "draw" => $draw,
            "recordsTotal" => User::count(),
            "recordsFiltered" => count($users),
        ), 200);
    }

    public function addUser(Request $r)
    {
        $user = User::find($r->id);
        if ($user == null) {
            $user = new User();

            $validator = Validator::make($r->all(), [
                'password' => ['required', Password::min(10)],
                'name' => 'required',
                'phone' => 'required',
                'number' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response(array('success' => "false", 'error' => $error), 200);
            }

        } else {
            $validator = Validator::make($r->all(), [
                'name' => 'required',
                'phone' => 'required',
                'number' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response(array('success' => "false", 'error' => $error), 200);
            }
        }
        $user->name = $r->name;
        $user->phone = $r->phone;
        $user->number = $r->number;
        $user->group_id = $r->group_id;
        $user->activation = $r->activation;


        if ($r->has('password') && $r->password != '') {
            $user->password = Hash::make($r->password);
        }

        $user->save();
        return response(array('success' => "true"), 200);
    }

    public function usersActivation(Request $r)
    {
        User::where('id', $r->id)->update(['activation' => $r->s]);
        return response(array('success' => "true"), 200);
    }

    public function getUserAjax($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response(array('success' => "false", 'error' => 'Пользователь не найден!'), 200);
        }

        return response(array('success' => "true", 'user' => $user), 200);
    }
}
