<div class="d-flex mb-3">
    <a class=" active btn btn-{{ Route::is('location.setting.index') ? 'primary' : '' }}" href="{{route('location.setting.index')}}">Setting </a>
    <a class="btn btn-{{ Route::is('location.get.all.voices') ? 'primary' : '' }} mx-3" href="{{route('location.get.all.voices')}}">All Voices</a>
    {{-- <a class="btn btn-{{ Route::is('location.get.own.voices') ? 'primary' : '' }}" href="{{route('location.get.own.voices')}}"">Own Voices</a> --}}
  </div>
