@extends("layouts.master")

@section('content')
  <div class="page buy-up-pin buy-up-pin-{{$item->id}}">
    <x-isite::breadcrumb>
      <li class="breadcrumb-item active" aria-current="page"> {{trans('iad::frontend.buy-up')}}</li>
    </x-isite::breadcrumb>
    
    <section id="pin" class="py-2">
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
                      <div class="custom-control custom-control-plan custom-radio mb-4 cursor-pointer">
                        <input type="radio" id="upPlanRadio{{$key}}" name="upId" class="custom-control-input"
                               value="{{$up->id}}" required>
                        <label class="custom-control-label w-100" for="upPlanRadio{{$key}}">
                          <div class="card-plan">
                            <div class="card-plan-body">
                              
                              
                              <h4 class="title">{{$up->title}}</h4>
                              <hr>
                              @if(!empty($up->description))
                                <div class="custom-html">
                                  {!! $up->description !!}
                                </div>
                              @endif
                              
                              <h5 class="d-inline-block"><strong>{{$up->days_limit}}</strong></h5> Días <br>
                              <h5 class="d-inline-block"><strong>{{$up->ups_daily}}</strong></h5> Subidas/Días
                              <hr>
                              @if(isset($up->product->price))
                                <div class="price font-weight-bold">
                                  ${{formatMoney($up->product->price)}}
                                </div>
                              @endif
                            
                            </div>
                          </div>
                        </label>
                      </div>
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
                      <input type="text" id="fromDate" class="form-control" name="fromDate" autocomplete="off" required>
                      <input type="hidden" id="toDate" name="toDate" >
                      <span id="endDateMessage"></span>
                    </p>
                  </div>
                  
                </div>
                <div class="row justify-content-center" id="timeUp">
                  <div class="col-md-6 col-lg-3">
                    <p>
                      <label for="to">Primera subida:</label>
                      <input type="time" id="fromHour" class="form-control" name="fromHour">
                    
                    </p>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <p>
                      <label for="to">Última subida:</label>
                      <input type="time" id="toHour" class="form-control" name="toHour">
                    
                    </p>
                  </div>
                </div>
                <div class="row justify-content-center">
                  <div class="col-md-6 col-lg-3">
                    <div class="custom-control custom-switch">
                      <input class="custom-control-input" type="checkbox" name="fullDay" id="to">
                      <label class="custom-control-label" for="to">24 Horas</label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3"></div>
                </div>
              
              </div>
              <div class="card-body">
                <div class="featured">
                  <h4 class="alert-heading">Quieres destacar tu anuncio?</h4>
                  Por <strong>$45.000</strong> podrás ver tu anuncio destacado con un color
                  resaltador, y
                  además subirás a nuestro top prepagos premium por una semana
                  <hr>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="featured" name="featured">
                    <label class="form-check-label" for="featured">¡Quiero destacarlo!</label>
                  </div>
                </div>
              </div>
              
              <div class="py-4 text-center">
                <input class="btn btn-submit btn-primary rounded-pill px-4 py-2" type="submit"
                       value="Pagar">
              
              </div>
              {!! Form::close() !!}
            </div>
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
      var dateFormat = "dd/mm/yy";
        
        var ups = {!!  $ups !!};
        
        $("#fromDate")
          .datepicker({
            dateFormat: "d MM, y",
            changeMonth: true
          })
          .on("change", function () {
  
            iad__addDays()
          })
  
      $("input[name=upId]").click(function () {
        iad__addDays()
      });
  
      function iad__addDays() {
        var upChecked = $("input:radio[name=upId]:checked").val();
        var initDate = $("#fromDate").val();
        
        if(upChecked && initDate){
          var up = ups.find((item) =>{
            return item.id == upChecked
          })
  
          var result = new Date(initDate);
          result.setDate(result.getDate() + up.days_limit);
  
          var dd = result.getDate();
          var mm = result.getMonth() + 1;
          var y = result.getFullYear();
  
          var someFormattedDate = dd + '/'+ mm + '/'+ y;
  
          $("#endDateMessage").html("Tu Plan de subidas durará hasta: "+someFormattedDate);
          $("#toDate").val(result);
          
        }
        
      }
      
      function iad__checkHourRange(){
        if ($('#to').is(':checked')) {
          $('#timeUp').css('display', 'none')
          $("#fromHour").removeAttr('required', '');
          $("#toHour").removeAttr('required', '');
        } else {
          $('#timeUp').css('display', 'flex')
          $("#fromHour").attr('required', '');
          $("#toHour").attr('required', '');
        }
      }
      
      $('#to').click(function () {
        iad__checkHourRange()
      });
  
      iad__checkHourRange()
    });
  </script>
  @parent

@stop