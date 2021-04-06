<div class="container modal-girl">
  <div class="row">
    <div class="col-lg-6 pb-4">
      <div class="modal-images">
  
        <div id="carouselGallery" class="carousel slide mb-2" data-ride="carousel">
          <a class="carousel-control-prev" href="#carouselGallery" role="button" data-slide="prev">
            <span class="fa fa-caret-right" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselGallery" role="button" data-slide="next">
            <span class="fa fa-caret-left" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <a
                href="{{$item->mediaFiles()->mainimage->extraLargeThumb}}"
                data-fancybox="gallery" data-caption="{{$item->title}}">
              
                  <x-media::single-image :alt="$item->title ?? $item->name"
                                         :title="$item->title ?? $item->name"
                                         :isMedia="true"
                                         imgClasses=""
                                         :mediaFiles="$item->mediaFiles()"/>
        
              </a>
            </div>
            @php($videos = $item->mediaFiles()->videos)
            @php($gallery = $item->mediaFiles()->gallery)
            @php($dataSlideTo = 1)
            @foreach($gallery as $itemGallery)
              <div class="carousel-item ">
                <a href="{{$itemGallery->extraLargeThumb}}"
                   data-fancybox="gallery" data-caption="{{$item->title}}">
                  <x-media::single-image :alt="$item->title ?? $item->name"
                                         :title="$item->title ?? $item->name"
                                         :src="$itemGallery->extraLargeThumb ?? null"
                                         imgClasses=""/>
                </a>
              </div>
              @php($dataSlideTo++)
            @endforeach
            @foreach($videos as $video)
              <div class="carousel-item ">
                <a data-fancybox href="#myVideo">
                  <video width="100%" height="450" controls>
                    <source src="{{$video->path}}" type="video/mp4">
                    <source src="{{$video->path}}" type="video/webm">
                    <source src="{{$video->path}}" type="video/ogg">
                    Your browser doesn't support HTML5 video tag.
                  </video>
                </a>
                <video width="100%" height="450" controls id="myVideo" style="display:none;">
                  <source src="{{$video->path}}" type="video/mp4">
                  <source src="{{$video->path}}" type="video/webm">
                  <source src="{{$video->path}}" type="video/ogg">
                  Your browser doesn't support HTML5 video tag.
                </video>
              </div>
            
            @endforeach

          </div>
        
        </div>
        <!--carusel de abajo-->

        <div class="owl-carousel owl-image-mini owl-image-mini{{$item->id}} owl-theme">
          <div class="item">
              <a data-slide-to="0" data-target="#carouselGallery"
                 href="{{$item->mediaFiles()->mainimage->extraLargeThumb}}"
                data-fancybox="gallery" data-caption="{{$item->title}}">
              <x-media::single-image :alt="$item->title ?? $item->name"
                                     :title="$item->title ?? $item->name"
                                     :isMedia="true"
                                     imgClasses=""
                                     :mediaFiles="$item->mediaFiles()"/>
              </a>
  
          </div>
          @php($dataSlideTo = 1)
          @foreach($gallery as $itemGallery)
            <div class="item">
              <a data-slide-to="{{$dataSlideTo}}" data-target="#carouselGallery"
                 href="{{$itemGallery->extraLargeThumb}}"
                data-fancybox="gallery" data-caption="{{$item->title}}">
                <x-media::single-image :alt="$item->title ?? $item->name"
                                       :title="$item->title ?? $item->name"
                                       :src="$itemGallery->extraLargeThumb ?? null"
                                       imgClasses=""/>
              </a>
            </div>
            @php($dataSlideTo++)
            @endforeach
          @foreach($videos as $video)
            <div class="item">
              <a data-slide-to="{{$dataSlideTo}}" data-target="#carouselGallery"
                 href="{{$video->path}}"
                 data-fancybox="gallery" data-caption="{{$item->title}}">
                <x-media::single-image :alt="$item->title ?? $item->name"
                                       :title="$item->title ?? $item->name"
                                       :src="url('/modules/iad/img/video.png')"
                                       imgClasses="card-img-top img-fluid p-3"/>
              </a>
          
            </div>
            @php($dataSlideTo++)
          @endforeach
          
        </div>
      
      </div>
    
    </div>
    <div class="col-lg-6 pb-4">
      
      <h2 class="modal-title mb-3">
        {{$item->title}}
      </h2>
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
     
      @if(count($videos)>0)
        <span class="badge info-badge videos">
          <i class="fa fa-play-circle-o" aria-hidden="true"></i>
          {{count($videos)}}</span>
      @endif
   
      @php($videos = $item->mediaFiles()->videos)
      @if(count($videos)>0)
        <span class="badge info-badge videos">{{count($videos)}}</span>
      @endif
      @php($gallery = $item->mediaFiles()->gallery)
      @if(count($gallery)>0)
        <span class="badge info-badge photos">
          <i class="fa fa-camera" aria-hidden="true"></i>
          {{count($gallery)}}</span>
      @endif
      
      <p class="modal-date my-3">
        06/11/2020 | 5:00PM
      </p>
      
      <div class="modal-description">
        {!! nl2br ($item->description) !!}
        {{--                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et ullamcorper ante, et mattis ipsum.--}}
        {{--                    Quisque a nisi in risus cursus ullamcorper. Vestibulum et laoreet orci. Duis pulvinar sapien quam,--}}
        {{--                    ut feugiat sem bibendum pellentesque. Interdum et malesuada fames ac ante ipsum primis.</p>--}}
        {{--                  <p>Curabitur congue tristique purus, non imperdiet dui tempus sit amet. Donec tincidunt congue sapien--}}
        {{--                    id placerat. Pellentesque id consequat arcu. Vestibulum sagittis velit non hendrerit pharetra.</p>--}}
      </div>
      
      <div class="group-btn">
        @if(isset($item->options->whatsapp))
          <a class="btn btn-whatsapp" href="https://wa.me/+57{{ $item->options->whatsapp }}?text=hola te ví en Sexy Latinas, me gustaría conocerte" target="_blank">
            <i class="fa fa-whatsapp"></i> WhatsApp
          </a>
        @endif
          @if(isset($item->options->instagram))
          <a class="btn btn-instagram" href="https://instagram.com/{{$item->options->instagram}}" target="_blank">
            <i class="fa fa-instagram"></i> Instagram
          </a>
        @endif
  
          @if(isset($item->options->twitter))
          <a class="btn btn-twitter"
             href="https://twitter.com/{{$item->options->twitter}}" target="_blank">
            <i class="fa fa-twitter"></i>Twitter
          </a>
        @endif
          @if(isset($item->options->youtube))
          <a class="btn btn-youtube"
             href="https://youtube.com/{{$item->options->youtube}}" target="_blank">
            <i class="fa fa-youtube"></i>Youtube
          </a>
        @endif
        
        @if(isset($item->options->phone))
          <a class="btn btn-phone" href="tel:{{$item->options->phone}}"
             target="_blank">
            <i class="fa fa-mobile"></i> {{$item->options->phone}}
          </a>
        @endif
        
        <a class="btn btn-like"
           onClick="window.livewire.emit('addToWishList',{{json_encode(["entityName" => "Modules\\Iad\\Entities\\Ad", "entityId" => $item->id])}})">
          <i class="fa fa-heart"></i>
        </a>
      </div>
    
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <hr class="mb-4">
    </div>
  </div>
  
  <div class="row">
    <!--Rates-->
    @if(isset($item->options->prices))
      <div class="col-lg-6 pb-4">
        <h3 class="modal-title mb-3">
          Tarifas
        </h3>
        @foreach($item->options->prices as $rate)
          <div class="row align-items-center modal-item">
            <div class="col-5 col-sm-3">{{$rate->description}}</div>
            <div class="col-2 col-sm-5">
              <hr class="solid">
            </div>
            <div class="col-5 col-sm-4 text-primary">${{formatMoney($rate->value)}}</div>
          </div>
        @endforeach
      </div>
    @endif
  <!--Schedule-->
    @if(isset($item->options->schedule))
      <div class="col-lg-6 pb-4">
        <h3 class="modal-title mb-3">
          Horarios
        </h3>
        @foreach($item->options->schedule as $schedule)
          <div class="row align-items-center modal-item">
            <div class="col-5 col-sm-4">
              
              {{trans("iad::schedules.days.".$schedule->name)}}
            
            </div>
            <div class="col-2 col-sm-4">
              <hr class="solid">
            </div>
            <div class="col-5 col-sm-4 text-primary">
              @if($schedule->schedules == 1)
                {{trans("iad::schedules.schedules.24Hours")}}
              @else
                @foreach($schedule->schedules as $shift)
                  {{date("g:ia",strtotime($shift->from))}} -
                  {{date("g:ia",strtotime($shift->to))}}
                @endforeach
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
    
    <div class="col-lg-12 pb-4">
      <hr>
    </div>
  </div>
  
  
  <div class="row">
    
    @php($categories = Modules\Iad\Entities\Category::all())
    @foreach($categories->toTree() as $categoryParent)
      @php($categoriesAd = array_intersect($item->categories->pluck("id")->toArray(),$categoryParent->children->pluck("id")->toArray()))
      @if(!empty($categoriesAd))
        
        <div class="col-12 col-md-4 pb-4">
          <h3 class="modal-title mb-3">
            
            {{$categoryParent->title}}
          </h3>
          @foreach($categoriesAd as $categoryId)
            @php($categoryAd = $item->categories->where("id",$categoryId)->first())
            <span class="badge info-badge">
              <a href="{{url("?filter[categories][0]=$categoryId")}}">{{$categoryAd->title}}</a>
              </span>
          @endforeach
        </div>
      @endif
    @endforeach
    
    <div class="col-lg-12 pb-4">
      <hr>
    </div>
  </div>
  
  @if(isset($item->options->map->title) && !empty($item->options->map->lat) && !empty($item->options->map->lng))
    <div class="row">
      
      
      <div class="col-lg-8 pb-4">
        <h3 class="modal-title mb-3">
          Ubicación
        </h3>
        <div id="girl-map{{$item->id}}">
        </div>
      </div>
    
    </div>

      <style type="text/css">
        #girl-map{{$item->id}}   {
          height: 400px;
          width: 100%;
        }
      </style>
   

    <script>
      // Initialize and add the map
      $(document).ready(function () {
        // The map, centered at Uluru
        var map{{$item->id}} = new google.maps.Map(document.getElementById("girl-map{{$item->id}}"), {
          zoom: 16,
          center: {lat: {{$item->options->map->lat}}, lng: {{$item->options->map->lng}} },
        });
        // The marker, positioned at Uluru
        var marker{{$item->id}} = new google.maps.Marker({
          position: {lat: {{$item->options->map->lat}}, lng: {{$item->options->map->lng}} },
          map: map{{$item->id}},
        });
      });
    
    </script>

  @endif
  
  <div class="row featured-pins">
  
    <x-isite::carousel.owl-carousel
      title="Anuncios Destacados"
      id="featuredPins{{$item->id}}"
      :params="[
                        'include' => ['city','schedule','fields','categories','translations'],
                        'filter' =>[ 'status' => [2,3], 'featured' => true ],
                        'take' => 10
                        ]"
      :responsive="[0 => ['items' =>  1],640 => ['items' => 2],992 => ['items' => 4]]"
      repository="Modules\Iad\Repositories\AdRepository"
      itemComponent="iad::list-item"
    />
  
  
    <div class="col-lg-12 pb-4">
      <hr>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-auto">
      <a class="btn btn-flag" data-toggle="collapse" href="#collapseGirl{{$item->id}}" role="button"
         aria-expanded="false" aria-controls="collapseGirl{{$item->id}}">
        <img class="img-fluid" src="{{Theme::url('girls-publication/ico-denunciar.png')}}" alt="Flag this ad">
        Denunciar éste anuncio
      </a>
    </div>
    <div class="col-12">
      <div class="collapse mt-4" id="collapseGirl{{$item->id}}">
        <div class="card card-body pt-4 bg-light">
          
          {!! Forms::render('denuncia','iforms::frontend.form.bt-nolabel.form') !!}
          
          <p class="text-justify mt-4 mb-0"><strong>Nota:</strong> Si el motivo de la denuncia es que eres la
            persona que aparece en las fotos y quieres eliminar el anuncio, y no tienes acceso ni al email que
            se usó para publicarlo ni al teléfono que aparece en el anuncio, debes indicarnos un teléfono y
            email para que podamos contactar contigo y confirmar que realmente eres tú.</p>
        </div>
      </div>
    </div>
  </div>
</div>


  <script>
  
    $(document).ready(function () {
    
      $('.owl-image-mini{{$item->id}}').owlCarousel({
        responsiveClass: true,
        nav: false,
        video: true,
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
    $(document).ready(function () {
  
      $('#featuredPins{{$item->id}}Carousel').owlCarousel({
        responsiveClass: true,
        nav: false,
        margin: 15,
        dots: false,
        lazyContent: true,
        autoplay: true,
        autoplayHoverPause: true,
        responsive: {
          0: {
            items: 2
          },
          768: {
            items: 3
          },
          992: {
            items: 4
          }
        }
      });
  
  
  
    });
  
  </script>
