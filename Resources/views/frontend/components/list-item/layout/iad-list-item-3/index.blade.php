<div class="pins">
  <div class="card-pin {{$item->featured ? 'featured' : ''}}">
    <div class="card-pin-id">
      {{$item->id}}
    </div>
    <figure class="figure">
      <x-media::single-image :alt="$item->title ?? $item->name"
                             :title="$item->title ?? $item->name"
                             :url="$item->url ?? null" :isMedia="true"
                             imgClasses=""
                             :mediaFiles="$item->mediaFiles()"/>
    </figure>
    <div class="card-pin-body p-0">
      <div class="card-pin-description">
        {{$item->description}}
      </div>
      <div class="row no-gutters">
        @if(isset($item->city->name))
        <div class="col-12">
          <div class="card-pin-location">
              <i class="fa fa-map-marker"></i>
              {{$item->city->name}}, {{$item->province->name ?? ""}}
          </div>
        </div>
        @endif
        @foreach($item->categories as $category)
        @if($category->parent_id == 2)
          <div class="col-auto mr-2">
            <i class="fa-solid fa-road icon"></i>
            <div class="card-pin-category"> {{$category->title}}</div>
          </div>
        @endif
        @endforeach
        <div class="col col-extra">
          @if(!empty($item->options->bpni))
            <div class="card-pin-title">BPIN</div>
            <div class="card-pin-text">{{$item->options->bpni}}</div>
          @endif
          @if(!empty($item->min_price))
            <div class="card-pin-title">VALOR</div>
            <div class="card-pin-text text-color">{{"$" . number_format($item->min_price, 0, ",", ".")}}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>