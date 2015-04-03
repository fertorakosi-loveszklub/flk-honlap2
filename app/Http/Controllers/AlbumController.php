<?php
namespace App\Http\Controllers;

use App\Album;
use App\Libraries\UrlBeautifier;
use Illuminate\Http\Request;

class AlbumController extends BaseController
{
    /**
     * Initializes a new instance of the AlbumController class.
     */
    public function __construct()
    {
        $this->middleware('admin', ['only' => ['postUj', 'postSzerkesztes', 'getTorles']]);
    }
    /**
     * GET method, index route (/)
     * Displays the list of all albums.
     *
     * @return mixed View
     */
    public function getIndex()
    {
        $albums = Album::orderBy('created_at', 'desc')->get();

        return view('layouts.albums.list', array('albums' => $albums));
    }

    /**
     * GET method, custom route (//album/$id/$title)
     * Displays all images of an album with the given id.
     *
     * @param $id       Id of the album to display.
     * @param $title    Title of the album. Unused.
     *
     * @return mixed View
     */
    public function getShowAlbum($id, $title)
    {
        $album = Album::find($id);

        // Check if album is valid
        if (is_null($album)) {
            return redirect('/galeria/');
        }

        return view('layouts.albums.show', array('album' => $album));
    }

    /**
     * POST method, uj route (/uj)
     * Saves a new album.
     *
     * @return mixed View
     */
    public function postUj(Request $req)
    {
        if (! $req->has('title') || ! $req->has('album_url')) {
            redirect('/galeria/')->with('message', array( 'message' => 'Hiányzó cím vagy URL',
                'type' => 'danger', ));
        }

        $album = new Album();
        $album->title = $req->input('title');
        $album->album_url = $req->input('album_url');

        // Validate
        if (!$album->validate()) {
            $errors = $album->getValidationErrors();

            $msg = $errors->has('title') ? $errors->get('title')[0].'<br/>' : '';
            $msg .= $errors->has('album_url') ? $errors->get('album_url')[0] : '';

            return redirect('/galeria/')->with(
                'message',
                array('message' => $msg,
                    'type' => 'danger', )
            );
        }

        $album->save();

        return redirect('/album/'.$album->id.'/'.UrlBeautifier::beautify($album->title))->with(
            'message',
            array( 'message' => 'Album létrehozva', 'type' => 'success')
        );
    }

    /**
     * POST method, szerkesztes route (/szerkesztes/$id)
     * Saves the changes to an album with the given id.
     *
     * @param $id       Id of the album to save.
     *
     * @return mixed View
     */
    public function postSzerkesztes($id, Request $req)
    {
        $album = Album::find($id);

        // Check if album is valid
        if (is_null($album)) {
            return redirect('/galeria/');
        }

        $album->title = $req->input('title');
        $album->album_url = $req->input('album_url');

        if (!$album->validate()) {
            $errors = $album->getValidationErrors();

            $msg = $errors->has('title') ? $errors->get('title')[0].'<br/>' : '';
            $msg .= $errors->has('album_url') ? $errors->get('album_url')[0] : '';

            return redirect('/album/'.$id.'/'.UrlBeautifier::beautify($album->title))->with(
                'message',
                array('message' => $msg,
                    'type' => 'danger', )
            );
        }

        $album->save();

        return redirect('/album/'.$id.'/'.
            UrlBeautifier::beautify($album->title))->with(
                'message',
                array( 'message' => 'Album frissítve', 'type' => 'success')
            );
    }

    /**
     * GET method, torles route (/torles/$id)
     * Deletes an album with the given id.
     *
     * @param $id       ID of the album to delete.
     *
     * @return mixed View
     */
    public function getTorles($id)
    {
        $album = Album::find($id);

        // Check if real id
        if (is_null($album)) {
            return redirect('/');
        }

        $album->delete();

        return redirect('/galeria/')->with(
            'message',
            array( 'message' => 'Album törölve.', 'type' => 'success')
        );
    }
}
