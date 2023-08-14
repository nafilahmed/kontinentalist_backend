<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryUser;
use Illuminate\Http\Request;
use App\Http\Resources\StoryResource;
use Illuminate\Database\Query\JoinClause;
use Validator;
use Auth;
use DB;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(Auth::user()['user_role_id'] == 1){

            $data = Story::select('*')->addSelect(DB::raw('1 as is_admin'))->get();

        }else{

            $id = Auth::user()['id'];
            $stories = Story::leftJoin('story_users', function (JoinClause $join) use ($id) {
                $join->on('stories.id', '=', 'story_users.story_id')
                    ->where('story_users.user_id', $id);
            })
            ->select('stories.id AS  id','stories.title AS title','stories.content AS content','stories.status AS status','story_users.story_id AS is_edit','stories.view_draft AS view_draft')
            ->get()->toArray();


            $data = collect($stories)->filter(function ($value, $key) {
                if($value['is_edit'] != null){
                    return $value;
                }elseif ($value['view_draft'] == 1) {
                    return $value;
                }

            });

        }

        return response(['status_code' => 200,'stories' => StoryResource::collection($data)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $status = 200;

        try{

            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required|max:12',
                'content' => 'required|max:500',
                'status' => 'required',
                'view_draft' => 'required',
            ]);
            
            $story = Story::create($data);

            StoryUser::create([
                'user_id' => Auth::user()['id'],
                'story_id' => $story->id,
            ]);

            $message = 'Story created successfully';

        }catch (\Illuminate\Database\QueryException $qe) {
            $status = 500;
            $message = $qe->getMessage();
        }
        catch(\Exception $ex) {
            $status = 500;            
            $message = $ex->getMessage();
        }
        catch (\Throwable $t) {
            $status = 500;            
            $message = $t->getMessage();
        }

        return response(['status_code' => $status, 'message' => $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $story = Story::find($id);

        return response(['status_code' => 200,'story' => new StoryResource($story)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        $status = 200;
        try{
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required|max:12',
                'content' => 'required|max:500',
                'status' => 'required',
                'view_draft' => 'required',

            ]);

            if($validator->fails()){
                return response(['error' => $validator->errors(), 'Validation Error']);
            }

            $storyCheck = StoryUser::where(['user_id' => Auth::user()['id'],'story_id' => $story['id']])->first();

            if(!empty($storyCheck)){

                $story->update($data);

                return response([
                    'status_code' => $status,
                    'story' => new StoryResource($story),
                    'message' => 'Story updated successfully'
                ]);
            }else{
                return response([
                    'status_code' => 500,
                    'story' => [],
                    'message' => "You don't have permission" 
                ]);
            }



        }catch (\Illuminate\Database\QueryException $qe) {
            $status = 500;
            $message = $qe->getMessage();
        }
        catch(\Exception $ex) {
            $status = 500;            
            $message = $ex->getMessage();
        }
        catch (\Throwable $t) {
            $status = 500;            
            $message = $t->getMessage();
        }

        return response(['status_code' => $status, 'message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        $status = 200;
        try{
            StoryUser::where('story_id', $story['id'])->delete();
            $story->delete();
            $message = 'Story deleted successfully';
        }catch (\Illuminate\Database\QueryException $qe) {
            $status = 500;
            $message = $qe->getMessage();
        }
        catch(\Exception $ex) {
            $status = 500;            
            $message = $ex->getMessage();
        }
        catch (\Throwable $t) {
            $status = 500;            
            $message = $t->getMessage();
        }

        return response(['status_code' => $status, 'message' => $message]);
    }
}
