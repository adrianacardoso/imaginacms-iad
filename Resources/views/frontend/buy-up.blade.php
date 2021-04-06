@extends("layouts.master")

@section('content')
  <div class="page buy-up-pin buy-up-pin-{{$item->id}}">
    <x-isite::breadcrumb>
      <li class="breadcrumb-item active" aria-current="page"> {{trans('iad::frontend.buy-up')}}</li>
    </x-isite::breadcrumb>
    
    <section id="pin" class="py-2" >
      <div class="container">
        
          <div class="card">
            <div class="card-header">
              <h1 class="h5">{{$item->title}}</h1>
            </div>
            <div class="card-body">
              <div class="row">
              <div class="col-12 col-md-4">
                <x-media::single-image :alt="$item->title"
                                       :title="$item->title"
                                       :isMedia="true"
                                       imgClasses="d-block h-100 pin-main-image"
                                       width="100%"
                                       :mediaFiles="$item->mediaFiles()" zone="mainimage"/>
              </div>
              <div class="col-12 col-md-8 pin-description">
    
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
        <div class="col-12 col-md-4">
          {!! Form::open(['url' => url("/pins/$item->id/buy-up"), 'method' => 'post']) !!}
          
          <h4><strong>1. Escoge un plan:</strong> </h4>
          @foreach($ups as $key => $up)
          <div class="custom-control custom-radio mb-4 cursor-pointer">
            <input type="radio" id="upPlanRadio{{$key}}" name="upId" class="custom-control-input" value="{{$up->id}}">
            <label class="custom-control-label w-100" for="upPlanRadio{{$key}}">
              <div class="card-plan ml-3 ml-sm-4 ml-md-5">
                <div class="card-plan-body">
            
            
                  <h4 class="title">{{$up->title}}</h4>
            
                  @if(!empty($up->description))
                  <div class="custom-html">
                    {!! $up->description !!}
                  </div>
                @endif
              
                  <h5 class="d-inline-block"><strong>{{$up->days_limit}}</strong></h5> Días <br>
                  <h5 class="d-inline-block"><strong>{{$up->ups_daily}}</strong></h5> Subidas/Días
  
                  @if(isset($up->product->price))
                  <div class="price">
                    ${{formatMoney($up->product->price)}}
                  </div>
                  @endif
          
                </div>
              </div>
            </label>
          </div>
          @endforeach
          <h4><strong>2. Configura tu plan:</strong> </h4>
          <p>
  
            <label for="from">Primer día:</label>
            <input type="text" id="fromDate" name="fromDate">
          </p>
          <p>
            <label for="to">Último día:</label>
            <input type="text" id="toDate" name="toDate">

          </p>
          <p>
            <label for="to">Primera subida:</label>
            <input type="time" name="fromHour">
            
          </p>
          <p>
            <label for="to">Última subida:</label>
            <input type="time" name="toHour">
            
          </p>
          <p>
            <input type="checkbox" name="fullDay">
            <label for="to">24 Horas</label>
          </p>
          <div class="featured">
            <h3>Quieres destacar tu anuncio?</h3>
            Por <strong>$45.000</strong> podrás ver tu anuncio destacado con un color resaltador, y además subirás a nuestro top prepagos premium por una semana
            <br>
            <input type="checkbox" name="featured">
            <label for="featured">¡Quiero destacarlo!</label>
          </div>
          
          <div class="py-4 text-center">
            <input class="btn btn-submit btn-outline-primary rounded-pill px-4 py-2" type="submit" value="Pagar">
 
          </div>
          {!! Form::close() !!}
        </div>
        
      </div>
      </div>
    </section>
    
  </div>
  @stop
@section("scripts")
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $(document).ready(function () {
      var dateFormat = "mm/dd/yy",
        from = $( "#fromDate" )
          .datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3
          })
          .on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
          }),
        to = $( "#toDate" ).datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 3
        })
          .on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
          });
  
      function getDate( element ) {
        var date;
        try {
          date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
          date = null;
        }
    
        return date;
      }
    } );
  </script>
  @parent
  
  @stop