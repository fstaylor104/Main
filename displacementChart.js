
 //Method that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.

 function drawDisplacementChart(){
          var graph = [];
          downloadUrl("Map.php", function (data){
              var xml = data.responseXML;
              var markers = xml.documentElement.getElementsByTagName("marker");
              var dataTable = new google.visualization.DataTable();
              var options = {title:'Displacement (m)', 
                  curveType:'function', 
                  legend:{position:'bottom'},
                  is3d:true     
              };
              
              var startPoint = new google.maps.LatLng(markers[0].getAttribute("lat"),markers[0].getAttribute("lng"));
              for(var i = 0; i<markers.length; i++){
                  var time = i+':00';
                  var current = new google.maps.LatLng(markers[i].getAttribute("lat"),markers[i].getAttribute("lng"));
                  var displacement = google.maps.geometry.spherical.computeDistanceBetween(startPoint,current);
                  graph[i] = [time, displacement];
              }
              
              var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
              dataTable.addColumn('string', 'time');
              dataTable.addColumn('number', 'Displacement');
              
               
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
      
      