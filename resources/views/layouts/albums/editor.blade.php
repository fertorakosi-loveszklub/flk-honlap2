@extends('layouts.master')

@section('content')
<!-- Album editor -->
<div class="modal fade" id="AlbumEdit" tabindex="-1" role="dialog" aria-labelledby="AlbumEdit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" id="AlbumEditForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-image"></i> <span id="AlbumEditTitle"></span></h4>
                </div>
                <div class="modal-body">
                    <p>A tárhellyel való spórolás miatt a galériába szánt képeket külső képmegosztóra kell feltölteni. Ennek menete a következő:</p>
                    <ol>
                        <li><a href="http://imgur.com" target="_blank">Imgur.com</a></li>
                        <li>Az 'Upload images' gombbal elkezdjük a feltöltést</li>
                        <li>A 'Browse your computer' gombbal kiválasztjuk a képeket</li>
                        <li>A 'Create album' gomb bekapcsolásával jelezzük, hogy albumot szeretnénk belőlük. A mezőket nem kell kitölteni.</li>
                        <li>A 'Start upload' gombbal elkezdjük a feltöltést</li>
                        <li>Ha elkészült a feltöltés, az album oldalára kerülünk</li>
                        <li>Másoljuk ki a kapott linket</li>
                    </ol>
                    <div class="form-group">
                        <label for="title">Album címe</label>
                        <input type="input" class="form-control" id="albumTitle" name="title" placeholder="Album címe" required>
                    </div>
                    <div class="form-group">
                        <label for="album_url">Imgur album URL</label>
                        <input type="input" class="form-control" id="album_url" name="album_url" placeholder="http://imgur.com/a/abcde" required>
                        <p id="wrongpattern" style="color: red; font-size: small;">Érvénytelen Imgur album URL</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="SaveAlbum">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

    @yield('subcontent')
@endsection