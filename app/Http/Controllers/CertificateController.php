<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PDF;
use QrCode;


class CertificateController extends Controller
{
    public function getCertificates()
    {
        return view('certificates.index');
    }

    public function getCertificate($id)
    {

        $Certificate = Certificate::find($id);
        if ($Certificate == null) {
            return view('error')->with('error', 'Сертификат не найден');
        }
        if ($Certificate->activation == 2) {
            return view('error')->with('error', 'Сертификат больше не действительный!');
        }
        $data = [
            'id' => $Certificate->id,
            'number' => $Certificate->number,
            'title' => $Certificate->title,
            'user_name' => $Certificate->user_name,
            'date_finished_course' => Carbon::parse($Certificate->date_finished_course)->format('d.m.Y'),
        ];
        //  return view('template.s1')->with($data);

        $pdf = PDF::loadView('template.s1', $data)->setPaper('a5', 'landscape');


        return $pdf->download($id . '.pdf');

    }

    public function getQr(Request $r)
    {
        $link = 'https://secure.co.ua/sreda/public/certificates/' . str_replace('.jpg', '', $r->id);
        return response(QrCode::size(70)->generate($link), 200)
            ->header('Content-Type', 'image/jpeg');
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

        if ($filter__status == '') {
            $users = Certificate::whereIn('activation', [1, 2]);
        } else {
            $users = Certificate::where('activation', $filter__status);
        }

        if ($search != '') {
            $users = $users->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('number', 'LIKE', '%' . $search . '%')
                    ->orWhere('user_name', 'LIKE', '%' . $search . '%');
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
                $select_active = '<select onchange="changeActivation(' . "'" . $u->id . "'" . ')"
                                    class="form-select form-select-sm form-select-solid changeActivation' . $u->id . '">
                                <option selected value="1">Действительный</option>
                                <option value="2">Удален</option>
                            </select>';
            } else {
                $select_active = '<select onchange="changeActivation(' . "'" . $u->id . "'" . ')"
                                    class="form-select form-select-sm form-select-solid changeActivation' . $u->id . '">
                                <option value="1">Действительный</option>
                                <option selected value="2">Удален</option>
                            </select>';
            }

            $file = '<a href="' . url('/') . '/certificates/' . $u->id . '" style="cursor: pointer;" class="svg-icon svg-icon-2x svg-icon-primary me-4">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="currentColor"></path>
																	<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"></path>
																</svg>
															</a>';

            $temp_arr = [
                '<a href="javascript:;" onclick="editCertificate(' . "'" . $u->id . "'" . ')">' . $u->number . '</a>',
                $u->title,
                $u->user_name,
                Carbon::parse($u->date_finished_course)->format('d.m.Y'),
                $file,
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

    public function addСertificate(Request $r)
    {

        $validator = Validator::make($r->all(), [
            'number' => 'required',
            'title' => 'required',
            'user_name' => 'required',
            'date_finished_course' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(array('success' => "false", 'error' => $error), 200);
        }


        $Certificate = Certificate::find($r->id);
        if ($Certificate == null) {
            $Certificate = new Certificate();
            $Certificate->activation = 1;
        }

        $Certificate->number = $r->number;
        $Certificate->title = $r->title;
        $Certificate->user_name = $r->user_name;
        $Certificate->date_finished_course = Carbon::createFromFormat('d.m.Y', $r->date_finished_course);
        $Certificate->save();
        return response(array('success' => "true"), 200);
    }

    public function getCertificaterAjax($id)
    {
        $Certificate = Certificate::find($id);
        if ($Certificate == null) {
            return response(array('success' => "false", 'error' => 'Сертификат не найден!'), 200);
        }

        $Certificate->date_finished_course = Carbon::parse($Certificate->date_finished_course)->format('d.m.Y');

        return response(array('success' => "true", 'certificate' => $Certificate), 200);
    }

    public function CertificaterActivation(Request $r)
    {
        Certificate::where('id', $r->id)->update(['activation' => $r->s]);
        return response(array('success' => "true"), 200);
    }
}
