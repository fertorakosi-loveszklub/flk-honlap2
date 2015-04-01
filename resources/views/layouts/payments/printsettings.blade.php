@extends('layouts.master')

@section('content')
    <div class="content">
    <h1>Tagdíjfizetések listája fizetés dátuma alapján</h1>
        <form class="form" method="post"> 
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="from">-Tól:</label>
                <input class="form-control" type="date" name="from">
            </div>
            <div class="form-group">
                <label for="to">-Ig:</label>
                <input class="form-control" type="date" name="to">
            </div>
            <input class="btn btn-primary" type="submit" value="Lista készítése">
        </form>
    </div>
@overwrite