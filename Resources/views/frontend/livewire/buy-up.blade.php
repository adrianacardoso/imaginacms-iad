  <div class="page buy-up-pin buy-up-pin-{{$item->id}} position-relative">
    <x-isite::breadcrumb>
      <li class="breadcrumb-item active" aria-current="page"> {{trans('iad::frontend.buy-up')}}</li>
    </x-isite::breadcrumb>
    @include('isite::frontend.partials.preloader')
    <section wire:ignore id="pin" class="py-2">
      <div class="container">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-auto">
                <x-media::single-image :alt="$item->title"
                                       :title="$item->title"
                                       :isMedia="true"
                                       imgClasses="d-block h-100 pin-main-image"
                                       width="100%"
                                       :mediaFiles="$item->mediaFiles()" zone="mainimage"/>
              </div>
              <div class="col-12 col-md pin-description">
                <h1 class="h5 font-weight-bold">{{$item->title}}</h1>
                {!! $item->description!!}
              </div>
            </div>
          </div>
        
        </div>
      </div>
    </section>
    
    <section id="ups" class="py-2">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12">
            
            
            <div class="card mb-4 shadow-sm">
              
              <div class="card-body">
                
                
                {!! Form::open(['url' => url("/pins/$item->id/buy-up"), 'method' => 'post']) !!}
                
                
                <h4 class="mb-4"><strong>1. Escoge un plan:</strong></h4>
                <div class="row justify-content-center">
         
                  @foreach($ups as $key => $up)
                    <div class="col-md-4 col-lg-3">
                     <x-iad::up-list-item :item="$up" />
                    </div>
                  @endforeach
                </div>
              
              </div>
              <div class="card-body">
                <h4 class="mb-4"><strong>2. Configura tu plan:</strong></h4>
                
                <div class="row justify-content-center">
                  <div class="col-md-6 col-lg-3">
                    <p>
                      
                      <label for="from">Día de inicio:</label>
                      <input type="date" wire:model="fromDate" class="form-control" name="fromDate" autocomplete="off" required />
                      <input type="hidden" name="toDate" value="{{ $toDate ? $toDate->format("Y-m-d") : ''}}" />
                  
                    </p>
                  </div>
                  
                </div>
                @if(!empty($endDateMessage))
                  <div class="alert-info p-1 text-center">
      
                    <span id="endDateMessage">{!! $endDateMessage!!}</span>
                  </div>
                @endif
                
                @if(!$fullDay)
                <div class="row justify-content-center" id="timeUp">
                  <div class="col-md-6 col-lg-3">
                    <p>
                      <label for="to">Primera subida:</label>
                      <input type="time" id="fromHour" wire:model="fromHour" class="form-control" name="fromHour" {{$fullDay ? "" : "required='true'"}}/>
                    
                    </p>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <p>
                      <label for="to">Última subida:</label>
                      <input type="time" id="toHour" wire:model="toHour" class="form-control" name="toHour" {{$fullDay ? "" : "required='true'"}}/>
                    
                    </p>
                  </div>
                </div>
                @endif
                @if(!empty($hourRangeMessage))
                  <div class="{{$invalidData ? "alert-warning" : "alert-info"}} p-1 text-center">
      
                    <span id="endDateMessage">{!! $hourRangeMessage!!}</span>
                  </div>
                @endif
                <div class="row justify-content-center">
  
               
                  <div class="col-md-6 col-lg-3">
                    <div class="custom-control custom-switch">
                      <input class="custom-control-input" type="checkbox" wire:model="fullDay" name="fullDay" id="to"/>
                      <label class="custom-control-label" for="to">24 Horas</label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3"></div>
                </div>
              
              </div>
              @if(isset($featuredProduct->id))
              <div class="card-body">
                <div class="featured">
                  <h4 class="alert-heading">Quieres destacar tu anuncio?</h4>
                  Por {!! isset($featuredProduct->discount->price) ? "<del>$".formatMoney($featuredProduct->price)."</del>" : "" !!}
                  <strong>${{formatMoney($featuredProduct->discount->price ?? $featuredProduct->price)}}</strong>
                  podrás ver tu anuncio destacado con un color resaltador, y
                  además subirás a nuestro top prepagos premium en cada subida automática
                  <hr>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="featured" name="featured"/>
                    <label class="form-check-label" for="featured">¡Quiero destacarlo!</label>
                  </div>
                </div>
              </div>
              @endif
              
              <div class="py-4 text-center">
                <input class="btn btn-submit btn-primary rounded-pill px-4 py-2" {{$invalidData ? "disabled='true'" : ""}} type="submit"
                       value="Pagar"/>
              
              </div>
              {!! Form::close() !!}
            </div>
          </div>
        
        </div>
      </div>
    </section>
  
  </div>
