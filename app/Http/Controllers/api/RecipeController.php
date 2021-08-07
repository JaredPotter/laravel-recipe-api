<?php

// https://www.itsolutionstuff.com/post/laravel-8-rest-api-with-passport-authentication-tutorialexample.html

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\Recipe;
use App\Models\Tag;
use Validator;
use App\http\Resources\Recipe as RecipeResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class RecipeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->input();
        $recipeQuery = Recipe::query();

        try {
            $filterByTag = $input['filterByTag'];

            if ($filterByTag) {
                $recipeQuery = $recipeQuery->whereHas('tags', function ($q) use ($filterByTag) {
                    $q->where('tags.tag', $filterByTag);
                });
            }
        } catch (Throwable $e) {
            // do nothing
        }

        try {
            $sortByField = $input['sortByField'];
            $sortByDir = $input['sortByDir'];

            $recipeQuery = $recipeQuery->orderBy($sortByField, $sortByDir);
        } catch (Throwable $e) {
            // do nothing
        }

        $recipes = $recipeQuery->simplePaginate(15);

        return response()->json($recipes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'tags' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $recipe = new Recipe();

        // TODO: figure out how to do this without performing it here
        $input['publish_date'] = Carbon::parse($input['publish_date'])->format('Y-m-d');
        $recipe = Recipe::create($input);

        // foreach ($input as $i) {
        //     error_log('input value: ' . $i . '\n');
        // }

        // Handle Tags
        $tags = $input['tags'];

        foreach ($tags as $tag) {
            // Check if it already exists in Tags table
            $existingTag = Tag::query()->where('tag', '=', $tag)->get();

            $tag_id = '';

            if ($existingTag->isEmpty()) {
                $tag_id = DB::table('tags')->insertGetId([
                    'tag' => $tag
                ]);
            } else {
                $tag_id = $existingTag[0]->id;
            }

            $recipe_id = $recipe['id'];


            DB::table('recipe_tag')->insert([
                'recipe_id' => $recipe_id,
                'tag_id' => $tag_id,
            ]);
        }

        return response(new RecipeResource($recipe), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recipe = Recipe::find($id);

        if (is_null($recipe)) {
            return $this->sendError("Recipe not found");
        }

        return response(new RecipeResource($recipe), 200);
        // return $this->sendResponse(new RecipeResource($recipe), 'Recipe retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'directions' => 'required',
            'ingredients' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        // TODO: how do we handle this better.
        // $recipe->name = $input['name'];
        // $recipe->directions = $input['directions'];
        // $recipe->ingredients = $input['ingredients'];

        $recipe->save();

        return $this->sendResponse(new RecipeResource($recipe), 'Recipe updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        // return $this->sendResponse([], 'Recipe deleted successfully.');
        return response('', 200);
    }
}
