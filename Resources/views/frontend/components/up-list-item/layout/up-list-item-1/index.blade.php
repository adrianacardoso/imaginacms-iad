<div class="up">
  <div class="card-up">
    
    <div class="card-body p-0">
      <h5 class="card-title">
        
        {{$item->title}}
      
      </h5>
 
      <div class="d-block">
        Daily Limit {{$item->ups_daily}}
      </div>
      
      <div class="d-block">
        Days {{$item->ups_daily}}
      </div>
      
      @if(isset($item->product->price))
      <div class="d-block">
        Price {{formatMoney($item->product->price)}}
      </div>
        @endif
      <a href="{{"pins/$item->id/buy-up/"}}" class="btn btn-submit"> Contratar</a>
    </div>
  </div>
  
</div>
