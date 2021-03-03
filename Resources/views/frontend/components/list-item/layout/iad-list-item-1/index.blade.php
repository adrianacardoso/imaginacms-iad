@php
  use Modules\Iad\Transformers\AdTransformer;
  $adRepo = app('Modules\Iad\Repositories\AdRepository');


  //$item = \Modules\Iad\Entities\Ad::where('category_id', 1)->with(['tags','categories','services.translations','translations'])->first();
  $item = $adRepo->getItem(30, json_decode(json_encode([
    'include' => ['category', 'fields', 'categories','rates', 'services', 'schedules', 'tags','services.translations','translations']
  ])));
  $item = json_decode(json_encode(new AdTransformer($item)));
@endphp
{{--{{dd($item->fields[0]->name,$item->fields[0]->value)}}--}}
{{--{{dd($item->mainImage)}}--}}


<div class="card-columns girls">
  <div class="card card-girl">
    <figure class="figure" data-toggle="modal" data-target="#modalGirl{{$item->id}}">
      <img src="https://adriana.modulos.imaginacolombia.com/assets/media/tips-y-recomendaciones.jpg" alt="...">
      {{--      <x-media::single-image :alt="$item->title ?? $item->name" :title="$item->title ?? $item->name" :--}}
      {{--                             :url="$item->url ?? null" :isMedia="true" width="100%"--}}
      {{--                             :mediaFiles="$item->mediaFiles()" :zone="$mediaImage ?? 'mainimage'"/>--}}
      <a class="link-like">
        <i class="fa fa-heart"></i>
      </a>
    </figure>

    <div class="card-body p-0">
      <h5 class="card-title" type="button" data-toggle="modal" data-target="#modalGirl{{$item->id}}">
        @if(isset($item->description))
          {{$item->description}}
        @endif
      </h5>

      <div class="d-inline-block">
        <span class="badge info-badge">
{{--          Medellín--}}
          @if(isset($item->city->name))
            {{$item->city->name}}
          @endif
        </span>
        <span class="badge info-badge">

          {{--          21 años--}}
          @if(isset(collect($item->fields)->where('name','age')->first()->value))
            {{collect($item->fields)->where('name','age')->first()->value}} años
          @endif
        </span>
        <span class="badge info-badge">$95.000</span>
        <span class="badge info-badge">Colombianas</span>
        <span class="badge info-badge certified"></span>
        <span class="badge info-badge comments">13</span>
        <span class="badge info-badge videos">1</span>
      </div>

    </div>
  </div>
  <div class="modal modal-girl fade" id="modalGirl{{$item->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <img class="img-fluid" src="{{Theme::url('girls-publication/close.png')}}" alt="girl-1">
          </button>
        </div>
        <div class="modal-body">

          <div class="container-fluid">
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
                          href="https://sexy-latinas.imaginacolombia.com/assets/Iad/ad/3YfGSuM7p8/gallery/0ZiUpnEdBa3yjZ7Xbd0gBl3zLA6m5ugK.jpg"
                          data-fancybox="gallery" data-caption="Nombre persona">
                          <picture class="slider-cover">
                            <img src="{{$item->mainImage->path}}" alt="">
                          </picture>
                        </a>
                      </div>

                    </div>

                  </div>
                  <!--carusel de abajo-->
                  <div class="owl-carousel owl-image-mini owl-theme">
                    <div class="item">
                      <picture class="slider-cover">
                        <img data-target="#carouselGallery" src="{{$item->mainImage->path}}" alt=""
                             class="lazyload">
                      </picture>
                    </div>
                  </div>

                </div>

              </div>
              <div class="col-lg-6 pb-4">

                <h2 class="modal-title mb-3">
                  {{$item->title}}
                </h2>

                <span class="badge info-badge">Medellín</span>
                <span class="badge info-badge">21 años</span>
                <span class="badge info-badge">$95.000</span>
                <span class="badge info-badge">Colombianas</span>
                <span class="badge info-badge certified"></span>
                <span class="badge info-badge comments">13</span>
                <span class="badge info-badge videos">1</span>

                <p class="modal-date my-3">
                  06/11/2020 | 5:00PM
                </p>

                <div class="modal-description">
                  {{ $item->description }}
                  {{--                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et ullamcorper ante, et mattis ipsum.--}}
                  {{--                    Quisque a nisi in risus cursus ullamcorper. Vestibulum et laoreet orci. Duis pulvinar sapien quam,--}}
                  {{--                    ut feugiat sem bibendum pellentesque. Interdum et malesuada fames ac ante ipsum primis.</p>--}}
                  {{--                  <p>Curabitur congue tristique purus, non imperdiet dui tempus sit amet. Donec tincidunt congue sapien--}}
                  {{--                    id placerat. Pellentesque id consequat arcu. Vestibulum sagittis velit non hendrerit pharetra.</p>--}}
                </div>


                <div class="group-btn">
                  @if(isset(collect($item->fields)->where('name','whatsapp')->first()->value))
                    <a class="btn btn-whatsapp" href="" target="_blank">
                      <i class="fa fa-whatsapp"></i> WhatsApp
                    </a>
                  @endif

                  {{--                    @if(isset(collect($item->fields)->where('name','twitter')->first()->value))--}}
                  <a class="btn btn-twitter"
                     href="" target="_blank">
                    <i class="fa fa-twitter"></i>twitter
                    {{--                        {{collect($item->fields)->where('name','twitter')->first()->value}}--}}
                  </a>
                  {{--                    @endif--}}

                  @if(isset(collect($item->fields)->where('name','phone')->first()->value))
                    <a class="btn btn-phone" href="tel:collect($item->fields)->where('name','phone')->first()->value"
                       target="_blank">
                      <i class="fa fa-mobile"></i> {{collect($item->fields)->where('name','phone')->first()->value}}
                    </a>
                  @endif

                  <a class="btn btn-like">
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
              <div class="col-lg-6 pb-4">
                <h3 class="modal-title mb-3">
                  Videos
                </h3>

                <div class="row">
                  <div class="col-6 col-sm-3 px-1 pb-2">
                    <div class="modal-video embed-responsive embed-responsive-4by3">
                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"
                              allowfullscreen></iframe>
                    </div>
                  </div>
                  <div class="col-6 col-sm-3 px-1 pb-2">
                    <div class="modal-video embed-responsive embed-responsive-4by3">
                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"
                              allowfullscreen></iframe>
                    </div>
                  </div>
                  <div class="col-6 col-sm-3 px-1 pb-2">
                    <div class="modal-video embed-responsive embed-responsive-4by3">
                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"
                              allowfullscreen></iframe>
                    </div>
                  </div>
                  <div class="col-6 col-sm-3 px-1 pb-2">
                    <div class="modal-video embed-responsive embed-responsive-4by3">
                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"
                              allowfullscreen></iframe>
                    </div>
                  </div>
                </div>


                <hr class="mt-5">
              </div>
            </div>

            <div class="row">

              <!--Rates-->
              @if(isset($item->rates))
                <div class="col-lg-6 pb-4">
                  <h3 class="modal-title mb-3">
                    Tarifas
                  </h3>
                  @foreach($item->rates as $rate)
                    <div class="row align-items-center modal-item">
                      <div class="col-5 col-sm-3">{{$rate->title}}</div>
                      <div class="col-2 col-sm-5">
                        <hr class="solid">
                      </div>
                      <div class="col-5 col-sm-4 text-primary">${{$rate->pivot->price}}</div>
                    </div>
                  @endforeach
                </div>
              @endif

            <!--Schedule-->
              @if(isset($item->customSchedules))
                <div class="col-lg-6 pb-4">
                  <h3 class="modal-title mb-3">
                    Horarios
                  </h3>
                  @foreach($item->customSchedules as $costumSchodule)
                    <div class="row align-items-center modal-item">
                      <div class="col-5 col-sm-4">

                        @if($costumSchodule->day == 0)
                          Domingo
                        @elseif ($costumSchodule->day == 1)
                          Lunes
                        @elseif ($costumSchodule->day ==2 )
                          Martes
                        @elseif ($costumSchodule->day == 3)
                          Miercoles
                        @elseif ($costumSchodule->day == 4)
                          Jueves
                        @elseif ($costumSchodule->day == 5)
                          Viernes
                        @else
                          Sabado
                        @endif

                      </div>
                      <div class="col-2 col-sm-4">
                        <hr class="solid">
                      </div>
                      <div class="col-5 col-sm-4 text-primary">{{$costumSchodule->from}}
                        - {{$costumSchodule->until}}</div>
                    </div>
                  @endforeach
                </div>
              @endif

              <div class="col-lg-12 pb-4">
                <hr>
              </div>
            </div>


            <div class="row">
              <!--About me-->
              @if(isset($item->tags))
                <div class="col-lg-4 pb-4">
                  <h3 class="modal-title mb-3">
                    Sobre Mi
                  </h3>
                  @foreach($item->tags as $tag)
                    <span class="badge info-badge">{{$tag->name}}</span>
                  @endforeach
                </div>
              @endif
            <!--Services-->
              @if(isset($item->services))
                <div class="col-lg-4 pb-4">
                  <h3 class="modal-title mb-3">
                    Servicio
                  </h3>
                  @foreach($item->services as $service)
                    <span class="badge info-badge">{{$service->title}}</span>
                  @endforeach
                </div>
              @endif
            <!--Services specials-->
              @if(isset($item->services))
                <div class="col-lg-4 pb-4">
                  <h3 class="modal-title mb-3">
                    Servicios Especiales
                  </h3>
                  @foreach($item->services as $service)
                    <span class="badge info-badge">{{$service->title}}</span>
                  @endforeach
                </div>
              @endif
              <div class="col-lg-12 pb-4">
                <hr>
              </div>
            </div>

            {{dd($item->options, $item)}}


            <div class="row">

              @if(isset($item->options->address))
                <div class="col-lg-4 pb-4">
                  <h3 class="modal-title mb-3">
                    Lugar de encuentro
                  </h3>
                  @foreach($item->options->address as  $addres)
                  <span class="badge info-badge">{{$addres->address}}</span>
                  @endforeach
                  <span class="badge info-badge">21 años</span>
                  <span class="badge info-badge">$95.000</span>
                  <span class="badge info-badge">Colombianas</span>
                  <span class="badge info-badge">Medellín</span>
                  <span class="badge info-badge">21 años</span>
                  <span class="badge info-badge">$95.000</span>
                  <span class="badge info-badge">Colombianas</span>
                </div>
              @endif

              <div class="col-lg-8 pb-4">
                <h3 class="modal-title mb-3">
                  Ubicación
                </h3>
                <div class="girl-map">
                  <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d254508.5164107565!2d-74.2478938043006!3d4.648283717342738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f9bfd2da6cb29%3A0x239d635520a33914!2sBogota%2C%20Colombia!5e0!3m2!1sen!2sve!4v1609998198943!5m2!1sen!2sve"
                    width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                    tabindex="0"></iframe>
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="modal-footer">
          <div class="container-fluid">
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
        </div>


      </div>
    </div>
  </div>
</div>



@section('scripts-owl')
  @parent
  <script>

    $(document).ready(function () {

      $('.owl-image-mini').owlCarousel({
        responsiveClass: true,
        nav: false,
        loop: true,
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
@endsection
