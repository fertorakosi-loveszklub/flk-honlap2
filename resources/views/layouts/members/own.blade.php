@extends('layouts.master')

@section('content')
    <div class="content">
        <h1>Saját adataim</h1>

        <h2 class="tmargin">Bejelentkezési adatok</h2>
        <div class="row">
          <div class="col-xs-4 col-sm-2">
            <img src="{!! $user->getProfilePicture($fb) !!}" alt="Profilkép" />
          </div>
          <div class="col-xs-8 col-sm-10">
            <p class="tmargin">{{ $user->real_name }} ({{ $user->name }})</p>
          </div>
        </div>

        <hr/>

        <h2>Tagnyilvántartás</h2>
        @if(isset($member) && !is_null($member))
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <p class="bold">Név</p>
            </div>
            <div class="col-xs-6 col-sm-8">
              <p>{{ $member->name }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <p class="bold">Tagság kezdete</p>
            </div>
            <div class="col-xs-6 col-sm-8">
              {{ (new DateTime($member->member_since))->format('Y. m. d.') }}
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <p class="bold">Tagdíj</p>
            </div>
            <div class="col-xs-6 col-sm-8">
              @if($member->isPaid())
                  <p><i class="fa fa-fw fa-check text-success"></i> - ({{ (new DateTime($member->getPaidUntil()))->format('Y. m. d.') }}-ig)</p>
              @else
                  <p><i class="fa fa-fw fa-times text-danger"></i></p>
              @endif
            </div>
          </div>
          <div class="row tmargin">
            <div class="col-xs-6 col-sm-4">
              <p class="bold">Szül.</p>
            </div>
            <div class="col-xs-6 col-sm-8">
              <p>{{ $member->birth_place }}, {{ (new DateTime($member->birth_date))->format('Y. m. d.') }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <p class="bold">Anyja neve</p>
            </div>
            <div class="col-xs-6 col-sm-8">
              <p>{{ $member->mother_name }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <p class="bold">Lakcím</p>
            </div>
            <div class="col-xs-6 col-sm-8">
              <p>{{ $member->address }}</p>
            </div>
          </div>
          @if(!is_null($member->card_id))
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <p class="bold">Igazolás sorszáma:</p>
            </div>
            <div class="col-xs-6 col-sm-8">
              <p>{{ $member->card_id }}</p>
            </div>
          </div>
          @endif
        @else
          <p>A Facebook profilod nincs hozzákapcsolva egy nyilvántartott taghoz sem.</p>
          <p>Ha ezt látod, kérlek, szólj valamelyik adminisztrátornak.</p>
        @endif
    </div>
@overwrite
