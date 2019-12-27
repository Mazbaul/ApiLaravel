<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;

class TaskController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
    * @param setTTL
	 * @return mixed
	 */
    public function setTTL()
    {
    	$ttl = 1; //minutes
		$credentials =[
			'email' =>  $this->user->email,
			'password' =>  $this->user->password
		];
		
		if (! $token = JWTAuth::factory()->setTTL($ttl)->attempt($credentials)) {
		  return false;
		}
		return true;
    }
    /**
	 * @return mixed
	 */

	public function index(Request $request)
	{
		$checkTTL = $this->setTTL();
		if(!$checkTTL){
			return response()->json([
			  	'success' => false,
			  	'message' => 'Unauthorized user'
			  ], 401);
		}
		try {
			if(isset($request->tasks)){
				$tasks = explode(',', $request->tasks);
				foreach ($tasks as $key => $id) {
		    		$query = $this->user->tasks()->find($id);
		    		if(!empty($query)){
		    			$task[] = $query;
		    		}
				}
				
			}else{
				$task = $this->user->tasks()->get(['title', 'description'])->toArray();
			}
			return response()->json([
		            'success' => true,
		            'data' => $task
		        ], 200);
			
		} catch (\Exception $e) {
			$bug = $e->getMessage();
			return response()->json([
	            'success' => false,
	            'message' => 'Sorry, '.$bug
	        ], 400);
		}
	}
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$checkTTL = $this->setTTL();
		if(!$checkTTL){
			return response()->json([
			  	'success' => false,
			  	'message' => 'Unauthorized user'
			  ], 401);
		}

	    $task = $this->user->tasks()->find($id);

	    if (!$task) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    return $task;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request)
	{
	    $this->validate($request, [
	        'title' => 'required',
	        'description' => 'required',
	    ]);

	    $task = new Task();
	    $task->title = $request->title;
	    $task->description = $request->description;

	    if ($this->user->tasks()->save($task))
	        return response()->json([
	            'success' => true,
	            'task' => $task
	        ]);
	    else
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task could not be added.'
	        ], 500);
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, $id)
	{
		$checkTTL = $this->setTTL();
		if(!$checkTTL){
			return response()->json([
			  	'success' => false,
			  	'message' => 'Unauthorized user'
			  ], 401);
		}

	    $task = $this->user->tasks()->find($id);

	    if (!$task) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    $updated = $task->fill($request->all())->save();

	    if ($updated) {
	        return response()->json([
	            'success' => true
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task could not be updated.'
	        ], 500);
	    }
	}
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
	    $task = $this->user->tasks()->find($id);

	    if (!$task) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    if ($task->delete()) {
	        return response()->json([
	            'success' => true
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'Task could not be deleted.'
	        ], 500);
	    }
	}
}
