<div class="pins">
  <div class="card-pin {{$item->featured ? 'featured' : ''}}">
   
    <figure class="figure" data-toggle="modal" data-target="#modalPin{{$item->id}}">
      @if($item->featured )
        <a class="link-star">
          <i class="fa fa-star text-white"></i>
        </a>
      @endif
      <x-media::single-image :alt="$item->title ?? $item->name"
                             :title="$item->title ?? $item->name"
                             :url="!$embedded ? $item->url ?? null : null" :isMedia="true"
                             imgClasses=""
                             :mediaFiles="$item->mediaFiles()"/>
      <a class="link-like">
        <i class="fa fa-heart"></i>
      </a>
    
    </figure>
    
    <div class="card-body p-0">
      @if(!$embedded)
      <a href="{{$item->url ?? ''}}">
  @endif
        <h5 class="card-title" type="button">
    
          {{$item->title}}
  
        </h5>
        @if(!$embedded)
      </a>
      @endif
      
      <div class="d-inline-block">
        <span class="badge info-badge">
          {{--Medellín--}}
          @if(isset($item->city->name))
            {{$item->city->name}}
          @endif
        </span>
        <span class="badge info-badge">

          {{--21 años--}}
          @if(isset(collect($item->fields)->where('name','age')->first()->value))
            {{collect($item->fields)->where('name','age')->first()->value}} años
          @endif
        </span>
        <span class="badge info-badge">${{formatMoney($item->min_price)}}</span>
        <span class="badge info-badge">{{$item->country->name}}</span>
        @if($item->status == 3)
          <span class="badge info-badge certified" title="{{trans("iad::status.checked")}}"></span>
        @endif
        @php($videos = $item->mediaFiles()->videos)
        @php($gallery = $item->mediaFiles()->gallery)
        @if(count($videos)>0)
          <span class="badge info-badge videos">
            <i class="fa fa-play-circle-o" aria-hidden="true"></i>
            {{count($videos)}}</span>
        @endif
          @if(count($gallery)>0)
          <span class="badge info-badge photos">
            <i class="fa fa-camera" aria-hidden="true"></i>
            {{count($gallery)}}</span>
        @endif
        
      </div>
    
    </div>
  </div>
  
</div>