
 // Method that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      
      function drawDistanceChart(){
      //get data
      downloadUrl("Map.php", function(data){
          var xml = data.responseXML;
          var allPoints = Array();
          var markers = xml.documentElement.getElementsByTagName("marker");
          
          for(var i = 0; i<markers.length; i++){
            var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("lat")),
                    parseFloat(markers[i].getAttribute("lng")));
                    allPoints[i] = point;
                }
                
               var total = 0;
               var graph = Array();
               graph[0] = ['0.00', total];
               for(var i = 1; i<allPoints.length; i++){
                    var time = i+':00';
                    var distance = google.maps.geometry.spherical.computeDistanceBetween(
                     allPoints[i], allPoints[i-1]
                    ); 
                      
                      
                       total+=distance;
                       graph[i] = [time , total]; 
               
               }
                       
               var dataTable = new google.visualization.DataTable();
               var options = {
                   title: 'Distance(m)',
                   curveType: 'function',
                   legend: { position:'bottom'},
                   is3d:true
               };
               
               var chart = new google.visualization.LineChart(document.getElementById('chart_div3'));
              dataTable.addColumn('string', 'id');
              dataTable.addColumn('number', 'Distance');
              
               
              var array = [];
              for(var i = 0; i<graph.length; i++){
                       setTimeout(function(y){
                        array.push(graph[y]);
                        dataTable.addRows(array);              
                        chart.draw(dataTable, options); 
                        array = [];
                  }, i*TIME, i);
              }
      });

      }