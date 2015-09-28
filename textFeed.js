/* 
*   methods to dynamically update a live text commentary of the run
*/

function getTextFeed(){
    
    //retrieve information from database
     downloadUrl("Map.php", function(data){
          var xml = data.responseXML;
          var markers = xml.documentElement.getElementsByTagName("marker");
          var allPoints = Array();
          
          //loop through xml and get lat lng points in an arry
          for(var i = 0; i<markers.length; i++){
            var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("lat")),
                    parseFloat(markers[i].getAttribute("lng")));
                    allPoints[i] = point;
                }
           //variable to represent total distance covered     
           var total = 0;
           //loop through all points, getting distance between each set and totalling this
             for(var i = 1; i<allPoints.length; i++){
                 var distance = google.maps.geometry.spherical.computeDistanceBetween(
                 allPoints[i], allPoints[i-1]
                 );
                 total+=distance;
           }
            
            //variable to represent how far has currently been travelled
            var travelled = 0;
            //loop through all points, calculating spped and distance remaining
            for(var i = 1; i <allPoints.length-1; i++){
                //timeout to update periodically rather than all at once
                setTimeout(function(y){
                    
                //calculate distance
                var distance = google.maps.geometry.spherical.computeDistanceBetween(
                        allPoints[y], allPoints[y-1]);
                //calculate speed in km/h and then round to 2 dp
                var s = ((distance/1000)*60);
                var speed = s.toFixed(2);
                
                travelled += distance;
                //get distance remaining to 2 dp
                var distanceRemaining = (total - travelled).toFixed(0);
                
             
                
               //output speed and distance remaining to text feed
               document.getElementById('log').innerHTML += TIME +' <em>Speed: ' 
                       +speed +' km/h   Distance Remaining: '+distanceRemaining+'m</em></br>';
               
               if(s===0){
                   document.getElementById('log').innerHTML += "<span style='color:red; font-size:15px;'>"+
                            '<strong>You have stopped moving!!</strong></br>'+"</span>";
               }
               if((s<7)&&(s>0)){
                   document.getElementById('log').innerHTML += "<span style='color:red; font-size:15px;'>"+
                            '<strong>Speed is low... pick up the pace!</strong></br>'+"</span>";
               }
               //scrolltop specifies scrolling offset in pixels from the top of the region.
                //Setting to a larhe number to ensure it will always be at the bottom of feed.
               document.getElementById('log').scrollTop = 9999999;
           
           //output every x seconds...
                },i*TIME, i);   
            }
                              
});

}

    