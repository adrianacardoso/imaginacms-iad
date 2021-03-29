<div class="girls">
  <div class="card-girl">
    <figure class="figure" data-toggle="modal" data-target="#modalGirl{{$item->id}}">
      <x-media::single-image :alt="$item->title ?? $item->name"
                             :title="$item->title ?? $item->name"
                             :url="$item->url ?? null" :isMedia="true"
                             imgClasses=""
                             :mediaFiles="$item->mediaFiles()"/>
      <a class="link-like">
        <i class="fa fa-heart"></i>
      </a>
    </figure>
    
    <div class="card-body p-0">
      <h5 class="card-title" type="button" data-toggle="modal" data-target="#modalGirl{{$item->id}}">
        
        {{$item->title}}
      
      </h5>
      
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
        @if(count($videos)>0)
          <span class="badge info-badge videos">{{count($videos)}}</span>
        @endif
      </div>
    
    </div>
  </div>
  
  {{--
  @include('iad::frontend.components.list-item.layout.iad-list-item-1.modal')
  --}}
 

</div>



@section('scripts-owl')
  @parent
  <script>
    
    $(document).ready(function () {
      
      $('.owl-image-mini').owlCarousel({
        responsiveClass: true,
        nav: false,
        loop: false,
        margin: 10,
        dots: false,
        lazyContent: true,
        autoplay: true,
        autoplayHoverPause: true,
        responsive: {
          0: {
            items: 4
          },
          768: {
            items: 4
          },
          992: {
            items: 4
          }
        }
      });
      
      
    });
  
  </script>
@stop