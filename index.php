<!DOCTYPE html >
<head>      
    <link rel="icon" type="image/png" href="http://2.bp.blogspot.com/-Khmw4e4ChOQ/UbSGNP9lSiI/AAAAAAAAC-U/DNcYoNYK2XU/s1600/favicon.gif">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Real-Time Run</title>
      <script type = "text/javascript"
    src = "https://maps.googleapis.com/maps/api/js?
                            key=AIzaSyB76xBqfQdgOLV77VK3JZ09vWwk8brkMFs&libraries=geometry&sensor=false"></script>
      <script type="text/javascript" src="downloadURL.js"></script>
      <script type="text/javascript" src="altitudeChart.js"></script>
      <script type="text/javascript" src="displacementChart.js"></script>
      <script type="text/javascript" src="distanceChart.js"></script>
      <script type="text/javascript" src="textFeed.js"></script>
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <script type="text/javascript">       
          
       //adding a contains method to google maps circle to enable comparison of current position 
       //in relation to preset circles/geofences...   
       google.maps.Circle.prototype.contains = function(latLng) {
       return this.getBounds().contains(latLng) && google.maps.geometry.
           spherical.computeDistanceBetween(this.getCenter(), latLng) <= this.getRadius();
}   

      //global array to store markers and circles(geofences)...
      var markersArray = [];
      var circlesArray = [];
      //array to hold latlng values to compare with geofence
      var pointsArray = [];
      
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']}); 
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawCharts);
      
      //retrive 'expected' route from database and add circles to act as geofences. 
      //Add to array to later update periodically.
      downloadUrl("GeoFence.php", function (data){
          var xml = data.responseXML;
          var fences = xml.documentElement.getElementsByTagName("GeoFence");
          for(var i = 0;i<fences.length; i++){
              var center = new google.maps.LatLng(
                    parseFloat(fences[i].getAttribute("lat")),
                    parseFloat(fences[i].getAttribute("lng")));
                    
                    var fence = new google.maps.Circle({
                        center:center,
                        radius: 75,
                        strokeColor: '#FF3333',
                        fillOpacity:0.25
                    });
                    circlesArray.push(fence);  
                }
          });
         

    function load() {
     //map object
        var MY_MAP = new google.maps.Map(document.getElementById("map"), {
        center: {lat: 54.870902, lng: -6.300565}, 
        zoom: 14
      });    
      
      
      //call to get and process data
      downloadUrl("Map.php", processXML);
  }
     function processXML(data){
        var MY_MAP = new google.maps.Map(document.getElementById("map"), {
        center: {lat: 54.870902, lng: -6.300565}, 
        zoom: 14
      });    
     //method to retrieve information via ajax
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        //clear markers before adding new ones
        resetMarkers(markersArray);
        for(var i =0; i<markers.length; i++){
            var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("lat")),
                    parseFloat(markers[i].getAttribute("lng")));
            pointsArray.push(point);
            var marker = new google.maps.Marker({
                position: point
            });
            //store marker object in new array
            markersArray.push(marker);
               
    }         
           getTextFeed();
         //update markers periodically and add circles...
          for (var x = 0; x < markersArray.length; x++) {               
            setTimeout(function(y) {
                markersArray[y].setMap(MY_MAP);
                circlesArray[y].setMap(MY_MAP);
                                
                if(((!circlesArray[y-1].contains(pointsArray[y-1])))&&(circlesArray[y].contains(pointsArray[y]))){
                    document.getElementById('log').innerHTML += "<span style='color:green'>"+
                            'Back on track!! Keep going!</br>'+"</span>";
                }else if(y === 1){
                     document.getElementById('log').innerHTML += "<span style='color:green'>"+
                            'Getting started...</br>'+"</span>";
                }else if(y === markersArray.length-1){
                    document.getElementById('log').innerHTML +="<span style='color:purple'>"+
                            'Congratulations!! You have finished the course!</br>'+"</span>";
                             alert('You have finished the route! Well Done!');
                }else if(circlesArray[y].contains(pointsArray[y])){
                   document.getElementById('log').innerHTML += "<span style='color:blue'>"+
                            'You are on the right track!</br>'+"</span>";
                }else{
                    document.getElementById('log').innerHTML +="<span style='color:red'>"+
                            'WARNING: You are off the expected path!</br>'+"</span>";
                    
                }
                              
            }, x*4000, x); 
        }
        
}
//cleatr existing markers from map
function resetMarkers(arr){
    for(var i = 0; i<arr.length; i++){
        arr[i].setMap(null);
    }
    //reset the main marker array
    arr = [];
}
  
    //function to draw different charts, to be called on call back
    function drawCharts(){
        drawAltitudeChart();
        drawDisplacementChart();
        drawDistanceChart();
    }

    function doNothing() {}
    
   </script>

  </head>

  <body onload="load()" style = "background: -moz-linear-gradient(#C2FFFF, #FFFFFF)">
    <div id="map" style="display: inline-block; margin-top: 100px; margin-left: 50px; width: 600px; height: 500px;"></div>
    <div id="log" style="overflow: scroll; vertical-align: top; display: inline-block; margin-top: 100px; border: solid; border-color: black; border-width: 1px; width: 500px; height: 500px;" >   
        <script type="text/javascript"> 
            //call method to populate text feed with information
           //getTextFeed();
    </script></div>
    <div id="chart_div" style="display: inline-block; width: 440px; height: 450px"></div>
    <div id="chart_div2" style="display: inline-block; width: 440px; height: 450px"></div>
    <div id="chart_div3" style= "display: inline-block; width: 440px; height: 450px"></div>
    <div id="footer" style="height:30px; background-color: #C2FFFF "></div>
            <script type="text/javascript"> 
            var today = new Date();
            document.getElementById('footer').innerHTML = today;
            </script>
    

  </body>

</html>

