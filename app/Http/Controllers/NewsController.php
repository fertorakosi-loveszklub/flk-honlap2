<?php
namespace App\Http\Controllers;

use App\Libraries\UrlBeautifier;
use App\News;
use App\User;
use Auth;
use Illuminate\Http\Request;

class NewsController extends BaseController
{
    /**
     * Initializes a new instance of the NewsController class.
     */
    public function __construct()
    {
        // Actions that need login
        $this->middleware('admin', ['only' => ['getUj', 'postUj', 'getSzerkesztes',
            'postSzerkesztes', 'getTorles', ]]);
    }

    /**
     * GET method, index route (/)
     * Displays the list of the last 10 news.
     *
     * @return mixed View
     */
    public function getIndex()
    {
        $news = News::with('author')->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

        return view('layouts.news.list', array('news' => $news));
    }

    /**
     * GET method, custom route (//hir/$id/$title)
     * Shows a news with the given id.
     *
     * @param $id       Id of the news to show
     * @param $title    Title of the news (unused)
     *
     * @return mixed View
     */
    public function getShowNews($id, $title)
    {

        // Check if ID is numeric
        $id = strval($id);

        $news = News::find($id);

        // Check if it exists
        if (is_null($news)) {
            return redirect('/');
        }

        // Display
        return view('layouts.news.show', array('news' => $news));
    }

    /**
     * GET method, uj route (/uj)
     * Displays a form for adding a new news.
     *
     * @return mixed View
     */
    public function getUj()
    {
        $options = array(
            'pageTitle'     => 'Új hír írása',
            'editAction'    => '/hirek/uj',
            'titleReadonly' => false,
            'title'         => '',
            'content'       => '',

        );

        return view('layouts.editor.editor', $options);
    }

    /**
     * POST method, uj route (/uj)
     * Saves a new news.
     *
     * @return mixed View
     */
    public function postUj(Request $req)
    {
        if (!$req->has('title') || !$req->has('content')) {
            redirect('/hirek/uj')->with('message', array( 'message' => 'Hiányzó cím vagy szöveg',
                'type' => 'danger', ));
        }

        $n = new News();
        $n->title = htmlentities($req->input('title'));
        $n->content = $req->input('content');
        $n->user_id = Auth::user()->id;

        // Validate
        if (!$n->validate()) {
            $errors = $n->getValidationErrors();

            $msg = $errors->has('title') ? $errors->get('title')[0] : '';

            return redirect('/hirek/')->with(
                'message',
                array('message' => $msg,
                    'type' => 'danger', )
            );
        }

        $n->save();

        return redirect('/hirek/')->with('message', array( 'message' => 'Hír létrehozva.',
            'type' => 'success', ));
    }

    /**
     * GET method, szerkesztes route (/szerkesztes/$id)
     * Displays a form for editing news.
     *
     * @param $id       News to edit.
     *
     * @return mixed View.
     */
    public function getSzerkesztes($id)
    {
        $hir = News::find($id);

        // Check if real id
        if (is_null($hir)) {
            return redirect('/');
        }

        $options = array(
            'pageTitle'     => 'Hír szerkesztése',
            'editAction'    => '/hirek/szerkesztes/'.$hir->id,
            'titleReadonly' => false,
            'title'         => $hir->title,
            'content'       => $hir->content,
        );

        return view('layouts.editor.editor', $options);
    }

    /**
     * POST method, szerkesztes route (/szerkesztes/$id)
     * Updates an existing news with the given id.
     *
     * @param $id       Id of the news to update.
     *
     * @return mixed View
     */
    public function postSzerkesztes($id, Request $req)
    {
        $hir = News::find($id);

        // Check if real id
        if (is_null($hir)) {
            return redirect('/');
        }

        $hir->title = $req->input('title');
        $hir->content = $req->input('content');

        // Validate
        if (!$hir->validate()) {
            $errors = $hir->getValidationErrors();

            $msg = $errors->has('title') ? $errors->get('title')[0] : '';

            return redirect('/hir/'.$id.'/'.UrlBeautifier::beautify($hir->title))->with(
                'message',
                array('message' => $msg,
                    'type' => 'danger', )
            );
        }

        $hir->save();

        return redirect('/hir/'.$id.'/'.UrlBeautifier::beautify($hir->title))->with(
            'message',
            array('message' => 'Hír frissítve.', 'type' => 'success')
        );
    }

    /**
     * GET method, torles route (/torles/$id)
     * Deletes a news with the given id.
     *
     * @param $id       Id of the news to delete.
     *
     * @return mixed View
     */
    public function getTorles($id)
    {
        $hir = News::find($id);

        // Check if real id
        if (is_null($hir)) {
            return redirect('/');
        }

        $hir->delete();

        return redirect('/hirek/')->with(
            'message',
            array( 'message' => 'Hír törölve.', 'type' => 'success')
        );
    }
}
