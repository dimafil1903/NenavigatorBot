<?php


namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use JWTAuth;


class TaskController extends Controller
{
    /**
     * @var
     */
    protected $user;
    protected $token;

    /**
     * TaskController constructor.
     * @param Request $request
     */
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
        $perPage = $request->get('perPage');
        $categoryId = $request->get('categoryId');

        $tasks = DB::table("TruthOrActionQuestions")
            ->leftJoin('TruthOrActionCategories', 'TruthOrActionCategories.id', '=', 'TruthOrActionQuestions.category_id')
            ->select('TruthOrActionQuestions.*', 'TruthOrActionCategories.name as category');
        if ($categoryId) {
            $tasks = $tasks->where('category_id', $categoryId);
        }
        $totalTasks = $tasks->count();
        $tasks = $tasks->paginate($perPage);

        return response()->json([
            'tasks' => $tasks,
            'CountTasks' => $totalTasks
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

    public function del(Request $request)
    {

    }


    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authenticate();
        $category_id = $request->get('category_id');
        $type = $request->input('type');
        $question = $request->input('question');
        $active = $request->input('active');
        $task = new Task;
        if ($category_id && $type && $question && $active) {

            $task->category_id = $category_id;
            $task->type = $type;
            $task->question = $question;
            $task->active = $active;
            $task->save();
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
                'task' => $task
            ]
        );

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authenticate();
        Task::destroy($id);
    }
}
