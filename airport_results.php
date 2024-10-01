<!DOCTYPE html>
<html>

<head>
    <title>Airport Search</title>
     <style>

table th {
    background-color: #f2f2f2; /* Background color for th elements */
}
        p.not_found {
         text-align: center;
         font-size: 20px;
        }
        .form-row {
            display: inline-grid;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-row label {
            margin-right: 10px;
            min-width: 100px;            
        }

        .form-row select,
        .form-row input[type="text"] {
            flex: 1;
            padding: 5px;
        }

        .container_fromcity {
            width: 47.5%;
            margin-top: 50px;
            padding: 18px;
            border-radius: 5px;
            background: #f2f2ff8a !important;
            margin-bottom: 40px;
            display: inline-grid;
            margin-left: 25px;
            margin-right: 6px;
        }
        .container_tocity{
            width: 47.5%;
            margin-top: 50px;
            padding: 18px;
            border-radius: 5px;
            background: #f2f2ff8a !important;
            margin-bottom: 40px;
            display: inline-grid;
            margin-right: 25px;
            margin-left: 6px;
        }

        button {
            padding: 5px 15px 5px 15px;
            border: 1px solid #aaaaaa;
            border-radius: 1px;
            background: #efefef;
        }

        .selectize-input.items.required.has-options.full.has-items {
            width: 115%;
        }

         table {
            border-collapse: collapse;
            width: 90%;
            margin: 0px auto;
        }
        table.dataTable{
            border-collapse: collapse;
        }
        table.dataTable thead th, table.dataTable thead td, table.dataTable tbody th, table.dataTable tbody td{
            vertical-align: middle;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        td {
            font-weight: 400;
        }

        span#to_combinedValue {
            text-decoration: underline;
            background: #ffd50075;
            padding: 7px 15px 7px 15px;
            border: none;
        }

        span#from_combinedValue {
            text-decoration: underline;
            background: #ff7f007d;
            padding: 7px 15px 7px 15px;
            border: none;
        }

        .spac {
            width: 97%;
        }

        h4.str_tab {
            margin-top: 25px;
        }
        
        span.sort-arrow {
             cursor: pointer;
        }
        input {
            padding: 3px 5px 3px 5px;
        }

         .from_airport{
           padding-top:5px;
           padding-bottom: 5px;
           margin-bottom: 40px;
           background-color: #333333;
           text-align: center;
           font-size: 22px;
           color:white;
           border-radius: 4px;
         }
         th.fix.sorting {
           width: 84px !important;
         }

    </style>
    <?php include 'fromcity.php'; ?>
    <?php include 'tocity.php'; ?>

</head>

<body>


    <div class="container_fromcity">
        <h2 class="from_airport">From Airports of
            <span>(<?php echo htmlspecialchars($dataArray['fromCity'] . ', ' . $dataArray['fromState']); ?>)</span>
    </h2>

    <!-- From Table Display -->
  <?php
  if (empty($tofilteredData)) {
      echo "<p>No data found for the given city and state within 40 miles.</p>";
  } else {
      ?>    

  <table id="airport-table" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th data-sortable="true" data-field="name">
          Name <span class="arrow"></span>
        </th>
        <th data-sortable="true" data-field="type">
          Airport Type <span class="arrow"></span>
        </th>
        <th class="fix" data-sortable="true" data-field="iata">
          IATA Code <span class="arrow"></span>
        </th>
        <th data-sortable="true" data-field="state">
          State<span class="arrow"></span>
        </th>
        <th data-sortable="true" data-field="distance">
          Distance(mi)<span class="arrow"></span>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($filteredData as $item): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($item['municipality']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($item['iata_code']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($item['region_name']); ?>
                                </td>
                                <td>
                                    <?php echo round($item['distance_to_city_center'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
      </div>
    
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
            <script>
                // Initialize the DataTable plugin
                    $(document).ready(function() {
                    $('#airport-table').DataTable({
                    "paging": false,     // Disable pagination
                    "searching": false,  // Hide search bar
                    "info": false,       // Hide information about number of entries
                    "order": [[4, "asc"]] // Sort by the 7th column (index 6) in accessding order
                    });
                });
            </script>
<?php } ?>

            </div>


    <!-- To Table Display -->
<div class="container_tocity">
    <h2 class="from_airport">To Airports of
        <span>(<?php echo htmlspecialchars($dataArray['toCity'] . ', ' . $dataArray['toState']); ?>)
        </span>
    </h2>
    
  <?php
        if (empty($tofilteredData)) {
            echo "<p>No data found for the given city and state within 40 miles.</p>";
        } else {
            ?>
    
            <table id="to_airport-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th data-sortable="true" data-field="name">
                            Name <span class="arrow"></span>
                        </th>
                        <th data-sortable="true" data-field="type">
                            Airport Type <span class="arrow"></span>
                        </th>
                        <th class="fix" data-sortable="true" data-field="iata">
                            IATA Code <span class="arrow"></span>
                        </th>
                        <th data-sortable="true" data-field="state">
                            State<span class="arrow"></span>
                        </th>
                        <th data-sortable="true" data-field="distance">
                            Distance(mi) <span class="arrow"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tofilteredData as $item): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($item['name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($item['municipality']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($item['iata_code']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($item['region_name']); ?>
                            </td>
                            <td>
                                <?php echo round($item['distance_to_city_center'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                    </div>
    
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
            <script>
                // Initialize the DataTable plugin
                $(document).ready(function () {
                    $('#to_airport-table').DataTable({
                        "paging": false,     // Disable pagination
                        "searching": false,  // Hide search bar
                        "info": false,       // Hide information about number of entries
                        "order": [[4, "asc"]] // Sort by the 7th column (index 6) in accessding order
                    });
                });
            </script>
        <?php }   ?>
            </div>

</body>

</html>