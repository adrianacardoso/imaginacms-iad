@if(isset($item->map)&&!empty($item->map))
  <div class="map bg-light">
    <div class="content">
      <div id="map_canvas" style="width:100%; height:314px"></div>
    </div>

  </div>
@endif

@section('scripts')
  @parent
  <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.css" type="text/css"/>
  <script type="text/javascript">

    function initialize() {
      var map = L.map('map_canvas').setView([{{ $item->map->lat ?? '4.570868' }}, {{ $item->map->lng ?? '-74.297333' }}], 16);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      L.marker([{{ $item->map->lat ?? '4.570868' }}, {{ $item->map->lng ?? '-74.297333' }}]).addTo(map)
        .bindPopup('{{ $item->title ?? 'Dirección' }}')
        .openPopup();
    }

    $(document).ready(function () {
      initialize();

    });
  </script>
@stop