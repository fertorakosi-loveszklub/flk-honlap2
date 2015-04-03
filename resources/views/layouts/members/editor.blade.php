@extends('layouts.master')

@section('content')
    <div class="content">
        <h1>{!! $title !!}</h1>
        <form method="post" class="form form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Név</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="name" name="name" placeholder="Név"
                  @if(isset($member)) value="{{ $member->name }}" @endif >

                  @if(isset($error) && $error->has('name'))<p class="text-danger"> {{ $error->get('name')[0] }}</p>@endif
                </div>
              </div>
              <div class="form-group">
                <label for="birth_date" class="col-sm-2 control-label">Szül. idő</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="birth_date" name="birth_date"
                  @if(isset($member)) value="{{ (new DateTime($member->birth_date))->format('Y-m-d') }}" @endif >

                  @if(isset($error) && $error->has('birth_date'))<p class="text-danger"> {{ $error->get('birth_date')[0] }}</p>@endif
                </div>
              </div>
              <div class="form-group">
                <label for="birth_place" class="col-sm-2 control-label">Szül. hely</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="birth_place" name="birth_place" placeholder="Szül. hely"
                  @if(isset($member)) value="{{ $member->birth_place }}" @endif >

                  @if(isset($error) && $error->has('birth_place'))<p class="text-danger"> {{ $error->get('birth_place')[0] }}</p>@endif
                </div>
              </div>
              <div class="form-group">
                <label for="mother_name" class="col-sm-2 control-label">Anyja neve</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Anyja neve"
                  @if(isset($member)) value="{{ $member->mother_name }}" @endif >

                  @if(isset($error) && $error->has('mother_name'))<p class="text-danger"> {{ $error->get('mother_name')[0] }}</p>@endif
                </div>
              </div>
              <div class="form-group">
                <label for="address" class="col-sm-2 control-label">Cím</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="address" name="address" placeholder="Cím"
                  @if(isset($member)) value="{{ $member->address }}" @endif >

                  @if(isset($error) && $error->has('address')) <p class="text-danger">{{ $error->get('address')[0] }}</p>@endif
                </div>
              </div>
              <div class="form-group">
                <label for="member_since" class="col-sm-2 control-label">Tagság kezdete</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="member_since" name="member_since"
                  @if(isset($member)) value="{{ (new DateTime($member->member_since))->format('Y-m-d') }}" @endif >

                  @if(isset($error) && $error->has('member_since'))<p class="text-danger"> {{ $error->get('member_since')[0] }}</p>@endif
                </div>
              </div>
              <div class="form-group">
                <label for="card_id" class="col-sm-2 control-label">Igazolás száma</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="card_id" name="card_id"
                  @if(isset($member)) value="{{ $member->card_id }}" @endif >
                  @if(isset($nextCardId))<p>A következő elérhető sorszám: {{ $nextCardId }}</p>@endif
                  @if(isset($error) && $error->has('card_id'))<p class="text-danger"> {{ $error->get('card_id')[0] }}</p>@endif
                </div>
              </div>

              <div class="form-group">
                <label for="save" class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <input class="btn btn-primary" type="submit" value="Mentés">
                </div>
              </div>
        </form>
    </div>
@overwrite
