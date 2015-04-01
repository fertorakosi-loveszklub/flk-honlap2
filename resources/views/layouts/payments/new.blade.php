@extends('layouts.master')

@section('content')
    <div class="content">
        <form class="form form-horizontal" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Név</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" readonly="readonly"
                        value="{{ $member->name }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="paid_at" class="col-sm-3 control-label">Fizetés dátuma</label>
                    <div class="col-sm-9">
                        <input type="date" name="paid_at" class="form-control" value="{{ (new DateTime('now'))->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="paid_at" class="col-sm-3 control-label">
                        Tagdíj befizetve eddig
                    </label>
                    <div class="col-sm-9">
                        <input type="date" name="paid_until" class="form-control" value="{{ (new DateTime('now'))->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="amount" class="col-sm-3 control-label">
                        Elkönyvelt összeg
                    </label>
                    <div class="col-sm-9">
                        <input type="number" name="amount" class="form-control" min="0"
                        max="100000">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <input type="submit" class="btn btn-primary" value="Mentés">
                    </div>
                </div>
        </form>
    </div>
@overwrite