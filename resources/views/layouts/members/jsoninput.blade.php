@extends('layouts.master')
@section('content')
    <div class="content">
        <form method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="data">JSON adat</label>
                <textarea name="data" rows="8" cols="32" class="form-control" ></textarea>
            </div>
            <p>Figyelem, a beírt adatok egyszerűen mentésre kerülnek, szinkronizáció nélkül!</p>
            <p>Csak akkor mentsd el, ha tudod, mit csinálsz!</p>
            <input type="submit" value="Mentés" class="btn btn-primary">
        </form>
    </div>
@overwrite
