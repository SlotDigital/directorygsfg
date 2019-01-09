<?php
 /**
 * Template Name: Contact page
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
get_header();
?>



<div class="contact-map">
	  <div id="map_elbp" style="height:80vh;"></div>
</div>

<script>
	
var locations = [
    ['GETSET EAST OF ENGLAND', 'Brookmount Court, Kirkwood Road, Cambridge CB4 2QH', 'http://www.getsetforgrowth.com/about-us/team/east-of-england'],
    //['GETSET LONDON', 'Old Poplar Library, 45 Gillender Street, London E14 6RN', 'http://www.getsetforgrowth.com/about-us/team/east-london/'],
    ['GETSET SOUTH WEST', '39 George Place, Plymouth, PL1 3DX', 'http://www.getsetforgrowth.com/about-us/team/south-west/'],
    //['GETSET SOLENT', 'Unit 35, Basepoint Southampton, Andersons Road, Southampton, SO14 5FE', 'http://www.getsetforgrowth.com/about-us/team/solent/'],
    //['GETSET SOLENT', 'The Enterprise Hub, 63 Darracott Road, Bournemouth, BH5 2AY', 'http://www.getsetforgrowth.com/about-us/team/solent/'],
    //['GETSET WEST MIDLANDS', 'Suite B, Floor 12, Centre City Tower, Hill Street, Birmingham, B5 4UA', 'http://www.getsetforgrowth.com/about-us/team/west-midlands/'],
    //['GETSET WEST OF ENGLAND', 'Royal Oak House, Royal Oak Avenue, Prince Street, Bristol, BS1 4GB', 'http://www.getsetforgrowth.com/about-us/team/west-of-england/']
];

var _locations = [
    ['GETSET EAST OF ENGLAND', 'Brookmount Court, Kirkwood Road, Cambridge CB4 2QH', 'http://www.getsetforgrowth.com/about-us/team/east-of-england', 52.233019, 0.136069],
    ['GETSET LONDON', 'Old Poplar Library, 45 Gillender Street, London E14 6RN', 'http://www.getsetforgrowth.com/about-us/team/east-london/', 51.518954, -0.009247],
    ['GETSET SOUTH WEST', '39 George Place, Plymouth, PL1 3DX', 'http://www.getsetforgrowth.com/about-us/team/south-west/',50.368831, -4.155811],
    ['GETSET SOLENT', 'Unit 35, Basepoint Southampton, Andersons Road, Southampton, SO14 5FE', 'http://www.getsetforgrowth.com/about-us/team/solent/', 50.899860,-1.392286],
    ['GETSET SOLENT', 'The Enterprise Hub, 63 Darracott Road, Bournemouth, BH5 2AY', 'http://www.getsetforgrowth.com/about-us/team/solent/',50.728824, -1.821612],
    ['GETSET WEST MIDLANDS', 'Suite B, Floor 12, Centre City Tower, Hill Street, Birmingham, B5 4UA', 'http://www.getsetforgrowth.com/about-us/team/west-midlands/', 52.4761221, -1.9007303],
    ['GETSET WEST OF ENGLAND', 'Royal Oak House, Royal Oak Avenue, Prince Street, Bristol, BS1 4GB', 'http://www.getsetforgrowth.com/about-us/team/west-of-england/',51.4498683, -2.5987502]
];


var geocoder;
var map;
var bounds = new google.maps.LatLngBounds();

function initialize() {
    map = new google.maps.Map(
    document.getElementById("map_elbp"), {
        center: new google.maps.LatLng(37.4419, -122.1419),
        zoom: 15,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"stylers":[{"hue":"#00aaff"},{"saturation":-100},{"gamma":2.15},{"lightness":12}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"lightness":24}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":57}]}]
    });
    geocoder = new google.maps.Geocoder();
	
	/*
    for (i = 0; i < locations.length; i++) {

        geocodeAddress(locations, i);
    }*/
    
    var image = '<?php bloginfo( 'stylesheet_directory' ); ?>/images/map-marker.png';
    
    for (i = 0; i < _locations.length; i++) {
			        
            var marker = new google.maps.Marker({
                icon: image,
                map: map,
                position: {lat:_locations[i][3], lng:_locations[i][4]},
                title: _locations[i][0],
                animation: google.maps.Animation.DROP,
                address: _locations[i][1],
                url: _locations[i][2]
            })
            infoWindow(marker, map, _locations[i][0], _locations[i][1], _locations[i][2]);
            bounds.extend(marker.getPosition());
            map.fitBounds(bounds);		
		
	}
	
    
    
}
google.maps.event.addDomListener(window, "load", initialize);

function geocodeAddress(locations, i) {
    var title = locations[i][0];
    var address = locations[i][1];
    var url = locations[i][2];
    geocoder.geocode({
        'address': locations[i][1]
    },

    function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
	        var image = '<?php bloginfo( 'stylesheet_directory' ); ?>/images/map-marker.png';
            var marker = new google.maps.Marker({
                icon: image,
                map: map,
                position: results[0].geometry.location,
                title: title,
                animation: google.maps.Animation.DROP,
                address: address,
                url: url
            })
            infoWindow(marker, map, title, address, url);
            bounds.extend(marker.getPosition());
            map.fitBounds(bounds);
        } else {
            alert("geocode of " + address + " failed:" + status);
        }
    });
}

function infoWindow(marker, map, title, address, url) {
    google.maps.event.addListener(marker, 'click', function () {
        var html = "<div><h3>" + title + "</h3><p>" + address + "<br></div><a target='blank' href='" + url + "'>View location</a></p></div>";
        iw = new google.maps.InfoWindow({
            content: html,
            maxWidth: 350
        });
        iw.open(map, marker);
    });
     google.maps.event.addListener(marker, 'mouseover', function () {
        var html = "<div><h3>" + title + "</h3><p>" + address + "<br></div><a target='blank' href='" + url + "'>View location</a></p></div>";
        iw = new google.maps.InfoWindow({
            content: html,
            maxWidth: 350
        });
        iw.open(map, marker);
    });
    google.maps.event.addListener(marker, 'mouseout', function(){
          iw.close(map, marker);
       });
}

function createMarker(results) {
    var marker = new google.maps.Marker({
        icon: image,
        map: map,
        position: results[0].geometry.location,
        title: title,
        animation: google.maps.Animation.DROP,
        address: address,
        url: url
    })
    bounds.extend(marker.getPosition());
    map.fitBounds(bounds);
    infoWindow(marker, map, title, address, url);
    return marker;
}



</script>


<?php get_footer();?>