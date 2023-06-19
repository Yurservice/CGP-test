<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request) 
	{
		$limit = $request->limit ?? 10;
		$offset = $request->offset ?? 0;

        $user = User::withCount('user_images')
			->orderBy('user_images_count')
			->offset($offset)
			->limit($limit)->get();
        return $user;
	}

	public function store(Request $request) 
	{
		try {
			if ($request->hasFile('image')) {
				$image = $request->file('image');
			}	
			
			$validator = Validator::make($request->all(), [
				'name' => ['required', 'string', 'max:50'],
				'city' => ['required', 'string', 'max:50'],
				'image' => ['file', 'max:1024', 'mimes:jpg,png'],
			]);

			if ($validator->fails()) {
				$errors = $validator->errors();
				$errorMsg = $errors->first();	
				return response()->json([
					'errors' => $errorMsg,
				], 422);
			}
			
			if($request->file('image')) $path = Storage::disk('public')->putFile('images',$request->file('image'));
			else $path = null;

			if($path !== null) $path = str_replace("images/", "", $path);

			DB::beginTransaction();
			$user = User::create([
				'name' => $request->name,
				'city' => $request->city,
			]);
			if($user instanceof User) {
				$userImage = UserImage::create([
					'user_id' => $user->id,
					'image' => $path,
				]);
			}
			if($userImage instanceof UserImage) {
				DB::commit();
				return $user;
			}
			else { 
				DB::rollBack();
				return false;
			}
		} catch(\Throwable $e) {
			file_put_contents('LOGFILE.txt',date("Y-m-d H:i:s").$e->getMessage(),FILE_APPEND);
		}
	}
}
