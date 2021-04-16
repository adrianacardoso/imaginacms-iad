@extends("layouts.master")

@section('content')
  <div class="page show show-pin show-pin-{{$pin->id ?? $item->id}}">
  @include("iad::frontend.partials.ad-item-show.layouts.iad-list-item-1.index")
  </div>
  @stop