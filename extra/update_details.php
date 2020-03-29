<div id="Historical" class="tabcontent">
            <center><table class="hist">
                        <tr>
                            <th class="tl" style="text-align:left;">Date</th>
                            <th class="tl">High</th>
                            <th class="tl">Low</th>
                            <th class="tl">Open</th>
                            <th class="tl">Close</th>
                            <th class="tl" style="text-align:right;">Volume</th>
                        </tr>
                <?php
                    //$open = "stock_data/" . $id . ".csv";
                    $file = fopen("../resources/csv/ADANIPORTS.csv","r");
                    $row5 = fgetcsv($file);
                    $row5 = fgetcsv($file);
                    print_r($row5[0]);
                    $time = new DateTime();
                    $newtime = $time->modify('-1 year')->format('Y-m-d');
                    echo $newtime . "<br>";
                    $c =0;
                    $row6 = array();
                    while(! feof($file))
                    {
                        //echo $c . "<br>";
                        $row5 = fgetcsv($file);
                        if($row5[0] == "2019-03-28")
                        {
                            $row6[$c] = $row5;
                            $c++;
                            while(! feof($file))
                            {
                                $row6[$c] = fgetcsv($file);
                                $c++;
                            }
                            //print_r($row6[$c-2]);
                            //echo $row6[$c-2][0];
                        }
                        //print_r($row5);
                    }
                     for($d=$c-2;$d>=0;$d--)
                        {
                            echo "<tr>";
                            echo "<td class='tl' style='text-align:left;'>" . $row6[$d][0] . "</td>";
                            printf("<td class='tl'>%.2f</td>", $row6[$d][1]);
                            printf("<td class='tl'>%.2f</td>", $row6[$d][2]);
                            printf("<td class='tl'>%.2f</td>", $row6[$d][3]);
                            printf("<td class='tl'>%.2f</td>", $row6[$d][4]);
                            printf("<td class='tl' style='text-align:right;'>%.0f</td>", $row6[$d][5]);
                            echo "</tr>";
                        }
                ?>
                </div>