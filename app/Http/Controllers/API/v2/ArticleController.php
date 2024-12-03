<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        /**
         * fetch data dari table artikel berdasarkan data
         * cari data yang terbaru
         * kita juga menambahkan fungis pencarina artikel
         * berdasarkan titlenya dan juga fungsi paginate
         * dimana kita membatasikan jumlah data yang ditampilkan
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
            ], Response::HTTP_NOT_FOUND);
        } else {
            // return response()->json([
            //     'data' => $articles->map(function ($article) {
            //         return [
            //             'title' => $article->title,
            //             'content' => $article->content,
            //             'publish_date' => $article->publish_date
            //         ];
            //     }),
            //     'message' => 'List Article',
            //     'status' => Response::HTTP_OK,
            // ], Response::HTTP_OK);

            return new ArticleCollection($articles);
        }
    }
}
