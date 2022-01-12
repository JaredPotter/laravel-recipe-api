<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use uhin\laravel_api\Helpers\UhinApi;

class TagController extends Controller
{

    /**
     * Get All
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = UhinApi::getQueryBuilder(Tag::class);
        $query = UhinApi::parseAll($query, $request);
        $tags = $query->paginate(150);

        return TagResource::collection($tags);
    }

    /**
     * Get by Index
     *
     * @param Request $request
     * @param $tagId
     * @return TagResource
     */
    public function show(Request $request, $tagId)
    {
        $query = UhinApi::getQueryBuilder(Tag::class);
        $query = UhinApi::parseFields($query, $request);

        return new TagResource($query->findOrFail($tagId));
    }

    /**
     * Post by index
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //Validate required fields
        $validated = $request->validate([
            'data.name' => 'required',
        ]);

        //Create new model and save
        $tag = new Tag;
        $tag = UhinApi::fillModelFromValidator($tag, $validated, 'data');
        $tag->save();

        $tag = Tag::findOrFail($tag->id);
        $resource = new TagResource($tag);

        return $resource->response()->setStatusCode(201);
    }

    /**
     * Patch by index
     *
     * @param Request $request
     * @param Tag $tag
     * @return TagResource
     */
    public function update(Request $request, Tag $tag)
    {
        $tag = UhinApi::fillModel($tag, $request);
        $tag->save();

        return new TagResource($tag);
    }

    /**
     * Delete by index
     *
     * @param Tag $tag
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
    }
}
