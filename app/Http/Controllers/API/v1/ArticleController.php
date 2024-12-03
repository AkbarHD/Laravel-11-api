<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        /**
         * fetch data dari table artikel berdasarkan data
         * cari data yang terbaru
         */

        //  search
        $query = Article::query()->latest('publish_date');
        $keyword = $request->input('title');
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
            $articles = $query->paginate(2);
        }
        $articles = $query->paginate(4);

        /** jika data kosong tampilkan hasil dan pesan di bawah */
        if ($articles->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'List Artcle empty'
            ]);
        } else {
            /** jika data ada tampilkan data sesuai hasil return dari fungsi map */
            return response()->json([
                'data' => $articles->map(function ($article) {
                    return [
                        'title' => $article->title,
                        'content' => $article->content,
                        'publish_date' => $article->publish_date
                    ];
                }),
                // 'data' => $articles,
                'message' => 'List Article',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        }

    }

    public function store(Request $request)
    {
        // melakukan validasi menggunakan validator
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'publish_date' => 'required'
        ]);

        // jika data tidak valid makan return response berikut
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            /**
             * jika validasi berhasil lakukan proses simpan data lalu return
             * response json dibawah
             */
            Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'publish_date' => Carbon::create($request->publish_date)->toDateString()
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data stored to db'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error storing data : ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed stored data to db'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $article = Article::where('id', $id)->first();
        if ($article) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => [
                    'title' => $article->title,
                    'content' => $article->content,
                    'publish_date' => $article->publish_date
                ]
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Aricle Not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'data' => 'Data not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'publish_date' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $article->update([
                'title' => $request->title,
                'content' => $request->content,
                'publish_date' => Carbon::create($request->publish_date)->toDateString()
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data succces to update'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error storing data : ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed updated data to db'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function destroy($id)
    {
        $article = Article::find($id);
        try {
            $article->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data succces to delete'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error storing data : ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed deleted data to db'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
