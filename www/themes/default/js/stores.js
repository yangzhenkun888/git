/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

function initMarkers()
{
	searchUrl += '?ajax=1&all=1';
	downloadUrl(searchUrl, function(data) {
		var xml = parseXml(data);
		var markerNodes = xml.documentElement.getElementsByTagName('marker');
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markerNodes.length; i++)
		{
			var name = markerNodes[i].getAttribute('name');
			var address = markerNodes[i].getAttribute('address');
			var addressNoHtml = markerNodes[i].getAttribute('addressNoHtml');
			var other = markerNodes[i].getAttribute('other');
			var id_store = markerNodes[i].getAttribute('id_store');
			var has_store_picture = markerNodes[i].getAttribute('has_store_picture');
			var latlng = new google.maps.LatLng(
			parseFloat(markerNodes[i].getAttribute('lat')),
			parseFloat(markerNodes[i].getAttribute('lng')));
			createMarker(latlng, name, address, other, id_store, has_store_picture);
			bounds.extend(latlng);
		}
	});
}

function searchLocations()
{
	$('#stores_loader').show();
	var address = document.getElementById('addressInput').value;
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address: address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK)
			searchLocationsNear(results[0].geometry.location);
		else
			alert(address+' '+translation_6);
		$('#stores_loader').hide();
	});
}

function clearLocations(n)
{
	infoWindow.close();
	for (var i = 0; i < markers.length; i++)
		markers[i].setMap(null);
		
	markers.length = 0;

	locationSelect.innerHTML = '';
	var option = document.createElement('option');
	option.value = 'none';
	if (!n)
		option.innerHTML = translation_1;
	else
	{
		if (n == 1)
			option.innerHTML = '1'+' '+translation_2;
		else
			option.innerHTML = n+' '+translation_3;
	}
	locationSelect.appendChild(option);
	$('#stores-table tr.node').remove();
}

function searchLocationsNear(center)
{
	var radius = document.getElementById('radiusSelect').value;
	var searchUrl = baseUri+'?controller=stores&ajax=1&latitude=' + center.lat() + '&longitude=' + center.lng() + '&radius=' + radius;
	downloadUrl(searchUrl, function(data) {
		var xml = parseXml(data);
		var markerNodes = xml.documentElement.getElementsByTagName('marker');
		var bounds = new google.maps.LatLngBounds();

		clearLocations(markerNodes.length);
		for (var i = 0; i < markerNodes.length; i++)
		{
			var name = markerNodes[i].getAttribute('name');
			var address = markerNodes[i].getAttribute('address');
			var addressNoHtml = markerNodes[i].getAttribute('addressNoHtml');
			var other = markerNodes[i].getAttribute('other');
			var distance = parseFloat(markerNodes[i].getAttribute('distance'));
			var id_store = parseFloat(markerNodes[i].getAttribute('id_store'));
			var phone = markerNodes[i].getAttribute('phone');
			var has_store_picture = markerNodes[i].getAttribute('has_store_picture');
			var latlng = new google.maps.LatLng(
			parseFloat(markerNodes[i].getAttribute('lat')),
			parseFloat(markerNodes[i].getAttribute('lng')));

			createOption(name, distance, i);
			createMarker(latlng, name, address, addressNoHtml, other, id_store, has_store_picture);
			bounds.extend(latlng);

			$('#stores-table tr:last').after('<tr class="node"><td class="num">'+parseInt(i + 1)+'</td><td><b>'+name+'</b>'+(has_store_picture == 1 ? '<br /><img src="'+img_store_dir+parseInt(id_store)+'-medium.jpg" alt="" />' : '')+'</td><td>'+address+(phone != '' ? '<br /><br />'+translation_4+' '+phone : '')+'</td><td class="distance">'+distance+' '+distance_unit+'</td></tr>');
			$('#stores-table').show();
		}

		if (markerNodes.length)
		{
			map.fitBounds(bounds);
			var listener = google.maps.event.addListener(map, "idle", function() { 
				if (map.getZoom() > 13) map.setZoom(13);
				google.maps.event.removeListener(listener); 
			});
		}
		locationSelect.style.visibility = 'visible';
		locationSelect.onchange = function() {
			var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
			google.maps.event.trigger(markers[markerNum], 'click');
		};
	});
}

function createMarker(latlng, name, address, other, id_store, has_store_picture)
{
	var html = '<b>'+name+'</b><br/>'+address+(has_store_picture == 1 ? '<br /><br /><img src="'+img_store_dir+parseInt(id_store)+'-medium.jpg" alt="" />' : '')+other+'<br /><a href="http://maps.google.com/maps?saddr=&daddr='+latlng+'" target="_blank">'+translation_5+'<\/a>';
	var image = new google.maps.MarkerImage(img_ps_dir+logo_store);
	if (hasStoreIcon)
		var marker = new google.maps.Marker({ map: map, icon: image, position: latlng });
	else
		var marker = new google.maps.Marker({ map: map, position: latlng });
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
	markers.push(marker);
}

function createOption(name, distance, num)
{
	var option = document.createElement('option');
	option.value = num;
	option.innerHTML = name+' ('+distance.toFixed(1)+' '+distance_unit+')';
	locationSelect.appendChild(option);
}

function downloadUrl(url, callback)
{
	var request = window.ActiveXObject ?
	new ActiveXObject('Microsoft.XMLHTTP') :
	new XMLHttpRequest;

	request.onreadystatechange = function() {
		if (request.readyState == 4) {
			request.onreadystatechange = doNothing;
			callback(request.responseText, request.status);
		}
	};

	request.open('GET', url, true);
	request.send(null);
}

function parseXml(str)
{
	if (window.ActiveXObject) {
		var doc = new ActiveXObject('Microsoft.XMLDOM');
		doc.loadXML(str);
		return doc;
	}
	else if (window.DOMParser) {
		return (new DOMParser).parseFromString(str, 'text/xml');
	}
}

function doNothing() {}

$(document).ready(function()
{
	map = new google.maps.Map(document.getElementById('map'), {
		center: new google.maps.LatLng(defaultLat, defaultLong),
		zoom: 10,
		mapTypeId: 'roadmap',
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
	});
	infoWindow = new google.maps.InfoWindow();

	locationSelect = document.getElementById('locationSelect');
		locationSelect.onchange = function() {
		var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
		if (markerNum != 'none')
		google.maps.event.trigger(markers[markerNum], 'click');
	};
	
	$('#addressInput').keypress(function(e) {
		code = e.keyCode ? e.keyCode : e.which;
		if(code.toString() == 13)
			searchLocations();
	});


	initMarkers();
});
