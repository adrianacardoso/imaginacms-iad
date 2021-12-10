<div class="">
  <x-media::single-image :alt="$item->title ?? $item->name"
                         :title="$item->title ?? $item->name"
                         :url="!$embedded ? $item->url ?? null : null" :isMedia="true"
                         imgClasses=""
                         :mediaFiles="$item->mediaFiles()"/>
  <div class="card-title d-flex px-4">
    @if(!empty($item->defaultPrice))
      <p class="item text-muted">
      <div class="d-inline-block price">
        ${{formatMoney($item->defaultPrice)}}
      </div>
      <span class="register"></span> COP | Venta
      </p>
    @endif
  </div>
  <div class="card-body">
    <h3 class="text-muted text-justify-center"> {{$item->title}}</h3>
    </br>
    @if(isset($item->city->name))
      <h3 class="text-center"><i class="fa fa-map-marker"></i>{{$item->city->name}}</h3>
    @endif
  </div>
  <div class="card-footer">
    <div class="col-auto text-center">
      @if(!empty($item->options->squareMeter))
        <i class="fa fa-square-o"> {!!$item->options->squareMeter!!} m²</i>
      @endif

      @if(!empty($item->options->bedrooms))
        <i class="fa fa-bed"> {!!$item->options->bedrooms!!}</i>
      @endif

      @if(!empty($item->options->toilets))
        <i class="fa fa-shower"> {!!$item->options->toilets!!}</i>
      @endif

      @if(!empty($item->options->parking))
        <i class="fa fa-car"> {!!$item->options->parking!!}</i>
      @endif
    </div>
  </div>
</div>
    
