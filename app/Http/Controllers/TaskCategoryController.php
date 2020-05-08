<?php

namespace App\Http\Controllers;


use App\Task;
use App\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class TaskCategoryController extends Controller
{
    protected $user;
    protected $token;

    public function __construct(Request $request)
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
        $this->token = $request->header('user-token');
        if (!$this->token) {
            $this->token = Cookie::get('user-token');
        }


        //

    }

    public function index(Request $request)
    {

        $this->authenticate();
        // $perPage= $request->get('perPage');
        $tasks = DB::table("TruthOrActionCategories")->get();


        $totalTasks = Task::count();
        return response()->json([
            'categories' => $tasks,
            //    'CountTasks'=>$totalTasks
        ])->withHeaders(['user-token' => $this->token]);
    }

    public function authenticate()
    {
        if ($this->token) {
            //   dd($this->token);
            $this->user = JWTAuth::toUser(" $this->token");


            //   dd($this->user);
            if (!$this->user) {
                abort(401);
            }
        } else {
            abort(401);
        }
    }

    public function store(Request $request)
    {
        $this->authenticate();
        $category_name = $request->get('nameCategory');

        $taskCategory = new TaskCategory();
        if ($category_name) {

            $taskCategory->name = $category_name;
            $taskCategory->save();
        } else {
            return response()->json(
                [
                    'success' => false,
                    'status' => 400,

                ]
            );
        }

        return response()->json(
            [
                'success' => true,
                'status' => 200,
                'task' => $taskCategory
            ]
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->authenticate();
        $category_name = $request->get('nameCategory');
        //$category_id = $request->get('id');

        //dd($category_name);
        if ($category_name) {
            $taskCategory = TaskCategory::find($id);
            $taskCategory->name = $category_name;
            $taskCategory->save();
        } else {
            return response()->json(
                [
                    'success' => false,
                    'status' => 400,

                ]
            );
        }

        return response()->json(
            [
                'success' => true,
                'status' => 200,
                'task' => $taskCategory
            ]
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authenticate();
        if (TaskCategory::destroy($id)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
        // dd("$id");
    }
}
