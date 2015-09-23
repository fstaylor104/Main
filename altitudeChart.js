
 //Method that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      

 function drawAltitudeChart(){

         var graph = [];
          downloadUrl("Map.php", function (data){
              var xml = data.responseXML;
              var markers = xml.documentElement.getElementsByTagName("marker");
              var dataTable = new google.visualization.DataTable();
              var options = {title:'Altitude (m above sea level)', 
                  curveType:'function', 
                  legend:{position:'bottom'},
                  is3d:true     
              };
              
              for(var i = 0; i<markers.length; i++){
                  graph[i] = ['', parseInt(markers[i].getAttribute("alt"))];
              }
              
              var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
               dataTable.addColumn('string', 'id');
               dataTable.addColumn('number', 'Altitude');
             
               var array = [];
              for(var i = 0; i<graph.length; i++){
                       setTimeout(function(y){
                        array.push(graph[y]);
                        dataTable.addRows(array);              
                        chart.draw(dataTable, options); 
                        array = [];
                  }, i*5000, i);
              }

      });
  }

