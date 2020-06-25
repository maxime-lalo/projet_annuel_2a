<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
$fRepo = new FoodTruckRepository();
file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,
+Mountain+View,+CA&key=YOUR_API_KEY')
?>
<style>
  #map {
    height: 100%;
  }
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
</style>
<div id="map"></div>
<script>
// Initialize and add the map
function initMap() {
  // The location of Uluru
  var paris = {lat: 48.8584, lng: 2.2945};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 12, center: paris});
  // The marker, positioned at Uluru
  <?php 
    $foodTrucks = $fRepo->getAll();

    foreach( $foodTrucks as  $foodTruck){
      $json = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($foodTruck->getFullAddress())."&key=".MAPS_API_KEY));
      
      echo "var marker".$foodTruck->getId()." = new google.maps.Marker({position: {lat: ".$json->results[0]->geometry->location->lat.", lng: ".$json->results[0]->geometry->location->lng."}, map: map, title: '".$foodTruck->getName()."', animation: google.maps.Animation.DROP});";

    }
  ?>
  
  
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY?>&callback=initMap">
</script>